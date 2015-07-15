<?php if(!defined('IN_GAME')) exit('Access Denied'); eval(import_module('weapon')); if(is_array($skilltypeinfo)) { foreach($skilltypeinfo as $key => $value) { if((in_array($key,$skillinfo))) { ?>
<tr>
<td class="b2"><span>
<?php if(($$key >= 100)) { ?>
<?php echo $value?>熟
<?php } else { ?>
<span class="grey">
<?php echo $value?>熟</span>
<?php } ?>
</span></td>
<td class="b3"><span>
<?php eval('echo $'.$key.';'); ?>
</span></td>
</tr>
<?php } } } ?>
