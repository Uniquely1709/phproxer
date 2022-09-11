<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Episodes extends Model
{
    use HasFactory;

    protected $table = 'episodes';

    public $fillable = [
        'series_id', 'EpisodeId', 'EpisodeName', 'Downloaded', 'Retries', 'res','Published', 'DownloadUrl',
    ];

    public function serie()
    {
        return $this->belongsTo(Series::class, 'series_id', 'id');
    }
}
