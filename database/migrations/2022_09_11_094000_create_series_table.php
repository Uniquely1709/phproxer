<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('series', function (Blueprint $table): void {
            $table->id();
            $table->timestamps();
            $table->string('TitleEN')->nullable();
            $table->string('TitleORG');
            $table->string('TitleGER')->nullable();
            $table->integer('ProxerId')->unique();
            $table->integer('Episodes');
            $table->boolean('Completed')->default(false);
            $table->boolean('Scraped')->default(false);
            $table->boolean('Downloaded')->default(false);
            $table->json('res')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('series');
    }
};
