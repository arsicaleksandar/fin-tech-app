<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('funds', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->bigInteger('fund_category_id')->unsigned()->index();
            $table->foreign('fund_category_id')->references('id')->on('fund_categories')->onDelete('cascade');
            $table->bigInteger('fund_sub_category_id')->unsigned()->index();
            $table->foreign('fund_sub_category_id')->references('id')->on('fund_sub_categories')->onDelete('cascade');
            $table->string('isin');
            $table->string('wkn');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('funds');
    }
};
