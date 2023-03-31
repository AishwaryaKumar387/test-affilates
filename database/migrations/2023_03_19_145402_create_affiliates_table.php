<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('affiliates', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('affiliate_id')->nullable();
            $table->foreign('affiliate_id', 'affiliate_id_fk_4734344')->references('id')->on('affiliates')->onDelete('cascade');
            
            $table->string('name');
            $table->string('company_name');
            $table->text('address');
            $table->text('phone');
            $table->unsignedTinyInteger('status')->default(1);
            
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('affiliates');
    }
};
