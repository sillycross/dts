<?php

namespace wep_b_extra_reloading
{
	function init() 
	{
		
	}
	
	//判定道具是不是能用来当箭装填
	function wep_b_extra_reloading_check(&$theitem)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','player','logger','itemmain'));
		$ret = true;
		//其实按执行逻辑，基本用不上这两条的判定，无所谓就这么写着吧
		if(strpos($wepk, 'WB')!==0){
			$ret = false;
			$log .= '你没有装备弓！<br>';
		}elseif(strpos($theitem['itmk'], 'WC')!==0 && strpos($theitem['itmk'], 'GA')!==0){
			$ret = false;
			$log .= '你不能为弓装填这个东西！<br>';
		}elseif('0' === $theitem['itmn']) {
			//捡到的箭矢不能马上拉弓，避免换箭覆盖itm0的问题
			$ret = false;
			$log .= "你两只手都抓着东西呢，没法马上搭箭。<span class=\"red b\">还是先把东西收进包裹里吧。</span><br>";
		}elseif($nosta == $theitem['itms']) {
			$ret = false;
			$log .= '不能为弓装填耐久值为∞的武器！<br>';
		}elseif(strpos($theitem['itmk'], '|')!==false || strpos($theitem['itmsk'], '|')!==false){
			$ret = false;
			$log .= '你要装填的这个东西怎么也是个弓啊？禁止套娃。<br>';
		}
		return $ret;
	}
	
	//装备着弓而使用投掷武器时，提供一个选择界面
	function itemuse(&$theitem)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		
		eval(import_module('sys','player','itemmain','logger','input'));
		
		$itm=&$theitem['itm']; $itmk=&$theitem['itmk'];
		$itme=&$theitem['itme']; $itms=&$theitem['itms']; $itmsk=&$theitem['itmsk'];
		
		if (strpos ( $itmk, 'WC' ) === 0 && strpos ( $wepk, 'WB' ) === 0)
		{
			if(empty($subcmd)){
				//指令为空，显示选择界面
				ob_start();
				include template(MOD_WEP_B_EXTRA_RELOADING_RELOAD_SELECT);
				$cmd = ob_get_contents();
				ob_end_clean();	
				return;
			}elseif('extra_reloading' == $subcmd){
				//把武器当做箭来装填
				if(wep_b_extra_reloading_check($theitem)){
					\wep_b\itemuse_uga($theitem);
				}
				return;
			}
			//其他指令，正常继续判定（切换武器）
		}
		$chprocess($theitem);
	}
}
?>