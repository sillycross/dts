<?php

namespace cardbase
{
	function init() {}
	
	function get_card($ci,$pa=NULL)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','player'));
		if ($pa==NULL){
			$n=$name;
		}else{
			if (isset($pa['username'])) $n=$pa['username'];
			else $n=$pa['name'];
		}
		$cn=$carddesc[$ci]['name'];
		$result = $db->query("SELECT * FROM {$gtablepre}users WHERE username='$n'");
		$pu = $db->fetch_array($result);
		extract($pu,EXTR_PREFIX_ALL,'p');
		$carr = explode('_',$p_cardlist);
		$clist = Array();
		foreach($carr as $key => $val){
			$clist[$key] = $val;
		}
		if (in_array($ci,$clist)){
			return 0;
		}else{
			$p_cardlist.="_".$ci;
			$db->query("UPDATE {$gtablepre}users SET cardlist='$p_cardlist' WHERE username='$n'");
			return 1;
		}
	}
	
	function get_qiegao($num,$pa=NULL)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','player'));
		if ($pa==NULL){
			$n=$name;
		}else{
			if (isset($pa['username'])) $n=$pa['username'];
			else $n=$pa['name'];
		}
		$result = $db->query("SELECT gold FROM {$gtablepre}users WHERE username='$n'");
		$cg = $db->result($result);
		$cg=$cg+$num;
		if ($cg<0) $cg=0;
		$db->query("UPDATE {$gtablepre}users SET gold='$cg' WHERE username='$n'");
	}

	function player_kill_enemy(&$pa,&$pd,$active){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$chprocess($pa, $pd, $active);
		eval(import_module('cardbase','sys','logger','map'));
		$qiegaogain=0;
		if (($pd['type']==90)&&(($areanum/$areaadd)<1)&&(rand(0,99)<10)){//杂兵
			$qiegaogain=rand(7,15);
			get_qiegao($qiegaogain,$pa);
		}
		if (($pd['type']==2)&&(($areanum/$areaadd)<1)){//幻象
			$qiegaogain=rand(9,19);
			get_qiegao($qiegaogain,$pa);
		}
		if ($qiegaogain>0)
			$log.="<span class=\"orange\">敌人掉落了{$qiegaogain}单位的切糕！</span><br>";
	}	
}

?>
