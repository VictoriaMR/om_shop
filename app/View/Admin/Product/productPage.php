<?php include(ROOT_PATH.'/app/View/'.'Admin/Common/baseHeader.php');?> 
<body>
	<div class="x-nav">
      <span class="layui-breadcrumb">
        <a href="">首页</a>
        <a href='<?php echo getenv('APP_DOMAIN');?>/admin/product/productList'>商品管理</a>
        <a>
          <cite><?php if (!empty($info['pro_id'])){ echo '编辑商品'; } else { echo '新增商品';} ?></cite></a>
      </span>
    </div>
    <div style="padding-top: 10px;"></div>
	<div class="layui-card">
		<div class="layui-fluid">
			<form id="addForm" class="layui-form" action="" style="width: 800px;">
				<div class="layui-form-item">
					<label class="layui-form-label">商品名称</label>
					<div class="layui-input-block">
						<input type="text" name="pro_name" required lay-verify="required" placeholder="xxx" autocomplete="off" class="layui-input" value="<?php echo $info['pro_name'] ?? '';?>">
						<?php if (!empty($info['pro_id'])) { ?>
							<input type="text" name="pro_id" class="hidden" value="<?php echo $info['pro_id'];?>">
						<?php } ?>
					</div>
				</div>
				<div class="layui-form-item product_img_box">
					<label class="layui-form-label">商品图片</label>
					<?php if (!empty($info['pro_imgs'])) {?>
					<?php foreach ($info['pro_imgs'] as $pk => $pv) { ?>
					<div class="layui-upload-drag product_img" id="goodsPic<?php echo $pk+1;?>" style="padding: 0px; line-height: 112px;">
						<img src="<?php echo $pv['url'];?>">
						<input class="hidden" name="pro_img[]" value="<?php echo $pv['attach_id'];?>">
					</div>
					<?php } ?>
					<?php if (count($info['pro_imgs']) < 5) { ?>
					<div class="layui-upload-drag product_img" id="goodsPic<?php echo count($info['pro_imgs'])+1;?>">
						<i class="layui-icon"></i>
			  			<p>点击上传，或将文件拖拽到此处</p>
					</div>
					<?php } ?>
					<?php } else { ?> 
					<div class="layui-upload-drag product_img" id="goodsPic1">
						<i class="layui-icon"></i>
			  			<p>点击上传，或将文件拖拽到此处</p>
					</div>
					<?php } ?>
				</div>
				<div class="layui-form-item">
					<label class="layui-form-label">原价价格</label>
					<div class="layui-input-block">
						<input type="text" name="original_price" required lay-verify="required|number" autocomplete="off" class="layui-input" value="<?php echo $info['origin_price'] ?? '';?>">
					</div>
				</div>
				<div class="layui-form-item">
					<label class="layui-form-label">售卖价格</label>
					<div class="layui-input-block">
						<input type="text" name="sale_price" required lay-verify="required|number" autocomplete="off" class="layui-input" value="<?php echo $info['sale_price'] ?? '';?>">
					</div>
				</div>
				<div class="layui-form-item">
					<label class="layui-form-label">库存</label>
					<div class="layui-input-block">
						<input type="text" name="pro_stock" required lay-verify="required|number" autocomplete="off" class="layui-input" value="<?php echo $info['pro_stock'] ?? '';?>">
					</div>
				</div>
				<div class="layui-form-item">
					<label class="layui-form-label">参数</label>
					<div class="layui-input-block">
						<div class="params_boxs">
							<?php if (!empty($info['pro_param'])) {?>
							<?php foreach ($info['pro_param'] as $pk => $pv) { ?>
								<input type="text" name="param[]" autocomplete="off" placeholder="参数名:xxx" class="layui-input" value="<?php echo $pk.':'.$pv;?>">
							<?php } ?>
							<?php } else { ?> 
							<input type="text" name="param[]" autocomplete="off" placeholder="参数名:xxx" class="layui-input">
							<?php } ?>
						</div>
						<div class="clear"></div>
						<div class="layui-btn" onClick="add_param();">新增</div>
					</div>
				</div>
				<div class="layui-form-item">
					<label class="layui-form-label">详情</label>
					<div class="layui-input-block">
						<script id="editor" type="text/plain" name="detail" style="width:1024px;height:500px;"><?php echo $info['pro_intro'];?></script>
					</div>
				</div>
				<div class="layui-form-item">
					<label class="layui-form-label">分类</label>
	                <div class="layui-input-inline">
	                    <select id="cate_parent" lay-filter="cate_parent">
	                    	<option value="">请选择</option>
	                        <?php if (!empty($cate_list)) { ?>
	                        	<?php foreach ($cate_list as $key => $value) { ?>
	                        		<option value="<?php echo $value['cate_id'];?>" <?php if ($info['cate_parent_id'] == $value['cate_id']) { echo 'selected'; }?>><?php echo $value['name'];?></option>
	                        	<?php } ?>
	                       	<?php } ?>
	                    </select>
	                </div>
	                <div class="layui-input-inline">
	                    <select name="cate_id" id="cate_son" lay-filter="cate_son">
	                    	<?php if (!empty($info['cate_id'])) { ?>
                    		<option value="<?php echo $info['cate_id'];?>"><?php echo $info['cate_name'];?></option>
                    		<?php } else { ?>
                    		<option value="">请选择</option>
                    		<?php } ?>
	                    </select>
	                </div>
				</div>
				<div class="layui-form-item">
					<label class="layui-form-label">状态</label>
					<div class="layui-input-block">
						<input type="radio" name="status" value="1" title="启用" <?php echo $info['status'] == 0 || $info['status'] === null ? 'checked' : '';?>>
						<input type="radio" name="status" value="0" title="禁用" <?php echo $info['status'] === 0 ? 'checked' : '';?>>
					</div>
				</div>
				
				<div class="layui-form-item">
					<div class="layui-input-block">
						<button class="layui-btn" lay-submit lay-filter="submitBut">立即提交</button>
						<button type="reset" class="layui-btn layui-btn-primary">重置</button>
					</div>
				</div>
			</form>
		</div>
	</div>
