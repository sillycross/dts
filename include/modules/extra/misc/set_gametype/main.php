<?php

namespace set_gametype
{
	function init() {}
	
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
			} elseif(0==$gametype) {//经典房交替开启经典和卡片模式。
				$gametype = 4;
			} else {
				$gametype = 0;
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
			$show[] = dump_template(MOD_SET_GAMETYPE_NEXT_GAMETYPE);
		}
		$ret = $chprocess($show);
		return $ret;
	}
	
	function user_set_gamevars_process($gamevar_key,$gamevar_val,&$valid_gamevars){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$retlog = $chprocess($gamevar_key,$gamevar_val,$valid_gamevars);
		if('next_gametype' == $gamevar_key) {
			eval(import_module('sys'));
			$retlog = '已将下一局游戏的'.$gamevarsinfo[$gamevar_key].'设定为'.$gtinfo[$gamevar_val].'。';
			addnews(0,'setnextgametype', $winner, $gamevar_val);
		}
		return $retlog;
	}
	
	function parse_news($nid, $news, $hour, $min, $sec, $a, $b, $c, $d, $e, $exarr = array())
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','player'));
		
		if($news == 'setnextgametype') 
			return "<li id=\"nid$nid\">{$hour}时{$min}分{$sec}秒，<span class=\"lime\">{$a}把下一局游戏设置为「{$gtinfo[$b]}」。</span></li>";
		return $chprocess($nid, $news, $hour, $min, $sec, $a, $b, $c, $d, $e, $exarr);
	}
}

?>