<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPeriodToUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->date('date_init')->nullable()->default(null);
            $table->date('date_end')->nullable()->default(null);
            $table->date('debit_date')->nullable()->default(null);
            $table->boolean('closed')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->dropColumn('date_init');
            $table->dropColumn('date_end');
            $table->dropColumn('debit_date');
            $table->dropColumn('closed');
        });
    }
}
