<?php if(!defined('IN_GAME')) exit('Access Denied'); ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?><div id=
<?php } else { echo '___aaab'; } ?><?php echo '"fmsgbox'.((string)\bubblebox\bubblebox_get_style('id')).'"'; ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?> style="display:none">

<!--全屏背景降低-->
<div style="position:fixed; left:0px; top:0px; z-index:
<?php } else { echo '___aaac'; } ?><?php echo 2+((int)\bubblebox\bubblebox_get_style('z-index-base')); ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?>; margin:auto auto; filter:alpha(opacity=20); opacity:0.2; min-width:100%; max-width:100%; min-height:100%; max-height:100%; background-color:#000000">
</div>
<!--END-->

<!--构建半透明外缘-->
<div style="position:fixed; left:0px; top:0px; z-index:
<?php } else { echo '___aaad'; } ?><?php echo 3+((int)\bubblebox\bubblebox_get_style('z-index-base')); ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?>; margin:auto auto; min-width:100%; max-width:100%; min-height:100%; max-height:100%;">
<div style="position:absolute; z-index:
<?php } else { echo '___aaae'; } ?><?php echo 4+((int)\bubblebox\bubblebox_get_style('z-index-base')); ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?>; top:50%; margin-top:
<?php } else { echo '___aaaf'; } ?><?php echo (((int)\bubblebox\bubblebox_get_style('offset-y'))-ceil((((int)\bubblebox\bubblebox_get_style('height'))+((int)\bubblebox\bubblebox_get_style('border-width-y'))*2+((int)\bubblebox\bubblebox_get_style('margin-top'))+((int)\bubblebox\bubblebox_get_style('margin-bottom')))/2)).'px'; ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?>; left:50%; margin-left:
<?php } else { echo '___aaag'; } ?><?php echo (((int)\bubblebox\bubblebox_get_style('offset-x'))-ceil((((int)\bubblebox\bubblebox_get_style('width'))+((int)\bubblebox\bubblebox_get_style('border-width-x'))*2+((int)\bubblebox\bubblebox_get_style('margin-left'))+((int)\bubblebox\bubblebox_get_style('margin-right')))/2)).'px'; ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?>; ">
<div style="position:relative; z-index:
<?php } else { echo '___aaah'; } ?><?php echo 4+((int)\bubblebox\bubblebox_get_style('z-index-base')); ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?>; margin-left:auto; margin-right:auto; filter:alpha(opacity=20); opacity:0.2; min-width:
<?php } else { echo '___aaai'; } ?><?php echo (((int)\bubblebox\bubblebox_get_style('width'))+((int)\bubblebox\bubblebox_get_style('border-width-x'))*2+((int)\bubblebox\bubblebox_get_style('margin-left'))+((int)\bubblebox\bubblebox_get_style('margin-right'))).'px'; ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?>; max-width:
<?php } else { echo '___aaaj'; } ?><?php echo (((int)\bubblebox\bubblebox_get_style('width'))+((int)\bubblebox\bubblebox_get_style('border-width-x'))*2+((int)\bubblebox\bubblebox_get_style('margin-left'))+((int)\bubblebox\bubblebox_get_style('margin-right'))).'px'; ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?>; min-height:
<?php } else { echo '___aaak'; } ?><?php echo (((int)\bubblebox\bubblebox_get_style('height'))+((int)\bubblebox\bubblebox_get_style('border-width-y'))*2+((int)\bubblebox\bubblebox_get_style('margin-top'))+((int)\bubblebox\bubblebox_get_style('margin-bottom'))).'px'; ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?>; max-height:
<?php } else { echo '___aaal'; } ?><?php echo (((int)\bubblebox\bubblebox_get_style('height'))+((int)\bubblebox\bubblebox_get_style('border-width-y'))*2+((int)\bubblebox\bubblebox_get_style('margin-top'))+((int)\bubblebox\bubblebox_get_style('margin-bottom'))).'px'; ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?>; background-color:#000000">
</div>
</div>
</div>
<!--END-->

