<?php

namespace App\Models;

use App\Models\Chapitre;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Lecon extends Model
{
    use HasFactory;

    protected $fillable = [ 'name', 'chapitre_id', 'content' ];

    public function chapitre ()
    {
        return $this->hasOne(Chapitre::class);
    }
}
