<?php

/**
 * Laravel Sms Package - Mezua
 */

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSmsApisTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('sms_api', function (Blueprint $table) {
			$table->bigIncrements('id');
			$table->string('name', 100);
			$table->string('slug', 100)->unique();
			$table->string('url', 1000);
			$table->enum('type', ['POST', 'GET']);
			$table->text('data');
			$table->text('headers')->nullable();
			$table->timestamps();
			$table->softDeletes();

			$table->index('slug');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::dropIfExists('sms_apis');
	}
}
