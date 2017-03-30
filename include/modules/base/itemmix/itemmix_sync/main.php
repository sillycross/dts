<?php

namespace itemmix_sync
{
	function init() 
	{
		eval(import_module('itemmain'));
		$itemspkinfo['s'] = '调整';
	}
	
	function itemmix($mlist, $itemselect=-1) 
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','player','logger','itemmix'));
		$sks_count=0; $sum_star=0; $mixitem=Array();
		foreach($mlist as $val)
		{
			$mitm = ${'itm'.$val};
			foreach(Array('/锋利的/','/电气/','/毒性/','/-改$/') as $value){
				$mitm = preg_replace($value,'',$mitm);
			}
			$mitm = str_replace('钉棍棒','棍棒',$mitm);
			$mixitem[] = $mitm;
			$z=${'itmk'.$val};
			$star=0;
			for ($i=0; $i<strlen($z); $i++) if ('0'<=$z[$i] && $z[$i]<='9') $star=$star*10+(int)$z[$i];
			
			if ($star==0) return $chprocess($mlist, $itemselect);	//所有道具都有星数
			$sum_star+=$star;
			if (strpos(${'itmsk'.$val},'s')!==false) $sks_count++;
		}
		
		//恰有一个调整属性
		if ($sks_count!=1) return $chprocess($mlist, $itemselect);
		
		$file = __DIR__.'/config/sync.config.php';
		$slist = openfile($file);
		$num = count($slist);
		$sync = -1; $syncn=$synck=$synce=$syncs=$syncsk=Array();
		for ($i=0; $i<$num; $i++)
		{
			$t = explode(',',$slist[$i]);
			$tn=$t[0];$tk=$t[1];$te=$t[2];$ts=$t[3];$tsk=$t[4];$tstar=$t[5];$treq=$t[6];
			
			//检查星数是否符合
			if ($sum_star!=$tstar) continue;
			
			//检查是否满足了要求的合成来源
			$lis=explode('+',$treq); $flag=1;
		
			foreach ($lis as $key) if ($key!='')
				if (!in_array($key,$mixitem)) { $flag=0; break;}
			
			if (!$flag) continue;
			
			$sync++;
			$syncn[$sync]=$tn;$synck[$sync]=$tk;$synce[$sync]=$te;$syncs[$sync]=$ts;$syncsk[$sync]=$tsk;
		}
		
		//无满足条件的同调结果，失败
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
			$cmd.= "请选择同调结果<br><br>";
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
			addnews($now,'syncmix',$name,$itm0);
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
		if($news == 'syncmix') 
			return "<li>{$hour}时{$min}分{$sec}秒，<span class=\"lime\">{$a}同调合成了{$b}</span><br>\n";
		return $chprocess($news, $hour, $min, $sec, $a, $b, $c, $d, $e);
	}
}

?>
