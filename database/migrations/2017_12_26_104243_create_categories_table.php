<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateCategoriesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('categories', function(Blueprint $table)
		{
			$table->increments('id');
			$table->timestamps();
			$table->integer('business');
			$table->string('name');
			$table->integer('has_discount')->default(0);
			$table->float('discount', 10, 0)->default(0);
			$table->float('tax', 10, 0)->default(0);
			$table->string('tax_name');
			$table->integer('tax_is_inclusive')->default(1);
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('categories');
	}

}
