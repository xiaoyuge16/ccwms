<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
    <head>
    <meta charset="utf-8">
<title><?php echo ($config['webInfo_web_title']); ?>后台管理</title>
<meta name="renderer" content="webkit">
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
<meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=0">
<link rel="stylesheet" href="/Public/plugins/layuiadmin/layui/css/layui.css" media="all">
<link rel="stylesheet" href="/Public/plugins/layuiadmin/style/admin.css" media="all">
<link rel="stylesheet" href="/Public/plugins/font-awesome/css/font-awesome.min.css" media="all">
<link rel="stylesheet" href="/Public/css/page.css">
<script type="text/javascript" src="/Public/js/jquery-1.8.3.min.js"></script>
</head>
<body>
    <div class="admin-main">
        <fieldset class="layui-elem-field">
            <div class="test-table-reload-btn" style="margin-top: 10px;margin-left:15px;">
                <form action="<?php echo U('Excel/exportUserActionLog');?>" class='layui-form' id="search-form2">
                    <div class="layui-inline">
                        <input type="text" name="account" placeholder="会员帐号" class="layui-input">
                    </div>
                    <div class="layui-inline">
                        <input type="text" name="note" placeholder="关键词" class="layui-input" />
                    </div>
                    <div class="layui-inline">
                        <input type="text" name="log_ip" placeholder="IP地址" class="layui-input" />
                    </div>
                    <div class="layui-inline" style='width:100px;'>
                        <select name="is_type">
                            <option value="">--类型--</option>
                            <option value="1">登录</option>
                            <option value="2">激活</option>
                        </select>
                    </div>
                    <div class="layui-inline">
                        <input type="text" name="time" placeholder="变动时间" id="time" class="layui-input" />
                    </div>
                    <input type="hidden" name="order_by" value="id">
                    <input type="hidden" name="sort" value="desc">
                    <button class="layui-btn" type="button" onclick="ajax_get_table('search-form2', 1);">搜索</button>
                    <button type="submit" class="layui-btn">导出</button>
                </form>
            </div>
            <div class="layui-field-box"><div id="ajax_return"></div></div>
        </fieldset>
    </div>
<script src="/Public/plugins/layuiadmin/layui/layui.js"></script>
<script>
    layui.config({
    base: '/Public/plugins/layuiadmin/' //静态资源所在路径
}).extend({
    index: 'lib/index' //主入口模块
}).use('index');
</script>
<script type="text/javascript" src="/Public/js/zfuwlAjax.js"></script>

<script>
    layui.use(['laydate', 'layer', 'form'], function () {
        var $ = layui.jquery, laydate = layui.laydate;
        $(document).ready(function () {
            ajax_get_table('search-form2', 1);
        });
        laydate.render({
            elem: '#time'
            , range: true,
            format: 'yyyy/MM/dd'
        });
    });
    function ajax_get_table(tab, page) {
        var loadVal = layer.load(3);
        cur_page = page;
        $.ajax({
            type: "POST",
            url: "<?php echo U('User/actionIndex');?>?p=" + page,
            data: $('#' + tab).serialize(),
            success: function (data) {
                if (data.status == 0) {
                    layer.msg(data.msg, {icon: 5});
                    return;
                }
                layer.close(loadVal);
                $("#ajax_return").html(data);
            }
        });
    }
    function emptyUserActionLog(){
        layer.confirm("是否确认清空数据，执行之后将不能撤销", {title:'温馨提示', icon:3}, function(){
            var getMoreLoad = layer.msg('数据清空中...',{shade:0.3, icon:16, time:0});
            var url = "<?php echo U('User/emptyActionLog');?>";
            $.post(url, function (data) {
                layer.close(getMoreLoad);
                if (data.status == 0) {
                    ajax_get_table('search-form2', cur_page);
                    layer.msg(data.msg, {icon: 5});
                    return;
                } else if(data.status == 1) {
                    var page = $('.pagination .active').find('a').data('p');
                    ajax_get_table('search-form2', page);
                    layer.msg(data.msg, {icon:6});
                }
            });
        });
    }
    function sort(field) {
        $("input[name='order_by']").val(field);
        var v = $("input[name='sort']").val() == 'desc' ? 'asc' : 'desc';
        $("input[name='sort']").val(v);
        ajax_get_table('search-form2', cur_page);
    }
</script>
</body>

</html>