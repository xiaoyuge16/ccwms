<?php if (!defined('THINK_PATH')) exit();?><table class="layui-table layui-form">
    <thead>
        <tr>
            <th style="width: 5px;"><input type="checkbox" lay-filter="allChoose"lay-skin="primary" /></th>
            <th>会员账号</th>
            <th>变动时间</th>
            <th>操作设备</th>
            <th>IP地址</th>
            <th>地区</th>
            <th>备注</th>
        </tr>
    </thead>
    <tbody>
    <?php if(count($list) == 0): ?><tr align="center">
            <td colspan="20">暂无数据</td>
        </tr>
        <?php else: ?>
        <?php if(is_array($list)): foreach($list as $k=>$v): ?><tr>
                <td><input type="checkbox" name="selected[]" value="<?php echo ($v['id']); ?>" lay-skin="primary"></td>
                <td><?php echo ($userList[$v[uid]]); ?></td>
                <td><?php echo (date('Y-m-d H:i',$v["zf_time"])); ?></td>
                <td><?php echo ($v[equipment]); ?></td>
                <td><?php echo ($v[log_ip]); ?></td>
                <td><a data-ip="<?php echo ($v['log_ip']); ?>" class="layui-btn layui-btn-radius layui-btn-normal checkAddressByIp <?php echo ($v['log_ip']); ?>">查看</a></td>
                <td><?php echo ($v[note]); ?></td>
            </tr><?php endforeach; endif; endif; ?>
</tbody>
</table>
<?php echo ($page); ?>
<script>
    layui.use(['form'], function () {
        var laypage = layui.laypage, $ = layui.jquery,form=layui.form;
        form.render('checkbox'); //刷新checkbox渲染
        form.on('checkbox(allChoose)', function (data) {
            var child = $(data.elem).parents('table').find('tbody input[type="checkbox"]');
            child.each(function (index, item) {
                item.checked = data.elem.checked;
            });
            form.render('checkbox');
        });
    });
    $('.checkAddressByIp').on('click', function(){
        var obj = $(this);
        var ip = $(this).data('ip');
        var tipsIndex = layer.tips('加载中...', obj, {time:0,tips:[4]});
        $.post('<?php echo U("User/checkAddressByIp");?>', {ip:ip}, function(data){
            layer.close(tipsIndex);
            layer.tips(data.msg, obj, {time:0,tips:[4], id:ip, closeBtn:true});
        });
    });
    $(".pagination a").click(function () {
        var page = $(this).data('p');
        ajax_get_table('search-form2', page);
    });
    $('.del').click(function () {
        var url = "<?php echo U('User/delAction');?>";
        var id = $(this).attr('data');
        if (!id) {
            var obj = $("input[name*='selected']");
            if (obj.is(":checked")) {
                var check_val = [];
                for (var k in obj) {
                    if (obj[k].checked)
                        check_val.push(obj[k].value);
                }
                id = check_val;
            }
        }
        if (!id) {
            return false;
        }
        layer.confirm('确定删除吗?', {icon: 3, skin: 'layer-ext-moon', btn: ['确认', '取消']}, function () {
            $.post(url, {id: id}, function (data) {
                if (data.status == 0) {
                    layer.msg(data.msg, {icon: 5});
                } else if (data.status == 1) {
                    layer.msg(data.msg, {icon: 6, time: 2000}, function () {
                        var page = $('.pagination .active').find('a').data('p');
                        ajax_get_table('search-form2', page);
                    });
                }
            });
        });
    });
</script>