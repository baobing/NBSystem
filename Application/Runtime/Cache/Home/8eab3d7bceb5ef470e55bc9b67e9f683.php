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

<style>
    #p_dg th{width: 100px;}
    #toolbar tr td:nth-child(even){width: 80px;}
    #toolbar tr td:nth-child(odd){width: 150px;}
    #toolbar tr td input,select{width: 145px}
</style>
<script>
    var industry_type=<?php echo ($industry_type); ?>;
    $(function(){
        if("<?php echo ($type); ?>"=="0"){
            $('#dg').datagrid({
                url:"/NBSystem/index.php/Home/Query/getProtocolList/prt/1/nums/1",title:"协议书列表",
                onDblClickRow:function(rowIndex, rowData){
                    toFinance();
                },
                view: detailview,
                detailFormatter: function(rowIndex, rowData){
                    return '<div id="ddv-' + rowIndex + '" style="padding:5px 0"></div>';
                },onExpandRow: function(index,row){
                    $('#ddv-'+index).panel({
                        border:false,
                        cache:false,
                        href:'/NBSystem/index.php/Home/Query/sampleDetail/protocol_num/'+row.protocol_num,
                        onLoad:function(){
                            $('#dg').datagrid('fixDetailRowHeight',index);
                        }
                    });
                },
                rowStyler:function(index,row){
                    if (row.is_finance == 0){
                        return 'color:red;'; // return inline style
                    }
                }
            });
        }else if("<?php echo ($type); ?>"=="1" || "<?php echo ($type); ?>"=="3"||"<?php echo ($type); ?>" == "5"||"<?php echo ($type); ?>" == "6"){ // 5收发等级表
            var data = {};
            data["url"] = "/NBSystem/index.php/Home/Query/getSampleList/sample/1";
            if("<?php echo ($is_printed); ?>"!=""){
                data["url"] += "/is_printed/<?php echo ($is_printed); ?>/finished/1";
            }
            data["title"] = "检测报告列表";
            if("<?php echo ($type); ?>" == "5"){
                data["title"] = "收发登记表";
            }
            data["onDblClickRow"] = function(rowIndex, rowData){
                sampleBtnClick();
            };
            data["rowStyler"]=function(index,row){
                if (row.priority == 1){
                    return 'color:red;'; // return inline style
                }
            }
            $('#dg').datagrid(data);
        }else if("<?php echo ($type); ?>"=="2"){
            $('#dg').datagrid({
                url: "/NBSystem/index.php/Home/Query/getProtocolList/protocol_step/1", title: "协议书列表",
                onDblClickRow: function (rowIndex, rowData) {
                    saveBtnClick();
                }
            });
        }
        $('#query_btn').click(function(){
            queryBtnClick();

        });
        $('#save_btn').click(function(){
            saveBtnClick();
        });
        $('#del_btn').click(function(){
            delBtnClick();
        });
        $('#protocol_btn').click(function(){
                protocolBtnClick();
        });
        $('#sample_btn').click(function(){
            sampleBtnClick();
        });
        $('#take_btn').click(function(){
            takeBtnClick();
        });
        $("#setPriority").click(function(){
            setPriority();
        });
        $("#finance_btn").click(function(){
            toFinance();
        });
    });
    /**
     * 由收发室转交给财务
     * 退回协议不可走次流程
     */
    function toFinance(){
        var row=$('#dg').datagrid('getSelected');
        if(row==null){
            $.messager.alert("操作提示",'请先选择一行');
            return ;
        }else if(row.is_finance == 1){
            $.messager.alert("操作提示",'已经转交财务室');
            return ;
        }
        var url =  "/NBSystem/index.php/Home/Query/toFinance";
        var postData = {id:row.id};
        confirmPost("#dg","确认转交财务室么?",url,postData);
    }
    function takeBtnClick(){             //取走报告点击事件
        var row=$('#dg').datagrid('getSelected');
        if(row==null){
            $.messager.alert("操作提示",'请先选择一行');
        }else if(row.step<5) {
            $.messager.alert("操作提示",'报告未完成！');
        }else if(row.step!=5) {
            $.messager.alert("操作提示",'报告已取走！');
        }else if((row.is_pay=0||row.is_pay==1||row.is_pay==2)&&row.payed==0) {
            $.messager.alert("操作提示",'未付款！');
        } else {

            var str="快递编号：<input id='sendNum'></br>";
            str += "取报告人姓名：<input id='takePerson' value='"+row.send_person+"'>";
            $.messager.confirm("操作提示",str,function(){
                var sendNum = $("#sendNum").val();
                var takePerson = $("#takePerson").val();
                var send_data={id:row.id,send_num:sendNum,take_person:takePerson};
                if(row.take_type == "邮寄"){
                    send_data["step"] = 7;
                }else {
                    send_data["step"] = 6;
                }
                $.post("<?php echo U('Progress/refreshStep');?>",send_data,function(data){
                    if(data){
                        showSuccess();
                        $('#dg').datagrid('reload');

                    }else{
                        showFail();
                    }
                });

            });
        }
    }
    function sampleBtnClick(){
        var row=$('#dg').datagrid('getSelected');
        if(row==null){
            $.messager.alert("操作提示",'请先选择一行');
        }else{
            if("<?php echo ($type); ?>" == "6"){
                $.post("/NBSystem/index.php/Home/Query/printed",{id:row.id},function(data){});
                $('#dg').datagrid('reload');
            }
            var iWidth=1200; //弹出窗口的宽度;
            var iLeft = (window.screen.availWidth-10-iWidth)/2; //获得窗口的水平位置;
            var iHeight=window.screen.availHeight-100; //弹出窗口的高度;
            var url="/NBSystem/index.php/Home/Progress/showSample/id/"+row.id+"/seq/0";
            window.open(url, "check_sample"+row.sample_num,
                    "height="+iHeight+",width=1200,left="+iLeft);
        }
    }
    function protocolBtnClick(){

        var row=$('#dg').datagrid('getSelected');
        if(row==null){
            $.messager.alert("操作提示",'请先选择一行');
        }else{
            var iWidth=1200; //弹出窗口的宽度;

            var iHeight=window.screen.availHeight-100; //弹出窗口的高度;
            var iTop = (window.screen.availHeight-30-iHeight)/2; //获得窗口的垂直位置;
            var iLeft = (window.screen.availWidth-10-iWidth)/2; //获得窗口的水平位置;
            window.open("/NBSystem/index.php/Home/Progress/showProtocol/id/"+row.id+"/curr/0","protocol"+row.id,
                    "height="+iHeight+",width=1200,left="+iLeft);
        }
    }
    function delBtnClick(){
        var row=$('#dg').datagrid('getSelected');
        if(row==null){
            $.messager.alert("操作提示",'请先选择一行');
        }else{
            $.messager.confirm("操作提示",'确认删除'+row.protocol_num+'委托协议书么？',function(is){
                if(is){
                    $.post('/NBSystem/index.php/Home/Protocol/delProtocol',{'id':row.id},function(data){
                        if(data){
                            $('#dg').datagrid('reload');
                        }
                        else {
                            showFail()
                        }
                    });
                }
            });
        }
    }
    function saveBtnClick(){
        var row=$('#dg').datagrid('getSelected');
        if(row==null){
            $.messager.alert("操作提示",'请先选择一行');
            return;
        }
        location.href='/NBSystem/index.php/Home/Protocol/addPage/protocol_num/'+row.protocol_num+'/type/2';

    }
    function queryBtnClick(){
        var date_from=$('input[name="date_from"]').val();
        var date_to=$('input[name="date_to"]').val();
        if((date_from=="")^(date_to=="")){
            $.messager.alert("操作失误","日期不全！");return;
        }
        if(date_from>date_to){
            $.messager.alert("操作失误","日期区间有误！");return;
        }

        var data=mySerializeObject('#toolbar');
        $('#dg').datagrid('load',data);

    }
    function setPriority(){
        var row=$('#dg').datagrid('getSelected');
        if(row==null){
            $.messager.alert("操作提示",'请先选择一行');
            return;
        }
        $.post("/NBSystem/index.php/Home/Query/setPriority",{id:row.id},function(data){
           if(data){
               $('#dg').datagrid("reload");
               showSuccess();
           } else{
               showFail();
           }
        });
    }
