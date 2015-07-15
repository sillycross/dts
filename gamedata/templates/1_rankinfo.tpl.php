<?php if(!defined('IN_GAME')) exit('Access Denied'); ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?><TABLE border="0" cellspacing="0" cellpadding="0">
<TR height="20">
<TD class="b1"><span>排名</span></TD>
<TD class="b1"><span>UID</span></TD>
<TD class="b1"><span>姓名</span></TD>
<TD class="b1"><span>性别</span></TD>
<TD class="b1"><span>头像</span></TD>
<TD class="b1" style="maxwidth:120"><span>口头禅</span></TD>
<TD class="b1"><span>积分</span></TD>
<TD class="b1"><span>游戏场数</span></TD>
<TD class="b1"><span>获胜场数</span></TD>
<TD class="b1"><span>胜率</span></TD>
<TD class="b1"><span>最后游戏</span></TD>
</TR>
<?php } else { echo '___aasl'; } ?><?php if(is_array($rankdata)) { foreach($rankdata as $urdata) { ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?><TR height="20">
<TD class="b2"><span>
<?php } else { echo '___aasm'; } ?><?php if($urdata['number']==1) { ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?><a title="触手！"><span class="red">榜首</span></a>
<?php } else { echo '___aasn'; } ?><?php } elseif($urdata['number']<=10) { ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?><span class="yellow"><?php } else { echo '___aacM'; } ?><?php echo $urdata['number']?><?php if (!defined('GEXIT_RETURN_JSON')) { ?></span>
<?php } else { echo '___aacN'; } ?><?php } else { ?>
<?php echo $urdata['number']?>
<?php } ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?></span></TD>
<TD class="b3"><span><?php } else { echo '___aanY'; } ?><?php echo $urdata['uid']?><?php if (!defined('GEXIT_RETURN_JSON')) { ?></span></TD>
<TD class="b3"><span><?php } else { echo '___aanY'; } ?><?php echo $urdata['username']?><?php if (!defined('GEXIT_RETURN_JSON')) { ?></span></TD>
<TD class="b3"><span>
<?php } else { echo '___aaso'; } ?><?php if($urdata['gender']) { ?>
<?php echo $sexinfo[$urdata['gender']]?>
<?php } else { ?>
<?php echo $sexinfo['0']?>
<?php } ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?></span></TD>
<TD class="b3"><span><IMG src="img/<?php } else { echo '___aasp'; } ?><?php echo $urdata['img']?><?php if (!defined('GEXIT_RETURN_JSON')) { ?>" width="70" height="40" border="0" align="absmiddle"></span></TD>
<TD class="b3"><span><?php } else { echo '___aasq'; } ?><?php echo $urdata['motto']?><?php if (!defined('GEXIT_RETURN_JSON')) { ?></span></TD>
<TD class="b3"><span><span class="yellow"><?php } else { echo '___aasr'; } ?><?php echo $urdata['credits']?><?php if (!defined('GEXIT_RETURN_JSON')) { ?></span></span></TD>
<TD class="b3"><span><?php } else { echo '___aass'; } ?><?php echo $urdata['validgames']?><?php if (!defined('GEXIT_RETURN_JSON')) { ?></span></TD>
<TD class="b3"><span><?php } else { echo '___aanY'; } ?><?php echo $urdata['wingames']?><?php if (!defined('GEXIT_RETURN_JSON')) { ?></span></TD>
<TD class="b3"><span><?php } else { echo '___aanY'; } ?><?php echo $urdata['winrate']?><?php if (!defined('GEXIT_RETURN_JSON')) { ?></span></TD>
<TD class="b3"><span><?php } else { echo '___aanY'; } ?><?php echo $urdata['lastgame']?><?php if (!defined('GEXIT_RETURN_JSON')) { ?></span></TD>
</TR>
<?php } else { echo '___aast'; } ?><?php } } ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?></TABLE>
<?php } else { echo '___aasu'; } ?>