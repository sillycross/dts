<?php

namespace team
{
	function teamcheck() {
		if (eval(__MAGIC__)) return $___RET_VALUE;
		
		eval(import_module('sys','logger','player','team','input'));
		if(!team_available()){
			$log .= '当前不允许建立或加入队伍。<br>';
			$mode = 'command';
		}	elseif($teamID) {
			$log .= '你已经加入了队伍<span class="yellow b">'.$teamID.'</span>，请先退出队伍。<br>';
			$mode = 'command';
		} elseif($teamcmd == 'teammake' && $sp <= $team_sp) {
			$log .= '体力不足，不能创建队伍。至少需要<span class="yellow b">'.$team_sp.'</span>点体力。<br>';
			$mode = 'command';
		} elseif($teamcmd == 'teamjoin' && $sp <= $teamj_sp) {
			$log .= '体力不足，不能加入队伍。至少需要<span class="yellow b">'.$teamj_sp.'</span>点体力。<br>';
			$mode = 'command';
		} else {
			include template(MOD_TEAM_TEAM);
			$cmd = ob_get_contents();
			ob_clean();
		}
		return;	
	}

	function teammake($tID,$tPass) {
		if (eval(__MAGIC__)) return $___RET_VALUE;
		
		eval(import_module('sys','logger','player','team','input'));

		if(!$tID || !$tPass) {
			$log .= '队伍名和密码不能为空，请重新输入。<br>';
			$mode = 'command';
			return;
		}
		if(strlen($tID) > 20){
			$log .= '队伍名称过长，请重新输入。<br>';
			$mode = 'command';
			return;
		}
		if(strlen($tPass) > 20){
			$log .= '队伍密码过长，请重新输入。<br>';
			$mode = 'command';
			return;
		}
		if($tID == $noitm || $tID == 'all') {//all保留字
			$log .= '队伍名不能为<span class="red b">'.$tID.'</span>，请重新输入。<br>';
			$mode = 'command';
			return;
		}
			
		if($teamID) {
			$log .= '你已经加入了队伍<span class="yellow b">'.$teamID.'</span>，请先退出队伍。<br>';
		} elseif($sp <= $team_sp) {
			$log .= '体力不足，不能创建队伍。至少需要<span class="yellow b">'.$team_sp.'</span>点体力。<br>';
		} else {
			$result = $db->query("SELECT pid FROM {$tablepre}players WHERE teamID='$tID'");
			if($db->num_rows($result)){
				$log .= '队伍<span class="yellow b">'.$tID.'</span>已经存在，请更换队伍名。<br>';
			} else {
				$teamID = $tID;
				$teamPass = $tPass;
				$sp -= $team_sp;
				$log .= '你创建了队伍<span class="yellow b">'.$teamID.'</span>。<br>';
				addnews($now,'teammake',$teamID,$name);
			}
		$mode = 'command';
		return;

		}
	}

	function teamjoin($tID,$tPass) {
		if (eval(__MAGIC__)) return $___RET_VALUE;
		
		eval(import_module('sys','logger','player','team','input'));
		if(!$tID || !$tPass){
			$log .= '队伍名和密码不能为空，请重新输入。<br>';
			$mode = 'command';
			return;
		}
		if(strlen($tID) > 20){
			$log .= '队伍名称过长，请重新输入。<br>';
			$mode = 'command';
			return;
		}
		if(strlen($tPass) > 20){
			$log .= '队伍密码过长，请重新输入。<br>';
			$mode = 'command';
			return;
		}
		if($tID == $noitm) {
			$log .= '队伍名不能为<span class="red b">'.$tID.'</span>，请重新输入。<br>';
			$mode = 'command';
			return;
		}

		if($teamID) {
			$log .= '你已经加入了队伍<span class="yellow b">'.$teamID.'</span>，请先退出队伍。<br>';
		} elseif($sp <= $teamj_sp) {
			$log .= '体力不足，不能加入队伍。至少需要<span class="yellow b">'.$teamj_sp.'</span>点体力。<br>';
		} else {
			$result = $db->query("SELECT teamPass FROM {$tablepre}players WHERE teamID='$tID'");
			if(!$db->num_rows($result)){
				$log .= '队伍<span class="yellow b">'.$tID.'</span>不存在，请先创建队伍。<br>';
			} elseif($db->num_rows($result) >= $teamlimit) {
				$log .= '队伍<span class="yellow b">'.$tID.'</span>人数已满，请更换队伍。<br>';
			} else {
				$password = $db->result($result,0);
				if($tPass == $password) {
					$teamID = $tID;
					$teamPass = $tPass;
					$sp -= $teamj_sp;
					$log .= '你加入了队伍<span class="yellow b">'.$teamID.'</span>。<br>';
					addnews($now,'teamjoin',$teamID,$name);
				} else {
					$log .= '密码错误，不能加入队伍<span class="yellow b">'.$tID.'</span>。<br>';
				}
			}
		}

		$mode = 'command';
		return;
	}

	function teamquit() {
		if (eval(__MAGIC__)) return $___RET_VALUE;
		
		eval(import_module('sys','logger','player','team','input'));
		if($teamID){
			$log .= '你退出了队伍<span class="yellow b">'.$teamID.'</span>。<br>';
			addnews($now,'teamquit',$teamID,$name);
			$teamID =$teamPass = '';
		} else {
			$log .= '你不在队伍中。<br>';
		}
		$mode = 'command';
		return;
	}
}

?>
