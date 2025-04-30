<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddKeteranganToPerangkingansTable extends Migration
{
    public function up()
    {
        Schema::table('perangkingans', function (Blueprint $table) {
            $table->text('keterangan')->nullable(); // Menambahkan kolom keterangan
        });
    }

    public function down()
    {
        Schema::table('perangkingans', function (Blueprint $table) {
            $table->dropColumn('keterangan'); // Menghapus kolom keterangan
        });
    }
}
    