<!--构建文本框-->
<div style="position:fixed; left:0px; top:0px; z-index:
<?php } else { echo '___aaam'; } ?><?php echo 3+((int)\bubblebox\bubblebox_get_style('z-index-base')); ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?>; margin:auto auto; min-width:100%; max-width:100%; min-height:100%; max-height:100%;" 
<?php } else { echo '___aaan'; } ?><?php if(((int)\bubblebox\bubblebox_get_style('cancellable'))==1) { ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?> onclick="bubblebox_hide_all();"
<?php } else { echo '___aaao'; } ?><?php } ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?>>
<div style="position:absolute; z-index:
<?php } else { echo '___aaap'; } ?><?php echo 5+((int)\bubblebox\bubblebox_get_style('z-index-base')); ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?>; top:50%; margin-top:
<?php } else { echo '___aaaf'; } ?><?php echo (((int)\bubblebox\bubblebox_get_style('offset-y'))-ceil((((int)\bubblebox\bubblebox_get_style('height'))+((int)\bubblebox\bubblebox_get_style('margin-top'))+((int)\bubblebox\bubblebox_get_style('margin-bottom')))/2)).'px'; ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?>; left:50%; margin-left:
<?php } else { echo '___aaag'; } ?><?php echo (((int)\bubblebox\bubblebox_get_style('offset-x'))-ceil((((int)\bubblebox\bubblebox_get_style('width'))+((int)\bubblebox\bubblebox_get_style('margin-left'))+((int)\bubblebox\bubblebox_get_style('margin-right')))/2)).'px'; ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?>; ">
<div style="position:relative; min-width:
<?php } else { echo '___aaaq'; } ?><?php echo (((int)\bubblebox\bubblebox_get_style('width'))+((int)\bubblebox\bubblebox_get_style('margin-left'))+((int)\bubblebox\bubblebox_get_style('margin-right'))).'px'; ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?>; max-width:
<?php } else { echo '___aaaj'; } ?><?php echo (((int)\bubblebox\bubblebox_get_style('width'))+((int)\bubblebox\bubblebox_get_style('margin-left'))+((int)\bubblebox\bubblebox_get_style('margin-right'))).'px'; ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?>; min-height:
<?php } else { echo '___aaak'; } ?><?php echo (((int)\bubblebox\bubblebox_get_style('height'))+((int)\bubblebox\bubblebox_get_style('margin-top'))+((int)\bubblebox\bubblebox_get_style('margin-bottom'))).'px'; ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?>; max-height:
<?php } else { echo '___aaal'; } ?><?php echo (((int)\bubblebox\bubblebox_get_style('height'))+((int)\bubblebox\bubblebox_get_style('margin-top'))+((int)\bubblebox\bubblebox_get_style('margin-bottom'))).'px'; ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?>; z-index:
<?php } else { echo '___aaar'; } ?><?php echo 5+((int)\bubblebox\bubblebox_get_style('z-index-base')); ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?>; margin:0 auto; filter:alpha(opacity=
<?php } else { echo '___aaas'; } ?><?php echo round(((double)\bubblebox\bubblebox_get_style('opacity'))*100); ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?>); opacity:
<?php } else { echo '___aaat'; } ?><?php echo ((double)\bubblebox\bubblebox_get_style('opacity')); ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?>; background-color:#000000" onclick="if (event.stopPropagation) event.stopPropagation(); else event.cancelBubble = true; ">
<div style="position:relative; margin-left:0px; margin-top:0px; margin-right:0px; margin-bottom:0px; z-index:
<?php } else { echo '___aaau'; } ?><?php echo 6+((int)\bubblebox\bubblebox_get_style('z-index-base')); ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?>; background-color:rgba(17,17,17,0.5); *BACKGROUND:rgb(17,17,17); *filter:alpha(opacity=50); BORDER: #000 0px none; color: #fff; text-align: center; border-right: #111 1px solid;font:10pt '微软雅黑' serif;">
<!--END-->

<!--构建文字区-->
<div style="position:relative; margin-left:
<?php } else { echo '___aaav'; } ?><?php echo ((int)\bubblebox\bubblebox_get_style('margin-left')).'px'; ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?>; margin-top:0px; margin-right:0px; margin-bottom:
<?php } else { echo '___aaaw'; } ?><?php echo ((int)\bubblebox\bubblebox_get_style('margin-bottom')).'px'; ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?>; min-height:
<?php } else { echo '___aaak'; } ?><?php echo (((int)\bubblebox\bubblebox_get_style('height'))+((int)\bubblebox\bubblebox_get_style('margin-top'))+((int)\bubblebox\bubblebox_get_style('margin-bottom'))).'px'; ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?>; max-height:
<?php } else { echo '___aaal'; } ?><?php echo (((int)\bubblebox\bubblebox_get_style('height'))+((int)\bubblebox\bubblebox_get_style('margin-top'))+((int)\bubblebox\bubblebox_get_style('margin-bottom'))).'px'; ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?>; text-align:left; overflow-x:hidden; overflow-y:auto;" >
<div class=
<?php } else { echo '___aaax'; } ?><?php echo '"scroll-pane'.((string)\bubblebox\bubblebox_get_style('id')).'"'; ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?> style="max-height:
<?php } else { echo '___aaay'; } ?><?php echo ((int)\bubblebox\bubblebox_get_style('height')).'px'; ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?>; width:
<?php } else { echo '___aaaz'; } ?><?php echo ((int)\bubblebox\bubblebox_get_style('width')).'px'; ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?>; overflow-x:hidden; overflow-y:auto; margin-top:
<?php } else { echo '___aaaA'; } ?><?php echo ((int)\bubblebox\bubblebox_get_style('margin-top')).'px'; ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?>; position:relative;">
<div style="position:relative; height:auto; margin-left:0px; margin-top:0px; margin-right:0px; margin-bottom:0px; ">
<!--END--><?php } else { echo '___aaaB'; } ?>