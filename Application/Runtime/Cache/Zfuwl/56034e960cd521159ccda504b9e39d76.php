<?php if (!defined('THINK_PATH')) exit();?><table class="layui-table layui-form">
    <thead>
        <tr>
            <th>会员账号</th>
            <th>变动时间</th>
            <th><a href="javascript:sort('y_id');">变动前</a></th>
            <th><a href="javascript:sort('x_id');">变动后</a></th>
            <th>状态</th>
            <th>备注</th>
            <th>管理员</th>
            <th>操作</th>
        </tr>
    </thead>
    <tbody>
    <?php if(count($list) == 0): ?><tr align="center">
            <td colspan="20">暂无数据</td>
        </tr>
        <?php else: ?>
        <?php if(is_array($list)): foreach($list as $k=>$v): ?><tr>
                <td><?php echo ($userList[$v[uid]]); ?></td>
                <td><?php echo (date('Y-m-d H:i',$v["zf_time"])); ?></td>
                <td><?php echo ($levelInfo[$v[y_id]]); ?></td>
                <td><?php echo ($levelInfo[$v[x_id]]); ?></td>
                <td><?php echo ($upgradeStatu[$v[statu]]); ?> 
                    <?php if($v[refuse] != ''): echo (date('Y-m-d H:i',$v["refuse_time"])); ?><br />
                        <span style="color:red;"><?php echo ($v[refuse]); ?></span><?php endif; ?>
                </td>
                <td><?php echo ($v[note]); ?> 
                    <?php if($v[confirm] != ''): echo (date('Y-m-d H:i',$v["confirm_time"])); ?><br />
                        <span style="color:red;"><?php echo ($v[confirm]); ?></span><?php endif; ?>
                </td>
                <td><?php echo ($adminlist[$v[admin_id]]); ?></td>
                <td>
                    <?php if($v[statu] == 2): ?><a data="<?php echo ($v['id']); ?>" class="layui-btn layui-btn-mini layui-btn-normal confirm"><i class="layui-icon">&#xe618;</i>确认</a>
                    <a data="<?php echo ($v['id']); ?>" class="layui-btn layui-btn-mini layui-btn-warm refuse"><i class="layui-icon">&#xe607;</i>拒绝</a><?php endif; ?>
                    <a data="<?php echo ($v['id']); ?>" class="layui-btn layui-btn-danger layui-btn-mini del"><i class="layui-icon">&#xe640;</i>删除(<?php echo ($v["id"]); ?>)</a>
                </td>           
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
    $(".pagination a").click(function () {
        var page = $(this).data('p');
        ajax_get_table('search-form2', page);
    });
    $('.del').click(function () {
        var url = "<?php echo U('Level/delUpgrade');?>";
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
    $('.confirm').click(function(){
            var id = $(this).attr('data');
            layer.prompt({title:'请输入确认备注',formType: 2}, function(value, index, elem){
                $.ajax({
                    type:'post',
                    data:{id:id, name:value},
                    url:"<?php echo U('Level/confirmInfo');?>",
                    success:function(data){
                        layer.close(index);
                        if (data.status == 0) {
                            layer.msg(data.msg, {icon: 5});
                        } else if (data.status == 1) {
                            layer.msg(data.msg, {icon: 6, time: 2000}, function () {
                                location.reload();
                            });
                        }
                    }
                })
            });
        });
        $('.refuse').click(function(){
            var id = $(this).attr('data');
            layer.prompt({title:'请输入扫绝备注',formType: 2}, function(value, index, elem){
                $.ajax({
                    type:'post',
                    data:{id:id, name:value},
                    url:"<?php echo U('Level/refuseInfo');?>",
                    success:function(data){
                        layer.close(index);
                        if (data.status == 0) {
                            layer.msg(data.msg, {icon: 5});
                        } else if (data.status == 1) {
                            layer.msg(data.msg, {icon: 6, time: 2000}, function () {
                                location.reload();
                            });
                        }
                    }
                })
            });
        });
</script>