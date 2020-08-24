<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnHasInvoiceToTableRentHistory extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('rent_history', function (Blueprint $table) {
            $table->tinyInteger('has_invoice')->default(0)->comment('1=> YES, 0=> NO');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('rent_history', function (Blueprint $table) {
            //
        });
    }
}
