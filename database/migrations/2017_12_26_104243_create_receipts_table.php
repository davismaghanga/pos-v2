<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateReceiptsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('receipts', function(Blueprint $table)
		{
			$table->increments('id');
			$table->timestamps();
			$table->integer('business');
			$table->integer('channel');
			$table->string('code');
			$table->integer('sale');
			$table->string('phone')->nullable();
			$table->integer('customer')->nullable();
			$table->integer('amount')->default(0);
			$table->string('remarks')->nullable();
			$table->integer('status')->default(0);
			$table->integer('cashier')->default(0);
			$table->integer('balance')->default(0);
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('receipts');
	}

}
