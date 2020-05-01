<?php include(ROOT_PATH.'/app/View/'.'Admin/Common/baseHeader.php');?>
<body>
  <div class="layui-fluid">
      <div class="layui-row">
          <form class="layui-form">
            <div class="layui-form-item">
                <label for="username" class="layui-form-label">
                    <span class="x-red">*</span>分类名称
                </label>
                <div class="layui-input-inline">
                    <input type="text" id="name" name="name" required="" lay-verify="required"
                    autocomplete="off" class="layui-input" value="<?php echo $name;?>">
                </div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label">排序</label>
                <div class="layui-input-inline">
                    <input type="text" id="sort" name="sort" required=""
                    autocomplete="off" class="layui-input" value="<?php echo $sort;?>">
                </div>
            </div>
            <div class="layui-form-item">
                <label for="L_repass" class="layui-form-label">
                </label>
                <button  class="layui-btn" lay-filter="add" lay-submit="">
                    <?php if (empty($cate_id)) { echo '增加'; } else { echo '确定'; } ?>
                </button>
            </div>
            <input type="text" name="cate_id" class="layui-input hidden" value="<?php echo $cate_id;?>">
            <input type="text" name="parent_id" class="layui-input hidden" value="<?php echo $parent_id;?>">
        </form>
      </div>
  </div>
  <script>layui.use(['form', 'layer'],
    function() {
      $ = layui.jquery;
      var form = layui.form,
      layer = layui.layer;

      //监听提交
      form.on('submit(add)',
      function(data) {
        $.post(  ADMIN_URI + 'product/saveCategory', data.field, function(res){
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
