<?php

namespace instance5
{
	function init() {}
	
	function get_shoplist(){
		if (eval(__MAGIC__)) return $___RET_VALUE; 
		eval(import_module('sys','instance5'));
		if ($gametype==15){
			$file = __DIR__.'/config/shopitem.config.php';
			$sl5 = openfile($file);
			return $sl5;
		}else return $chprocess();
	}
	
	function checkcombo(){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','map','gameflow_combo'));
		if (($gametype==15)&&($areanum<$areaadd*3)&&($alivenum>0)){
			return;
		}
		$chprocess();
	}
	
	function rs_game($xmode = 0) 	//开局天气初始化
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		
		$chprocess($xmode);
		
		eval(import_module('sys'));
		if (($gametype==15)&&($xmode & 2)) 
		{
			$weather = 1;
		}
	}
	
	function act(){//一禁之前每次行动后判断并记录最大金钱数
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','player','map'));
		$chprocess();
		if($gametype==15 && $areanum<$areaadd){
			if($money > \skillbase\skill_getvalue(313,'max_money')) 
				\skillbase\skill_setvalue(313,'max_money',$money);
		}
	}
	
//	function add_once_area($atime)	//一禁时记录玩家身上金钱
//	{
//		if (eval(__MAGIC__)) return $___RET_VALUE;
//		eval(import_module('sys','map'));
//		$chprocess($atime);
//		if($gametype==15 && $areanum==$areaadd) {
//			$result = $db->query("SELECT pid FROM {$tablepre}players WHERE type=0");
//			while($apid = $db->fetch_array($result)) {
//				$apid = $apid['pid'];
//				$apdata = \skillbase\fetch_playerdata_by_pid($apid);
//				\skillbase\skill_setvalue(313,'area1_money',$apdata['money'],$apdata);
//			}
//		}
//	}
	
	function check_addarea_gameover($atime){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','map'));
		if ($gametype==15){
			if($alivenum <= 0){
				\sys\gameover($atime,'end1');
				return;
			}
			if (ceil($areanum/$areaadd) >=3){//限时3禁 
				$result = $db->query("SELECT * FROM {$tablepre}players WHERE hp>0 AND type=0");
				$wdata = $db->fetch_array($result);
				$winner = $wdata['name'];
				\sys\gameover($atime,'end8',$winner);
				return;
			}
			\sys\rs_game(16+32);
			return;
		}
		$chprocess($atime);	
	}
	
	function itemuse(&$theitem) //使用伐木解除钥匙立刻结束游戏
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		
		eval(import_module('sys','player','itemmain','logger'));
		
		$itm=&$theitem['itm']; $itmk=&$theitem['itmk'];
		$itme=&$theitem['itme']; $itms=&$theitem['itms']; $itmsk=&$theitem['itmsk'];
		
		if (strpos ( $itmk, 'Y' ) === 0 || strpos ( $itmk, 'Z' ) === 0) {
			if ($itm == '伐木解除钥匙') 
			{
				$url = 'end.php';
				\sys\gameover ( $now, 'end8', $name );
			}
		}
		$chprocess($theitem);
	}
	
	function get_club_choice_array()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','player','clubbase'));
		
		if ($gametype==15) {
			$res = array(0);
			foreach($clublist as $key=>$val){
				if ($val['probability']) $res[] = $key;
			}
			return $res;
		}
		$chprocess();
	}
}

?>