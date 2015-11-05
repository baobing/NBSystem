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
<script>
    var rowNums = [];

    $(function(){
        $("#dg").datagrid({url:"/NBSystem/index.php/Home/Query/getSampleList/step/-1"});
        $("#modify_btn").click(function(){modifyBtnClick()});
        $("#save_btn").click(function(){saveBtnClick()});
    });
    function modifyBtnClick(){
        var rows = $("#dg").datagrid("getChecked");
        if(!rows){
            $.messager.alert("操作提示","请先选中一行或多行");
            return ;
        }
        for(var i =0;i < rows-1;i++){
            if(rows[i]["protocol_num"] != rows[i + 1]["protocol_num"]){
                $.messager.alert("操作提示","非同一协议,不可同时修改!");
                return ;
            }
        }
        for(var i =0;i < rows;i++){
            if(1 == rows[i]["is_finance"] ){
                $.messager.alert("操作提示","该协议处于财务审核阶段,不可修改!");
                return ;
            }
        }
        rowNums = [];
        for(var i in rows){
            var rowNum = $("#dg").datagrid("getRowIndex",rows[i]);
            rowNums.push(rowNum);
            $("#dg").datagrid("beginEdit",rowNum);
        }
    }
    function saveBtnClick(){
        var allRow = $("#dg").datagrid("getRows");
        var postRow = [];
        for(var i in rowNums){
            var rowIndex = rowNums[i];
            $("#dg").datagrid("endEdit",rowIndex);
            postRow[i] = allRow[rowIndex];
        }
        //debugger;
        var var_data=[{type:0,key:"back_reason",value:"#back_reason"}];
        confirmPost("#dg","确认保存么？<br/>改动原因：<br/>" +
        "<textarea id='back_reason' style='width: 260px;height: 50px;'>"+postRow[0].back_reason+"</textarea>",
                "/NBSystem/index.php/Home/WangOffice/saveTest",{rows:postRow},null,var_data);
    }
</script>
<div style="height: 100%;;width: 96%;position: fixed;">
    <table id="dg" style="width:100%;min-height:90%;"
           rownumbers="true"  singleSelect="false"
           title="样品列表"pagination="true" toolbar="#toolbar"
           pageSize="<?php echo ($pageSize); ?>" pageList="<?php echo ($pageList); ?>">
        <thead>
        <th field="id" checkbox="true"></th>
        <th width="100px" field ="protocol_num" >协议编号</th>
        <th width="100px" field ="sample_num" >样品编号</th>
        <th width="100px" field ="sample_name" editor="{type:'text'}">样品名称</th>
        <th width="100px" field ="test_item"   editor="{type:'text'}">检测项目</th>
        <th width="300px" field ="test_detail"   editor="{type:'text'}">检测项目(详细)</th>
        <th width="200px" field ="test_basis"  editor="{type:'text'}">检测依据</th>
        <th width="100px" field ="stake_mark"  editor="{type:'text'}">现场桩号/结构部位</th>
        <th width="100px" field ="molding_date" editor="{type:'text'}">取样/成型日期</th>
        <th width="100px" field ="sample_loca" editor="{type:'text'}">取样地点</th>
        <th width="100px" field ="sample_cnt"  editor="{type:'text'}">样品数量</th>
        <th width="100px" field ="sample_desc" editor="{type:'text'}">样品描述</th>
        <th width="100px" field ="sample_format"editor="{type:'text'}">规格/牌号</th>
        <th width="100px" field ="producing_area"editor="{type:'text'}">厂家/产地</th>
        <th width="100px" field ="delegate_cnt"   editor="{type:'text'}">代表数量</th>
        <th width="100px" field ="make_num"   editor="{type:'text'}">生产批号</th>
        <th width="100px" field ="price"    editor="{type:'text'}">检测费用(元)</th>
        <th width="100px" field ="note"     editor="{type:'text'}">附加说明</th>
        <th width="100px" field ="back_type" editor="{type:'text'}">退样方式</th>
        </thead>
    </table>
</div>
<div id="toolbar" style="height: 30px;">
    <a id="modify_btn" class="easyui-linkbutton" iconCls="icon-edit" plain="true">修改</a>
    <a id="save_btn" class="easyui-linkbutton" iconCls="icon-save" plain="true">保存</a>
</div>


</body>
</html>