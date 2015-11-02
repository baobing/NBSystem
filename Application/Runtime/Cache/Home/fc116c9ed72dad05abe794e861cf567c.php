<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head lang="en">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />


    <title><?php echo ($company); ?></title>
    <link rel="stylesheet" type="text/css" href="/Public/css/default.css">
    <link rel="stylesheet" type="text/css" href="/Public/easyui/themes/icon.css">
    <link rel="stylesheet" type="text/css" href="/Public/easyui/themes/bootstrap/easyui.css">
    <link rel="stylesheet" type="text/css" href="/Public/easyui/themes/color.css">
    <link rel="stylesheet" type="text/css" href="/Public/easyui/demo/demo.css">


    <script type="text/javascript" src="/Public/js/jquery-1.8.0.min.js"></script>
    <script type="text/javascript" src="/Public/js/jgxLoader.js"></script>
    <script type="text/javascript" src="/Public/easyui/jquery.easyui.min.js"></script>
    <script type="text/javascript" src="/Public/easyui/locale/easyui-lang-zh_CN.js"></script>
    <script type="text/javascript" src="/Public/easyui/datagrid-detailview.js"></script>
    <script type="text/javascript" src="/Public/js/jquery.cookie.js"></script>
    <script type="text/javascript" src="/Public/js/base.js"></script>

</head>
<style>

    .panel-outside{
        margin-top: 5px;;
        width:950px;
    }
    .my-btn-right{
        float: right;height: 22px;width: 100px;padding: 5px;
    }
    .my-select{
        width: 150px;border-radius: 3px;height: 22px;border: solid 1px #aaaaaa;
    }
    .my-lable{
        float: left;font-size: 15px;font-size: 600;line-height: 20px;
    }
    #fm{
        margin:0;
        padding:10px 10px;
    }
    .ftitle{
        font-size:14px;
        font-weight:bold;
        padding:5px 0;
        margin-bottom:10px;
        border-bottom:1px solid #ccc;
    }

    .fitem{
        margin-bottom:5px;
    }
    .fitem label{
        display:inline-block;
        width:80px;
    }
    .fitem input{
        width:160px;
    }
    .textbox{
        height: 20px;
        margin: 0px;
        padding: 0px 2px;
        width: 145px;;
        box-sizing: content-box;
    }
    #toolbar tr td:nth-child(even){width: 80px;}
    #toolbar tr td:nth-child(odd){width: 150px;}
    #toolbar tr td input,select{width: 145px}
