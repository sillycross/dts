<?php if(!defined('IN_GAME')) exit('Access Denied'); ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?>想要做什么？<br>

<input type="hidden" name="mode" value="special">
<input type="radio" name="command" id="menu" value="menu" checked><a onclick=sl('menu'); href="javascript:void(0);" >返回</a><br><br>
<?php } else { echo '___aaj6'; } ?><?php if((strpos($inf,'h') !== false || strpos($inf,'b') !== false || strpos($inf,'a') !== false || strpos($inf,'f') !== false) && $club == 16) { ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?><input type="radio" name="command" id="infA" value="infA"><a onclick=sl('infA'); href="javascript:void(0);" ><span class="red">包扎全身伤口</span></a><br>
<?php } else { echo '___aaj7'; } ?><?php } if(strpos($inf,'b') !== false) { ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?><input type="radio" name="command" id="infb" value="infb"><a onclick=sl('infb'); href="javascript:void(0);" >包扎<?php } else { echo '___aaj8'; } ?><?php echo $infinfo['b']?><?php if (!defined('GEXIT_RETURN_JSON')) { ?><span class="red">部</span>伤口</a><br>
<?php } else { echo '___aaj9'; } ?><?php } if(strpos($inf,'h') !== false) { ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?><input type="radio" name="command" id="infh" value="infh"><a onclick=sl('infh'); href="javascript:void(0);" >包扎<?php } else { echo '___aaj.'; } ?><?php echo $infinfo['h']?><?php if (!defined('GEXIT_RETURN_JSON')) { ?><span class="red">部</span>伤口</a><br>
<?php } else { echo '___aaj9'; } ?><?php } if(strpos($inf,'a') !== false) { ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?><input type="radio" name="command" id="infa" value="infa"><a onclick=sl('infa'); href="javascript:void(0);" >包扎<?php } else { echo '___aaj-'; } ?><?php echo $infinfo['a']?><?php if (!defined('GEXIT_RETURN_JSON')) { ?><span class="red">部</span>伤口</a><br>
<?php } else { echo '___aaj9'; } ?><?php } if(strpos($inf,'f') !== false) { ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?><input type="radio" name="command" id="inff" value="inff"><a onclick=sl('inff'); href="javascript:void(0);" >包扎<?php } else { echo '___aaka'; } ?><?php echo $infinfo['f']?><?php if (!defined('GEXIT_RETURN_JSON')) { ?><span class="red">部</span>伤口</a><br>
<?php } else { echo '___aaj9'; } ?><?php } if(strpos($inf,'p') !== false && $club == 16) { ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?><input type="radio" name="command" id="infp" value="infp"><a onclick=sl('infp'); href="javascript:void(0);" >治疗<?php } else { echo '___aakb'; } ?><?php echo $exdmginf['p']?><?php if (!defined('GEXIT_RETURN_JSON')) { ?>状态</a><br>
<?php } else { echo '___aakc'; } ?><?php } if(strpos($inf,'u') !== false && $club == 16) { ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?><input type="radio" name="command" id="infu" value="infu"><a onclick=sl('infu'); href="javascript:void(0);" >治疗<?php } else { echo '___aakd'; } ?><?php echo $exdmginf['u']?><?php if (!defined('GEXIT_RETURN_JSON')) { ?>状态</a><br>
<?php } else { echo '___aakc'; } ?><?php } if(strpos($inf,'i') !== false && $club == 16) { ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?><input type="radio" name="command" id="infi" value="infi"><a onclick=sl('infi'); href="javascript:void(0);" >治疗<?php } else { echo '___aake'; } ?><?php echo $exdmginf['i']?><?php if (!defined('GEXIT_RETURN_JSON')) { ?>状态</a><br>
<?php } else { echo '___aakc'; } ?><?php } if(strpos($inf,'e') !== false && $club == 16) { ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?><input type="radio" name="command" id="infe" value="infe"><a onclick=sl('infe'); href="javascript:void(0);" >治疗<?php } else { echo '___aakf'; } ?><?php echo $exdmginf['e']?><?php if (!defined('GEXIT_RETURN_JSON')) { ?>状态</a><br>
<?php } else { echo '___aakc'; } ?><?php } if(strpos($inf,'w') !== false && $club == 16) { ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?><input type="radio" name="command" id="infw" value="infw"><a onclick=sl('infw'); href="javascript:void(0);" >治疗<?php } else { echo '___aakg'; } ?><?php echo $exdmginf['w']?><?php if (!defined('GEXIT_RETURN_JSON')) { ?>状态</a><br>
<?php } else { echo '___aakc'; } ?><?php } ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?> 

<?php } else { echo '___aakh'; } ?>