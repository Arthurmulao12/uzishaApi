<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInvoicesdetailsdefectsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('invoicesdetailsdefects', function (Blueprint $table) {
            $table->id();
            $table->double('quantity');
            $table->bigInteger('detail_id')->unsigned();
            $table->foreign('detail_id')->references('id')->on('invoice_details'); 
            $table->bigInteger('defect_id')->unsigned();
            $table->foreign('defect_id')->references('id')->on('defects');
            $table->string('observation');
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
        Schema::dropIfExists('invoicesdetailsdefects');
    }
}
