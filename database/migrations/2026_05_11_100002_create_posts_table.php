<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('posts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('author_id')->constrained('users')->cascadeOnDelete();
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('excerpt')->nullable();
            $table->longText('content');
            $table->json('content_json')->nullable();
            $table->string('cover_image_url', 500)->nullable();
            $table->enum('status', ['draft', 'review', 'published', 'archived'])->default('draft');
            $table->enum('visibility', ['public', 'private', 'unlisted'])->default('public');
            $table->dateTime('published_at')->nullable();
            $table->unsignedInteger('reading_time_minutes')->default(1);
            $table->unsignedBigInteger('view_count')->default(0);
            $table->boolean('is_featured')->default(false);
            $table->string('seo_title')->nullable();
            $table->string('seo_description', 500)->nullable();
            $table->timestamps();

            $table->index('status', 'idx_posts_status');
            $table->index('published_at', 'idx_posts_published');
            $table->index('author_id', 'idx_posts_author');
        });

        if (Schema::getConnection()->getDriverName() === 'mysql') {
            Schema::table('posts', function (Blueprint $table) {
                $table->fullText(['title', 'excerpt', 'content'], 'ft_posts_search');
            });
        }
    }

    public function down(): void
    {
        if (Schema::getConnection()->getDriverName() === 'mysql') {
            Schema::table('posts', function (Blueprint $table) {
                $table->dropFullText('ft_posts_search');
            });
        }

        Schema::dropIfExists('posts');
    }
};
