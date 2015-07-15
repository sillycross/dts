<?php if(!defined('IN_GAME')) exit('Access Denied'); ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?><img id="injuerd" 
<?php } else { echo '___aafj'; } ?><?php if(strpos($inf,'h') !== false || strpos($inf,'b') !== false ||strpos($inf,'a') !== false ||strpos($inf,'f') !== false) { ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?>src="img/injured.gif"
<?php } else { echo '___aafk'; } ?><?php } else { ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?>src="img/injured2.gif"
<?php } else { echo '___aafl'; } ?><?php } ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?> style="position:absolute;top:0;left:10;width:84;height:20;">
<img id="poisoned" 
<?php } else { echo '___aafm'; } ?><?php if(strpos($inf,'p') !== false) { if($club==16 && CURSCRIPT == 'game' && $mode == 'command') { ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?>onclick="$('mode').value='special';$('command').value='infp';postCmd('gamecmd','command.php');return false;" 
<?php } else { echo '___aafn'; } ?><?php } ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?>src="img/p.gif"
<?php } else { echo '___aafo'; } ?><?php } else { ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?>src="img/p2.gif"
<?php } else { echo '___aafp'; } ?><?php } ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?> style="position:absolute;top:20;left:4;width:98;height:20;z-index:1;">
<img id="burned" 
<?php } else { echo '___aafq'; } ?><?php if(strpos($inf,'u') !== false) { if($club==16 && CURSCRIPT == 'game' && $mode == 'command') { ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?>onclick="$('mode').value='special';$('command').value='infu';postCmd('gamecmd','command.php');return false;" 
<?php } else { echo '___aafr'; } ?><?php } ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?>src="img/u.gif"
<?php } else { echo '___aafs'; } ?><?php } else { ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?>src="img/u2.gif"
<?php } else { echo '___aaft'; } ?><?php } ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?> style="position:absolute;top:40;left:11;width:81;height:20;z-index:1;">
<img id="frozen" 
<?php } else { echo '___aafu'; } ?><?php if(strpos($inf,'i') !== false) { if($club==16 && CURSCRIPT == 'game' && $mode == 'command') { ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?>onclick="$('mode').value='special';$('command').value='infi';postCmd('gamecmd','command.php');return false;" 
<?php } else { echo '___aafv'; } ?><?php } ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?>src="img/i.gif"
<?php } else { echo '___aafw'; } ?><?php } else { ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?>src="img/i2.gif"
<?php } else { echo '___aafx'; } ?><?php } ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?> style="position:absolute;top:60;left:13;width:77;height:20;z-index:1;">
<img id="paralysed" 
<?php } else { echo '___aafy'; } ?><?php if(strpos($inf,'e') !== false) { if($club==16 && CURSCRIPT == 'game' && $mode == 'command') { ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?>onclick="$('mode').value='special';$('command').value='infe';postCmd('gamecmd','command.php');return false;" 
<?php } else { echo '___aafz'; } ?><?php } ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?>src="img/e.gif"
<?php } else { echo '___aafA'; } ?><?php } else { ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?>src="img/e2.gif"
<?php } else { echo '___aafB'; } ?><?php } ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?> style="position:absolute;top:80;left:2;width:101;height:20;z-index:1;">
<img id="confused" 
<?php } else { echo '___aafC'; } ?><?php if(strpos($inf,'w') !== false) { if($club==16 && CURSCRIPT == 'game' && $mode == 'command') { ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?>onclick="$('mode').value='special';$('command').value='infw';postCmd('gamecmd','command.php');return false;" 
<?php } else { echo '___aafD'; } ?><?php } ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?>src="img/w.gif"
<?php } else { echo '___aafE'; } ?><?php } else { ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?>src="img/w2.gif"
<?php } else { echo '___aafF'; } ?><?php } ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?> style="position:absolute;top:100;left:3;width:100;height:20;z-index:1;">
<?php } else { echo '___aafG'; } ?><?php if(strpos($inf,'h') !== false) { ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?><img src="img/hurt.gif" style="position:absolute;top:0;left:121;width:37;height:37;z-index:1;" 
<?php } else { echo '___aafH'; } ?><?php if(CURSCRIPT == 'game' && $mode == 'command') { ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?>onclick="$('mode').value='special';$('command').value='infh';postCmd('gamecmd','command.php');return false;"
<?php } else { echo '___aafI'; } ?><?php } ?>
>
<?php } if(strpos($inf,'b') !== false) { ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?><img src="img/hurt.gif" style="position:absolute;top:43;left:121;width:37;height:37;z-index:1;" 
<?php } else { echo '___aafJ'; } ?><?php if(CURSCRIPT == 'game' && $mode == 'command') { ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?>onclick="$('mode').value='special';$('command').value='infb';postCmd('gamecmd','command.php');return false;"
<?php } else { echo '___aafK'; } ?><?php } ?>
>
<?php } if(strpos($inf,'a') !== false) { ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?><img src="img/hurt.gif" style="position:absolute;top:17;left:102;width:37;height:37;z-index:1;" 
<?php } else { echo '___aafL'; } ?><?php if(CURSCRIPT == 'game' && $mode == 'command') { ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?>onclick="$('mode').value='special';$('command').value='infa';postCmd('gamecmd','command.php');return false;"
<?php } else { echo '___aafM'; } ?><?php } ?>
>
<?php } if(strpos($inf,'f') !== false) { ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?><img src="img/hurt.gif" style="position:absolute;top:111;left:121;width:37;height:37;z-index:1;" 
<?php } else { echo '___aafN'; } ?><?php if(CURSCRIPT == 'game' && $mode == 'command') { ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?>onclick="$('mode').value='special';$('command').value='inff';postCmd('gamecmd','command.php');return false;"
<?php } else { echo '___aafO'; } ?><?php } ?>
>
<?php } ?>

