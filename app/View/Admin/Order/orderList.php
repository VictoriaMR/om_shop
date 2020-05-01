<?php include(ROOT_PATH.'/app/View/'.'Admin/Common/baseHeader.php');?> 
<body>
    <div class="x-nav">
        <span class="layui-breadcrumb">
            <a href="">首页</a>
            <a href="">订单</a>
            <a>
                <cite>订单列表</cite></a>
        </span>
        <a class="layui-btn layui-btn-small" style="line-height:1.6em;margin-top:3px;float:right" onclick="location.reload()" title="刷新">
            <i class="layui-icon layui-icon-refresh" style="line-height:30px"></i>
        </a>
    </div>
    <div class="layui-fluid">
        <div class="layui-row layui-col-space15">
            <div class="layui-col-md12">
                <div class="layui-card">
                    <div class="layui-card-body ">
                        <form class="layui-form layui-col-space5">
                            <div class="layui-input-inline layui-show-xs-block">
                                <input class="layui-input" placeholder="开始日" name="start" id="start"  value="<?php echo $start;?>"></div>
                            <div class="layui-input-inline layui-show-xs-block">
                                <input class="layui-input" placeholder="截止日" name="end" id="end" value="<?php echo $end;?>"></div>
                            <div class="layui-input-inline layui-show-xs-block">
                                <select name="payment">
                                    <option value="">支付方式</option>
                                    <option value="1" <?php echo $payment == 1 ? 'selected' : '';?>>支付宝</option>
                                    <option value="2" <?php echo $payment == 2 ? 'selected' : '';?>>微信</option>
                                    <option value="3" <?php echo $payment == 3 ? 'selected' : '';?>>其他</option></select>
                            </div>
                            <div class="layui-input-inline layui-show-xs-block">
                                <select name="status">
                                    <option value="">订单状态</option>
                                    <option value="0" <?php echo $status == 0 && $status !== null ? 'selected' : '';?>>待支付</option>
                                    <option value="1" <?php echo $status == 1 ? 'selected' : '';?>>已支付</option>
                                    <option value="2" <?php echo $status == 2 ? 'selected' : '';?>>已发货</option>
                                    <option value="3" <?php echo $status == 3 ? 'selected' : '';?>>已完成</option>
                                    <option value="4" <?php echo $status == 4 ? 'selected' : '';?>>已取消</option>
                                    <option value="5" <?php echo $status == 5 ? 'selected' : '';?>>已退款</option>
                                </select>
                            </div>
                            <div class="layui-input-inline layui-show-xs-block">
                                <input type="text" name="ord_no" placeholder="请输入订单号" autocomplete="off" class="layui-input"></div>
                            <div class="layui-input-inline layui-show-xs-block">
                                <button class="layui-btn" lay-submit="" lay-filter="sreach">
                                    <i class="layui-icon">&#xe615;</i></button>
                            </div>
                        </form>
                    </div>
                    <div class="layui-card-header">
                        <button class="layui-btn layui-btn-danger" onclick="delAll()">
                            <i class="layui-icon"></i>批量删除</button>
                        <button class="layui-btn" onclick="xadmin.open('添加订单',ADMIN_URL+'order/addPage', 800, 600)">
                            <i class="layui-icon"></i>添加</button></div>
                    <div class="layui-card-body ">
                        <table class="layui-table layui-form">
                            <thead>
                                <tr>
                                    <th>
                                        <input type="checkbox" name="" lay-skin="primary">
                                    </th>
                                    <th>订单编号</th>
                                    <th>收货人</th>
                                    <th>总金额</th>
                                    <th>应付金额</th>
                                    <th>订单状态</th>
                                    <th>支付状态</th>
                                    <th>发货状态</th>
                                    <th>支付方式</th>
                                    <th>配送方式</th>
                                    <th>下单时间</th>
                                    <th>操作</th></tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>
                                        <input type="checkbox" name="" lay-skin="primary"></td>
                                    <td>2017009171822298053</td>
                                    <td>老王:18925139194</td>
                                    <td>7829.10</td>
                                    <td>7854.10</td>
                                    <td>待确认</td>
                                    <td>未支付</td>
                                    <td>未发货</td>
                                    <td>其他方式</td>
                                    <td>申通物流</td>
                                    <td>2017-08-17 18:22</td>
                                    <td class="td-manage">
                                        <a title="查看" onclick="xadmin.open('编辑','order-view.html')" href="javascript:;">
                                            <i class="layui-icon">&#xe63c;</i></a>
                                        <a title="删除" onclick="member_del(this,'要删除的id')" href="javascript:;">
                                            <i class="layui-icon">&#xe640;</i></a>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <input type="checkbox" name="" lay-skin="primary"></td>
                                    <td>2017009171822298053</td>
                                    <td>老王:18925139194</td>
                                    <td>7829.10</td>
                                    <td>7854.10</td>
                                    <td>待确认</td>
                                    <td>未支付</td>
                                    <td>未发货</td>
                                    <td>其他方式</td>
                                    <td>申通物流</td>
                                    <td>2017-08-17 18:22</td>
                                    <td class="td-manage">
                                        <a title="查看" onclick="xadmin.open('编辑','order-view.html')" href="javascript:;">
                                            <i class="layui-icon">&#xe63c;</i></a>
                                        <a title="删除" onclick="member_del(this,'要删除的id')" href="javascript:;">
                                            <i class="layui-icon">&#xe640;</i></a>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <input type="checkbox" name="" lay-skin="primary"></td>
                                    <td>2017009171822298053</td>
                                    <td>老王:18925139194</td>
                                    <td>7829.10</td>
                                    <td>7854.10</td>
                                    <td>待确认</td>
                                    <td>未支付</td>
                                    <td>未发货</td>
                                    <td>其他方式</td>
                                    <td>申通物流</td>
                                    <td>2017-08-17 18:22</td>
                                    <td class="td-manage">
                                        <a title="查看" onclick="xadmin.open('编辑','order-view.html')" href="javascript:;">
                                            <i class="layui-icon">&#xe63c;</i></a>
                                        <a title="删除" onclick="member_del(this,'要删除的id')" href="javascript:;">
                                            <i class="layui-icon">&#xe640;</i></a>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="layui-card-body ">
                        <div class="page">
                            <div>
                                <a class="prev" href="">&lt;&lt;</a>
                                <a class="num" href="">1</a>
                                <span class="current">2</span>
                                <a class="num" href="">3</a>
                                <a class="num" href="">489</a>
                                <a class="next" href="">&gt;&gt;</a></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
