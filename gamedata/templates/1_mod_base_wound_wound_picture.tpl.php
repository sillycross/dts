<?php if(!defined('IN_GAME')) exit('Access Denied'); ?>
<img id="injuerd" 
<?php if(strpos($inf,'h') !== false || strpos($inf,'b') !== false ||strpos($inf,'a') !== false ||strpos($inf,'f') !== false) { ?>
src="img/injured.gif"
<?php } else { ?>
src="img/injured2.gif"
<?php } ?>
 style="position:absolute;top:0;left:10;width:84;height:20;">
<img id="poisoned" 
<?php if(strpos($inf,'p') !== false) { if($club==16 && CURSCRIPT == 'game' && $mode == 'command') { ?>
onclick="$('mode').value='special';$('command').value='infp';postCmd('gamecmd','command.php');return false;" 
<?php } ?>
src="img/p.gif"
<?php } else { ?>
src="img/p2.gif"
<?php } ?>
 style="position:absolute;top:20;left:4;width:98;height:20;z-index:1;">
<img id="burned" 
<?php if(strpos($inf,'u') !== false) { if($club==16 && CURSCRIPT == 'game' && $mode == 'command') { ?>
onclick="$('mode').value='special';$('command').value='infu';postCmd('gamecmd','command.php');return false;" 
<?php } ?>
src="img/u.gif"
<?php } else { ?>
src="img/u2.gif"
<?php } ?>
 style="position:absolute;top:40;left:11;width:81;height:20;z-index:1;">
<img id="frozen" 
<?php if(strpos($inf,'i') !== false) { if($club==16 && CURSCRIPT == 'game' && $mode == 'command') { ?>
onclick="$('mode').value='special';$('command').value='infi';postCmd('gamecmd','command.php');return false;" 
<?php } ?>
src="img/i.gif"
<?php } else { ?>
src="img/i2.gif"
<?php } ?>
 style="position:absolute;top:60;left:13;width:77;height:20;z-index:1;">
<img id="paralysed" 
<?php if(strpos($inf,'e') !== false) { if($club==16 && CURSCRIPT == 'game' && $mode == 'command') { ?>
onclick="$('mode').value='special';$('command').value='infe';postCmd('gamecmd','command.php');return false;" 
<?php } ?>
src="img/e.gif"
<?php } else { ?>
src="img/e2.gif"
<?php } ?>
 style="position:absolute;top:80;left:2;width:101;height:20;z-index:1;">
<img id="confused" 
<?php if(strpos($inf,'w') !== false) { if($club==16 && CURSCRIPT == 'game' && $mode == 'command') { ?>
onclick="$('mode').value='special';$('command').value='infw';postCmd('gamecmd','command.php');return false;" 
<?php } ?>
src="img/w.gif"
<?php } else { ?>
src="img/w2.gif"
<?php } ?>
 style="position:absolute;top:100;left:3;width:100;height:20;z-index:1;">
<?php if(strpos($inf,'h') !== false) { ?>
<img src="img/hurt.gif" style="position:absolute;top:0;left:121;width:37;height:37;z-index:1;" 
<?php if(CURSCRIPT == 'game' && $mode == 'command') { ?>
onclick="$('mode').value='special';$('command').value='infh';postCmd('gamecmd','command.php');return false;"
<?php } ?>
>
<?php } if(strpos($inf,'b') !== false) { ?>
<img src="img/hurt.gif" style="position:absolute;top:43;left:121;width:37;height:37;z-index:1;" 
<?php if(CURSCRIPT == 'game' && $mode == 'command') { ?>
onclick="$('mode').value='special';$('command').value='infb';postCmd('gamecmd','command.php');return false;"
<?php } ?>
>
<?php } if(strpos($inf,'a') !== false) { ?>
<img src="img/hurt.gif" style="position:absolute;top:17;left:102;width:37;height:37;z-index:1;" 
<?php if(CURSCRIPT == 'game' && $mode == 'command') { ?>
onclick="$('mode').value='special';$('command').value='infa';postCmd('gamecmd','command.php');return false;"
<?php } ?>
>
<?php } if(strpos($inf,'f') !== false) { ?>
<img src="img/hurt.gif" style="position:absolute;top:111;left:121;width:37;height:37;z-index:1;" 
<?php if(CURSCRIPT == 'game' && $mode == 'command') { ?>
onclick="$('mode').value='special';$('command').value='inff';postCmd('gamecmd','command.php');return false;"
<?php } ?>
>
<?php } ?>
