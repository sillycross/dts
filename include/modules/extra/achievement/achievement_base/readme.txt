成就系统说明

成就系统基于技能系统，每个成就都使用游戏内技能来实现逻辑

一个成就技能：

	类似称号技能，必须定义define('MOD_SKILL技能编号_INFO','achievement;');
	必须定义define('MOD_SKILL技能编号_ACHIEVEMENT_ID','成就编号ID'); （数字，从0开始编号）
	
	必须提供一个函数 string finalize技能编号(array &$pa, string $data)，这个函数会在游戏结束时自动被调用，
	$pa是当前玩家信息，$data会是数据库中这个成就保存的数据（即这局游戏开始前这个成就的状况），这个函数应当返回新的$data（即这局游戏后这个成就的状况）
	
	必须提供一个函数 void show_achievement技能编号(string $data)，用于user profile中显示成就（直接输出html即可）。$data意义同上。
	
	$data应当是一个base64字符串。数字的编码和解码请使用这两个函数：
		string base64_encode_number(unsigned int $number, unsigned int $length)	
			把非负整数number编码成长度为length的base64字符串。也就是number的可行范围是[0,64^length)
			
		unsigned int base64_decode_number(string $data)
			上个函数的解码函数
	
	特别：$data绝对不可以包含分号字符“;”
	
示例：
	
	统计吃补给数量（技能编号300，成就编号0）：
	
		数据编码：一个长度为5的base64字符串，表示当前吃补给数量。（可表示范围是[0,2^30)，足够大了）
		
	载入玩家时进行判断，如没有技能自动获得技能，并初始化计数器'cnt'=0
	（注意请不要试图访问user表获得原有data，这么做的目的是万一因为某些bug导致游戏数据损坏，也不至于直接把玩家原有的成就给炸了）
	
	技能在玩家吃补给时更新计数器'cnt'
	
	function finalize300(&$pa,$data)			//更新成就函数
	{
		if ($data=='')					//如果原本没有这个成就的记录
			$x=0;						//初始化为0
		else	$x=base64_char_decode($data);		//解码原本吃了多少补给
		$x+=\skill\getvalue(300,'cnt',$pa);		//加上这局吃的补给数目
		$x=min($x,(1<<30)-1);				//防止溢出
		return base64_char_encode($x,5);		//返回长度为5的base64编码，返回内容会被写回数据库
	}

	function show_achievement300($data)			//成就界面显示
	{
		if ($data=='')
			$x=0;
		else	$x=base64_char_decode($data);		//解码吃了多少补给
		include template('MOD_SKILL300_DESC');	//包含模板desc，模板显示出html
	}
	
	desc.htm: （略）
	