<script>layui.use(['laydate', 'form'],
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
    });

    /*用户-停用*/
    function member_stop(obj, id) {
        layer.confirm('确认要停用吗？',
        function(index) {

            if ($(obj).attr('title') == '启用') {

                //发异步把用户状态进行更改
                $(obj).attr('title', '停用');
                $(obj).find('i').html('&#xe62f;');

                $(obj).parents("tr").find(".td-status").find('span').addClass('layui-btn-disabled').html('已停用');
                layer.msg('已停用!', {
                    icon: 5,
                    time: 1000
                });

            } else {
                $(obj).attr('title', '启用');
                $(obj).find('i').html('&#xe601;');

                $(obj).parents("tr").find(".td-status").find('span').removeClass('layui-btn-disabled').html('已启用');
                layer.msg('已启用!', {
                    icon: 5,
                    time: 1000
                });
            }

        });
    }

    /*用户-删除*/
    function member_del(obj, id) {
        layer.confirm('确认要删除吗？',
        function(index) {
            //发异步删除数据
            $(obj).parents("tr").remove();
            layer.msg('已删除!', {
                icon: 1,
                time: 1000
            });
        });
    }

    function delAll(argument) {

        var data = tableCheck.getData();

        layer.confirm('确认要删除吗？' + data,
        function(index) {
            //捉到所有被选中的，发异步进行删除
            layer.msg('删除成功', {
                icon: 1
            });
            $(".layui-form-checked").not('.header').parents('tr').remove();
        });
    }</script>
<?php include(ROOT_PATH.'/app/View/'.'Admin/Common/baseFooter.php');?>