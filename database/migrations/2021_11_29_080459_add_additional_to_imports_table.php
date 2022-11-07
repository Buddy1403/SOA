<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAdditionalToImportsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('imports', function (Blueprint $table) {
            $table->string('totalCommission')->default(0);
            $table->string('totalOthers')->default(0);
            $table->string('salesDeductionTablet')->default(0);
            $table->string('netOperatorsCommission')->default(0);
            $table->string('otherCommissionIntel05')->default(0);
            $table->string('consolidatorsCommission')->default(0);
            $table->string('safetyFund')->default(0);
            $table->string('paymentForOutstandingBalance')->default(0);
            $table->string('otherDeductiblesFromCommission')->default(0);
            $table->string('systemErrorCOArmsi')->default(0);
            $table->string('cashLoad')->default(0);
            $table->string('cashWithdrawal')->default(0);

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('imports', function (Blueprint $table) {
            //
        });
    }
}
