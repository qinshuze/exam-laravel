<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateUserApplyTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('user_apply', function(Blueprint $table)
		{
			$table->bigInteger('id', true)->unsigned();
			$table->bigInteger('user_id')->unsigned()->index('user_id')->comment('用户ID');
			$table->string('username')->comment('用户名');
			$table->string('wechat')->comment('微信号');
			$table->boolean('status')->comment('状态');
			$table->string('phone')->nullable()->comment('手机号');
			$table->string('description')->comment('描述');
			$table->bigInteger('approval_by')->nullable()->unique('approval_by')->comment('审批人');
			$table->dateTime('approval_at')->nullable()->comment('审批时间');
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
		Schema::drop('user_apply');
	}

}
