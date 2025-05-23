<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dossiers_valides', function (Blueprint $table) {
            $table->id();
            $table->foreignId('dossier_id')->constrained('dossiers')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->timestamp('date_validation')->useCurrent()->useCurrentOnUpdate();
            $table->text('commentaire')->nullable();
            $table->text('observations')->nullable();
            $table->timestamps();
        });

        // ATTENTION: Cette migration modifie aussi la table 'receptions'
        // en ajoutant les colonnes 'traite' et 'date_traitement'
        // car elles sont liées fonctionnellement à la validation des dossiers
        if (!Schema::hasColumn('receptions', 'traite')) {
            Schema::table('receptions', function (Blueprint $table) {
                $table->boolean('traite')->default(false);
                $table->timestamp('date_traitement')->nullable();
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('dossiers_valides');
        
        if (Schema::hasColumn('receptions', 'traite')) {
            Schema::table('receptions', function (Blueprint $table) {
                $table->dropColumn(['traite', 'date_traitement']);
            });
        }
    }
};