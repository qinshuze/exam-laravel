<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreatePaperApplyTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('paper_apply', function(Blueprint $table)
		{
			$table->bigInteger('id', true)->unsigned();
			$table->bigInteger('user_id')->unsigned()->comment('用户ID');
			$table->bigInteger('paper_id')->unsigned()->comment('考卷ID');
			$table->boolean('status')->comment('状态');
			$table->bigInteger('approved by')->unsigned()->nullable()->comment('审批人');
			$table->timestamp('approval_at')->nullable()->default(DB::raw('CURRENT_TIMESTAMP'))->comment('审批时间');
			$table->timestamps();
			$table->softDeletes();
			$table->index(['user_id','paper_id','status']);
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('paper_apply');
	}

}
