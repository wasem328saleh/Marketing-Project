<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->integer('o_price');
            $table->integer('s_price');
            $table->text('description');
            $table->text('phone_number');
            $table->date('exp_date');
            $table->text('image_url');
            $table->integer('quantity')->default(1);
            $table->string('category');
            $table->integer('s1');
            $table->integer('Counter_days1');
            $table->integer('s2');
            $table->integer('Counter_days2');
            $table->integer('s3');
            $table->integer('count_views')->default(0)->nullable();
            $table->integer('count_comments')->default(0)->nullable();
            $table->integer('count_likes')->nullable();
            $table->foreignId('user_id')->references('id')->on('users')->cascadeOnDelete();
            $table->foreignId('category_id')->references('id')->on('categories')->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('products');
    }
}
