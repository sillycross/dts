<?php if(!defined('IN_GAME')) exit('Access Denied'); eval(import_module('wound')); ?>
<td class="b2"><span>受伤部位</span></td>
<td class="b3">
<span>
<?php if($inf) { if(is_array($infinfo)) { foreach($infinfo as $key => $val) { if(strpos($inf,$key)!==false) { ?>
<?php echo $val?>
<?php } } } } else { ?>
无
<?php } ?>
</span>
</td>