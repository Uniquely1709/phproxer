<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Series extends Model
{
    use HasFactory;

    protected $table = 'series';

    public $fillable = [
        'TitleEN','TitleORG', 'TitleGER', 'ProxerId', 'Completed', 'Episodes', 'Scraped', 'Downloaded',  'res',
    ];

    public function episodes()
    {
        return $this->hasMany(Episodes::class);
    }
}
