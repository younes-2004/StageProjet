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
            $table->renameColumn('numéro du dossier judiciaire', 'numero_dossier_judiciaire'); // Numéro du dossier au tribunal
            $table->string('titre');
            $table->text('contenu');
            $table->date('date_creation');
            $table->enum('statut', [
                'Créé',
                'En attente',
                'Validé',
                'En traitement',
                'Transmis',
                'Réaffecté',
                'Archivé'
            ]);
            $table->foreignId('createur_id')->constrained('users')->onDelete('cascade'); // Clé étrangère vers `users`
            $table->foreignId('service_id')->constrained('services')->onDelete('cascade'); // Clé étrangère vers `services`
            $table->string('statut');
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