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
    // Di model Alternative.php
    public function getDataKriteriaAttribute($value)
    {
        return is_string($value) ? json_decode($value, true) : $value;
    }

    public function getNilaiManualAttribute()
    {
        $result = [];

        foreach ($this->data_kriteria as $criteriaId => $subId) {
            // Jika isinya numeric langsung (bukan subId)
            if (is_numeric($subId)) {
                $result[$criteriaId] = $subId;
            } else {
                $sub = \App\Models\SubCriteria::find($subId);
                if ($sub) {
                    $result[$criteriaId] = $sub->nilai;
                }
            }
        }

        return $result;
    }
}
