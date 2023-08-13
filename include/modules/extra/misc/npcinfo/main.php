<?php

namespace npcinfo
{
	$bubblebox_style = Array();
	
	function init() {}
	
	function npcinfo_gen_item_description($itm, $itmk, $itme, $itms, $itmsk)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE; 
		echo $itm;
		if ($itmk!='') echo '/'.\itemmain\parse_itmk_words($itmk,1);
		if ($itme!='') echo '/'.$itme;
		if ($itms!='') echo '/'.$itms;
		if (\itemmain\count_itmsk_num($itmsk)>0)
		{
			echo '/'.\itemmain\parse_itmsk_words($itmsk);
		}
	}
	
	function npcinfo_get_npc_description($npckind, $npcsubkind, $npcdata = NULL, $ninfo_custom = NULL)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE; 
		eval(import_module('sys','player','clubbase','npc','tactic','pose','map','weapon'));
		if($ninfo_custom) {
			$o_npcinfo = $npcinfo;
			$npcinfo = $ninfo_custom;
		}
		$nownpc = array_merge($npcinit,$npcinfo[$npckind]);
		$nownpc = array_merge($nownpc,$npcinfo[$npckind]['sub'][$npcsubkind]);
		unset($nownpc['sub']);
		//操，玩家头像带a是大头像，NPC带a是小头像，谁想的数据结构，脚趾头长大脑里了吗
//		if ($nownpc['mode']==3){//有大头像
//			$nownpc['icon'].='a';
//		}
		if ($npcdata)
			$nownpc = array_merge($nownpc,$npcdata);
		else  $nownpc['___count']=ceil($npcinfo[$npckind]['num']/sizeof($npcinfo[$npckind]['sub']));
		$nownpc['type'] = $npckind;
		//熟练度初始化
		if(!isset($nownpc['wp'])) {
			if(is_array($nownpc['skill'])) $nownpc = array_merge($nownpc, $nownpc['skill']);
			else $nownpc['wp'] = $nownpc['wk'] = $nownpc['wc'] = $nownpc['wg'] = $nownpc['wd'] = $nownpc['wf'] = $nownpc['skill'];
		}
		//技能和称号初始化
		\npc\init_npcdata_skills($nownpc);
		if(!empty($nownpc['club'])) \clubbase\club_acquire($nownpc['club'],$nownpc);
		//if($nownpc['type'] == 14) file_put_contents('a.txt', var_export($nownpc,1)."\r\n\r\n",FILE_APPEND);
		if(isset($ninfo_custom['enpcflag'])) $nownpc['pls'] = '原地';
		include template('MOD_NPCINFO_NPCINFO');
		if($ninfo_custom) {
			$npcinfo = $o_npcinfo;
		}
	}
	
	function npcinfo_get_npc_description_all($npckind, $ninfo_custom = NULL)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE; 
		eval(import_module('npc'));
		if($ninfo_custom) {
			$o_npcinfo = $npcinfo;
			$npcinfo =$ninfo_custom;
		}
		foreach ($npcinfo[$npckind]['sub'] as $key => $value)
		{
			npcinfo_get_npc_description($npckind, $key, NULL, $ninfo_custom);
			echo '<br>';
		}
		if($ninfo_custom) {
			$npcinfo = $o_npcinfo;
		}
	}
}

?>