</script>
<form id="toolbar">
    <div style="width:900px;">
        <div style="width: 800px;margin-top: 5px;">
   <a id="query_btn" class="easyui-linkbutton" iconCls="icon-search" plain="true">查询</a>
    <?php if($type == 0 or $type == 2): ?><a id="save_btn" class="easyui-linkbutton" iconCls="icon-edit" plain="true">修改</a>
        <a href="/NBSystem/index.php/Home/Query/getExcelPrt" class="easyui-linkbutton" iconCls="icon-print" plain="true">导出</a><?php endif; ?>
    <?php if($type == 1 or $type == 6): ?><a id="sample_btn" class="easyui-linkbutton" iconCls="icon-edit" plain="true">检测报告</a><?php endif; ?>
    <?php if($type == 1): ?><a id="take_btn" class="easyui-linkbutton" iconCls="icon-redo" plain="true">取走报告</a>
        <a href="/NBSystem/index.php/Home/Query/getExcelSample" class="easyui-linkbutton" iconCls="icon-print" plain="true">导出</a><?php endif; ?>
    <?php if($type == 0): ?><a id="protocol_btn" class="easyui-linkbutton" iconCls="icon-edit" plain="true">协议书</a><?php endif; ?>
    <?php if($type == 0): ?><a id="del_btn" class="easyui-linkbutton" iconCls="icon-remove" plain="true">删除</a><?php endif; ?>
    <?php if($type == 0): ?><a id="finance_btn" class="easyui-linkbutton" iconCls="icon-redo" plain="true">转交财务</a><?php endif; ?>
    <?php if($type == 5): ?><a href="/NBSystem/index.php/Home/Query/getExcelSample/testDetail/1" class="easyui-linkbutton" iconCls="icon-print" plain="true">导出</a>
        <a id="setPriority" class="easyui-linkbutton" iconCls="icon-edit" plain="true">优先试验</a><?php endif; ?>
