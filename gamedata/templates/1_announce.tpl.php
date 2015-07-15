<?php if(!defined('IN_GAME')) exit('Access Denied'); include template('header'); ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?><br>

<div style="width:50%; margin:0 auto; text-align:left">
<font size="5" class="red">关于这次版本回退的说明</font><br><br>
<font size="3">
可能诸位已经注意到了，游戏版本从GE710L变为了GE600M，很多游戏特性都失去了，包括称号技能、怒气技能、帮助中的众多人性化内容等等。<br><br>
这次回退是出于迫不得已的技术原因。<br><br>
GE710L无疑是一个优秀的版本，但由于古老的BR大逃杀本身的落后架构，以及我们大逃杀开发组的开发模式中存在的一些问题，
在GE710L丰富有趣的游戏内容的背后，是大逃杀早已腐烂不堪的代码。（曾经下载过源码包而且看过combat.func.php的应该深有感触:D）<br><br>
开发组深感无力在这样的代码上继续开发，也一致同意代码腐败问题拖下去只会越来越严重。
为了给大逃杀一个更可持续的发展，<span class="yellow">开发组一致同意基于GE600版本重构代码，并基于这个版本进行后续开发。</span>
现在经过代码重构的大逃杀已经拥有了一个高度模块化的架构，希望这能给大逃杀带来更长远的发展。<br><br>
由于重构修改了大量代码逻辑，可能会产生一些bug，因此决定在完成GE600的重构后即在电波进行测试。
<span class="yellow">GE710L提供的新游戏特性会被慢慢补上，请玩家耐心等待。</span><br>
希望玩家配合测试，如发现任何问题或bug，请通过主群告诉我（lemon）或虚子或四面。<br><br>
<font size="2">P.S. 关于技能点生命获得修改为+2：减少的1点生命已经在升级时直接赠送了。</font>
</font>
</div>
<?php } else { echo '___aamA'; } ?><?php include template('footer'); ?>

