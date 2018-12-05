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
			$table->integer('status')->default(1);
			$table->string('remember_token', 200);
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
