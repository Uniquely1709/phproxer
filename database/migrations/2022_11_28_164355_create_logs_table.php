<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::create('logs', function (Blueprint $table): void {
            $table->id();
            $table->timestamps();
            $table->string("User");
            $table->text("Entry");
            $table->string("Level");
            $table->json("res")->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('logs');
    }
};
