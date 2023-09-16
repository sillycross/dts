<?php

namespace skill506
{
	function init() 
	{
		define('MOD_SKILL506_INFO','card;hidden;');
		eval(import_module('clubbase'));
		$clubskillname[506] = '接力';
		//有这个技能的NPC的血量会每局继承，如果死亡则下局回满。暂时只支持1名NPC
	}
	
	function acquire506(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		loadhp506($pa);
		eval(import_module('sys'));
		//记录有这个技能的NPCid
		if($pa['type']) $gamevars['skill506name'][] = $pa['name'];
	}
	
	function lost506(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		savehp506($pa);
	}
	
	function check_unlocked506(&$pa=NULL)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return 1;
	}
	
	function cleanhp506(){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$file = GAME_ROOT.'./gamedata/cache/skill506.hp.dat';
		file_put_contents($file,'');
		chmod($file, 0777);
	}
	
	function loadhp506(&$pa){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		//获得技能时，从对应文件读取血量
		$stored_hp = 0;
		$file = GAME_ROOT.'./gamedata/cache/skill506.hp.dat';
		if(file_exists($file)) {
			$cont = explode(';',file_get_contents($file));
			foreach($cont as $cv){
				list($cn, $chp) = explode(':',$cv);
				if($pa['name'] == $cn && !empty($chp)) {
					$stored_hp = (int)trim($chp);
					break;
				}
			}
		}
		if($stored_hp) $pa['hp'] = $stored_hp;
	}
	
	function savehp506(&$pa){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		//失去技能或者游戏结束时，保存血量
		$file = GAME_ROOT.'./gamedata/cache/skill506.hp.dat';
		writeover($file, $pa['name'].':'.(int)($pa['hp']).';', 'ab+');
	}
	
	//游戏结束时，如果有这样技能的NPC入场，则保存其血量
	function post_gameover_events()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$chprocess();
		eval(import_module('sys'));
		if(!empty($gamevars['skill506name'])) {
			cleanhp506();
			foreach($gamevars['skill506name'] as $pn) {
				$result = $db->query("SELECT * FROM {$tablepre}players WHERE name='$pn' AND type > 0");
				if($db->num_rows($result)){
					$pdata = $db->fetch_array($result);
					savehp506($pdata);
				}
			}
		}
//		logmicrotime('506号接力技能处理');
	}
	
	//嗯？好像完全没有用到技能id的样子
}

?>