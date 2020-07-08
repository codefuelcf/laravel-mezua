<?php

/**
 * Laravel Sms Package - Mezua
 */

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSmsesTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('sms', function (Blueprint $table) {
			$table->bigIncrements('id');
			$table->unsignedBigInteger('sms_api_id');
			$table->string('receiver_phone', 25);
			$table->string('message', 1000);
			$table->enum('status', ['sent', 'queued']);
			$table->text('response')->nullable();
			$table->timestamps();

			$table->index('sms_api_id');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::dropIfExists('smses');
	}
}
