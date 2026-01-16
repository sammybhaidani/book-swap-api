<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('genres', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->timestamps();
        });

        Schema::create('books', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('author');
            $table->foreignId('genre_id')->constrained()->onDelete('cascade');
            $table->string('image')->nullable();
            $table->text('blurb')->nullable();
            $table->integer('year')->nullable();
            $table->integer('page_count')->nullable();
            $table->boolean('available')->default(true);

            $table->string('claimed_by_name')->nullable();
            $table->string('claimed_by_email')->nullable();

            $table->timestamps();
        });

        Schema::create('reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('book_id')->constrained()->onDelete('cascade');
            $table->string('reviewer_name');
            $table->text('review_text');
            $table->integer('rating');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reviews');
        Schema::dropIfExists('books');
        Schema::dropIfExists('genres');
    }
};
