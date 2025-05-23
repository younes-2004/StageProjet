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
    Schema::create('historique_actions', function (Blueprint $table) {
        $table->id();
        $table->foreignId('dossier_id')->constrained('dossiers')->onDelete('cascade');
        $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
        $table->foreignId('service_id')->constrained('services')->onDelete('cascade');
        $table->enum('action', [
            'creation', 
            'modification', 
            'transfert', 
            'archivage',
            'réaffectation',    
            'validation',       
            'annulation'       
        ])->nullable(); 
        $table->text('description');
        $table->datetime('date_action')->nullable();  
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
        Schema::dropIfExists('historique_actions');
    }
};