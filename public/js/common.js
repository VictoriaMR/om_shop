
var APP = {
	init: function(upload) {
		$('.product_img_box .product_img').each(function (){

			var imgId = $(this).attr('id');
			console.log(imgId)
			//拖拽上传
			upload.render({
				elem: '#'+imgId,
				url: API_URI + 'common/upload',
				data: {cate:'product'},
				before: function(obj){
			    //预读本地文件示例，不支持ie8
			    obj.preview(function(index, file, result){		//在当前ID为“demo2”的区域显示图片
			      $('#demo2').append('<img name = "s_pmt_dw" style="width: 120px; height: 150px; margin-left: 16px;" src="'+ result +'" alt="'+ file.name +'" class="layui-upload-img">')
			    });
			   },
				done: function(res) {
				  	if (res.code == 200) {
				  		var html = '<img src="'+res.data.file_url+'"/><input class="hidden" name="pro_img[]" value="'+res.data.attach_id+'"/>'
				  		$('#'+imgId).html(html).css({padding: 0, 'line-height': '112px'});
				  		var len = $('.product_img_box .product_img').length
				  		if (len < 5 && $('.product_img_box .layui-icon').length == 0) {
				  			var appendHtml = '<div class="layui-upload-drag product_img" id="goodsPic'+(len + 1)+'"> <i class="layui-icon"></i> <p>点击上传，或将文件拖拽到此处</p> </div>'
				  			$('.product_img_box').append(appendHtml);
				  			APP.init(upload)
				  		}
				  	} else {
				  		layer.msg(res.message)
				  	}
				}
			});
		});
	}
}

//获取参数值
function getQueryVariable(variable)
{
   var query = window.location.search.substring(1);
   var vars = query.split("&");
   for (var i=0;i<vars.length;i++) {
           var pair = vars[i].split("=");
           if(pair[0] == variable){return pair[1];}
   }
   return(false);
}

function replacrQueryParma(obj)
{
	var query = window.location.search.substring(1);
	var vars = query.split("&");

	for (var i in obj) {
		var inArr = false
		for (var j=0 ;j < vars.length; j++) {
		   	var pair = vars[j].split("=");
		   	if(pair[0] == obj[i]) {
		   		vars[j] = i + '=' + obj[i]
		   		inArr = true
		   	}
		}

		if (!inArr) {
			vars.push(i + '=' + obj[i])
		}
	}
	var parmaString = '?'
	for (var i in vars) {
		if (vars[i] != '') {
			parmaString += vars[i]
			if (i < vars.length - 1)
				parmaString += '&'
		}
	}

	return parmaString
}

function logout()
{
    $.post(ADMIN_URI + 'member/loginOut', {}, function(res) {
    	if (res.code == 200) {
    		layer.msg(res.message)
	        setTimeout(function(){location.href = ADMIN_URL}, 300);
    	} else {
    		layer.msg(res.message)
    	}
    })
}