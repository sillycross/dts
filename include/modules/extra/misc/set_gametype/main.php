<?php

namespace set_gametype
{
	$set_gametype_allow_list = Array(0,4,6);//只允许设置到的游戏类型
	
	function init() {}
	
	//判定要设置到的游戏类型是否被允许
	function check_gametype_set_valid($gt){
		if (eval(__MAGIC__)) return $___RET_VALUE; 
		eval(import_module('set_gametype'));
		if(in_array($gt, $set_gametype_allow_list)) return true;
		return false;
	}
	
	function user_set_gamevars_list_init($registered_gamevars = array()){
		if (eval(__MAGIC__)) return $___RET_VALUE; 
		$registered_gamevars = $chprocess($registered_gamevars);
		$registered_gamevars[] = 'next_gametype';
		return $registered_gamevars;
	}
	
	function reset_gametype()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE; 
		eval(import_module('sys'));
		if(!$groomid){//只对经典房生效
			if(isset($gamevars['next_gametype'])) {//如果指定了下一局模式
				$gametype = (int)($gamevars['next_gametype']);
				unset($gamevars['next_gametype']);
			} elseif(4==$gametype) {//经典房交替开启自选卡片和随机卡片模式。
				$gametype = 6;
			} else {
				$gametype = 4;
			}
		}
	}
	
//	function user_set_next_gametype($ngametype){
//		if (eval(__MAGIC__)) return $___RET_VALUE; 
//		eval(import_module('sys'));
//		$ret = \sys\user_set_gamevars(array('gametype' => $ngametype));
//		return $ret;
//	}

	
	function user_display_gamevars_setting($show = array()){
		if (eval(__MAGIC__)) return $___RET_VALUE; 
		eval(import_module('sys'));
		if(in_array($winmode, array(2,3,5,7))){
//			$show[] = array(
//				'desc' => $gamevarsinfo['gametype'],
//				'cont' => array(
//					'type' => 'select',
//					'varname' => 'gametype',
//					'options' => array(
//						$gtinfo[0] => 0,
//						$gtinfo[4] => 4,
//					)
//				)
//			);
			$show[] = dump_template(get_gametype_setting_html());
		}
		$ret = $chprocess($show);
		return $ret;
	}
	
	function get_gametype_setting_html()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return MOD_SET_GAMETYPE_NEXT_GAMETYPE;
	}
	
	function user_set_gamevars_process($gamevar_key,$gamevar_val,&$valid_gamevars){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$retlog = $chprocess($gamevar_key,$gamevar_val,$valid_gamevars);
		if('next_gametype' == $gamevar_key) {
			eval(import_module('sys','player'));
			$retlog = '已将下一局游戏的'.$gamevarsinfo[$gamevar_key].'设定为'.$gtinfo[$gamevar_val].'。';
			addnews(0,'setnextgametype', $name, $gamevar_val);
		}
		return $retlog;
	}
	
	function parse_news($nid, $news, $hour, $min, $sec, $a, $b, $c, $d, $e, $exarr = array())
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','player'));
		
		if($news == 'setnextgametype') 
			return "<li id=\"nid$nid\">{$hour}时{$min}分{$sec}秒，<span class=\"lime b\">{$a}把下一局游戏设置为「{$gtinfo[$b]}」。</span></li>";
		return $chprocess($nid, $news, $hour, $min, $sec, $a, $b, $c, $d, $e, $exarr);
	}
}

?>