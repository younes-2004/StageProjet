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
        Schema::create('transferts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('dossier_id')->constrained('dossiers')->onDelete('cascade');
            $table->foreignId('service_source_id')->constrained('services')->onDelete('cascade');
            $table->foreignId('service_destination_id')->constrained('services')->onDelete('cascade');
            $table->foreignId('user_source_id')->constrained('users')->onDelete('cascade'); 
            $table->foreignId('user_destination_id')->nullable()->constrained('users')->onDelete('set null'); 
            $table->datetime('date_envoi')->nullable(); 
            $table->datetime('date_reception')->nullable(); 
            $table->enum('statut', ['envoyé', 'reçu', 'validé', 'refusé', 'réaffectation']); 
            $table->text('commentaire')->nullable();
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
        Schema::dropIfExists('transferts');
    }
};