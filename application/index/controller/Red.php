<?php

namespace app\index\controller;

use think\Request;
use func\PassN;
use think\Db;
/**
 * 玩家个人
 */
class Red extends Base
{
	public function index(){
		$arr = [
			'us_red_time' => $this->user['us_red_time'],
		];
		$this->msg($arr);
	}
	public function yao(){
		if($this->user['us_red_time']==0){
			$this->e_msg('您没有摇一摇的权限');
		}
		$min = cache('setting')['red_min'];
		$max = cache('setting')['red_max'];
		$number = rand($min,$max)/100;

		$brr = [
			'us_red_time' => $this->user['us_red_time'] - 1,
		];
		Db::name('user')->where('id',$this->user['id'])->update($brr);
		model('User')::usWalChange($this->user['id'],input('money'),12);

		$arr = [
			'num' => $number,
			'us_red_time' => $brr['us_red_time'],
		];
		$this->msg($arr);
	}
	// public function ling(){
	
		// 	if(input('money')){
				
		// 	}else{
		// 		$this->e_msg('金额不能是0');
		// 	}
		
	// }
}