<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreatePaperTopicTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('paper_topic', function(Blueprint $table)
		{
			$table->bigInteger('id', true)->unsigned();
			$table->bigInteger('paper_id')->unsigned()->comment('考卷ID');
			$table->bigInteger('topic_id')->unsigned()->comment('题目ID');
			$table->integer('score')->comment('题目分数');
			$table->integer('weight')->comment('权重');
			$table->softDeletes();
			$table->index(['paper_id','topic_id']);
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('paper_topic');
	}

}
