<?php if(!defined('IN_GAME')) exit('Access Denied'); ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?><table align="center">
<tr>
<td>口头禅</td>
<td><input size="30" type="text" name="motto" maxlength="30" value="<?php } else { echo '___aaru'; } ?><?php echo $motto?><?php if (!defined('GEXIT_RETURN_JSON')) { ?>"><br />写下彰显个性的台词，30个字以内。</td>
</tr>
<tr>
<tr>
<td>杀人宣言</td>
<td><input size="30" type="text" name="killmsg" maxlength="30" value="<?php } else { echo '___aarv'; } ?><?php echo $killmsg?><?php if (!defined('GEXIT_RETURN_JSON')) { ?>"><br />写下你杀死对手的留言，30个字以内</td>
</tr>
<tr>
<td>遗言</td>
<td><input size="30" type="text" name="lastword" maxlength="30" value="<?php } else { echo '___aarw'; } ?><?php echo $lastword?><?php if (!defined('GEXIT_RETURN_JSON')) { ?>"><br />写下你不幸被害时的台词，30个字以内</td>
</tr>
</table> <?php } else { echo '___aarx'; } ?>