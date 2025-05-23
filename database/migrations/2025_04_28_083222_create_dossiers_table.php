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
        Schema::create('dossiers', function (Blueprint $table) {
            $table->id(); // Identifiant auto-incrémenté
            $table->string('numero_dossier_judiciaire')->nullable(); 
            $table->string('titre');
            $table->text('contenu');
            $table->timestamp('date_creation')->useCurrent(); 
            $table->enum('statut', [
                'Créé',
                'En attente',
                'Validé',
                'En traitement',
                'Transmis',
                'Réaffecté',
                'Archivé'
            ]);
            $table->foreignId('createur_id')->nullable()->constrained('users')->onDelete('cascade'); 
            $table->foreignId('service_id')->constrained('services')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('dossiers');
    }
};