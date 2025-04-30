<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Alternative extends Authenticatable
{
    use HasFactory;

    protected $fillable = ['nama', 'nim', 'password', 'data_kriteria', 'jurusan', 'foto'];

    protected $casts = [
        'data_kriteria' => 'array',
    ];

    public function perangkingan()
    {
        return $this->hasOne(Perangkingan::class, 'alternative_id', 'id');
    }

    public function weightedValues()
    {
        return $this->hasMany(WeightedValue::class, 'alternative_id');
    }

    public function getManualNilaiAttribute()
{
    $result = [];

    foreach ($this->data_kriteria as $criteriaId => $subId) {
        $sub = \App\Models\SubCriteria::find($subId);
        if ($sub) {
            $result[$criteriaId] = $sub->nilai;
        }
    }

    return $result;
}

}
