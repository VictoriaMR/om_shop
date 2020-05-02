$(function() {
    layui.use('form', function() {
        var form = layui.form;
        //监听提交
        form.on('submit(login)', function(data) {
            $.post(ADMIN_URI + 'member/loginIn', data.field, function(res) {
                layer.msg(res.message);
                if (res.code == 200) {
                    setTimeout(function(){location.href = ADMIN_URL}, 300);
                } else {
                    layer.msg(res.message)
                    return false
                }
            })
            return false;
        });
    });
})

