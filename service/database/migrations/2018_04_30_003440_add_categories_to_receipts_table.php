<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCategoriesToReceiptsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('receipts', function (Blueprint $table) {
            $table->string('name', 32)->default('Receipt');
            $table->text('notes')->nullable();
            $table->enum('category', [
                'airfare',
                'vehicle rental',
                'fuel',
                'lodging',
                'meals',
                'phone',
                'entertainment',
                'weapons',
                'other'
            ])->default('other')->index();
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
            $table->dropColumn('name');
            $table->dropColumn('notes');
            $table->dropColumn('category');
        });
    }
}
