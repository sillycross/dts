<?php if(!defined('IN_GAME')) exit('Access Denied'); ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?><!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php } else { echo '___aaoY'; } ?><?php echo $charset?><?php if (!defined('GEXIT_RETURN_JSON')) { ?>">
<meta http-equiv="pragma" content="no-cache">
<meta http-equiv='expires' content='0'>
<title> ACFUN 大 逃 杀 </title>
<?php } else { echo '___aaoZ'; } ?><?php if(isset($extrahead)) { ?>
<?php echo $extrahead?>
<?php } ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?><link rel="stylesheet" type="text/css" id="css" href="gamedata/cache/style_1.20150620.css">
<script type="text/javascript" src="include/javascript/common.20150627.js"></script>
<script type="text/javascript" src="include/javascript/game.20150711.js"></script>
<!-- jScrollPanel Utility Start -->
<link type="text/css" href="include/javascript/jScrollPane/jquery.jscrollpane.css" rel="stylesheet" media="all" />
<link type="text/css" href="include/javascript/jScrollPane/jquery.jscrollpane.lozenge.css" rel="stylesheet" media="all" />
<script type="text/javascript" src="include/javascript/jScrollPane/jquery.min.js"></script>
<script type="text/javascript" src="include/javascript/jScrollPane/jquery.mousewheel.js"></script>
<script type="text/javascript" src="include/javascript/jScrollPane/mwheelIntent.js"></script>
<script type="text/javascript" src="include/javascript/jScrollPane/jquery.jscrollpane.js"></script>
<script type="text/javascript" id="sourcecode">
jQuery.noConflict();
</script>
<!-- jScrollPanel Utility End -->

<!-- gzip Decode Utility Start -->
<script type="text/javascript" src="include/javascript/gzip.util.js"></script>
<!-- gzip Decode Utility End -->

<!-- Game Word Data Lib Start -->
<?php } else { echo '___aao0'; } ?><?php global $___MOD_CODE_ADV3; if($___MOD_CODE_ADV3) { ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?><script type="text/javascript" src=
<?php } else { echo '___aao1'; } ?><?php $___TEMP_curdatalibname=file_get_contents(GAME_ROOT.'./gamedata/javascript/datalib.current.txt'); echo '"gamedata/javascript/'.$___TEMP_curdatalibname.'"'; ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?>></script>
<?php } else { echo '___aam4'; } ?><?php } ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?><!-- Game Word Data Lib End -->

<!-- jQuery Load Script Utility -->
<script type="text/javascript" src="include/javascript/jquery.cachedScript.js"></script>
<!-- jQuery Load Script Utility End -->

</head>
<BODY 
<?php } else { echo '___aao2'; } ?><?php if(CURSCRIPT == 'never') { ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?>onkeydown="hotkey(event);"
<?php } else { echo '___aao3'; } ?><?php } ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?>>
<div class="title" >ACFUN 大 逃 杀</div>

<div class="headerlink" >
<a href="index.php">>>首页</a>
<?php } else { echo '___aao4'; } ?><?php if(isset($cuser) && isset($cpass)) { ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?><a href="user.php">>>帐号资料</a>
<?php } else { echo '___aao5'; } ?><?php } else { ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?><a href="register.php">>>账号注册</a>
<?php } else { echo '___aao6'; } ?><?php } ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?><a href="game.php">>>进入游戏</a>
<a href="map.php">>>战场地图</a>
<a href="news.php">>>进行状况</a>
<a href="alive.php">>>当前幸存</a>
<a href="winner.php">>>历史优胜</a>
<a href="rank.php">>>玩家排行</a>
<a href="help.php">>>游戏帮助</a>
<a href="admin.php">>>游戏管理</a>
<a href="http://76573.org/" target="_blank">>>官方讨论区</a>
<a href="<?php } else { echo '___aao7'; } ?><?php echo $homepage?><?php if (!defined('GEXIT_RETURN_JSON')) { ?>" target="_blank">>>官方网站</a>
<!--[if lt IE 7]> <div style=' clear: both; height: 59px; padding:0 0 0 15px; position: relative;'> <a href="http://windows.microsoft.com/en-US/internet-explorer/products/ie/home?ocid=ie6_countdown_bannercode"><img src="http://storage.ie6countdown.com/assets/100/images/banners/warning_bar_0027_Simplified Chinese.jpg" border="0" height="42" width="820" alt="You are using an outdated browser. For a faster, safer browsing experience, upgrade for free today." /></a></div> <![endif]-->


</div>
<div id="fmsgbox-container"></div>
<div id="hidden-fmsgbox-container" style="display:none;"></div>
<div id="hidden-persistent-fmsgbox-container" style="display:none;"></div>
<div><?php } else { echo '___aao8'; } ?>