</style>
<body style="margin: 0px;padding: 15px;" >
<script>
    $(function() {
        var data={};

        if('<?php echo ($type); ?>'=='0'){
             data.url="/index.php/Home/Query/getListSample/step/2/check_cnt/0/permission/3";
            data["singleSelect"]=0;
            data.onDblClickRow=function(){showTest("/index.php/Home/Progress/showTest");};
        }else if('<?php echo ($type); ?>'=='1'){  //待修改
            data.url="/index.php/Home/Query/getListSample/step/2/egt_check_cnt/1/permission/3";
            data.onClickCell=function(rowIndex, field, value){
                if(value==0) return ;
                if(field=='check_cnt'){
                    var rows=$('#s_dg').datagrid('getRows');
                    var row=rows[rowIndex];
                    $.messager.alert("驳回理由",row['refuse_note']);
                }
            }
            data.onDblClickRow=function(){showTest("/index.php/Home/Progress/showTest");};
            data["singleSelect"]=0;
        }else {
            data.url="/index.php/Home/Query/getListSample/tested/1/permission/3";
           // data.onDblClickRow=function(){edit28Report();};
        }
        data["rowStyler"]=function(index,row){
            if (row.priority == 1){
                return 'color:red;'; // return inline style
            }
        }
        $('#s_dg').datagrid(data);
    });


    function finishTest(){
        var rows=$("#s_dg").datagrid("getChecked");
        if(rows==null) {
            $.messager.alert("操作提示","请先选择一行！！");
            return ;
        }
        var ids=[];
        for(var i=0;i<rows.length;i++){
            ids.push(rows[i]["id"]);
        }
        $.post("<?php echo U(checkFile);?>",{ids:ids},function(res) {
            if (res == "-1") {
                $.messager.alert("操作提示", "请先编写检验报告！！");
            }
            else{
                $.messager.confirm("请选择审核人员",'' +
                        '<label for="check_person" style="margin-left: 10px;">请选择审核人员:</label>' +
                        '<select id="check_person" name="check_person"  class="my-select"  multiple="multiple" style="margin-left: 10px;margin-top: 10px;min-height: 180px;">' +
                        '<?php if(is_array($checker)): $k = 0; $__LIST__ = $checker;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($k % 2 );++$k;?>' +
                        '<option value="<?php echo ($vo["id"]); ?>" <?php if($k == 1): ?>selected<?php endif; ?>><?php echo ($vo["name"]); ?></option>' +
                        '<?php endforeach; endif; else: echo "" ;endif; ?>' +
                        '</select>',
                        function(data){
                            if(data){
                                var  checker=$("#check_person").val();
                                $.post("<?php echo U(refreshStep);?>",{ids:ids,step:3,check_person:checker[0]},function(data){
                                    if(data) {
                                        $("#s_dg").datagrid('reload');
                                        showSuccess();
                                    } else {
                                        showFail();
                                    }
                                });
                            }
                        });
            }
        });

    }
    function finalTest(){
        var rows=$("#s_dg").datagrid("getChecked");
        if(rows==null) {
            $.messager.alert("操作提示","请先选择一行！！");
            return ;
        }
        var ids=[];
        for(var i=0;i<rows.length;i++){
            ids.push(rows[i]["id"]);
        }
        $.post("<?php echo U(checkFile);?>",{ids:ids},function(res) {
            if (res == "-1") {
                $.messager.alert("操作提示", "请先编写检验报告！！");
            }
            else{
                $.messager.confirm("操作提示",'确认将报告提交到最终报告？',
                        function(data){
                            if(data){
                                var  checker=$("#check_person").val();
                                $.post("<?php echo U(refreshStep);?>",{ids:ids,step:5,final:1},function(data){
                                    if(data) {
                                        $("#s_dg").datagrid('reload');
                                        showSuccess();
                                    } else {
                                        showFail();
                                    }
                                });
                            }
                        });
            }
            });
    }
    function edit28Report(){

        var row=$("#s_dg").datagrid("getSelected");
        if(row==null) {
            $.messager.alert("操作提示","请先选择一行！！");
            return ;
        }
        var sample_num = row.sample_num;

        if(sample_num.charAt(sample_num.length-1) == "a"){
            $.messager.alert("操作提示","不可以编写28天报告！！");
            return ;
        }

        var iWidth=1200; //弹出窗口的宽度;
        var iLeft = (window.screen.availWidth-10-iWidth)/2; //获得窗口的水平位置;
        var iHeight=window.screen.availHeight-100; //弹出窗口的高度;
        window.open("/index.php/Home/Progress/edit28Report/id/"+row.id, "sample"+row.sample_num,
                "height="+iHeight+",width=1200, top=0, left=20");

    }
    /**
     * 退回报告
     */
    function btnClickBack(){
        var rows=$("#s_dg").datagrid("getChecked");
        if(rows.length==0){
            $.messager.alert("操作提示","请先选择一行！");return ;
        }
        var url = "/index.php/Home/Progress/testBackLeader"
        var post_data = [];
        for(var i in rows){
            var tmp = {id:rows[i].id,sample_num:rows[i].sample_num};
            post_data.push(tmp);
        }
        confirmPost("#s_dg","确认退回么？",url,{data:post_data});
    }
