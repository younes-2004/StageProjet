<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReceptionsTable extends Migration
{
    public function up()
    {
        Schema::create('receptions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('dossier_id'); // Référence au dossier
            $table->unsignedBigInteger('user_id'); // Utilisateur destinataire
            $table->timestamp('date_reception')->nullable(); // Date de réception
            $table->timestamps();

            // Clés étrangères
            $table->foreign('dossier_id')->references('id')->on('dossiers')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('receptions');
    }
}
