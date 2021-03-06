<?php include(ROOT_PATH.'/app/View/'.'Admin/Common/baseHeader.php');?>
<body>
    <div class="layui-fluid">
        <div class="layui-row">
            <form class="layui-form">
              <?php if (!empty($mem_id)) { ?>
                <input type="text" id="mem_id" name="mem_id" required="" lay-verify="required"
                      autocomplete="off" class="layui-input hidden" value="<?php echo $mem_id ?? '' ;?>">
              <?php } ?>
              <div class="layui-form-item">
                  <label for="username" class="layui-form-label">
                      <span class="x-red">*</span>姓名
                  </label>
                  <div class="layui-input-inline">
                      <input type="text" id="username" name="name" required="" lay-verify="required"
                      autocomplete="off" class="layui-input" value="<?php echo $name ?? '' ;?>">
                  </div>
                  <div class="layui-form-mid layui-word-aux">
                      <span class="x-red">*</span>将会成为您唯一的登入名
                  </div>
              </div>
              <div class="layui-form-item">
                  <label for="nickname" class="layui-form-label">
                      昵称
                  </label>
                  <div class="layui-input-inline">
                      <input type="text" id="nickname" name="nickname"
                      autocomplete="off" class="layui-input" value="<?php echo $nickname ?? '' ;?>">
                  </div>
              </div>
              <div class="layui-form-item">
                  <label for="phone" class="layui-form-label">
                      <span class="x-red">*</span>手机
                  </label>
                  <div class="layui-input-inline">
                      <input type="text" id="phone" name="mobile" required="" lay-verify="phone"
                      autocomplete="off" class="layui-input" value="<?php echo $mobile ?? '' ;?>">
                  </div>
                  <div class="layui-form-mid layui-word-aux">
                      <span class="x-red">*</span>将会成为您唯一的登入名
                  </div>
              </div>
              <div class="layui-form-item">
                  <label class="layui-form-label"><span class="x-red">*</span>属性</label>
                  <div class="layui-input-block">
                    <input type="checkbox" name="is_super" lay-skin="primary" title="超级管理员" <?php if ($is_super) {?> checked="checked" disabled="disabled" <?php } ?>>
                  </div>
              </div>
              <div class="layui-form-item">
                  <label for="L_pass" class="layui-form-label">
                      <span class="x-red">*</span>密码
                  </label>
                  <div class="layui-input-inline">
                      <input type="password" id="L_pass" name="password" required="" lay-verify="pass"
                      autocomplete="off" class="layui-input">
                  </div>
                  <div class="layui-form-mid layui-word-aux">
                      6到16个字符
                  </div>
              </div>
              <div class="layui-form-item">
                  <label for="L_repass" class="layui-form-label">
                      <span class="x-red">*</span>确认密码
                  </label>
                  <div class="layui-input-inline">
                      <input type="password" id="L_repass" name="repass" required="" lay-verify="repass"
                      autocomplete="off" class="layui-input">
                  </div>
              </div>
              <div class="layui-form-item">
                  <label for="L_repass" class="layui-form-label">
                  </label>
                  <button  class="layui-btn" lay-filter="add" lay-submit="">
                    <?php if (empty($mem_id)) { echo '增加'; } else { echo '确定'; } ?>
                  </button>
              </div>
          </form>
        </div>
    </div>
    <script>
      layui.use(['form', 'layer'],
        function() {
            $ = layui.jquery;
            var form = layui.form,
            layer = layui.layer;

            //自定义验证规则
            form.verify({
                nikename: function(value) {
                    if (value.length < 5) {
                        return '昵称至少得5个字符啊';
                    }
                },
                pass: [/(.+){6,12}$/, '密码必须6到12位'],
                repass: function(value) {
                    if ($('#L_pass').val() != $('#L_repass').val()) {
                        return '两次密码不一致';
                    }
                }
            });

            //监听提交
            form.on('submit(add)',
            function(data) {
                <?php if (empty($mem_id)) { ?>
                  var url = ADMIN_URI + 'member/addMember';
                <?php } else { ?>
                  var url = ADMIN_URI + 'member/editMember';
                <?php } ?>
                $.post( url, data.field, function(res){
                    
                    if (res.code == 200) {
                        layer.alert(res.message, {
                            icon: 6
                        },
                        function() {
                            //关闭当前frame
                            xadmin.close();

                            // 可以对父窗口进行刷新 
                            xadmin.father_reload();
                        });
                    } else {
                        layer.alert(res.message);
                    }
                })
                return false;
            });
        });
</script>
</body>
<?php include(ROOT_PATH.'/app/View/'.'Admin/Common/baseFooter.php');?>
