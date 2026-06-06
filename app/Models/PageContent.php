<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Traits\PurifiesHtml;

class PageContent extends Model
{
    use HasFactory, PurifiesHtml;

    public $htmlPurifiable = ['content'];

    protected $fillable = ['slug', 'content'];

    protected $casts = [
        'content' => 'array',
    ];
}
