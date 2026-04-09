<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Blog extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'photo',
        'title',
        'category',
        'publish_date',
        'short_caption',
        'content',
        'sync_insta',
        'status',
    ];

    protected $casts = [
        'publish_date' => 'date',
        'sync_insta' => 'boolean',
    ];

    public function scopePublished($query)
    {
        return $query->where('status', 'published');
    }

    public function getFormattedDateAttribute()
    {
        return $this->publish_date
            ? $this->publish_date->format('d M Y')
            : null;
    }


    public function isPublished()
    {
        return $this->status === 'published';
    }
}