</script>
<div style="height: 85%;;width: 96%;position: fixed;">
    <table id="s_dg" style="width:100%;min-height: 90%;"
           rownumbers="true"  singleSelect="true"pagination="true"
           title="样品列表"toolbar="#toolbar"
           pageSize="<?php echo ($pageSize); ?>" pageList="<?php echo ($pageList); ?>">
        <thead>
        <?php if($type == 0 or ($step == 2 and $type == 1)): ?><th field="id" checkbox="true"></th><?php endif; ?>
        <th field="protocol_num" width="100px;" sortable>协议编号</th>
        <th field="sample_num" width="100px;" sortable>样品编号</th>
        <th field="deadline" width="100px;">截止日期</th>
        <?php if($step >= 2 or $type >= 1): ?><th field="check_cnt" width="100px;" formatter="checkCnt">修改次数</th><?php endif; ?>
        <th field="sample_name"width="100px;" >样品名称</th>
        <th field="test_item"  width="100px;" >检测项目</th>
        <th field="test_basis" width="100px;" >检测依据</th>
        <th field="stake_mark" width="100px;" >现场桩号/结构部位</th>
        <th field="molding_date"width="100px;" >取样/成型日期</th>
        <th field="sample_loca"width="100px;" >取样地点</th>
        <th field="sample_cnt"width="100px;"  >样品数量</th>
        <th field="sample_desc"width="100px;" >样品描述</th>
        <th field="sample_format"width="100px;">规格/牌号</th>
        <th field="producing_area"width="100px;">厂家产地</th>
        <th field="delegate_cnt" width="100px;"  editor="{type:'text'}">代表数量</th>
        <th field="make_num" width="100px;"  editor="{type:'text'}">生产批号</th>
        <th field="note"   width="100px;"  editor="{type:'text'}">附加说明</th>
        <th field="back_type" editor="{type:'text'}">退样方式</th>
        <?php if($step != 1 or $type != 0): ?><th field="tester_name" width="100px;">试验人</th><?php endif; ?>
        </thead>
    </table>
</div>
<script>

</script>
<form id="toolbar">
    <div>
        <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-search" plain="true" onclick="sampleSearchClick()">搜索</a>
        <?php if($step == 1): if($type == 0): ?><a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-edit" plain="true" onclick="btnClickAssign()">分配任务</a>
                <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-reload" plain="true" onclick="btnClickTransfer()">转交任务</a>
                <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-back" plain="true" onclick="btnClickBack()">退回收发室</a><?php endif; ?>
            <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-print" plain="true" onclick="showTask('/index.php/Home/Progress/showTask')">任务书</a><?php endif; ?>
        <?php if($step == 2): if($type != 2): ?><a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-edit" plain="true" onclick="showTest('/index.php/Home/Progress/showTest')">编写检测报告</a>
                <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-ok" plain="true" onclick="finishTest()">完成试验</a>
                <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-remove" plain="true" onclick="finalTest()">最终报告</a>
                <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-print" plain="true" onclick="showTask('/index.php/Home/Progress/showTask')">任务书</a>
                <?php elseif($type == 2): ?>
                <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-edit" plain="true" onclick="edit28Report()">编写28天报告</a><?php endif; ?>
            <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-back" plain="true" onclick="btnClickBack()">退回报告</a><?php endif; ?>
        <?php if($step == 3): if($type == 0): ?><a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-edit" plain="true" onclick="showCheck()">批阅报告</a>
                <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-ok"   plain="true" onclick="finishTest()">通过报告</a>
                <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-no"   plain="true" onclick="refuseTest('<?php echo U('refreshStep');?>')">驳回报告</a><?php endif; endif; ?>
        <?php if($step == 4): if($type == 0): ?><a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-edit" plain="true" onclick="showCheck()">批阅报告</a>
                <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-ok"   plain="true" onclick="finishTest()">通过报告</a>
                <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-no"   plain="true" onclick="refuseTest('<?php echo U('refreshStep');?>')">驳回报告</a><?php endif; endif; ?>

        
    <table style="width: 800px;margin-top: 5px;">
        <tr >
            <td>样品编号：</td>
            <td><input name="sample_num" class="textbox"></td>
            <td>样品名称：</td>
            <td><input name="sample_name" class="textbox"></td>
            <td>检测项目：</td>
            <td><input name="test_item" class="textbox"></td>
        </tr>
        <tr >
            <td>截止日期：</td>
            <td><input name="dead_from" class="easyui-datebox"></td>
            <td>到：</td>
            <td><input name="dead_to" class="easyui-datebox"></td>
            <?php if(($step != 1 or $type != 0) and $step != 2 ): ?><td>试验人：</td>
                <td>
                    <select name="tester" class="my-select">
                        <option value="">全部</option>
                        <?php if(is_array($tester)): $i = 0; $__LIST__ = $tester;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><option value="<?php echo ($vo["id"]); ?>"><?php echo ($vo["name"]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
                    </select>
                </td><?php endif; ?>

        </tr>
    </table>
    </div>
</form>


</body>
</html>