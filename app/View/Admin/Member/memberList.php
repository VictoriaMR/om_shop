<?php include(ROOT_PATH.'/app/View/'.'Admin/Common/baseHeader.php');?>
<body>
    <div class="layui-fluid">
        <div class="layui-row layui-col-space15">
            <div class="layui-col-md12">
                <div class="layui-card">
                    <div class="layui-card-body ">
                        <form class="layui-form layui-col-space5">
                            <div class="layui-inline layui-show-xs-block">
                                <input type="text" name="name"  placeholder="用户名搜索" autocomplete="off" class="layui-input" value="<?php echo $name ?? '';?>">
                            </div>

                            <div class="layui-inline layui-show-xs-block">
                                <input type="text" name="mobile"  placeholder="手机搜索" autocomplete="off" class="layui-input" value="<?php echo $mobile ?? '';?>">
                            </div>
                            <div class="layui-inline layui-show-xs-block">
                                <button class="layui-btn"  lay-submit="" lay-filter="search"><i class="layui-icon">&#xe615;</i></button>
                            </div>
                            <div class="layui-inline layui-show-xs-block">
                                <div class="layui-btn" lay-filter="reload" onclick="reload();">重置</div>
                            </div>
                        </form>
                    </div>
                    <?php if(!empty($info['is_super'])) {?>
                    <div class="layui-card-header">
                        <button class="layui-btn" onclick="xadmin.open('添加用户', ADMIN_URL+'member/add', 700, 500)"><i class="layui-icon"></i>添加</button>
                    </div>
                    <?php } ?>
                    <div class="layui-card-body ">
                        <table class="layui-table layui-form">
                          <thead>
                            <tr>
                              <th width="20">ID</th>
                              <th>名称</th>
                              <th>昵称</th>
                              <th>手机</th>
                              <th>属性</th>
                              <th>加入时间</th>
                              <th>状态</th>
                              <th>操作</th>
                          </thead>
                          <tbody>
                            <?php if (!empty($list)) { ?>
                            <?php foreach($list as $userInfo) { ?>
                            <tr>
                              <td><?php echo $userInfo['mem_id'] ?? '';?></td>
                              <td><?php echo $userInfo['name'] ?? '';?></td>
                              <td><?php echo $userInfo['nickname'] ?? '';?></td>
                              <td><?php echo $userInfo['mobile'] ?? '';?></td>
                              <td><?php echo $userInfo['is_super'] ? '超级管理员' : '';?></td>
                              <td><?php echo $userInfo['create_at'];?></td>
                              <td class="td-status">
                                <?php if ($userInfo['status']) {?>
                                <span class="layui-btn layui-btn-normal layui-btn-mini">已启用</span>
                                <?php } else { ?>
                                <span class="layui-btn layui-btn-normal layui-btn-mini layui-btn-disabled">已停用</span>
                                <?php } ?>
                              </td>
                              <td class="td-manage">
                                <?php if(!empty($info['is_super']) && empty($userInfo['is_super'])) { ?>
                                <a onclick="member_stop(this, <?php echo $userInfo['mem_id'];?>)" href="javascript:;"  title="<?php echo $userInfo['status'] ? '停用' : '启用'; ?>">
                                  <?php if ($userInfo['status']) {?>
                                  <i class="layui-icon">&#xe6af;</i>
                                  <?php } else { ?>
                                  <i class="layui-icon">&#xe69c;</i>
                                  <?php } ?>
                                </a>
                                &nbsp;&nbsp;
                                <?php } ?>
                                <?php if ((!empty($info['is_super']) && empty($userInfo['is_super'])) || $userInfo['mem_id'] == $info['mem_id']) { ?>
                                <a title="编辑"  onclick="xadmin.open('编辑',  ADMIN_URL+'member/add?mem_id='+<?php echo $userInfo['mem_id'];?>, 700, 500)" href="javascript:;">
                                  <i class="layui-icon">&#xe642;</i>
                                </a>
                                &nbsp;&nbsp;
                                <?php } ?>
                                <?php if(!empty($info['is_super']) && empty($userInfo['is_super'])) { ?>
                                <a title="删除" onclick="member_del(this, <?php echo $userInfo['mem_id'];?>)" href="javascript:;">
                                  <i class="layui-icon">&#xe640;</i>
                                </a>
                                <?php } ?>
                              </td>
                            </tr>
                            <?php } ?>
                            <?php } else { ?>
                            <tr>
                              <td colspan="8" align="center">
                                <div style="color: orange;margin: 0 auto;">暂无数据</div>
                              </td>
                            </tr>
                            <?php } ?>
                          </tbody>
                        </table>
                    </div>
                    <div class="layui-card-body ">
                        <div id="page">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div> 
</body>
<script>
function reload()
{

    location.href = ADMIN_URL + 'member/memberList';
}

layui.use('laypage', function(){
    var laypage = layui.laypage;
  
    //执行一个laypage实例
    laypage.render({
        elem: 'page', //注意，这里的 test1 是 ID，不用加 # 号
        count: <?php echo $pagination['total'] ?? 0;?>, //数据总数，从服务端得到
        limit: <?php echo $pagination['pagesize'];?>,
        curr: <?php echo $pagination['page'] ?? 1;?>, //当前页
        hash: 'fenye',
        jump: function(obj, first){        
            //首次不执行
            if(!first){
                var parmaString = replacrQueryParma({page: obj.curr, 'pagesize': obj.limit});

                var url = 'http://'+ window.location.host + window.location.pathname + parmaString

                window.location.href = url
            }
        }
    });
});

/*用户-停用*/
function member_stop(obj, id){
    var title = $(obj).attr('title');
    layer.confirm('确认要'+title+'吗？',function(index){
        if(title=='启用'){
            var status = 1;
        } else {
            var status = 0;
        }
        $.post(ADMIN_URI + 'member/updateStatus', {mem_id: id, status: status}, function(res) {
            layer.msg(res.message)
            if (res.code == 200) {
                if(title=='停用'){
                    //发异步把用户状态进行更改
                    $(obj).attr('title','启用')
                    $(obj).find('i').html('&#xe69c;');

                    $(obj).parents("tr").find(".td-status").find('span').addClass('layui-btn-disabled').html('已停用');
                    layer.msg('已停用!', {icon: 5,time:1000});
                }else{
                    $(obj).attr('title','停用')
                    $(obj).find('i').html('&#xe6af;');

                    $(obj).parents("tr").find(".td-status").find('span').removeClass('layui-btn-disabled').html('已启用');
                    layer.msg('已启用!', {icon: 6,time:1000});
                } 
            }
        })
    });
}

/*用户-停用*/
function member_del(obj, id){
    layer.confirm('确认要删除吗？删除之后不出现在列表中',function(index){
        $.post(ADMIN_URI + 'member/delete', {mem_id: id, status: status}, function(res) {
            layer.msg(res.message)
            $(obj).parents('tr').remove();
        })
    });
}

</script>
<?php include(ROOT_PATH.'/app/View/'.'Admin/Common/baseFooter.php');?>