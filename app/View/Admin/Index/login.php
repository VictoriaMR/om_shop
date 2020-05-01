<?php include(ROOT_PATH.'/app/View/'.'Admin/Common/baseHeader.php');?>
<link rel="stylesheet" href="<?php echo getenv('APP_DOMAIN');?>/css/login.css">
<script type="text/javascript" src="<?php echo getenv('APP_DOMAIN');?>/js/admin/login.js"></script>
<body class="login-bg">
    <div class="login layui-anim layui-anim-up">
        <div class="message"><?php echo $title ?? '';?></div>
        <div id="darkbannerwrap"></div>
        
        <form method="post" class="layui-form" >
            <input name="name" placeholder="用户名/手机号"  type="text" lay-verify="required" class="layui-input" >
            <hr class="hr15">
            <input name="password" lay-verify="required" placeholder="密码"  type="password" class="layui-input">
            <hr class="hr15">
            <input value="登录" lay-submit lay-filter="login" style="width:100%;" type="submit">
            <hr class="hr20" >
        </form>
    </div>
</body>
<?php include(ROOT_PATH.'/app/View/'.'Admin/Common/baseFooter.php');?>