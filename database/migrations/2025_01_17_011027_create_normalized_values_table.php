<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNormalizedValuesTable extends Migration
{
    public function up()
    {
        Schema::create('normalized_values', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('alternative_id');
            $table->unsignedBigInteger('criteria_id');
            $table->unsignedBigInteger('sub_criteria_id');
            $table->decimal('sub_criteria_value', 10, 2);
            $table->decimal('max_value', 10, 2);
            $table->decimal('normalized_value', 10, 4);
            $table->timestamps();

            $table->foreign('alternative_id')->references('id')->on('alternatives')->onDelete('cascade');
            $table->foreign('criteria_id')->references('id')->on('criterias')->onDelete('cascade');
            $table->foreign('sub_criteria_id')->references('id')->on('sub_criterias')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('normalized_values');
    }
}
