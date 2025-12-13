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
        Schema::create('posts', function (Blueprint $table) {
            $table->id();
            
            // Nội dung bài viết
            $table->string('title');
            $table->json('content')->comment('Editor.js format');
            $table->text('excerpt')->nullable()->comment('Tóm tắt bài viết');
            
            // Thông tin đăng bài
            $table->enum('status', ['pending', 'posted'])->default('pending');
            $table->dateTime('post_day')->nullable()->comment('Thời gian lên lịch/đăng bài');
            
            // Tác giả
            $table->foreignId('writer_id')
                  ->constrained('users')
                  ->onDelete('cascade')
                  ->comment('Foreign key: user với role writer');
            
            // Thống kê & Engagement
            $table->integer('views')->default(0);
            $table->integer('likes')->default(0);
            
            // Hình ảnh thumbnail
            $table->string('featured_image')->nullable();
            
            // Phân loại: news, events, clubs, student-life.
            $table->string('category')->nullable();
            
            $table->timestamps();
            
            $table->index('writer_id');
            $table->index('status');
            $table->index('post_day');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('posts');
    }
};
