<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateSysReceiptsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('sys_receipts', function(Blueprint $table)
		{
			$table->increments('id');
			$table->timestamps();
			$table->integer('channel');
			$table->string('code');
			$table->integer('business');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('sys_receipts');
	}

}
