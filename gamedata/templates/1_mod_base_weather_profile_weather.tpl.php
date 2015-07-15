<?php if(!defined('IN_GAME')) exit('Access Denied'); eval(import_module('weather')); ?>
<td colspan="2" class="b1"><span>天气:<?php echo $wthinfo[$weather]?></span></td>