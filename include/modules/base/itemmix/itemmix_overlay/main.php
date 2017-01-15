<?php

namespace itemmix_overlay
{
	function init() 
	{
		eval(import_module('itemmain'));
		$itemspkinfo['J'] = '超量素材';
	}
	
	function itemmix_star_culc($mlist){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','player','logger','itemmix'));
		
	}
	
	function itemmix($mlist, $itemselect=-1) 
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','player','logger','itemmix'));
		
		$last_star=-1; $num=0; 
		foreach($mlist as $val)
		{
			$z=${'itmk'.$val};
			$star=0;
			for ($i=0; $i<strlen($z); $i++) if ('0'<=$z[$i] && $z[$i]<='9') $star=$star*10+(int)$z[$i];
			
			if ($star==0) return $chprocess($mlist, $itemselect);	//所有道具都有星数
			if ($last_star==-1) 
			{
				$last_star=$star; $num=1;
			}
			else  if ($star!=$last_star)	//所有星数必须相同
					return $chprocess($mlist, $itemselect);
				else  $num++;
				
			if (strpos(${'itmsk'.$val},'J')===false) return $chprocess($mlist, $itemselect);	//必须均为超量素材
		}
		
		if ($last_star==-1) return $chprocess($mlist, $itemselect);
		
		$file = __DIR__.'/config/overlay.config.php';
		$olist = openfile($file); $n = count($olist);
		$sync=-1; $syncn=$synck=$synce=$syncs=$syncsk=Array();
		for ($i=0;$i<$n;$i++){
			$t = explode(',',$olist[$i]);
			if ($t[5]!=$last_star || $t[6]!=$num) continue;
			$sync++;
			$syncn[$sync]=$t[0]; $synck[$sync]=$t[1]; $synce[$sync]=$t[2]; $syncs[$sync]=$t[3]; $syncsk[$sync]=$t[4];
		}

		//无满足条件的超量结果，失败
		if ($sync==-1) return $chprocess($mlist, $itemselect);	
				
		if ($itemselect==-1)
		{
			$mask=0;
			foreach($mlist as $k)
				if (1<=$k && $k<=6)
					$mask|=(1<<((int)$k-1));
						
			$cmd.='<input type="hidden" id="mode" name="mode" value="itemmain">';
			$cmd.='<input type="hidden" id="command" name="command" value="itemmix">';
			$cmd.='<input type="hidden" id="mixmask" name="mixmask" value="'.$mask.'">';
			$cmd.='<input type="hidden" id="itemselect" name="itemselect" value="999">';
			$cmd.= "请选择超量结果<br><br>";
			for($i=0;$i<=$sync;$i++){
				$tn=$syncn[$i];
				$cmd.="<input type=\"button\" class=\"cmdbutton\"  style=\"width:200\" value=\"".$tn."\" onclick=\"$('itemselect').value='".$i."';postCmd('gamecmd','command.php');this.disabled=true;\">";
			}
			$cmd.="<input type=\"button\" class=\"cmdbutton\"  style=\"width:200\" value=\"返回\" onclick=\"postCmd('gamecmd','command.php');this.disabled=true;\">";
			return;
		}
		else
		{
			$i=(int)$itemselect;
			if ($i<0 || $i>$sync)
			{
				$mode='command'; return; 
			}
			foreach($mlist as $val)
			{
				\itemmix\itemreduce('itm'.$val);
			}
			$itm0=$syncn[$i]; $itmk0=$synck[$i]; $itme0=$synce[$i]; $itms0=$syncs[$i]; $itmsk0=$syncsk[$i];
			addnews($now,'overmix',$name,$itm0);
			\itemmain\itemget();
			$mode = 'command';
			return;
		}
		$chprocess($mlist, $itemselect);
	}
	
	function parse_news($news, $hour, $min, $sec, $a, $b, $c, $d, $e)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys'));
		if($news == 'overmix') 
			return "<li>{$hour}时{$min}分{$sec}秒，<span class=\"lime\">{$a}超量合成了{$b}</span><br>\n";
		return $chprocess($news, $hour, $min, $sec, $a, $b, $c, $d, $e);
	}
}

?>
