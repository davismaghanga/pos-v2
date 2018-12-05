<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreatePaymentConfirmationsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('payment_confirmations', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('trans_amount', 11);
			$table->string('bill_ref_number', 200);
			$table->string('trans_type', 200);
			$table->string('trans_id', 200);
			$table->string('trans_time', 200);
			$table->string('business_short_code', 200);
			$table->string('invoice_no', 200);
			$table->string('org_account_bal', 200);
			$table->string('third_party_trans_id', 200);
			$table->string('msisdn', 200);
			$table->string('kyc_name', 200);
			$table->integer('user_id')->default(0);
			$table->integer('state')->default(0);
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
		Schema::drop('payment_confirmations');
	}

}
