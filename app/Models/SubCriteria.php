<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubCriteria extends Model
{
    use HasFactory;

    protected $fillable = [
        'criteria_id', 'nama_sub', 'nilai',
    ];

    public function criteria()
    {
        return $this->belongsTo(Criteria::class);
    }
}
