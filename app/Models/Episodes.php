<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Episodes extends Model
{
    use HasFactory;

    protected $table = 'episodes';

    public $fillable = [
        'series_id', 'EpisodeId', 'EpisodeName', 'Downloaded', 'Retries', 'res','Published', 'DownloadUrl', 'Skipped', 'epNumber'
    ];

    public function serie()
    {
        return $this->belongsTo(Series::class, 'series_id', 'id');
    }

    public function parent(): HasOne
    {
        return $this->hasOne(Series::class, 'id', 'series_id');
    }

    public function next()
    {
        $ep = self::getAttribute('epNumber')+ 1;
        return self::query()
            ->where('series_id', self::getAttribute('series_id'))
            ->where('epNumber', $ep)
            ->first();
    }
}
