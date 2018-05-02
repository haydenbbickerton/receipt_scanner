<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddInfoToReceiptsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('receipts', function (Blueprint $table) {
            $table->decimal('totalAmount', 8, 2)->nullable();
            $table->decimal('taxAmount', 8, 2)->nullable();
            $table->string('merchantName', 50)->nullable();
            $table->date('date')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('receipts', function (Blueprint $table) {
            $table->dropColumn('totalAmount');
            $table->dropColumn('taxAmount');
            $table->dropColumn('merchantName');
            $table->dropColumn('date');
        });
    }
}
