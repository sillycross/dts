<?php if(!defined('IN_GAME')) exit('Access Denied'); ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?><TABLE border="1">
<tr align="center" class="b1">
<td class="b1"><span>名字&编号</span></td>
<td width="140" class="b1"><span>头像</span></td>
<td class="b1"><span>等级</span></td>
<td class="b1"><span>杀害者数</span></td>
<?php } else { echo '___aap3'; } ?><?php if($gamestate < 40 ) { ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?><td class="b1"><span>队伍名</span></td>
<?php } else { echo '___aap4'; } ?><?php } ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?><td width="300" class="b1"><span>口头禅</span></td>
</tr>
<?php } else { echo '___aap5'; } ?><?php if(is_array($alivedata)) { foreach($alivedata as $alive) { ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?><tr class="b3">
<td align="center" class="b3"><span><?php } else { echo '___aap6'; } ?><?php echo $alive['name']?><?php if (!defined('GEXIT_RETURN_JSON')) { ?><br><?php } else { echo '___aap7'; } ?><?php echo $sexinfo[$alive['gd']]?> <?php echo $alive['sNo']?><?php if (!defined('GEXIT_RETURN_JSON')) { ?> 号</span></td>
<td align="center" class="b3"><span><IMG src="img/<?php } else { echo '___aap8'; } ?><?php echo $alive['iconImg']?><?php if (!defined('GEXIT_RETURN_JSON')) { ?>" width="140" height="80" border="0" align="absmiddle"></span></td>
<td class="b3"><span><?php } else { echo '___aap9'; } ?><?php echo $alive['lvl']?><?php if (!defined('GEXIT_RETURN_JSON')) { ?></span></td>
<td class="b3"><span><?php } else { echo '___aap.'; } ?><?php echo $alive['killnum']?><?php if (!defined('GEXIT_RETURN_JSON')) { ?></span></td>
<?php } else { echo '___aaew'; } ?><?php if($gamestate < 40 ) { ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?><td class="b3"><span>
<?php } else { echo '___aaho'; } ?><?php if($alive['teamID']) { ?>
<?php echo $alive['teamID']?>
<?php } else { ?>
无
<?php } ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?></span></td>
<?php } else { echo '___aaew'; } ?><?php } ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?><td class="b3"><span><?php } else { echo '___aahq'; } ?><?php echo $alive['motto']?><?php if (!defined('GEXIT_RETURN_JSON')) { ?></span></td>
</tr>
<?php } else { echo '___aap-'; } ?><?php } } ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?></table><BR>
【生存者数：<?php } else { echo '___aaqa'; } ?><?php echo $alivenum?>人】