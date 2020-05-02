/* 
 *	jQuery文件上传插件,封装UI,上传处理操作采用Baidu WebUploader;
 *	@Author 黑爪爪;
 */
(function( $ ) {

    $.fn.extend({
        /*
         *	上传方法 opt为参数配置;
         *	serverCallBack回调函数 每个文件上传至服务端后,服务端返回参数,无论成功失败都会调用 参数为服务器返回信息;
         */
        diyUpload:function( opt, serverCallBack ) {
            if ( typeof opt != "object" ) {
                alert(tip_param_error);
                return;
            }

            var $fileInput = $(this);
            var $fileInputId = $fileInput.attr('id');

            //组装参数;
            if( opt.url ) {
                opt.server = opt.url;
                delete opt.url;
            }

            if( opt.success ) {
                var successCallBack = opt.success;
                delete opt.success;
            }

            if( opt.error ) {
                var errorCallBack = opt.error;
                delete opt.error;
            }

            //迭代出默认配置
            $.each( getOption( '#'+$fileInputId ),function( key, value ){
                opt[ key ] = opt[ key ] || value;
            });

            if ( opt.buttonText ) {
                opt['pick']['label'] = opt.buttonText;
                delete opt.buttonText;
            }

            var webUploader = getUploader( opt );

            if ( !WebUploader.Uploader.support() ) {
                alert(tip_upload_component);
                return false;
            }

            //绑定文件加入队列事件;
            webUploader.on('fileQueued', function( file ) {
                createBox( $fileInput, file ,webUploader);

            });

            //进度条事件
            webUploader.on('uploadProgress',function( file, percentage  ){
                var $fileBox = $('#fileBox_'+file.id);
                var $diyBar = $fileBox.find('.diyBar');
                $diyBar.show();
                percentage = percentage*100;
                showDiyProgress( percentage.toFixed(2), $diyBar);

            });

            //全部上传结束后触发;
            webUploader.on('uploadFinished', function(){
                $('.diyButton').remove();
            });
            //绑定发送至服务端返回后触发事件;
            webUploader.on('uploadAccept', function( object ,data ){
                if ( serverCallBack ) serverCallBack( data );
            });

            //上传成功后触发事件;
            webUploader.on('uploadSuccess',function( file, response ){
                var $fileBox = $('#fileBox_'+file.id);
                var $diyBar = $fileBox.find('.diyBar');
                $fileBox.removeClass('diyUploadHover');
                $diyBar.fadeOut( 1000 ,function(){
                    $fileBox.children('.diySuccess').show();
                });
                setTimeout(function(){
                    $fileBox.attr('data-imgid',response.content.img_id);
                })
                if ( successCallBack ) {
                    successCallBack( response );
                }
            });

            //上传失败后触发事件;
            webUploader.on('uploadError',function( file, reason ){
                var $fileBox = $('#fileBox_'+file.id);
                var $diyBar = $fileBox.find('.diyBar');
                showDiyProgress( 0, $diyBar , tip_upload_error );
                var err = tip_upload_error + tip_file + file.name + tip_errocode + reason;
                if ( errorCallBack ) {
                    errorCallBack( err );
                }
            });

            //选择文件错误触发事件;
            webUploader.on('error', function( code ) {
                var text = '';
                switch( code ) {
                    case  'F_DUPLICATE' : text = tip_filehaschoose ;
                        break;
                    case  'Q_EXCEED_NUM_LIMIT' : text = tip_fileNumer_exceedslimit ;
                        break;
                    case  'F_EXCEED_SIZE' : text = tip_fileSize_exceedslimit;
                        break;
                    case  'Q_EXCEED_SIZE_LIMIT' : text = tip_allSize_exceedslimit;
                        break;
                    case 'Q_TYPE_DENIED' : text = tip_fileType_error;
                        break;
                    default : text = tip_unknow_error;
                        break;
                }
                alert( text );
            });
             return webUploader;
        }
    });

    //Web Uploader默认配置;
    function getOption(objId) {
        /*
         *	配置文件同webUploader一致,这里只给出默认配置.
         *	具体参照:http://fex.baidu.com/webuploader/doc/index.html
         */
        return {
            //按钮容器;
            pick:{
                id:objId,
                label:""
            },
            //类型限制;
            accept:{
                title:"Images",
                extensions:"gif,jpg,jpeg,bmp,png",
                mimeTypes:"image/*"
            },
            //配置生成缩略图的选项
            thumb:{
                width:1000,
                height:1000,
                // 图片质量，只有type为`image/jpeg`的时候才有效。
                quality:90,
                // 是否允许放大，如果想要生成小图的时候不失真，此选项应该设置为false.
                allowMagnify:false,
                // 是否允许裁剪。
                crop:false,
                // 为空的话则保留原有图片格式。
                // 否则强制转换成指定的类型。
                type:"image/jpeg",
                noCompressIfLarger: false,
                compressSize:0
            },
            //文件上传方式
            method:"POST",
            //服务器地址;
            server:"",
            //是否已二进制的流的方式发送文件，这样整个上传内容php://input都为文件内容
            sendAsBinary:false,
            // 开起分片上传。 thinkphp的上传类测试分片无效,图片丢失;
            chunked:true,
            // 分片大小
            chunkSize:512 * 1024,
            //最大上传的文件数量, 总文件大小,单个文件大小(单位字节);
            fileNumLimit:50,
            fileSizeLimit:500000 * 1024,
            fileSingleSizeLimit:50000 * 1024,
        };
    }

    //实例化Web Uploader
    function getUploader( opt ) {

        return new WebUploader.Uploader( opt );
    }

    //操作进度条;
    function showDiyProgress( progress, $diyBar, text ) {
        if ( progress >= 100 ) {
            progress = progress + '%';
            text = text || tip_upload_completed;
        } else {
            progress = progress + '%';
            text = text || progress;
        }

        var $diyProgress = $diyBar.find('.diyProgress');
        $diyProgress.width( progress ).text( text );

    }

    //取消事件;
    function removeLi ( $li ,file_id ,webUploader) {
        webUploader.removeFile( file_id );
        $li.remove();
    }

    //左移事件;
    function leftLi ($leftli, $li) {
        $li.insertBefore($leftli);
    }

    //右移事件;
    function rightLi ($rightli, $li) {
        $li.insertAfter($rightli);
    }
   
    //创建文件操作div;
    function createBox( $fileInput, file, webUploader ) {
        var file_id = file.id,
            $parentFileBox = $fileInput.parents(".upload-ul"),
            file_len=$parentFileBox.children(".diyUploadHover").length,
            imgUpArr = [];
        if ( $parentFileBox.length <= 0 ) {
            
            var div = '<div class="parentFileBox"> \
                        <ul class="fileBoxUl"></ul>\
                    </div>';
            $fileInput.after( div );
            $parentFileBox = $fileInput.next('.parentFileBox');
        
        }
        
        //创建按钮
        if ( $parentFileBox.find('.diyButton').length <= 0 ) {
            
            var div = '<div class="diyButton"> \
                        <a class="diyStart" href="javascript:void(0)" style="display:none;">开始上传</a> \
                    </div>';
            $parentFileBox.append( div );
            var $startButton = $parentFileBox.find('.diyStart');
            //开始上传,暂停上传,重新上传事件;
            var uploadStart = function (){
                var imgArr = [];
                imgUpArr.forEach(function(el,i){
                    imgArr.push(el)
                })
                webUploader.options.formData.image= imgArr
                webUploader.upload();
            }
            //绑定开始上传按钮;
            $startButton.one('click',uploadStart);
           
        }
        //添加子容器;
        var li = '<li id="fileBox_'+file_id+'" class="diyUploadHover list" data-imgid="0"> \
					<div class="viewThumb">\
					    <div class="diyBar"> \
							<div class="diyProgress">100%</div> \
					    </div> \
					    <p class="diyControl"><span class="diyLeft"><i></i></span><span class="diyCancel"><i></i></span><span class="diyRight"><i></i></span></p>\
					</div> \
				</li>';
                // <input type="text" value="" size="15" name="old_img_desc['+ file_id +']" placeholder="'+ lab_thumb_url + '" /><span class="fill-img">'+ Preview +'</span>
        $parentFileBox.find('ul').append(li);

        var $fileBox = $parentFileBox.find('#fileBox_'+file_id);

        //绑定左移事件;
        $fileBox.find('.diyLeft').on('click',function(){
            leftLi($(this).parents('.list').prev(), $(this).parents('.list'));
        });

        //绑定右移事件;
        $fileBox.find('.diyRight').on('click',function(){
            rightLi($(this).parents('.list').next(), $(this).parents('.list') );
        });
        //绑定取消事件;
        $('.diyCancel').on('click',function(){
            removeLi( $(this).parents('li'), file_id, webUploader ); 
        })

        if ( file.type.split("/")[0] != 'image' ) {
            var liClassName = getFileTypeClassName( file.name.split(".").pop() );
            $fileBox.addClass(liClassName);
            return;
        }
        //绑定取消事件;
        // var $diyCancel = $fileBox.children('.diyCancel').on('click',function(){
        //     alert(11)
        //     removeLi( $(this).parent('li'), file_id, webUploader ); 
        // });
        //生成预览缩略图;
        webUploader.makeThumb( file, function( error, dataSrc ) {
            if ( !error ) {
                $fileBox.find('.viewThumb').append('<img src="'+dataSrc+'" >');
                // $fileBox.find('.viewThumb > input').val(dataSrc);
                imgUpArr.push(dataSrc)
                $('.diyButton .diyStart').click();
            }
        });
    }

    //获取文件类型;
    function getFileTypeClassName ( type ) {
        var fileType = {};
        var suffix = '_diy_bg';
        fileType['pdf'] = 'pdf';
        fileType['ppt'] = 'ppt';
        fileType['doc'] = 'doc';
        fileType['docx'] = 'doc';
        fileType['jpg'] = 'jpg';
        fileType['zip'] = 'zip';
        fileType['rar'] = 'rar';
        fileType['xls'] = 'xls';
        fileType['xlsx'] = 'xls';
        fileType['txt'] = 'txt';
        fileType = fileType[type] || 'ppt';
        return 	fileType+suffix;
    }
})( jQuery );