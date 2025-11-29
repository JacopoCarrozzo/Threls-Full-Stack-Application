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
        Schema::create('menu_links', function (Blueprint $table) {
            $table->id();

            //Relation 1: Which menu does this link belong to?
            $table->foreignId('menu_id')->constrained()->cascadeOnDelete();

            //Relationship 2: Which page does this link point to (can be null if it is an external link)
            $table->foreignId('page_id')->nullable()->constrained()->cascadeOnDelete();

            //Link content
            $table->string('label'); //The link text (e.g. "Contact")
            $table->string('url')->nullable(); //Optional external URL (if page_id is null)
            $table->integer('order')->default(0); //Display order
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('menu_links');
    }
};
