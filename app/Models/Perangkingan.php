<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Perangkingan extends Model
{
    use HasFactory;

    protected $fillable = [
        'alternative_id',
        'rank',
        'total_score',
        'keterangan',
        'status',
    ];

    public function alternative()
    {
        return $this->belongsTo(Alternative::class, 'alternative_id', 'id');
    }
}
