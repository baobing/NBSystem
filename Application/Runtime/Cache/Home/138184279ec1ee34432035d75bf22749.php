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
    $(function(){
        var data={};
        data["title"]="取报告付款审批";
        data["url"]="<?php echo U('Query/getProtocolList?checking=1&permission=7');?>";
        data["onDblClickRow"]= function () {
            submitResult();
        }
        $("#dg").datagrid(data);
    });
    function submitResult(t){
        var row=$("#dg").datagrid("getSelected");
        if(row==null){
            return ;
        }
        var check_pay=parseInt(row.check_pay)+parseInt(t);
        var str="确认通过吗？";
        if(t>20) str="<span style='color: red;'>确认拒绝吗？</span>";
        confirmPost("#dg",str,"/index.php/Home/Manger/submitResult",{id:row.id,check_pay:check_pay});

    }
</script>
<div style="height: 100%;;width: 96%;position: fixed;">
    <div style="height: 100%;;width: 96%;position: fixed;">
    <table id="dg"  style="width:100%;min-height: 90%;"
           toolbar="#toolbar" pagination="true"
           rownumbers="true" fitColumns="true"
           singleSelect="true" pageSize="<?php echo ($pageSize); ?>" pageList="<?php echo ($pageList); ?>" resizable="true">
        <thead>
        <tr >
            <th field="protocol_num">协议编号</th>
            <th field="check_pay" formatter="fm_ck">审核类型</th>
            <th field="date" >协议日期</th>
            <th field="client_name">委托公司</th>
            <th field="inspected"  >受检单位</th>
            <th field="project_name">工程名称</th>
            <th field="send_person">送样人</th>
            <th field="tel">送样人电话</th>
            <th field="receive_person">收样人</th>
            <th field="price">检测费用(元)</th>
            <th field="payed_price">已收费用(元)</th>
            <th field="discount_price">折扣费用(元)</th>
            <th field="plan_pay">缴纳费用(元)</th>
            <th field="first_price">原始检测费用(元)</th>
            <th field="back_reason">检测项目变更原因</th>
            <th field="operator_name">操作员</th>
        </tr>
        </thead>
    </table>
</div>
</div>
<div id="toolbar">
    <a  class="easyui-linkbutton" plain="true" iconCls="icon-ok" style="margin-left: 10px;" onclick="submitResult(10)">通过</a>
    <a  class="easyui-linkbutton" plain="true" iconCls="icon-no" style="margin-left: 10px;" onclick="submitResult(20)">拒绝</a>
</div>


</body>
</html>