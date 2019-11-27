<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateUserAnswerTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('user_answer', function(Blueprint $table)
		{
			$table->bigInteger('id', true)->unsigned();
			$table->bigInteger('user_id')->unsigned()->comment('用户ID');
			$table->bigInteger('paper_id')->unsigned()->comment('考卷ID');
			$table->text('content')->comment('用户作答内容');
			$table->bigInteger('answer_frequency')->comment('答题次数');
			$table->timestamps();
			$table->index(['user_id','paper_id']);
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('user_answer');
	}

}
