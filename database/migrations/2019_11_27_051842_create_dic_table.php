<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateDicTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('dic', function(Blueprint $table)
		{
			$table->bigInteger('id', true)->unsigned();
			$table->string('en_name')->comment('字典英文名称');
			$table->string('cn_name')->comment('字典中文名称');
			$table->text('entry')->comment('字典条目');
			$table->string('description')->comment('字典说明');
			$table->timestamps();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('dic');
	}

}
