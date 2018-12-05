<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateBusinessesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('businesses', function(Blueprint $table)
		{
			$table->increments('id');
			$table->timestamps();
			$table->string('name');
			$table->string('location');
			$table->string('phone');
			$table->string('email');
			$table->integer('pay_plan')->default(0);
			$table->integer('pay_status')->default(0);
			$table->integer('pay_balance')->default(0);
			//set to nullable
			$table->string('logo')->nullable();
			$table->integer('sms_has_custom')->default(0);
			$table->string('sms_extension')->nullable()->default('Thank you.');
			$table->string('sms_greeting')->default('Hello ');
			//set to nullable
			$table->string('sms_sender')->nullable();

			//set to nullable
			$table->string('sms_api_key')->nullable();
			$table->integer('has_loyalty')->default(0);
			$table->integer('loyalty_earn_rate')->default(0);
			$table->integer('loyalty_redeem_rate')->default(0);
			$table->integer('loyalty_min_earn')->default(0);
			$table->integer('units_consumed')->default(0);
			$table->float('minimum_redeemable_points', 10, 0)->default(0);
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('businesses');
	}

}
