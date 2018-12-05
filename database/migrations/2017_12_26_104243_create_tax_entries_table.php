<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateTaxEntriesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('tax_entries', function(Blueprint $table)
		{
			$table->increments('id');
			$table->timestamps();
			$table->integer('business');
			$table->integer('category');
			$table->string('tax_type');
			$table->integer('is_inclusive')->default(1);
			$table->float('tax', 10, 0)->default(0);
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('tax_entries');
	}

}
