<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head lang="en">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />


    <title><?php echo ($company); ?></title>
    <link rel="stylesheet" type="text/css" href="/NBSystem/Public/css/default.css">
    <link rel="stylesheet" type="text/css" href="/NBSystem/Public/easyui/themes/icon.css">
    <link rel="stylesheet" type="text/css" href="/NBSystem/Public/easyui/themes/bootstrap/easyui.css">
    <link rel="stylesheet" type="text/css" href="/NBSystem/Public/easyui/themes/color.css">
    <link rel="stylesheet" type="text/css" href="/NBSystem/Public/easyui/demo/demo.css">


    <script type="text/javascript" src="/NBSystem/Public/js/jquery-1.8.0.min.js"></script>
    <script type="text/javascript" src="/NBSystem/Public/js/jgxLoader.js"></script>
    <script type="text/javascript" src="/NBSystem/Public/easyui/jquery.easyui.min.js"></script>
    <script type="text/javascript" src="/NBSystem/Public/easyui/locale/easyui-lang-zh_CN.js"></script>
    <script type="text/javascript" src="/NBSystem/Public/easyui/datagrid-detailview.js"></script>
    <script type="text/javascript" src="/NBSystem/Public/js/jquery.cookie.js"></script>
    <script type="text/javascript" src="/NBSystem/Public/js/base.js"></script>

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

<div style="height: 500px;width: 100%;">
    <table id="dg" title="工程协议列表"
           style="width:100%;min-height: 100%;;float: left;"
           toolbar="#toolbar"
           rownumbers="true" fitColumns="true"
           singleSelect="true" >
        <thead>
        <tr >
                <th field="account" width="100">账户</th>
                <th field="name" width="100">姓名</th>
                <th field="department" width="100">部门</th>
                <th field="minister_seq" width="100">排序</th>
        </tr>
        </thead>
    </table>
</div>
<div id="toolbar">
    <a  class="easyui-linkbutton" iconCls="icon-add" plain="true" onclick="editUnit()">修改排序</a>

</div>

<div id="new_dlg" class="easyui-dialog  " style="width:450px;height:300px;padding:10px 20px"
     closed="true" buttons="#dlg-buttons3">
    <div class="ftitle">基本信息</div>
    <form id="new_fm" method="post" novalidate>
        <div class="fitem">
            <label>请输入序号:</label>
            <input name="minister_seq" class="textbox" required="" >
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
        $('#dg').datagrid({
            url:'/NBSystem/index.php/Home/System/getMinisterInfo',
            onDblClickRow: function(index,row){
                editUnit();
            }
        });
    });
    var id;
    function editUnit(){
        var row = isSelected("#dg");
        if(row==null) return ;
        if (row){
            $('#new_dlg').dialog('open').dialog('setTitle','修改排序');
            $('#new_fm').form('load',row);
            id = row.id;
        }
    }
    //保存确认
    function saveUnit(){

        var data={};
        data["id"] = id;
        data["minister_seq"] = $("[name='minister_seq']").val();
        $.post("/NBSystem/index.php/Home/System/modifySeq",data,function(data){
            if(data){
                $("#dg").datagrid("reload");
                showSuccess();
                $('#new_dlg').dialog('close')
            }else{
                showFail();
            }
        });


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