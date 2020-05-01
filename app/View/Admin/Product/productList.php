<?php include(ROOT_PATH.'/app/View/'.'Admin/Common/baseHeader.php');?> 
<body>
    <div class="layui-fluid">
        <div class="layui-row layui-col-space15">
            <div class="layui-col-md12">
                <div class="layui-card">
                    <div class="layui-card-body ">
                        <form class="layui-form layui-col-space5">
                            <div class="layui-input-inline layui-show-xs-block">
                                <input class="layui-input" placeholder="开始日" name="start" id="start" value="<?php echo $start;?>"></div>
                            <div class="layui-input-inline layui-show-xs-block">
                                <input class="layui-input" placeholder="截止日" name="end" id="end" value="<?php echo $end;?>"></div>
                            <div class="layui-input-inline layui-show-xs-block">
                                <select name="status">
                                    <option value="">状态</option>
                                    <option value="0" <?php if ($status == 0 && $status !== null) { echo 'selected'; }?>>待上架</option>
                                    <option value="1" <?php if ($status == 1) { echo 'selected'; }?>>已上架</option>
                                </select>
                            </div>
                            <div class="layui-input-inline layui-show-xs-block">
                                <input type="text" name="pro_no" placeholder="请输入商品号" autocomplete="off" class="layui-input" value="<?php echo $pro_no;?>"></div>
                            <div class="layui-input-inline layui-show-xs-block">
                                <button class="layui-btn" lay-submit="" lay-filter="sreach">
                                    <i class="layui-icon">&#xe615;</i></button>
                            </div>
                            <button class="layui-btn" style="line-height:1.6em;margin-top:3px;float:right" onclick="location.reload()" title="刷新">
                                <i class="layui-icon layui-icon-refresh" style="line-height:30px"></i>
                            </button>
                        </form>
                    </div>
                    <div class="layui-card-header">
<!--                         <button class="layui-btn layui-btn-danger" onclick="delAll()">
                            <i class="layui-icon"></i>批量删除</button> -->
                        <a class="layui-btn" href='<?php echo getenv('APP_DOMAIN');?>/admin/product/productPage'>
                            <i class="layui-icon"></i>添加</a>
                        </div>
                    <div class="layui-card-body ">
                        <table class="layui-table layui-form">
                            <thead>
                                <tr>
                                    <th width="150">商品编号</th>
                                    <th>商品名称</th>
                                    <th width="200">图片</th>
                                    <th>状态</th>
                                    <th>原价金额</th>
                                    <th>销售金额</th>
                                    <th>库存</th>
                                    <th>分类</th>
                                    <th width="130">创建时间</th>
                                    <th width="50">操作</th></tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($list)) { ?>
                                <?php foreach ($list as $key => $value) { ?>
                                <tr>
                                    <td><?php echo $value['pro_no'];?></td>
                                    <td><?php echo $value['pro_name'];?></td>
                                    <td>
                                        <?php if (!empty($value['pro_imgs'])) { ?>
                                        <?php foreach ($value['pro_imgs'] as $ik => $iv) { ?>
                                            <div class="table_img_box">
                                                <img src="<?php echo $iv['url'];?>">
                                            </div>
                                        <?php } ?>
                                        <?php } ?>
                                    </td>
                                    <td><?php echo $value['status'] == 1 ? '上架' : '下架';?></td>
                                    <td><?php echo sprintf('%.2f', $value['origin_price']);?></td>
                                    <td><?php echo sprintf('%.2f', $value['sale_price']);?></td>
                                    <td><?php echo $value['pro_stock'];?></td>
                                    <td><?php echo $value['cate_name'];?></td>
                                    <td><?php echo $value['create_at'];?></td>
                                    <td class="td-manage">
                                        <a title="查看" href="<?php echo getenv('APP_DOMAIN');?>/admin/product/productPage?pro_id=<?php echo $value['pro_id'];?>">
                                            <i class="layui-icon">&#xe63c;</i></a>
                                        <a title="删除" onclick="pro_del(this, <?php echo $value['pro_id'];?>)" href="javascript:;">
                                            <i class="layui-icon">&#xe640;</i></a>
                                    </td>
                                </tr>
                                <?php } ?>
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
    layui.use(['laydate', 'form'],
        function() {
            var laydate = layui.laydate;

            //执行一个laydate实例
            laydate.render({
                elem: '#start' //指定元素
            });

            //执行一个laydate实例
            laydate.render({
                elem: '#end' //指定元素
            });
        }
    );

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

    /*商品-删除*/
    function pro_del(obj, id) {
        layer.confirm('确认要删除吗？',
        function(index) {
            $.post(  ADMIN_URI + 'product/del', {pro_id:id}, function(res){
                layer.msg(res.message, {
                    icon: 1,
                    time: 1000
                });
              if (res.code == 200) {
                //发异步删除数据
                $(obj).parents("tr").remove();
              }
          })
        })
    }
</script>

<?php include(ROOT_PATH.'/app/View/'.'Admin/Common/baseFooter.php');?>