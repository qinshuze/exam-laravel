<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreatePaperConfigTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('paper_config', function(Blueprint $table)
		{
			$table->bigInteger('paper_id')->unsigned()->primary();
			$table->boolean('mode')->comment('考卷模式');
			$table->boolean('is_show_result')->comment('答题完成后是否允许答题人查看考试结果');
			$table->boolean('is_open')->comment('是否公开，公开后所有人都能看到');
			$table->boolean('is_allow_clone')->comment('是否允许克隆考卷');
			$table->boolean('visit_restriction')->comment('是否启用访问限制');
			$table->string('visit_password')->comment('访问密码(为空则表示不启用密码访问)');
			$table->integer('limited_time')->comment('答题时长(为0则不限制)');
			$table->integer('pass_score')->comment('及格分数(为0则不限制)');
			$table->integer('answer_frequency')->comment('每个用户的答题次数(为0则表示不限制)');
			$table->text('validity_period')->comment('考卷有效期');
			$table->text('organization_method')->comment('组卷方式');
			$table->text('applet_config')->comment('小程序配置');
			$table->text('score_config')->comment('分数设置');
			$table->text('topic_type_description')->comment('分数设置');
			$table->text('archives')->comment('考生档案');
			$table->text('custom_archives')->nullable()->comment('用户自定义档案');
			$table->softDeletes();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('paper_config');
	}

}
