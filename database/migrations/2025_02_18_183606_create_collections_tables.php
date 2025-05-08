<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('games', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->timestamps();
        });

        Schema::create('expansions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('game_id')->constrained('games')->onDelete('cascade');
            $table->string('set');
            $table->string('name');
            $table->timestamps();
        });

        Schema::create('cards', function (Blueprint $table) {
            $table->id();
            $table->foreignId('expansion_id')->constrained('expansions')->onDelete('cascade');
            $table->string('name');
            $table->string('rarity')->nullable();
            $table->string('image_url')->nullable();
            $table->text('other_attributes')->nullable();
            $table->timestamps();
        });

        Schema::create('user_cards', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('card_id')->constrained('cards')->onDelete('cascade');
            $table->integer('quantity')->default(1);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('user_cards');
        Schema::dropIfExists('cards');
        Schema::dropIfExists('expansions');
        Schema::dropIfExists('games');
    }
};
