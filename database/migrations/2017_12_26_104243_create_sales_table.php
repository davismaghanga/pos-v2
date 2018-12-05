<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateSalesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('sales', function(Blueprint $table)
		{
			$table->increments('id');
			$table->timestamps();
			$table->integer('business');
			$table->string('invoice')->nullable();
			$table->integer('by_user');
			$table->integer('customer')->nullable();
			$table->integer('amount')->nullable();
			$table->integer('discount')->default(0);
			$table->integer('tax_in')->default(0);
			$table->integer('tax_ex')->default(0);
			$table->integer('balance')->default(0);
			$table->integer('status');
			$table->integer('processing_time')->default(0);
			$table->string('expected_completion', 200)->default('0');
			$table->integer('job_status')->default(3);
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('sales');
	}

}
