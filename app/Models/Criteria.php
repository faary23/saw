<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Criteria extends Model
{
    use HasFactory;

    protected $fillable = ['kode', 'name', 'type', 'weight', 'keterangan'];

    // Menambahkan relasi dengan SubCriteria
    public function subCriterias()
    {
        return $this->hasMany(SubCriteria::class);
    }

    
}
