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
        Schema::create('episodes', function (Blueprint $table): void {
            $table->id();
            $table->timestamps();
            $table->foreignId('series_id')->constrained()->onDelete('cascade');
            $table->integer('EpisodeID');
            $table->string('EpisodeName')->nullable();
            $table->boolean('Downloaded')->default(false);
            $table->boolean('Published')->default(false);
            $table->string('DownloadUrl')->nullable();
            $table->integer('Retries');
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
        Schema::dropIfExists('episodes');
    }
};
