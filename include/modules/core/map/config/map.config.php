<?php

namespace map
{
	//禁区间隔时间,单位 分钟（已废弃）
	$areahour = 30;
	//不同游戏类型的禁区间隔时间，单位：分钟
	$areainterval = Array( 0 => 30 );
	//每次间隔增加的禁区数量
	$areaadd = 4;
	//聊天记录里的禁区提示时间，单位秒
	$areawarntime = 60;
	//玩家激活结束时的增加禁区的回数，相当于已经进行的小时数/间隔时间，〉0
	$arealimit = 2;
	//是否激活自动躲避禁区
	$areaesc = 0;
	
	$plsinfo = Array(
		0=>'无月之影',
		1=>'端点',
		2=>'RF高校',
		3=>'雪之镇',
		4=>'索拉利斯',
		5=>'指挥中心',
		6=>'梦幻馆',
		7=>'清水池',
		8=>'白穗神社',
		9=>'墓地',
		10=>'麦斯克林',
		11=>'对天使用作战本部',
		12=>'夏之镇',
		13=>'三体星',
		14=>'光坂高校',
		15=>'守矢神社',
		16=>'常磐森林',
		17=>'常磐台中学',
		18=>'秋之镇',
		19=>'精灵中心',
		20=>'春之镇',
		21=>'圣Gradius学园',
		22=>'初始之树',
		23=>'幻想世界',
		24=>'永恒的世界',
		25=>'妖精驿站',
		26=>'冰封墓场',
		27=>'花菱商厦',
		28=>'FARGO前基地',
		29=>'风祭森林',
		30=>'天使队移动格纳库',
		31=>'和田町研究所',
		32=>'ＳＣＰ研究设施',
		33=>'雏菊之丘',
		34=>'英灵殿'
	);
	$xyinfo = Array(
		0=>'B-2',
		1=>'A-6',
		2=>'H-3',
		3=>'B-6',
		4=>'F-10',
		5=>'D-6',
		6=>'H-6',
		7=>'F-3',
		8=>'E-10',
		9=>'J-4',
		10=>'I-8',
		11=>'D-8',
		12=>'F-9',
		13=>'H-4',
		14=>'H-8',
		15=>'G-1',
		16=>'I-2',
		17=>'A-5',
		18=>'G-4',
		19=>'D-4',
		20=>'I-7',
		21=>'F-7',
		22=>'J-6',
		23=>'A-8',
		24=>'C-9',
		25=>'D-2',
		26=>'A-1',
		27=>'F-8',
		28=>'E-1',
		29=>'F-5',
		30=>'F-6',
		31=>'J-1',
		32=>'J-2',
		33=>'F-4',
		34=>'J-10',
	);
	$areainfo = Array
		(
		0=>"充满了灵力的永久禁区，也是整个战场的入口。<br>逗留在这里也许会被时空吞噬……<br><span class=\"yellow b\">买完东西就快点离开吧。</span><br>",
		1=>"蓝白色的大地上仿佛有种令人心悸的波动在回荡。<br>还是快离开吧。<br>",
		2=>"这是一所位于郊区的高校。<br>之前毕业旅行的学生似乎遇到了惨烈的交通事故，幸存的学生们大概接受心理疏导去了吧。<br>",
		3=>"飘着雪花的北国小镇，俄罗斯风格的建筑使人愈发感到寒冷。<br>",
		4=>"柔软的大地不断变化，在人眼前生长成巨大而诡异的形状，然后在海水的冲刷下四散崩溃。<br>这究竟是什么地方？<br>",
		5=>"海风的腥味表明这幢建筑似乎是从某个岛屿上移动过来的。<br><span class=\"yellow b\">自动防御系统还在忠实地工作着，看来需要随时保持警惕。</span><br>",
		6=>"建筑里外都充满了向日葵的香味，然而四处散布的妖怪气息依然让人不寒而栗。<br><span class=\"yellow b\">自动防御系统还在忠实地工作着，看来需要随时保持警惕。</span><br>",
		7=>"各种各样的雕像环绕着一个欧式的大水池。<br>池水十分清澈，也许可以直接饮用。<br>",
		8=>"神社空无一人。<br>但是，在这里仰望天空的话，总觉得会被某种忧伤的思绪所感染。<br>",
		9=>"这里埋葬着很多被当做祭品杀死的怪兽。<br>不知道是怎样奇怪的召唤仪式呢……<br>",
		10=>"一踏入这里，身躯就觉得沉重，而且气压也好像陡然增加了许多。<br>大概是个不宜久留的地方。<br>",
		11=>"充满了各种夸张的陷阱的房间，似乎是某所高校的校长室。<br><span class=\"yellow b\">在这里，似乎不管受到怎样的伤害也会很快回复。</span><br>",
		12=>"洒满了阳光的靠海的村庄，青色的天空吸引人久久凝望、不能罢怀。<br>",
		13=>"这里连时令都错乱了么？<br>不仅昼夜错乱、忽冷忽热，有时甚至有两三个太阳同时升起……<br>",
		14=>"长长的坂道的尽头是一所学校。<BR>学校规模不小，但总给人寂寥无人之感。<br><span class=\"yellow b\">从校内的自动售货机似乎能买到些什么。</span><br>",
		15=>"高大的御柱环绕在山中湖泊周围。尽管景色美丽，却找不见本应在此的巫女。<br>",
		16=>"浓郁的树叶遮住了阳光，是容易被袭击的地方啊……<br>林间不时还有奇怪的生物出没……<br>听说这附近每隔十年都会有超能力者诞生。<br>",
		17=>"不同于现世的先进设备兀自运转着，令人觉得是不是来到了某个技术先进的域外之城。<br><span class=\"yellow b\">自动防御系统还在忠实地工作着，看来需要随时保持警惕。</span><br>",
		18=>"与其他住宅区相比，这里的商店特别多。<BR>虽然如此，整个城市弥漫着一种莫名的悲伤的气氛……<br>",
		19=>"巨大的建筑物内，不知名的设备依然在运转。<br>根据残缺不全的说明，<span class=\"yellow b\">也许这些机器可以为人治疗伤口和恢复精力？</span><br>",
		20=>"尽管处于春季，也无法掩饰衰败的萧条小镇。<br>逗留在这里总使人感到命运无情……<br>",
		21=>"表面上看去像是标准的贵族女校，<br>有着与学校不相称的停机坪和地下仓库。<br>",
		22=>"在绿地上孤零零矗立的大树，像是一座纪念碑。<BR>这到底意味着什么呢？<br>",
		23=>"被白雪笼罩，一片荒芜的空间……<BR>时空错乱了吗？为什么我会在这里？<br>",
		24=>"诡异的地方……脚下已经看不见什么地面了……<BR>这个地方究竟是什么？<br>",
		25=>"一间孤独的小屋子。<br>貌似没有人住在这里了。<br>门上贴着告示：<br>TRAIN WITH MY HOLOGRAM IF YOU WANT TO --- GA-04<br>",
		26=>"代表火与血的牺牲的曾经的战场。<BR>现在被未明的力量全部冰封，不过看起来地上的武器还勉强能用……<br><span class=\"yellow b\">你感觉到一股苍凉的杀气！还是快逃跑吧！</span><br>",
		27=>"荒废的都市里，顶层有着天象馆的废弃商场。<BR>虽然大部分区域都停电了，<span class=\"yellow b\">角落里的自动售货机似乎还能运行。</span><br>",
		28=>"现在已经是一团废墟的遗迹。<BR>可能能找到有用的物品也说不定。<br>",
		29=>"传说有神秘力量的森林。<BR>谁知道这个地方会出现什么。<br>",
		30=>"在远处看这个建筑你还以为那是一个集装箱，<BR>走进去以后才发现这里别有洞天。<br>庞大的电脑系统正在忠实地工作着。<br>",
		31=>"最近突然在地平线远端出现的大型建筑，<BR>你注意到建筑的后院时空裂缝之外貌似还有一个小镇……<br>",
		32=>"最近突然在地平线远端出现的第二座大型建筑，<BR>感觉有种不祥的气息……<br>",
		33=>"风祭森林的最深处。<BR>被盛开的雏菊花覆盖着的山丘。<BR>山丘上貌似有个身影坐着，<BR>还是离她远一点为妙。<BR>",
		34=>"看不见尽头的走廊……<BR>",
	);
}

?>
