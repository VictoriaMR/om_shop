<?php include(ROOT_PATH.'/app/View/'.'Admin/Common/baseHeader.php');?>
<body>
  <div class="layui-fluid">
      <div class="layui-row layui-col-space15">
          <div class="layui-col-md12">
              <div class="layui-card">
                  <div class="layui-card-header">
                      <button class="layui-btn layui-btn-danger" onclick="del_all()">
                          <i class="layui-icon"></i>批量删除</button>
                      <button class="layui-btn" onclick="xadmin.open('添加', ADMIN_URL+'product/categoryEdit', 500, 400)"><i class="layui-icon"></i>增加</button>
                    <button class="layui-btn" style="line-height:1.6em;margin-top:3px;float:right" onclick="location.reload()" title="刷新">
                        <i class="layui-icon layui-icon-refresh" style="line-height:30px"></i>
                    </button>
                  </div>
                  <div class="layui-card-body ">
                      <table class="layui-table layui-form">
                        <thead>
                          <tr>
                            <th width="20">
                              
                            </th>
                            <th width="70">ID</th>
                            <th>栏目名</th>
                            <th width="50">排序</th>
                            <!-- <th width="80">状态</th> -->
                            <th width="250">操作</th>
                        </thead>
                        <tbody class="x-cate">
                          <?php if (!empty($list)) { ?>
                          <?php foreach ($list as $key => $value) { ?>
                          <tr cate-id="<?php echo $value['cate_id'];?>" fid="<?php echo $value['parent_id'];?>" <?php if (empty($value['parent_id'])) {?> class="orange" <?php } ?>>
                            <td>
                              <input type="checkbox" name="check_del" lay-skin="primary" <?php if (!empty($value['son'])) { ?> disabled="disabled" <?php } ?> cate_id="<?php echo $value['cate_id'];?>">
                            </td>
                            <td><?php echo $value['cate_id'];?></td>
                            <td>
                              <i class="layui-icon x-show" status='true'><?php if (empty($value['parent_id'])) { ?>&#xe623;<?php } else { ?> &nbsp;&nbsp;&nbsp;&nbsp; <?php } ?></i>
                              <?php echo $value['name'];?>
                            </td>
                            <td><?php echo $value['sort'];?></td>
                            <td class="td-manage">
                              <button class="layui-btn layui-btn layui-btn-xs"  onclick="xadmin.open('编辑', ADMIN_URL+'product/categoryEdit?cate_id='+<?php echo $value['cate_id'];?>, 500, 400)" ><i class="layui-icon">&#xe642;</i>编辑</button>
                              <?php if (empty($value['parent_id'])) { ?>
                              <button class="layui-btn layui-btn-warm layui-btn-xs"  onclick="xadmin.open('添加', ADMIN_URL+'product/categoryEdit?parent_id='+<?php echo $value['cate_id'];?>, 500, 400)" ><i class="layui-icon">&#xe642;</i>添加子栏目</button>
                              <?php } ?>
                              <?php if (empty($value['son'])) { ?>
                              <button class="layui-btn-danger layui-btn layui-btn-xs"  onclick="cate_del(this, <?php echo $value['cate_id'];?>)" href="javascript:;" ><i class="layui-icon">&#xe640;</i>删除</button>
                              <?php } ?>
                            </td>
                          </tr>
                          <?php if (!empty($value['son'])) { ?>
                            <?php foreach ($value['son'] as $sonk => $sonv) {?>
                              <tr cate-id="<?php echo $sonv['cate_id'];?>" fid="<?php echo $sonv['parent_id'];?>" <?php if (empty($sonv['parent_id'])) {?> class="orange" <?php } ?>>
                                <td>
                                  <input type="checkbox" name="check_del" cate_id="<?php echo $sonv['cate_id'];?>" lay-skin="primary">
                                </td>
                                <td><?php echo $sonv['cate_id'];?></td>
                                <td>
                                  <i class="layui-icon x-show" status='true'><?php if (empty($sonv['parent_id'])) { ?>&#xe623;<?php } else { ?> &nbsp;&nbsp;&nbsp;&nbsp; <?php } ?></i>
                                  <?php echo $sonv['name'];?>
                                </td>
                                <td><input type="text" class="layui-input x-sort" name="order" value="<?php echo $sonv['sort'];?>"></td>
                                <td class="td-manage">
                                  <button class="layui-btn layui-btn layui-btn-xs"  onclick="xadmin.open('编辑', ADMIN_URL+'product/categoryEdit?cate_id='+<?php echo $sonv['cate_id'];?>, 500, 400)" ><i class="layui-icon">&#xe642;</i>编辑</button>
                                  <?php if (empty($sonv['parent_id'])) { ?>
                                  <button class="layui-btn layui-btn-warm layui-btn-xs"  onclick="xadmin.open('添加', ADMIN_URL+'product/categoryEdit?parent_id='+<?php echo $sonv['cate_id'];?>, 500, 400)" ><i class="layui-icon">&#xe642;</i>添加子栏目</button>
                                  <?php } ?>
                                  <button class="layui-btn-danger layui-btn layui-btn-xs"  onclick="cate_del(this, <?php echo $sonv['cate_id'];?>)" href="javascript:;" ><i class="layui-icon">&#xe640;</i>删除</button>
                                </td>
                              </tr>
                            <?php } ?>
                          <?php } ?>
                          <?php } ?>
                          <?php } else { ?>
                          <tr fid="0">
                            <td colspan="5" align="center">
                              <div style="color: orange;margin: 0 auto;">暂无数据</div>
                            </td>
                          </tr>
                          <?php } ?>
                        </tbody>
                      </table>
                  </div>
              </div>
          </div>
      </div>
  </div>
  <script>
    layui.use(['form'], function(){
      form = layui.form;
      
    });

    // 分类展开收起的分类的逻辑
    // 
    $(function(){
      $("tbody.x-cate tr[fid!='0']").hide();
      // 栏目多级显示效果
      $('.x-show').click(function () {
          if($(this).attr('status')=='true'){
              $(this).html('&#xe625;'); 
              $(this).attr('status','false');
              cateId = $(this).parents('tr').attr('cate-id');
              $("tbody tr[fid="+cateId+"]").show();
         }else{
              cateIds = [];
              $(this).html('&#xe623;');
              $(this).attr('status','true');
              cateId = $(this).parents('tr').attr('cate-id');
              getCateId(cateId);
              for (var i in cateIds) {
                  $("tbody tr[cate-id="+cateIds[i]+"]").hide().find('.x-show').attr('status','true');
              }
         }
      })
    })

    var cateIds = [];
    function getCateId(cateId) {
        $("tbody tr[fid="+cateId+"]").each(function(index, el) {
            id = $(el).attr('cate-id');
            cateIds.push(id);
            getCateId(id);
        });
    }

    function del_all()
    {
      var id_array = new Array(); 
      $('input[name="check_del"]:checked').each(function(){ 
          id_array.push($(this).attr('cate_id'));//向数组中添加元素 
      }); 
      if (id_array.length == 0) {
        layer.msg('请先勾选!')
        return false;
      }

      $.post(ADMIN_URI + 'product/delCategory', {cate_id: id_array.join(',')}, function(res) {
        layer.msg(res.message)
        if (res.code == 200) {
          setTimeout(function(){ location.reload()}, 300);
        }
      })
    }

    function cate_del(obj, id)
    {
      $.post(ADMIN_URI + 'product/delCategory', {cate_id: id}, function(res) {
        layer.msg(res.message)
        if (res.code == 200) {
          $(obj).parents('tr').remove();
        }
      })
    }
  </script>
</body>
<?php include(ROOT_PATH.'/app/View/'.'Admin/Common/baseFooter.php');?>
