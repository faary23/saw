<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('criterias', function (Blueprint $table) {
            $table->text('keterangan')->nullable()->after('weight'); // Menambahkan kolom setelah 'weight'
        });
    }
};
