<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('historique_actions', function (Blueprint $table) {
            // Modifier la colonne ENUM pour inclure "réaffectation"
            $table->enum('action', ['creation', 'modification', 'transfert', 'archivage', 'réaffectation'])->change();
        });
    }

    public function down()
    {
        Schema::table('historique_actions', function (Blueprint $table) {
            // Revenir à la définition précédente
            $table->enum('action', ['creation', 'modification', 'transfert', 'archivage'])->change();
        });
    }
};