</body>
<script>
	UE.getEditor('editor');

	var cate_json = <?php echo json_encode($cate_list);?>

	layui.use(['upload','form'], function() {
		var form = layui.form;
		var upload = layui.upload;
		var layer = layui.layer;

		//分类下拉更新
		form.on('select(cate_parent)', function(data){
			var html = '<option value="">请选择</option>';

			for (var i in cate_json) {
				if (cate_json[i]['cate_id'] == data.value) {
					for (var j in cate_json[i]['son']) {
						html += '<option value = "' + cate_json[i]['son'][j]['cate_id'] + '">' + cate_json[i]['son'][j]['name'] + '</option>'
					}
				}
			}

			$('#cate_son').html(html);
			form.render('select');
		});

		//监听提交
		form.on('submit(submitBut)', function(data) {
			// console.log(data.field);return false;
			$.post( ADMIN_URI + 'product/save', data.field, function(res){
                    
	            if (res.code == 200) {
	                layer.alert(res.message, {
	                    icon: 6
	                },
	                function() {
	                    location.href=ADMIN_URL+'product/productList'
	                });
	            } else {
	                layer.alert(res.message);
	            }
	        })
	        return false;
		});
		form.verify({
			//数组的两个值分别代表：[正则匹配、匹配不符时的提示文字]
		  	ZHCheck: [
			    /^[\u0391-\uFFE5]+$/
			    ,'只允许输入中文'
		  	] 
		});

		APP.init(upload)

	});

	function add_param()
	{
		$('.params_boxs').append('<input type="text" name="param[]" autocomplete="off" placeholder="参数名:xxx" class="layui-input">');
	}
</script>

<?php include(ROOT_PATH.'/app/View/'.'Admin/Common/baseFooter.php');?>