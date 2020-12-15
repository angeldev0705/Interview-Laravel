<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    use HasFactory;

    protected $fillable = ['id', 'name', 'campaign_id'];
    public $timestamps = TRUE;

    public function campaign()
    {
        return $this->belongsTo(Campaign::class,'campaign_id');
    }
}