</div>
        <?php if($type == 0 or $type == 2): ?><table style="width: 1000px;margin-top: 5px;">
    <tr>
        <td>协议书号：</td>
        <td><input name="protocol_num"   class="textbox"></td>
        <td>委托单位：</td>
        <td><input name="client_name"  name="client" class="textbox"></td>
        <td>工程名称：</td>
        <td><input name="project_name"   class="textbox"></td>
        <td>送样人：</td>
        <td><input name="send_person"   class="textbox"></td>
    </tr>
    <tr>
        <td>协议日期：</td>
        <td><input name="date_from"  class="easyui-datebox"  ></td>
        <td>到：</td>
        <td><input name="date_to"  class="easyui-datebox"  ></td>
        <td>付款类型：</td>
        <td>
            <select  name="is_pay" class="my-select" >
                <option value=""></option>
                <option value="0">未处理</option>
                <option value="1">取报告付款</option>
                <option value="2">立即支付</option>
                <option value="3">协议结算</option>
            </select>
        </td>
        <td>行业类别：</td>
        <td>
            <select name="industry_type" id="industry_type" class="my-select">
                <option value="">全部</option>
                <?php if(is_array($industry)): $i = 0; $__LIST__ = $industry;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><option value="<?php echo ($vo["type"]); ?>"><?php echo ($vo["content"]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
            </select>
        </td>
    </tr>
</table>

            <?php else: ?>
<table style="width: 1000px;margin-top: 5px;">
    <tr >

        <td>委托单位：</td>
        <td><input name="client_name" class="textbox"></td>
        <td>工程名称：</td>
        <td><input name="project_name" class="textbox"></td>
        <td>送样人：</td>
        <td><input name="send_person" class="textbox"></td>
        <td>检验类别：</td>
        <td>
            <select name="check_type" class="my-select">
                <option value=""></option>

                <option value="委托">委托</option>
                <option value="抽检">抽检</option>
                <option value="比对">比对</option>
            </select>
        </td>

    </tr>
    <tr >
        <td>协议日期：</td>
        <td><input name="date_from" class="easyui-datebox"></td>
        <td>到：</td>
        <td><input name="date_to" class="easyui-datebox"></td>
        <td>样品名称：</td>
        <td><input name="sample_name" class="textbox"></td>
        <td>报告实时状态：</td>
        <td>
            <select name="report_step" class="my-select">
                <option value=""></option>

                <option value="-1">未完成</option>
                <option value="5">已完成</option>
                <option value="6">已取走</option>
                <option value="7">已邮寄</option>
            </select>
        </td>

    </tr>
</table><?php endif; ?>

    </div>
</form>
<div style="height: 85%;;width: 96%;position: fixed;">
    <?php if($type == 0 or $type == 2): ?><table id="dg"
       style="width:100%;min-height: 80%;;float: left;"
       toolbar="#toolbar" pagination="true"
       rownumbers="true"
       singleSelect="true" pageSize="<?php echo ($pageSize); ?>" pageList="<?php echo ($pageList); ?>" resizable="true">
    <thead>
    <tr>
        <th width="100px" field="date">协议日期</th>
        <th width="100px" field="protocol_num">协议编号</th>
        <th width="100px" field="client_name">委托单位</th>
        <th width="100px" field="project_name">工程名称</th>
        <th width="100px" field="send_person">送样人</th>
        <th width="100px" field="tel"  >联系电话 </th>
        <th width="100px" field="price">试验费用</th>
        <th width="100px" field="inspected">受检单位</th>
        <th width="100px" field="industry_type" formatter="fm_industry_type" >行业类别</th>
        <th width="100px" field="check_type">检验类别</th>
        <th width="100px" field="witness_contact">见证人</th>
        <th width="100px" field="witness_company">见证单位</th>
        <th width="100px" field="witness_tel">联系电话 </th>
        <th width="100px" field="receive_person">收样人  </th>
        <th width="100px" field="report_cnt">报告数量</th>
        <th width="100px" field="is_conform">是否做符合性声明  </th>
        <th width="100px" field="back_type">退样方式  </th>
        <th width="100px" field="take_type" >报告提取方式</th>
        <th width="100px" field="mail_contact">收件人</th>
        <th width="100px" field="mail_tel">联系电话</th>
        <th width="100px" field="mail_number" >邮编</th>
        <th width="100px" field="mail_address" >邮寄地址</th>
    </tr>
    </thead>
</table>
        <?php else: ?>
        <table id="dg" title="样品列表"
       style="width:100%;min-height: 80%;;float: left;"
       toolbar="#toolbar"
       pagination="true"
       rownumbers="true"

       singleSelect="true"
       pageSize="<?php echo ($pageSize); ?>" pageList="<?php echo ($pageList); ?>"
       resizable="true"
        >
    <thead>
    <tr >
        <th field="id" checkbox="true"></th>
        <th width="100px" field="date" >协议日期</th>
        <th width="100px" field="protocol_num"  >协议编号</th>
        <th width="100px" field="sample_num" >样品编号</th>
        <th width="100px" field="sample_name" >样品名称</th>
        <th width="150px" field="client_name"   >委托单位</th>
        <th width="200px" field="project_name"  >工程名称</th>
        <th width="100px" field="send_person"  >送样人</th>
        <th width="100px" field="tel">联系电话</th>
        <th width="100px" field="price">试验费用</th>
        <?php if($type == 5): ?><!--收发记录 需要显示检测项目的具体信息-->
            <th width="150px" field="test_detail" >检测项目</th>
            <?php else: ?>  <th width="150px" field="test_item" >检测项目</th><?php endif; ?>
        <th width="100px" field="step" formatter="fmStep">报告实时状态</th>
        <th width="100px" field="is_pay" formatter="fm_ip">付费状态</th>
        <th width="100px" field="receive_person" >收样人</th>
        <th width="100px" field="take_type">报告提取方式</th>
        <th width="100px" field="take_person">取报告人</th>
        <th width="100px" field="mail_contact">收件人</th>
        <th width="100px" field="mail_tel" >联系电话</th>
        <th width="100px" field="mail_number" >邮编</th>
        <th width="100px" field="mail_address" >邮寄地址</th>
        <th width="100px" field="send_num" >快递编号</th>
        <th width="100px" field="back_type">退样方式</th>
    </tr>
    </thead>
</table><?php endif; ?>
</div>


</body>
</html>