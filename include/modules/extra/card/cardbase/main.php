<?php

namespace cardbase
{
	function init() {}

	function get_user_cards($username){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys'));
		$result = $db->query("SELECT * FROM {$gtablepre}users WHERE username='$username'");
		$udata = $db->fetch_array($result);
		$cardlist = get_user_cards_process($udata);
		return $cardlist;
	}	
	
	function get_user_cards_process($udata){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys'));
		$cardlist = explode('_',$udata['cardlist']);
		if (!in_array(0, $cardlist))
		{
			$cardlist[] = 0;
			$clstr = implode('_',$cardlist);
			$db->query("UPDATE {$gtablepre}users SET cardlist='{$clstr}' WHERE username = '$username'");
		}
		return $cardlist;
	}
	
	function get_energy_recover_rate($cardlist, $qiegao)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','cardbase'));
		/*
		 * 返回 Array ('S'=>..,'A'=>..,'B'=>..,'C'=>0)
		 */
		/*
		 * 新规：S卡CD时间大约在1-3天
		 * A卡CD时间大约在半天-1天
		 * B卡CD时间大约为几小时
		 */
		$ret = Array();
		//$ret['S']=100.0/7/86400;	//S卡固定基准CD 7天
		$ret['C']=0;			//C卡不受能量制影响
		$ret['M']=0;			//M卡更不受能量制影响
		$cnt=Array(); $cnt['S']=0; $cnt['A']=0; $cnt['B']=0;
		//计算S卡、A卡、B卡的数目
		foreach ($cardlist as $key)
		{
			if ($cards[$key]['rare']=='S') $cnt['S']++;
			if ($cards[$key]['rare']=='A') $cnt['A']++;
			if ($cards[$key]['rare']=='B') $cnt['B']++;
		}
		//估算现有切糕对卡片数量的影响，也即还可抽出多少张新卡
//		$bcost = Array('S'=> 90/0.01, 'A' => 90/0.05, 'B'=>90/0.2);
//		foreach (Array('S','A','B') as $ty)
//		{
//			$z=$qiegao;
//			$all=count($cardindex[$ty]);
//			while ($cnt[$ty]<$all && $z>$bcost[$ty]*$all/($all-$cnt[$ty]))
//			{
//				$z-=$bcost[$ty]*$all/($all-$cnt[$ty]);
//				$cnt[$ty]++;
//			}
//		}
		
