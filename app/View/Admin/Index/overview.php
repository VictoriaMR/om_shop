<?php include(ROOT_PATH.'/app/View/'.'Admin/Common/baseHeader.php');?>
<body>
    <div class="layui-fluid">
        <div class="layui-row layui-col-space15">
            <div class="layui-col-md12">
                <div class="layui-card">
                    <div class="layui-card-body ">
                        <blockquote class="layui-elem-quote">欢迎管理员：
                            <span class="x-red"><?php echo $_SESSION['admin_user']['name'] ?? '';?></span>！
                        </blockquote>
                    </div>
                </div>
            </div>
            <div class="layui-col-md12">
                <div class="layui-card">
                    <div class="layui-card-header">数据统计</div>
                    <div class="layui-card-body ">
                        <ul class="layui-row layui-col-space10 layui-this x-admin-carousel x-admin-backlog">
                            <li class="layui-col-md2 layui-col-xs6">
                                <a href="javascript:;" class="x-admin-backlog-body">
                                    <p class="font-size-15">注册会员数</p>
                                    <p class="height-30">
                                        <cite><?php echo $member_count ?? '';?></cite>
                                    </p>
                                </a>
                            </li>
                            <li class="layui-col-md2 layui-col-xs6">
                                <a href="javascript:;" class="x-admin-backlog-body">
                                    <p class="font-size-15">商品数</p>
                                    <p class="height-30">
                                        <cite><?php echo $product_count ?? '';?></cite>
                                    </p>
                                </a>
                            </li>
                            <li class="layui-col-md2 layui-col-xs6 ">
                                <a href="javascript:;" class="x-admin-backlog-body">
                                    <p class="font-size-15">订单数</p>
                                    <p class="height-30">
                                        <cite><?php echo $order_count ?? '';?></cite>
                                    </p>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="layui-col-sm6 layui-col-md3">
                <div class="layui-card">
                    <div class="layui-card-header">下载
                        <span class="layui-badge layui-bg-cyan layuiadmin-badge">月</span></div>
                    <div class="layui-card-body  ">
                        <p class="layuiadmin-big-font">33,555</p>
                        <p>新下载
                            <span class="layuiadmin-span-color">10%
                                <i class="layui-inline layui-icon layui-icon-face-smile-b"></i></span>
                        </p>
                    </div>
                </div>
            </div>
            <div class="layui-col-sm6 layui-col-md3">
                <div class="layui-card">
                    <div class="layui-card-header">下载
                        <span class="layui-badge layui-bg-cyan layuiadmin-badge">月</span></div>
                    <div class="layui-card-body ">
                        <p class="layuiadmin-big-font">33,555</p>
                        <p>新下载
                            <span class="layuiadmin-span-color">10%
                                <i class="layui-inline layui-icon layui-icon-face-smile-b"></i></span>
                        </p>
                    </div>
                </div>
            </div>
            <div class="layui-col-sm6 layui-col-md3">
                <div class="layui-card">
                    <div class="layui-card-header">下载
                        <span class="layui-badge layui-bg-cyan layuiadmin-badge">月</span></div>
                    <div class="layui-card-body ">
                        <p class="layuiadmin-big-font">33,555</p>
                        <p>新下载
                            <span class="layuiadmin-span-color">10%
                                <i class="layui-inline layui-icon layui-icon-face-smile-b"></i></span>
                        </p>
                    </div>
                </div>
            </div>
            <div class="layui-col-sm6 layui-col-md3">
                <div class="layui-card">
                    <div class="layui-card-header">下载
                        <span class="layui-badge layui-bg-cyan layuiadmin-badge">月</span></div>
                    <div class="layui-card-body ">
                        <p class="layuiadmin-big-font">33,555</p>
                        <p>新下载
                            <span class="layuiadmin-span-color">10%
                                <i class="layui-inline layui-icon layui-icon-face-smile-b"></i></span>
                        </p>
                    </div>
                </div>
            </div>
            <div class="layui-col-md12">
                <div class="layui-card">
                    <div class="layui-card-header">系统信息</div>
                    <div class="layui-card-body ">
                        <table class="layui-table">
                            <tbody>
                                <tr>
                                    <th>平台版本</th>
                                    <td>1.0</td></tr>
                                <tr>
                                    <th>服务器地址</th>
                                    <td><?php echo $server_addr;?></td></tr>
                                <tr>
                                    <th>操作系统</th>
                                    <td><?php echo $system_os;?></td></tr>
                                <tr>
                                    <th>服务器版本</th>
                                    <td><?php echo $server_software;?></td></tr>
                                <tr>
                                    <th>最大内存限制</th>
                                    <td><?php echo $memory_limit;?></td></tr>
                                <tr>
                                    <th>处理器</th>
                                    <td><?php echo $processor_identifier;?></td></tr>
                                <tr>
                                    <th>PHP版本</th>
                                    <td><?php echo $php_version;?></td></tr>
                                <tr>
                                    <th>PHP运行方式</th>
                                    <td><?php echo $php_sapi_name;?></td></tr>
                                <tr>
                                    <th>MySQL版本</th>
                                    <td><?php echo $mysql_version;?></td></tr>
                                <tr>
                                    <th>Frame Version</th>
                                    <td><?php echo $app_version;?></td></tr>
                                <tr>
                                    <th>上传附件限制</th>
                                    <td><?php echo $upload_max_filesize;?></td></tr>
                                <tr>
                                    <th>执行时间限制</th>
                                    <td><?php echo $max_execution_time;?></td></tr>
                                <tr>
                                    <th>剩余空间</th>
                                    <td><?php echo $disk_free_space;?></td></tr>
                                <tr>
                                    <th>已使用磁盘比</th>
                                    <td><?php echo $disk_used_rate;?></td></tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
</body>
<?php include(ROOT_PATH.'/app/View/'.'Admin/Common/baseFooter.php');?>