<?php

namespace App\Services;

use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use SimpleXMLElement;

class ChancoNoticiasService
{
    public function destacadas(int $limit = 3): Collection
    {
        $limit = max(1, min($limit, 10));
        $cacheKey = "chanco.noticias.destacadas.{$limit}";
        $ttl = (int) config('services.chanco.cache_ttl', 3600);

        return Cache::remember($cacheKey, $ttl, fn () => $this->fetchNoticias($limit));
    }

    public function verTodasUrl(): string
    {
        return (string) config('services.chanco.noticias_url', 'https://chanco.cl');
    }

    public function clearCache(): void
    {
        foreach ([2, 3, 4, 5] as $limit) {
            Cache::forget("chanco.noticias.destacadas.{$limit}");
        }
    }

    protected function fetchNoticias(int $limit): Collection
    {
        $rest = $this->fetchFromRestApi($limit);
        if ($rest->isNotEmpty()) {
            return $rest;
        }

        return $this->fetchFromRss($limit);
    }

    protected function fetchFromRestApi(int $limit): Collection
    {
        $apiUrl = config('services.chanco.wp_api_url');
        if (! $apiUrl) {
            return collect();
        }

        try {
            $response = Http::timeout(15)
                ->withHeaders($this->httpHeaders())
                ->get(rtrim($apiUrl, '/').'/posts', [
                    'per_page' => $limit,
                    '_embed' => 'wp:featuredmedia',
                ]);

            if (! $response->successful()) {
                return collect();
            }

            $posts = $response->json();
            if (! is_array($posts)) {
                return collect();
            }

            return collect($posts)->map(function (array $post): ?array {
                $title = trim(strip_tags($post['title']['rendered'] ?? ''));
                $link = trim((string) ($post['link'] ?? ''));
                if ($title === '' || $link === '') {
                    return null;
                }

                $img = null;
                $embedded = $post['_embedded']['wp:featuredmedia'][0] ?? null;
                if (is_array($embedded)) {
                    $img = $embedded['source_url'] ?? $embedded['media_details']['sizes']['medium']['source_url'] ?? null;
                }

                return [
                    'titulo' => html_entity_decode($title, ENT_QUOTES | ENT_HTML5, 'UTF-8'),
                    'url' => $link,
                    'fecha' => $this->formatFecha($post['date'] ?? null),
                    'img' => $img,
                ];
            })->filter()->values();
        } catch (\Throwable $e) {
            Log::debug('REST WordPress Chanco no disponible', ['error' => $e->getMessage()]);

            return collect();
        }
    }

    protected function fetchFromRss(int $limit): Collection
    {
        $feedUrl = (string) config('services.chanco.feed_url', 'https://chanco.cl/feed/');

        try {
            $response = Http::timeout(15)
                ->withHeaders($this->httpHeaders())
                ->get($feedUrl);

            if (! $response->successful()) {
                Log::warning('Feed RSS Chanco respondió con error', ['status' => $response->status()]);

                return collect();
            }

            return $this->parseRss($response->body(), $limit);
        } catch (\Throwable $e) {
            Log::warning('No se pudieron obtener noticias de Chanco', ['error' => $e->getMessage()]);

            return collect();
        }
    }

    protected function parseRss(string $xml, int $limit): Collection
    {
        $previous = libxml_use_internal_errors(true);
        $feed = simplexml_load_string($xml, SimpleXMLElement::class, LIBXML_NOCDATA);
        libxml_clear_errors();
        libxml_use_internal_errors($previous);

        if ($feed === false || ! isset($feed->channel->item)) {
            return collect();
        }

        $items = [];
        foreach ($feed->channel->item as $item) {
            if (count($items) >= $limit) {
                break;
            }

            $title = trim(strip_tags((string) $item->title));
            $link = trim((string) $item->link);

            if ($title === '' || $link === '') {
                continue;
            }

            $items[] = [
                'titulo' => html_entity_decode($title, ENT_QUOTES | ENT_HTML5, 'UTF-8'),
                'url' => $link,
                'fecha' => $this->formatFecha((string) ($item->pubDate ?? '')),
                'img' => $this->extractImageFromRssItem($item),
            ];
        }

        return collect($items);
    }

    protected function extractImageFromRssItem(SimpleXMLElement $item): ?string
    {
        $namespaces = $item->getNamespaces(true);

        if (isset($namespaces['media'])) {
            $media = $item->children($namespaces['media']);
            if (isset($media->content)) {
                $url = (string) $media->content->attributes()->url;
                if ($url !== '') {
                    return $url;
                }
            }
            if (isset($media->thumbnail)) {
                $url = (string) $media->thumbnail->attributes()->url;
                if ($url !== '') {
                    return $url;
                }
            }
        }

        if (isset($item->enclosure)) {
            $url = (string) $item->enclosure->attributes()->url;
            $type = (string) $item->enclosure->attributes()->type;
            if ($url !== '' && str_starts_with($type, 'image/')) {
                return $url;
            }
        }

        $contentNs = $namespaces['content'] ?? 'http://purl.org/rss/1.0/modules/content/';
        $content = $item->children($contentNs)->encoded ?? null;
        if ($content && preg_match('/<img[^>]+src=["\']([^"\']+)["\']/i', (string) $content, $matches)) {
            return $matches[1];
        }

        return null;
    }

    protected function formatFecha(?string $date): string
    {
        if (! $date) {
            return '';
        }

        try {
            $formatted = Carbon::parse($date)->locale('es')->translatedFormat('d M Y');

            return strtoupper(str_replace('.', '', $formatted));
        } catch (\Throwable) {
            return '';
        }
    }

    protected function httpHeaders(): array
    {
        return [
            'User-Agent' => 'PortalCiudadano/1.0 (+https://portalvecino.chanco.cl)',
            'Accept' => 'application/json, application/rss+xml, application/xml, text/xml, */*',
        ];
    }
}