		$tbase = Array('S' => 86400.0, 'A' => 28800.0, 'B' => 3600.0);
		foreach (Array('S','A','B') as $ty)
		{
			//卡片数目开根号
			$z = round(sqrt($cnt[$ty]));
			if($z<1) $z = 1;
//			$z=$cnt[$ty]/2;
//			if ($cnt[$ty]<=6) $z=$cnt[$ty]*2/3; 
//			if ($cnt[$ty]<=3) $z=2; 
			
			$tbase[$ty]*=$z;
			$ret[$ty]=100.0/$tbase[$ty];
		}
		return $ret;
	}
		
	function get_user_cardinfo($who)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','cardbase'));
		$result = $db->query("SELECT * FROM {$gtablepre}users WHERE username='$who'");
		$udata = $db->fetch_array($result);
		
		$cardlist = get_user_cards_process($udata);		
		$energy_recover_rate = get_energy_recover_rate($cardlist, $udata['gold']);
		
		$cardenergy=Array();
		if ($udata['cardenergy']=="") $t=Array(); else $t=explode('_',$udata['cardenergy']);
		$lastupd = $udata['cardenergylastupd'];
		for ($i=0; $i<count($cardlist); $i++)
			if ($i<count($t))
			{
				$cardenergy[$cardlist[$i]]=((double)$t[$i])+($now-$lastupd)*$energy_recover_rate[$cards[$cardlist[$i]]['rare']];
				if (in_array($cards[$cardlist[$i]]['rare'], array('C','M')) || $cardenergy[$cardlist[$i]] > $cards[$cardlist[$i]]['energy']-1e-5)
					$cardenergy[$cardlist[$i]] = $cards[$cardlist[$i]]['energy'];
			}
			else
			{
				$cardenergy[$cardlist[$i]] = $cards[$cardlist[$i]]['energy'];
			}
		
		$nt='';
		for ($i=0; $i<count($cardlist); $i++)
		{
			$x=(double)$cardenergy[$cardlist[$i]];
			if ($i>0) $nt.='_';
			$nt.=$x;
		}
		$db->query("UPDATE {$gtablepre}users SET cardenergy='$nt', cardenergylastupd='$now' WHERE username = '$who'");
		
		$ret=Array(
			'cardlist' => $cardlist,
			'cardenergy' => $cardenergy,
			'cardchosen' => $udata['card']
		);
		return $ret;
	}
	
	function save_cardenergy($data, $who)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys'));
		$nt='';
		for ($i=0; $i<count($data['cardlist']); $i++)
		{
			$x=(double)$data['cardenergy'][$data['cardlist'][$i]];
			if ($i>0) $nt.='_';
			$nt.=$x;
		}
		$db->query("UPDATE {$gtablepre}users SET cardenergy='$nt' WHERE username = '$who'");
	}
	
	function get_card($ci,$pa=NULL)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','player','cardbase'));
		if ($pa==NULL){
			$n=$name;
		}else{
			if (isset($pa['username'])) $n=$pa['username'];
			else $n=$pa['name'];
		}
		$cn=$cards[$ci]['name'];
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
		$cg = $db->result($result,0);
		$cg=$cg+$num;
		if ($cg<0) $cg=0;
		$db->query("UPDATE {$gtablepre}users SET gold='$cg' WHERE username='$n'");
	}
	
	function calc_qiegao_drop(&$pa,&$pd,&$active){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('cardbase','sys','logger','map'));
		$qiegaogain=0;
		if (!in_array($gametype,$qiegao_ignore_mode)){		
			if ($pd['type']==90)	//杂兵
			{
				if ($areanum/$areaadd<1)	//0禁
				{
					$dice = rand(0,99);
					if ($dice<5) 
						$qiegaogain=rand(7,15);
					else if ($dice<20)
						$qiegaogain=rand(3,7);
					else if ($dice<50)
						$qiegaogain=rand(1,3);
				}
				else if ($areanum/$areaadd<2)	//1禁
				{
					$dice = rand(0,99);
					if ($dice<5) 
						$qiegaogain=rand(3,5);
					else if ($dice<15)
						$qiegaogain=rand(1,3);
				}
			}
			if ($pd['type']==2)	//幻象
			{
				if ($areanum/$areaadd<1)
				{
					$qiegaogain=rand(9,19);
				}
				else if ($areanum/$areaadd<2)
				{
					$dice=rand(0,99);
					if ($dice<30)
						$qiegaogain=rand(3,7);
					else  $qiegaogain=rand(1,3);
				}
			}
		}
		return $qiegaogain;
	}
	
	function player_kill_enemy(&$pa,&$pd,$active){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$chprocess($pa, $pd, $active);
		eval(import_module('cardbase','sys','logger','map'));
		$qiegaogain=calc_qiegao_drop($pa,$pd,$active);
		if ($qiegaogain>0){
			get_qiegao($qiegaogain,$pa);
			$log.="<span class=\"orange\">敌人掉落了{$qiegaogain}单位的切糕！</span><br>";
		}
	}	
	
	function itemmix_success()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		/*eval(import_module('sys','player','logger','map','cardbase'));
		if (!in_array($gametype,$qiegao_ignore_mode)){
			if (($itm0=="绝冲大剑【神威】")&&(($areanum/$areaadd)<2)){
				if (get_card(42)==1){
					$log.="恭喜您获得了活动奖励卡<span class=\"orange\">Fleur</span>！<br>";
				}else{
					$log.="您已经拥有活动奖励卡了，系统奖励您<span class=\"yellow\">100</span>切糕！<br>";
					get_qiegao(100);
				}
			}
		}*/
		$chprocess();	
	}
	
	function get_card_pack($card_pack_name) {
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('cardbase'));
		$card_pack = Array();
		foreach ($cards as $ci => $card) {
			if ($card["pack"] == $card_pack_name)
				$card_pack[$ci] = $card;
		}
		//return  json_encode($card_pack, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT)."test";
		return $card_pack;
	}

	function get_card_pack_list() {
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('cardbase'));
		return $packlist;
	}

	function in_card_pack($packname) {
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('cardbase'));
		return in_array($packname, $packlist);
	}
	
	function kuji($type, &$pa, $is_dryrun = false){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('cardbase'));
		$ktype=(int)$type;
		$func='kuji'.$ktype.'\\kujidraw'.$ktype;
		if (defined('MOD_KUJI'.$ktype)) {
			$kr=$func($pa, $is_dryrun);
			if (!is_array($kr)){
				if ($kr==-1){
					return -1;
				}else{
					$dr=array($kr);
				}
			}else{
				$dr=$kr;
			}
			return $dr;
		}
		return -1;
	}
}

?>