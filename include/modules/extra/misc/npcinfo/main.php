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
	
	//为显示而初始化NPC参数
	//与npc/init_npcdata()判定不完全相同（如需要显示随机性别和随机地点），不能直接用
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
		//记录一下性别和地点以免被初始化函数给抹掉了
		$tmp_gd = $nownpc['gd']; $tmp_pls = $nownpc['pls'];
		//直接调用初始化函数
		$nownpc = \npc\init_npcdata($nownpc, Array(1));
		
		//熟练度初始化
//		if(!isset($nownpc['wp'])) {
//			if(is_array($nownpc['skill'])) $nownpc = array_merge($nownpc, $nownpc['skill']);
//			else $nownpc['wp'] = $nownpc['wk'] = $nownpc['wc'] = $nownpc['wg'] = $nownpc['wd'] = $nownpc['wf'] = $nownpc['skill'];
//		}
		//技能和称号初始化
//		if(!empty($nownpc['club'])) \clubbase\club_acquire($nownpc['club'],$nownpc);
//		\clubbase\init_npcdata_skills($nownpc);
		
		//if($nownpc['type'] == 14) file_put_contents('a.txt', var_export($nownpc,1)."\r\n\r\n",FILE_APPEND);
		//还原性别和地点
		$nownpc['gd'] = $tmp_gd;
		if(isset($ninfo_custom['enpcflag'])) $nownpc['pls'] = '原地';
		else $nownpc['pls'] = $tmp_pls;
		//载入模板页面
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