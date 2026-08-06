<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (! Schema::hasColumn('users', 'limite_alerta_percentual')) {
                // Guarda o último limiar (80/90/100) já notificado por e-mail,
                // pra não reenviar o mesmo aviso a cada nova receita cadastrada.
                // É resetado todo dia 1º de janeiro pelo comando mei:resetar-limite-anual.
                $table->unsignedTinyInteger('limite_alerta_percentual')->nullable()->after('avatar');
            }
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('limite_alerta_percentual');
        });
    }
};
