<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateUploadFileTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('upload_file', function(Blueprint $table)
		{
			$table->bigInteger('id', true)->unsigned();
			$table->string('name')->comment('文件原名');
			$table->string('path')->comment('文件存储路径');
			$table->string('suffix')->comment('文件后缀名');
			$table->bigInteger('size')->unsigned()->comment('文件大小[bit]');
			$table->bigInteger('created_by')->unsigned()->comment('上传人');
			$table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('upload_file');
	}

}
