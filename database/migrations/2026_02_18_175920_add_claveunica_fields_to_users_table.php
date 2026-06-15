<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('run', 12)->nullable()->after('email');
            $table->string('dv', 1)->nullable()->after('run');
            $table->string('claveunica_id')->nullable()->unique()->after('dv');
            // Hacer email y password nullable para usuarios de Clave Única
            $table->string('email')->nullable()->change();
            $table->string('password')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['run', 'dv', 'claveunica_id']);
            // Revertir cambios de nullable
            $table->string('email')->nullable(false)->change();
            $table->string('password')->nullable(false)->change();
        });
    }
};
