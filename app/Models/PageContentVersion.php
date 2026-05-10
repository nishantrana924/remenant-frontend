<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PageContentVersion extends Model
{
    protected $fillable = [
        'page_content_id',
        'content',
        'status',
        'user_id',
        'version_note'
    ];

    protected $casts = [
        'content' => 'array'
    ];

    public function pageContent()
    {
        return $this->belongsTo(PageContent::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
