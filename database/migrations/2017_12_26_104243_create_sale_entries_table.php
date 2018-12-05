<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateSaleEntriesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('sale_entries', function(Blueprint $table)
		{
			$table->increments('id');
			$table->timestamps();
			$table->integer('sale');
			$table->integer('product');
			$table->integer('is_service')->default(0);
			$table->integer('quantity')->default(1);
			$table->integer('status')->default(0);
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('sale_entries');
	}

}
