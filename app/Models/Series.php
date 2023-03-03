<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Series extends Model
{
    use HasFactory;

    protected $table = 'series';

    public $fillable = [
        'TitleEN','TitleORG', 'TitleGER', 'ProxerId', 'Completed', 'Episodes', 'Scraped', 'Downloaded',  'res', 'Title', 'Season', 'next_episode_id',
    ];

    public function episodes()
    {
        return $this->hasMany(Episodes::class);
    }

    public function nextEpisode()
    {
        return $this->hasOne(Episodes::class, 'id', 'next_episode_id')->first();
    }

//    public function unpublishedEpisode()
//    {
//        return $this->episodes()
//            ->where('series_id', $this->id)
//            ->where('Published', true)
//            ->where('Downloaded', false)
//            ->where('Skipped', false)
//            ->orderByDesc('created_at')
//            ->first();
//    }

    public static function inProgress()
    {
        return self::query()->where('Completed', false)
            ->whereNotNull('next_episode_id')
            ->get();
    }
}
