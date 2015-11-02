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
        $("#dg").datagrid({
            "url":"<?php echo U('Query/getSampleList','finance=1');?>",
            rowStyler: function(index,row){
                if(row.payed==0){
                    return "background:#A2CD5A;color:#ffffff;";
                }
            },
            view: detailview,
            detailFormatter: function(rowIndex, rowData){
                return '<div id="ddv-' + rowIndex + '" style="padding:5px 0"></div>';
            },onExpandRow: function(index,row){
                $('#ddv-'+index).panel({
                    border:false,
                    cache:false,
                    href:'/index.php/Home/Query/sampleDetail/protocol_num/'+row.protocol_num,
                    onLoad:function(){
                        $('#dg').datagrid('fixDetailRowHeight',index);
                    }
                });
            }
        });
    });
    function btnSearchClick(){
        var obj={};
        $.each( $('#search_fm' ).serializeArray() ,function(index ,param){
            if (!(param.name in obj)){
                obj[param.name ]=param.value;
            }
        });
        if((obj['rc_from']!="")^(obj['rc_to']!="")){
            $.messager.alert("操作提示","日期区间不能存在一方为空！");
            return ;
        }
        if(obj['rc_from']>obj['rc_to']!=""){
            $.messager.alert("操作提示","日期区间存在问题！");
            return ;
        }
        $('#dg').datagrid("load",obj);
    }
</script>


<div style="height: 100%;;width: 96%;position: fixed;">
    <table id="dg"  style="width:100%;min-height: 90%;"
           toolbar="#toolbar" pagination="true" title="财务列表"
           rownumbers="true" fitColumns="true"
           singleSelect="true" pageSize="<?php echo ($pageSize); ?>" pageList="<?php echo ($pageList); ?>" resizable="true">
        <thead>
        <tr >
            <th field="protocol_num">协议编号</th>
            <th field="sample_num">样品编号</th>
            <th field="date" >协议日期</th>
            <th field="client_name">委托公司</th>
            <th field="inspected"  >受检单位</th>
            <th field="project_name">工程名称</th>
            <th field="price">检测费用(元)</th>
            <th field="discount_price">折扣费用(元)</th>
            <th field="payed_price">已付费用(元)</th>
            <th field="contract_num">合同号</th>
            <th field="invoice_num">发票号</th>
            <th field="first_price">原始费用(元)</th>
            <th field="back_reason">检测项目变更原因</th>
            <th field="payed" formatter="fm_payed">是否付款</th>
            <th field="is_pay" formatter="fm_ip">付款类型</th>
            <th field="terms_pay" formatter="fm_terms_pay">支付方式</th>
            <th field="test_detail">检测项目</th>
            <th field="send_person">送样人</th>
            <th field="tel">送样人电话</th>
            <th field="receive_person">收样人</th>
            <th field="time_pay">付费或操作日期</th>
            <th field="operator_name"  >操作员</th>
        </tr>
        </thead>
    </table>
    <script>

    </script>
</div>
<div id="toolbar" >
    <a href="javascript:void(0)" id="btn_search" class="easyui-linkbutton" plain="true" iconCls="icon-search" onclick="btnSearchClick()">搜索</a>
    <a href="/index.php/Home/Manger/getExcelFinance" class="easyui-linkbutton" plain="true" iconCls="icon-print" >导出</a>
    <form id="search_fm" style="padding: 0px;margin: 0px;">
        <table style="width:1000px;">
            <tr>
                <td>委托单位：</td>
                <td><input name="client_name" class="textbox"> </td>
                <td>受检单位：</td>
                <td><input name="inspected"   class="textbox"> </td>
                <td>工程名称：</td>
                <td><input name="project_name" class="textbox"></td>
                <td>付款情况：</td>
                <td>
                    <select name="payed" class="my-select" >
                        <option value="">全部</option>
                        <option value="1">已付</option>
                        <option value="0">未付</option>
                    </select>
                </td>
            </tr>

            <tr>

                <td>协议日期：</td>
                <td><input name="date_from" class="easyui-datebox"></td>
                <td>到：</td>
                <td><input name="date_to" class="easyui-datebox"></td>

                <td>付款类型：</td>
                <td>
                    <select name="is_pay" class="my-select" >
                        <option value="">全部</option>
                        <option value="1">取报告付费</option>
                        <option value="3">协议结算</option>
                        <option value="2">立即支付</option>
                    </select>
                </td>
                <td>支付方式：</td>
                <td>
                    <select name="terms_pay" class="my-select" >
                        <option value="">全部</option>
                        <option value="1">现金</option>
                        <option value="2">刷卡</option>
                    </select>
                </td>
            </tr>
        </table>

    </form>
</div>




</body>
</html>