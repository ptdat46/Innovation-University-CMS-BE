<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Post extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'title',
        'content',
        'excerpt',
        'status',
        'post_day',
        'writer_id',
        'views',
        'likes',
        'featured_image',
        'category',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'content' => 'json',
            'post_day' => 'datetime',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }

    /**
     * Get the user (writer) that wrote the post.
     */
    public function writer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'writer_id');
    }

    /**
     * Scope to filter posts by status.
     */
    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Scope to filter posts by writer.
     */
    public function scopeByWriter($query, $writerId)
    {
        return $query->where('writer_id', $writerId);
    }

    /**
     * Scope to filter posts by category.
     */
    public function scopeByCategory($query, $category)
    {
        return $query->where('category', $category);
    }

    /**
     * Scope to get published posts.
     */
    public function scopePublished($query)
    {
        return $query->where('status', 'posted');
    }

    /**
     * Scope to get pending posts.
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope to get scheduled posts (post_day is in future).
     */
    public function scopeScheduled($query)
    {
        return $query->whereNotNull('post_day')->where('post_day', '>', now());
    }

    public function scopeGetPostsByWriter($query, $writerId)
    {
        return $query->where('writer_id', $writerId)->orderBy('created_at', 'desc')->get();
    }

    public function scopeGetPostById($query, $postId)
    {
        return $query->where('id', $postId)->first();
    }

    public function scopeGetPendingPosts($query)
    {
        return $query->where('status', 'pending')->orderBy('created_at', 'desc')->get();
    }

    //Catagory: news, events, clubs, student-life.
    public function scopeGetNewestPostsByNews($query, $limit = null)
    {
        $result = $query->published()
            ->where('category', 'news')
            ->orderByDesc('created_at');
        
        if ($limit !== null) {
            $result->limit((int) $limit);
        }
        
        return $result->get();
    }
    public function scopeGetNewestPostsByEvents($query, $limit = null)
    {
        $result = $query->published()
            ->where('category', 'events')
            ->orderByDesc('created_at');
        
        if ($limit !== null) {
            $result->limit((int) $limit);
        }
        
        return $result->get();
    }
    public function scopeGetNewestPostsByClubs($query, $limit = null)
    {
        $result = $query->published()
            ->where('category', 'clubs')
            ->orderByDesc('created_at');
        
        if ($limit !== null) {
            $result->limit((int) $limit);
        }
        
        return $result->get();
    }
    public function scopeGetNewestPostsByStudentLife($query, $limit = null)
    {
        $result = $query->published()
            ->where('category', 'student-life')
            ->orderByDesc('created_at');
        
        if ($limit !== null) {
            $result->limit((int) $limit);
        }
        
        return $result->get();
    }
}
