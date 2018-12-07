<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateUsersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('users', function(Blueprint $table)
		{
			$table->increments('id');
			$table->timestamps();
			$table->string('name');
			$table->string('username');
			$table->string('password');
			$table->string('phone');
			$table->integer('business');
			$table->integer('level');
			$table->integer('status')->nullable();
			$table->string('remember_token', 200)->nullable();
			$table->integer('access')->default(0);
			$table->integer('can_send_sms')->default(1);
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('users');
	}

}
