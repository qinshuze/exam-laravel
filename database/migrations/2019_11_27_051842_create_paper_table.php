<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreatePaperTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('paper', function(Blueprint $table)
		{
			$table->bigInteger('id', true)->unsigned();
			$table->string('title')->comment('考卷标题');
			$table->string('front_cover')->comment('考卷封面');
			$table->bigInteger('paper_type_id')->unsigned()->comment('考卷类型ID');
			$table->bigInteger('answer_number')->unsigned()->comment('答题人数');
			$table->bigInteger('topic_number')->unsigned()->comment('题目数量');
			$table->boolean('status')->comment('考卷状态');
			$table->string('description')->comment('考卷说明');
			$table->bigInteger('created_by')->unsigned()->comment('创建人');
			$table->timestamps();
			$table->softDeletes();
			$table->index(['title','status','created_by']);
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('paper');
	}

}
