<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWeightedValuesTable extends Migration
{
    public function up()
    {
        Schema::create('weighted_values', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('alternative_id');
            $table->unsignedBigInteger('criteria_id');
            $table->decimal('normalized_value', 10, 4);
            $table->decimal('weight', 10, 4);
            $table->decimal('weighted_value', 10, 4);
            $table->timestamps();

            $table->foreign('alternative_id')->references('id')->on('alternatives')->onDelete('cascade');
            $table->foreign('criteria_id')->references('id')->on('criterias')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('weighted_values');
    }
}
