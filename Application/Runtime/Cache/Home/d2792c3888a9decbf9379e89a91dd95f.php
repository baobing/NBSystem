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

<div style="height: 100%;;width: 100%;position: fixed;">
    <table id="dg" title="合同信息列表"
           style="width:95%;height: 90%;"
           toolbar="#toolbar" pagination="true"
           rownumbers="true"
           singleSelect="true" pageSize="<?php echo ($pageSize); ?>" pageList="<?php echo ($pageList); ?>">
        <thead>
        <tr >
            <th field="contract_num" width="100px">合同编号</th>
            <?php if($type == 1): ?><th field="client" width="200px">委托单位</th>
                <th field="inspected" width="200px">受检单位</th><?php endif; ?>
            <th field="name" width="200px">工程名称</th>
            <th field="contact" width="100px">联系人</th>
            <th field="tel" width="100px">联系电话</th>
            <th field="discount" width="70px">折扣(折)</th>
            <th field="amount" width="100px">合同金额</th>
            <th field="c_deadline" width="100px">合同截止日期</th>
            <th field="operator_name" width="100px">操作人员</th>
            <th field="sign_date" width="150px">操作时间</th>
        </tr>
        </thead>
    </table>
</div>
<div id="toolbar">
    <a  class="easyui-linkbutton" iconCls="icon-add" plain="true" onclick="newUnit()">新的合同</a>
    <a  class="easyui-linkbutton" iconCls="icon-edit" plain="true" onclick="editUnit()">修改合同</a>
    <a  class="easyui-linkbutton" iconCls="icon-remove" plain="true" onclick="destroyUnit()">删除合同</a>
</div>

<div id="new_dlg" class="easyui-dialog  " style="width:450px;height:400px;padding:10px 20px"
     closed="true" buttons="#dlg-buttons3">
    <div class="ftitle">基本信息</div>
    <form id="new_fm" method="post" novalidate>
        <div class="fitem">
            <label>合同编号:</label>
            <input name="contract_num" class="easyui-validatebox textbox" required="" >
        </div>
        <?php if($type == 1): ?><div class="fitem">
                <label>委托单位:</label>
                <select id="client_id" name="company_id" class="easyui-combobox" required=""  style="width: 255px;">
                    <?php if(is_array($company)): $i = 0; $__LIST__ = $company;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><option value="<?php echo ($vo["id"]); ?>"><?php echo ($vo["company"]); echo ($vo["id"]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
                </select>
            </div>
            <div class="fitem">
                <label>受检单位:</label>
                <select id="inspected_id" name="inspected_id" class="easyui-combobox" required=""  style="width: 255px;">
                    <?php if(is_array($company)): $i = 0; $__LIST__ = $company;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><option value="<?php echo ($vo["id"]); ?>"><?php echo ($vo["company"]); echo ($vo["id"]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
                </select>
            </div><?php endif; ?>
        <div class="fitem">
            <label>工程名称:</label>
            <select id="project_id" name="project_id" class="easyui-combobox" required=""  style="width: 255px;">
                <?php if(is_array($project)): $i = 0; $__LIST__ = $project;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><option value="<?php echo ($vo["id"]); ?>"><?php echo ($vo["name"]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
            </select>
        </div>
        <div class="fitem">
            <label>联系人:</label>
            <input name="contact" class="textbox" required="" >
        </div>
        <div class="fitem">
            <label>联系电话:</label>
            <input name="tel" class="textbox" required="" >
        </div>
        <?php if($type == 1): ?><div class="fitem">
                <label>合同金额:</label>
                <input id="amount"name="amount" class="textbox" required=""  >
            </div><?php endif; ?>
        <div class="fitem">
            <label>折扣(折):</label>
            <input id="discount"name="discount" class="textbox" required=""  >
        </div>
        <div class="fitem">
            <label>合同截止日期:</label>
            <input id="c_deadline"name="c_deadline" class="easyui-datebox"  >
        </div>
    </form>
</div>
<div id="dlg-buttons3">
    <a href="javascript:void(0)" class="easyui-linkbutton c6" iconCls="icon-ok" onclick="saveUnit()" style="width:90px">保存</a>
    <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-cancel" onclick="javascript:$('#new_dlg').dialog('close')"
       style="width:90px">取消</a>
</div>

<script type="text/javascript">
    $(document).ready(function(){
        var  url = '/index.php/Home/System/getContractList/type/<?php echo ($type); ?>';
        $('#dg').datagrid({
            url:url,
            onDblClickRow: function(index,row){
                editUnit();
            }
        });
    });
    var url;
    function newUnit(){
        $('#new_dlg').dialog('open').dialog('setTitle','添加信息');
        $('#new_fm').form('clear');
        $("#discount").val(10);
        url = '/index.php/Home/System/submitContract/type/<?php echo ($type); ?>';
    }
    function editUnit(){
        var row = isSelected("#dg");
        if(row==null) return ;
        if (row){
            $('#new_dlg').dialog('open').dialog('setTitle','修改信息');
            $('#new_fm').form('load',row);
            $("#client_id").combobox("setValue",row.client_id);
            $("#inspected_id").combobox("setValue",row.inspected_id);
            $("#project_id").combobox("setValue",row.project_id);
            url = '/index.php/Home/System/submitContract/type/<?php echo ($type); ?>/id/'+row.id;
        }
    }
    //保存确认
    function saveUnit(){
        if('/index.php/Home/System/submitContract'==url){
            $.messager.confirm("操作提示","确认添加新的合同信息么？",function(flg){
                if(flg){
                    saveOperate();
                }
            });
        }
      else{
            saveOperate();
        }
    }
    function saveOperate(){
        var data = serializeObject("#new_fm");
        data["project_name"] = $("#project_id").combobox("getText");
        if("<?php echo ($type); ?>" == "1"){
            data["client_name"] = $("#client_id").combobox("getText");
            data["inspected_name"] = $("#inspected_id").combobox("getText");
        }
        if($("#new_fm").form('validate')){
            $.post(url,data,function(){
                $('#dg').datagrid("reload");
                $('#new_dlg').dialog('close');
            });
        }
    }
    function destroyUnit(){
        var row = $('#dg').datagrid('getSelected');
        if (row){
            $.messager.confirm('操作确认','确定删除?',function(r){
                if (r){
                    url='/index.php/Home/System/deleteContract/type/<?php echo ($type); ?>/id/'+row.id+"/project_id/"+row.project_id;
                    $.post(url, {},function (data, textStatus){
                                $('#dg').datagrid("reload");
                            },
                            'json'
                    );
                }
            });
        }
    }
</script>
<style type="text/css">
    #fm{
        margin:0;
        padding:10px 30px;
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
        width:250px;
    }
</style>


</body>
</html>