<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateTopicTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('topic', function(Blueprint $table)
		{
			$table->bigInteger('id', true)->unsigned();
			$table->bigInteger('topic_type_id')->unsigned()->comment('题目类型');
			$table->text('content', 65535)->comment('题目内容');
			$table->text('media')->comment('题目媒体资源');
			$table->text('option')->comment('题目选项');
			$table->text('answer')->comment('题目答案，非选项题的时候，需要填写此项');
			$table->text('answer_analysis', 65535)->comment('题目答案解析');
			$table->bigInteger('created_by')->unsigned()->comment('创建人ID');
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
		Schema::drop('topic');
	}

}
