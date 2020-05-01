<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	<title><?php echo $title ?? ''?></title>
	<meta name="renderer" content="webkit|ie-comp|ie-stand">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta http-equiv="Cache-Control" content="no-siteapp" />
    <link rel="stylesheet" href="<?php echo getenv('APP_DOMAIN');?>/css/font.css">
    <link rel="stylesheet" href="<?php echo getenv('APP_DOMAIN');?>/css/login.css">
	<link rel="stylesheet" href="<?php echo getenv('APP_DOMAIN');?>/css/xadmin.css">
    <script type="text/javascript" src="<?php echo getenv('APP_DOMAIN');?>/js/jquery.min.js" charset="utf-8"></script>
    <script type="text/javascript" src="<?php echo getenv('APP_DOMAIN');?>/lib/layui/layui.js" charset="utf-8"></script>
    <script type="text/javascript" src="<?php echo getenv('APP_DOMAIN');?>/js/xadmin.js" charset="utf-8"></script>
    <script type="text/javascript" src="<?php echo getenv('APP_DOMAIN');?>/js/common.js" charset="utf-8"></script>
    <script type="text/javascript" src="<?php echo getenv('APP_DOMAIN');?>/ueditor/ueditor.config.js" charset="utf-8"></script>
    <script type="text/javascript" src="<?php echo getenv('APP_DOMAIN');?>/ueditor/ueditor.all.js" charset="utf-8"></script>
    <script type="text/javascript" src="<?php echo getenv('APP_DOMAIN');?>/ueditor/lang/zh-cn/zh-cn.js" charset="utf-8"></script>
</head>
<script>
    var ADMIN_URL = '<?php echo getenv('APP_DOMAIN');?>/admin/'
    var ADMIN_URI = '<?php echo getenv('APP_DOMAIN');?>/api/admin/'
    var API_URI = '<?php echo getenv('APP_DOMAIN');?>/api/'
</script>