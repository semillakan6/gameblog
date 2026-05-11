<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('comments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('post_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('parent_comment_id')->nullable()->constrained('comments')->cascadeOnDelete();
            $table->string('author_name', 100)->nullable();
            $table->string('author_email', 150)->nullable();
            $table->text('content');
            $table->enum('status', ['pending', 'approved', 'spam', 'deleted'])->default('pending');
            $table->timestamps();

            $table->index('post_id', 'idx_comments_post');
            $table->index('status', 'idx_comments_status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('comments');
    }
};
