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
    var manger_str="<div id='checker_div'><label for='checker'>打折审批:</label><select id='checker' class='textbox' style='width: 250px;margin: 5px;'>" +
            "<?php if(is_array($checker)): $i = 0; $__LIST__ = $checker;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><option value='<?php echo ($vo["id"]); ?>'><?php echo ($vo["name"]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?></select></div>";
    var obj={};
    $(function(){
        var data={};
        if("<?php echo ($type); ?>"=="0"){
            data['url']="/NBSystem/index.php/Home/Query/getProtocolList/prgPay/0/check_pay/0/is_finance/1";
            data['title']="待处理协议";
            data['onDblClickRow']=function(rowIndex, rowData){
                bntClickProgress();
            }
        }else  if("<?php echo ($type); ?>"=="1"){
            data['url']="/NBSystem/index.php/Home/Query/getProtocolList/checking/1/is_finance/1";
            data['title']="审批中协议列表";
        }else  if("<?php echo ($type); ?>"=="2"){
            data['url']="/NBSystem/index.php/Home/Query/getProtocolList/checked/1/is_finance/1";
            data['title']="已审批协议列表";
            data['onDblClickRow']=function(){
                checkConfirm();
            }
        }else if("<?php echo ($type); ?>"=="3"){
            data["url"]="/NBSystem/index.php/Home/Query/getProtocolList/is_pay/1/payed/0/check_pay/0/is_finance/1";
            data['title']="取报告付款协议列表";
            data['onDblClickRow']=function(rowIndex, rowData){
                secondTakePay();
            }
        }else if("<?php echo ($type); ?>"=="4"){
            data["url"]="/NBSystem/index.php/Home/Query/getProtocolList/is_pay/3/payed/0/is_finance/1";
            data['title']="协议结算列表";
            data["singleSelect"]=0;

        }
        else if("<?php echo ($type); ?>"=="5"){
            data["url"]="/NBSystem/index.php/Home/Query/getProtocolList/payed/1/is_finance/1";
            data['title']="已付费协议列表";
            data['onDblClickRow']=function(rowIndex, rowData){
                bntModifyPrice();
            }

        }else if("<?php echo ($type); ?>"=="6"){
            data["url"]="/NBSystem/index.php/Home/Query/getProtocolList/is_pay/4/payed/0/check_pay/0/is_finance/1";
            data['title']="挂账协议列表";
            data['onDblClickRow']=function(rowIndex, rowData){
                nowPay(rowData);
            }
        }
        data.onClickCell=function(rowIndex, field, value){

            if(field=='price'){
                var rows=$('#dg').datagrid('getRows');
                var row=rows[rowIndex];
                $.post("/NBSystem/index.php/Home/Cost/detailPrice",{protocol_num:row.protocol_num},function(res){
                    var str='';
                    for(var i in res){
                        str+=res[i]["test_item"];
                    }
                    $.messager.alert("检测费用明细",str);
                });
            }
        }
        $('#dg').datagrid(data);
        $('#btn_search').click(function(){
            btnSearchClick();
        });
        $("#toolbar").css("width","100%");
    });
    function btnSearchClick(){
       var obj={};
        $.each( $('#search_fm' ).serializeArray() ,function(index ,param){
            if (!(param.name in obj)){
                obj[param.name ]=param.value;
            }
        });
        if((obj['date_from']!="")^(obj['date_to']!="")){
            $.messager.alert("操作提示","日期区间不能存在一方为空！");
            return ;
        }
        if(obj['date_from']>obj['date_to']!=""){
               $.messager.alert("操作提示","日期区间存在问题！");
            return ;
        }
        $('#dg').datagrid("load",obj);
    }

     function checkConfirm(){          //   审批结果确认
        var row=isSelected("#dg");
         if(!row) return;
        var post_data={id:row.id};
        var url="/NBSystem/index.php/Home/Cost/checkConfirm";
        var confirmStr = "确认审批结果？";
        if((row.is_pay==1)&&(row.check_pay==12||row.check_pay==22)){  //第二次打折结算使用
            url="/NBSystem/index.php/Home/Cost/second";
            post_data["check_pay"]=row.check_pay;
            confirmStr = "<h3 >请收取"+row.plan_pay+"元！</h3>"
        }else if(13 == row.check_pay){
            if( row.check_pay < 20 ){
                url = "/NBSystem/index.php/Home/Cost/saveHungPay";
            }
        }
        else if(row.check_pay<20){
            if(row.check_pay==11){        //取报告付款 挂账 未付款
                post_data["payed"]=0;
            }else if(row.check_pay==12){                    //打折结算已经付款
                post_data["is_pay"]=2;
                post_data["payed"]=1;
                confirmStr = "<h3 >请收取"+row.plan_pay+"元！</h3>"
            }
            post_data["step"]=1;                             //进入部门主任步骤
            post_data["check_pay"]=row.check_pay;
            post_data["plan_pay"]= row.plan_pay;
            post_data["payed_price"]= row.payed_price;
        }else{                                               //拒绝就清空所有信息
            //退回协议 领导未通过
            if(row.check_pay==24){
                post_data["check_pay"] = 0;
            } else{
                post_data["terms_pay"]=0;
                post_data["plan_pay"]=0;
                post_data["discount_price"]=0;
                post_data["check_pay"]=0;
                post_data["operator_pay"]=0;
                post_data["time_pay"]="";
                post_data["person_pay"]="";
                post_data["is_pay"]=0;
                post_data["leader"]=0;
            }
        }
        confirmPost("#dg",confirmStr,url,post_data)
    }
    // 0未付费 1取报告 2立即 3协议 4挂账 5退回
    var payType = 0;
    /**
     *  TODO 选择结算方法
     */
    function bntClickProgress(){
        //debugger;
        var row=isSelected('#dg');
        if(!row){
            return;
        }
        if(row.is_back == 1){
            backPay(row);
            return ;
        }
        payType = 2;
        var str="<div id='pay_type_div'>支付类型：</div>" ;
        if(null != row.discount) { //
            str += "<div style='margin-left: 42px;'><span style='width: 70px;display: inline-block;'>协议结算</span><input name='is_pay' type='radio' value='3' onclick='changeType(this)'/></div> ";
        }
        str+="<div><span style='width: 70px;display: inline-block;'>立即支付</span><input name='is_pay' type='radio'checked value='2' onclick='changeType(this)' ></div> ";
        str+="<div style='margin-left: 40px;'><span style='width: 70px;display: inline-block;'>取报告付款</span><input name='is_pay'type='radio' value='1' onclick='changeType(this)'/> </div>";
        str+="<div style='margin-left: 42px;'><span style='width: 70px;display: inline-block;'>挂账</span><input name='is_pay' type='radio' value='4'onclick='changeType(this)' /></div> ";

        $.messager.confirm("操作提示",str,function(data){
            if(data){

                var res= $("[name='is_pay']:checked").val();
                if(1 == res){
                    takePay(row);
                }else if(2 == res){
                    nowPay(row);
                }else if(res==3){  //协议结算直接更新协议信息、进入下一步
                    if(null == row.discount){
                        $.messager.alert("操作提示","该工程并未签订协议！");
                        return ;
                    }
                    var content='<lable>发票号</lable><input id="invoice_num4" style="width: 150px;margin-left: 20px;" value="'+row.invoice_num+'">';
                    var url="/NBSystem/index.php/Home/Cost/submitSettle";
                    var post_data={is_pay:3,id:row.id,step:1};
                    var var_data=[{key:"invoice_num",value:"#invoice_num4",type:0}];
                    confirmPost("#dg",content,url,post_data,null,var_data);
                }else if(res==4){
                    hangPay(row);
                }
            }
        });
    }
    function changeType(t){
        payType = t.value;
    }
    /**
     * TODO 取报告付款处理,初次弹出框
     * @param row
     */
    function takePay(row) {
        payType = 1;
        $("#payed_price_div").hide();
        $("#discount_price_div").hide();
        $("#plan_pay_div").hide();
        $("#checker_div").show();
        $("#discount_change").hide();
        $("#terms_pay_div").hide();
        fillDialogInfo(row);
        $("#terms_pay").val(1);
        $("#dlg").dialog("open");
    }
    /**
     * TODO 取报告填充付费信息,二次弹出弹出框
     */
    function secondTakePay() {
        var row = isSelected("#dg");
        if(!row) {return;}
        payType = 1;
        $("#payed_price_div").hide();
        $("#discount_price_div").show();
        $("#plan_pay_div").show();
        $("#checker_div").hide();
        $("#discount_change").show();
        $("#terms_pay_div").show();
        fillDialogInfo(row);
        $("#terms_pay").val(1);
        $("#dlg").dialog("open");
    }
    /**
     * TODO 立即支付处理,弹出框
     * @param row
     */
    function nowPay(row) {
        payType = 2;
        $("#payed_price_div").hide();
        $("#discount_price_div").show();
        $("#plan_pay_div").show();
        $("#checker_div").hide();
        $("#discount_change").show();
        $("#terms_pay_div").show();
        fillDialogInfo(row);
        $("#terms_pay").val(1);
        $("#dlg").dialog("open");
    }
    /**
     * TODO 挂账付费处理,弹出框
     * @param row
     */
    function hangPay(row) {
        payType = 4;
        $("#payed_price_div").hide();
        $("#discount_price_div").show();
        $("#plan_pay_div").show();
        $("#checker_div").show();
        $("#discount_change").show();
        $("#terms_pay_div").show();
        fillDialogInfo(row);
        $("#terms_pay").val(1);
        $("#dlg").dialog("open");
    }
    /**
     * TODO 退回协议费用处理,弹出框
     * @param row
     */
    function backPay(row) {
        payType = 5;
        $("#payed_price_div").show();
        $("#discount_price_div").show();
        $("#plan_pay_div").show();
        $("#checker_div").show();
        $("#discount_change").hide();
        $("#terms_pay_div").hide();
        fillDialogInfo(row);
        $("#terms_pay").val(1);
        $("#dlg").dialog("open");
    }

    /**
     * TODO 填充初次处理协议弹出框信息
     */
    function fillDialogInfo(row){
        $('#fm').form("load",row);
        $('#rc_person').val(row.send_person);
        var shouldPay = row.price - row.payed_price - row.discount_price;
        $("#plan_pay").val(shouldPay);
        $("[name='is_reduce']:eq(0)").attr("checked","checked");
        $("#discount_price").val(row.discount_price);
        $("#plan_pay").attr("readonly",true);
    }
    /**
     * TODO 初次输入费用信息 提交服务器
     */
    function savePayInfo(){
        var row=isSelected("#dg");
        if(1 == payType ){
            if(1 == row.is_pay){
                saveTakePay(row);
            }else {
                stepTake(row);
            }
        }else if(2 == payType){
            saveNowPay(row);
        }else if(4 == payType){
            stepHung(row);
        }else if(5 == payType ){
           stepBack(row);
        }
    }
    /**
     * TODO 保存立即支付
     */
    function saveNowPay(row){
        var data = {
            id:row.id,
            person_pay:$('#rc_person').val(),
            terms_pay:$('#terms_pay').val(),
            plan_pay:$('#plan_pay').val(),
            discount_price:$("#discount_price").val(),
            is_reduce:$("[name='is_reduce']:checked").val(),
            invoice_num:$("#invoice_num").val(),
            leader:$("#checker").val()
        };
        url="/NBSystem/index.php/Home/Cost/nowPay";
        confirmPost("#dg","确认结算么?",url,data,savePayFn);
    }
    /**
     * TODO 取报告提交审核
     */
    function stepTake(row){
        var data = {
            id:row.id,
            person_pay:$('#rc_person').val(),
            invoice_num:$("#invoice_num").val(),
            leader:$("#checker").val()
        };
        url="/NBSystem/index.php/Home/Cost/stepTake";
        confirmPost("#dg","确认结算么?",url,data,savePayFn);
    }
    /**
     * TODO 提交取报告付款付费信息
     */
    function saveTakePay(row){

        var data = {
            id:row.id,
            person_pay:$('#rc_person').val(),
            terms_pay:$('#terms_pay').val(),
            plan_pay:$('#plan_pay').val(),
            discount_price:$("#discount_price").val(),
            is_reduce:$("[name='is_reduce']:checked").val(),
            invoice_num:$("#invoice_num").val(),
            leader:$("#checker").val()
        };
        url="/NBSystem/index.php/Home/Cost/takePay";
        confirmPost("#dg","确认结算么?",url,data,savePayFn);
    }

    function savePayFn(data){
        if(data){
            showSuccess();
            $("#dg").datagrid("reload");
            $("#dlg").dialog("close");
        }else{
            showFail();
        }
    }
    /**
     * todo 审核挂账
     */
    function stepHung(row){
        var data = {
            id:row.id,
            person_pay:$('#rc_person').val(),
            invoice_num:$("#invoice_num").val(),
            leader:$("#checker").val(),
            terms_pay:$('#terms_pay').val(),
            plan_pay:$('#plan_pay').val(),
            discount_price:$("#discount_price").val(),
        };
        url="/NBSystem/index.php/Home/Cost/stepHung";
        confirmPost("#dg","确认结算么?",url,data,savePayFn);
    }
    /**
     * TODO 返回审核
     */
    function stepBack(row){
        var data = {
            id:row.id,
            person_pay:$('#rc_person').val(),
            invoice_num:$("#invoice_num").val(),
            leader:$("#checker").val(),
            plan_pay:$("#plan_pay").val()
        };
        url="/NBSystem/index.php/Home/Cost/stepBack";
        confirmPost("#dg","确认结算么?",url,data,savePayFn);
    }
    /**
     * TODO  打折 缴纳费用可填
     */
    function reducePrice(){
        $("#plan_pay").attr("readonly",false);
        $("#checker_div").show();
    }
    function removeReduce(){
        var row = isSelected("#dg");
        $("#checker_div").hide();
        var shouldPay = row.price - row.payed_price - row.discount_price;
        $("#plan_pay").val(shouldPay);
        $("#discount_price").val(row.discount_price);
        $("#plan_pay").attr("readonly",true);
    }
    /**
     * TODO 付费发生变化 折扣框跟随变化
     */
    function payChange(){

        var price = $("#price").val();
        var payedPrice = $("#payed_price").val();
        var planPay = $("#plan_pay").val();
        var discountPrice = parseFloat(price) - parseFloat(payedPrice) - parseFloat(planPay);
        $("#discount_price").val(discountPrice);
    }
    function bntClickSettle(){             //协议结算 付款按钮点击
        var rows=$("#dg").datagrid("getChecked");
        if(!rows) {$.messager.alert("操作提示","请先选择一行！");return ;}
        var nums=[];
        var total=0;
        //只有同一个合同才能采用多个一起结算
        for(var i=0;i< rows.length-1;i++){
            if(rows[i]["contract_num"]!=rows[i+1]["contract_num"]){
                $.messager.alert("操作提示","多个工程名称！！");
                return ;
            }
        }

        for(var i=0;i< rows.length;i++){
            nums.push(rows[i]["protocol_num"]);
            total+=parseFloat(rows[i]["price"]);
        }
        $('#fm').form('clear');
        $('#protocol_nums').val(nums.join());
        $("#client_name").val(rows[0]["client_name"]);
        $("#project_name").val(rows[0]["project_name"]);
        $("#prices").val(total);
        $("#discount_span").text(rows[0]["discount"]);
        $('#rc_person').val(rows[0]["send_person"]);
        var plan_pay= parseFloat(total)*parseFloat(rows[0]["discount"])/10.00;
        $("#plan_pay").val(plan_pay);
        $('#terms_pay').val(1);
        $('#dlg').dialog('open');
    }
    function saveSettle(){        //提交协议结算信息,已付费
        var rows=$("#dg").datagrid("getChecked");
        var ids=[];
        var plans=[];
        for(var i=0;i< rows.length;i++){
            ids.push(rows[i]["id"]);
            var price=rows[i]["price"];
            if(rows[i]["discount"])
            price=parseFloat(price)*parseFloat(rows[i]["discount"])/10.0;

            plans.push(price)
        }
        var data={ids:ids,prices:$("#prices").val(),invoice_num:$("#invoice_num").val(),
            terms_pay:$('#terms_pay').val(),plan_pay:plans,person_pay:$("#rc_person").val(),payed:1};
        $.post("/NBSystem/index.php/Home/Cost/saveSettle",data,function(res){
            if(res) {
                $("#dg").datagrid("reload");
                $("#dlg").dialog("close");
                showSuccess();
            }
            else showFail();
        })
    }
    function bntModifyPrice(){      //已经付费的协议添加费用
        var row=isSelected("#dg");
        if(!row) return;
        var str="<div>增加费用金额</div>";
        str+="<input id='add'name='add' class='textbox' value='0' type='text'/>";
        var data={"id":row.id};
        var var_data=[{key:"add",value:"#add",type:0}];
        confirmPost("#dg",str,"/NBSystem/index.php/Home/Cost/modifyPrice",data,null,var_data);
    }
    function bntModifyInvoice(){
        var row=isSelected("#dg");
        if(row==null) return ;
        var content='<lable>发票号</lable><input id="invoice_num4" style="width: 150px;margin-left: 20px;" value="'+row.invoice_num+'">';
        var url="/NBSystem/index.php/Home/Cost/modifyInvoice";
        var post_data={id:row.id};
        var var_data=[{key:"invoice_num",value:"#invoice_num4",type:0}];
        confirmPost("#dg",content,url,post_data,null,var_data);
    }
</script>
<div style="height: 100%;;width: 96%;position: fixed;">
    

<div style="height: 100%;;width: 96%;position: fixed;">
    <table id="dg"  style="width:100%;min-height: 90%;"
           toolbar="#toolbar" pagination="true"
           rownumbers="true" fitColumns="true"
           singleSelect="true" pageSize="<?php echo ($pageSize); ?>" pageList="<?php echo ($pageList); ?>"
           resizable="true">
        <thead>
        <tr >
            <?php if($type == 4): ?><th field="id" checkbox="true"></th><?php endif; ?>
            <th field="protocol_num">协议编号</th>
            <?php if($type == 2): ?><th field="check_pay" formatter="fm_check_pay">审批结果</th><?php endif; ?>
            <th field="date" >协议日期</th>
            <th field="client_name">委托公司</th>
            <th field="inspected"  >受检单位</th>
            <th field="project_name">工程名称</th>
            <th field="contract_num">合同编号</th>
            <th field="discount">折扣</th>
            <th field="price">检测费用(元)</th>
            <th field="payed_price">已收费用(元)</th>
            <th field="discount_price">折扣费用(元)</th>
            <?php if( $type > 0): ?><th field="plan_pay"   >缴纳费用(元)</th>
                <th field="is_pay"  formatter="fm_ip">付款类型</th>
                <th field="terms_pay"  formatter="fm_terms_pay">支付方式</th>
                <th field="operator_name"  >操作员</th>
                <th field="person_pay"  >付款者</th>
                <th field="invoice_num">发票号</th><?php endif; ?>
            <th field="send_person">送样人</th>
            <th field="tel">送样人电话</th>
            <th field="receive_person">收样人</th>
            <?php if($type == 5): ?><th field="time_pay"  >付费日期</th>
                <?php elseif($type != 0): ?>
                <th field="time_pay"  >操作日期</th><?php endif; ?>

        </tr>
        </thead>
    </table>
</div>
</div>
<?php if($type == 0 or $type == 3 or $type == 6): ?><div id="dlg" class="easyui-dialog" title="付费信息" style="width:430px;height:550px;top:10px;"
     toolbar="#toolbar"
     iconCls='icon-save'resizable=true modal=true buttons="#buttons" closed=true>
    <style>
        #fm input{width: 250px;}
    </style>
    <form id="fm" method="post" novalidate>

        <div class="fitem">
            <label>协议编号:</label>
            <input name="protocol_num" class="textbox" readonly>
        </div>
        <div class="fitem">
            <label>协议日期:</label>
            <input  name="date" class="textbox" readonly>
        </div>
        <div class="fitem">
            <label>委托单位:</label>
            <input  name="client_name" class="textbox" readonly>
        </div>
        <div class="fitem">
            <label>受检单位:</label>
            <input name="inspected" class="textbox" readonly>
        </div>
        <div class="fitem">
            <label>工程名称:</label>
            <input  name="project_name" class="textbox" readonly>
        </div>

        <div id="terms_pay_div" class="fitem" >
            <label>付费方式:</label>
            <select id="terms_pay" name="terms_pay"  class="textbox" style="width: 253px;" >
                <option value="1">现金</option>
                <option value="2">刷卡</option>
            </select>
        </div>

        <div class="fitem" id="rc_person_div">
            <label>缴费人:</label>
            <input id="rc_person" name="rc_person" class="textbox" maxlength="20">
        </div>
        <div class="fitem" id="discount_change">
            <label>折扣变动:</label>
            <span>否</span><input  type="radio" name="is_reduce" onclick="removeReduce()" style="width: 30px;"value="0">
            <span>是</span><input  type="radio" name="is_reduce"  onclick="reducePrice()" style="width: 30px;" value="1">
        </div>

        <div id="checker_div" class="fitem">
            <label >审批人:</label>
            <select id='checker' class='textbox' style='width: 253px;'>
                  <?php if(is_array($checker)): $i = 0; $__LIST__ = $checker;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><option value='<?php echo ($vo["id"]); ?>'><?php echo ($vo["name"]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
            </select>
        </div>
        <div class="fitem" id="price_div">
            <label>检测费用(元):</label>
            <input  name="price" id="price" class="textbox" readonly>
        </div>
        <div class="fitem" id="payed_price_div" >
            <label>已付费用(元):</label>
            <input id="payed_price" name="payed_price" class="textbox"  readonly>
        </div>
        <div class="fitem" id="plan_pay_div">
            <label>缴纳费用(元):</label>
             <input id="plan_pay" name="plan_pay" class="textbox" required="" readonly onchange="payChange()">
        </div>

        <div class="fitem" id="discount_price_div">
            <label>折扣费用(元):</label>
            <input id="discount_price" name="discount_price" class="textbox" required="" readonly>
        </div>

        <div class="fitem">
            <label>发票号:</label>
            <input id="invoice_num" name="invoice_num" class="textbox" required="" >
        </div>
    </form>
</div>
<div id="buttons">
    <a href="javascript:void(0)" class="easyui-linkbutton c6" iconCls="icon-ok" onclick="savePayInfo()" style="width:90px">确认</a>
    <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-cancel" onclick="javascript:$('#dlg').dialog('close')"
       style="width:90px">取消</a>
</div>


    <?php elseif($type == 4): ?>
    
<div id="dlg" class="easyui-dialog" title="付费信息" style="width:430px;height:450px;top:10px;"
     toolbar="#toolbar"
     iconCls='icon-save'resizable=true modal=true buttons="#buttons" closed=true>
    <style>
        #fm input{width: 250px;}
    </style>
    <form id="fm" method="post" novalidate>

        <div class="fitem">
            <label>协议编号:</label>
            <textarea id="protocol_nums"  readonly style="width: 250px;border-radius: 3px;height: 40px;"></textarea>
        </div>
        <div class="fitem">
            <label>委托单位:</label>
            <input  id="client_name" class="textbox" readonly>
        </div>
        <div class="fitem">
            <label>工程名称:</label>
            <input  id="project_name" class="textbox" readonly>
        </div>
        <div class="fitem">
            <label>费用金额:</label>
            <input   id="prices" class="textbox" readonly>
        </div>
        <div class="fitem">
            <label>付费方式:</label>
            <select id="terms_pay" name="terms_pay"  class="my-select" style="width: 255px;" >
                <option value="1">现金</option>
                <option value="2">刷卡</option>
            </select>
        </div>
        <div class="fitem">
            <label>缴费人姓名:</label>
            <input id="rc_person" name="rc_person" type="text" class="textbox" maxlength="20">
        </div>
        <div class="fitem" id="real_div">
            <label>实际收费:</label>
             <input id="plan_pay" name="plan_pay" class="textbox" required="" style="width: 170px;">
            <span style="color: red">折扣：</span>
            <span style="color: red" id="discount_span"></span>
            <span style="color: red">折</span>
        </div>
        <div class="fitem">
            <label>发票号:</label>
            <input id="invoice_num" name="invoice_num" class="textbox" required="" >
        </div>
    </form>
</div>
<div id="buttons">
    <a href="javascript:void(0)" class="easyui-linkbutton c6" iconCls="icon-ok" onclick="saveSettle()" style="width:90px">确认</a>
    <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-cancel" onclick="javascript:$('#dlg').dialog('close')"
       style="width:90px">取消</a>
</div><?php endif; ?>

<div id="toolbar" >
    <a id="btn_search" class="easyui-linkbutton" plain="true" iconCls="icon-search">搜索</a>
    <?php if($type == 0): ?><a href="#" class="easyui-linkbutton" iconCls="icon-edit" plain="true" onclick="bntClickProgress()">处理协议</a><?php endif; ?>
    <?php if($type == 2): ?><a href="#" class="easyui-linkbutton" iconCls="icon-edit" plain="true" onclick="checkConfirm()">确认审批</a><?php endif; ?>
    <?php if($type == 3 ): ?><a href="#" class="easyui-linkbutton" iconCls="icon-edit" plain="true" onclick="secondTakePay()">费用结算</a><?php endif; ?>
    <?php if($type == 4): ?><a href="#" class="easyui-linkbutton" iconCls="icon-edit" plain="true" onclick="bntClickSettle()">费用结算</a><?php endif; ?>
    <?php if($type == 5): ?><!--       <a href="#" class="easyui-linkbutton" iconCls="icon-edit" plain="true" onclick="bntModifyPrice()">费用修改</a>-->
        <a href="/NBSystem/index.php/Home/Cost/getExcelCost/payed/1"  class="easyui-linkbutton" iconCls="icon-print" plain="true" >Excel导出</a><?php endif; ?>
    <a href="#" class="easyui-linkbutton" iconCls="icon-reload" plain="true" onclick="bntModifyInvoice()">发票号修改</a>
    
<form id="search_fm" style="margin: 0px;padding: 0px;">
<table style="width:1150px;margin-top: 5px;padding: 0px;">
    <tr>
        <td>协议编号</td>
        <td><input name="protocol_num" class="textbox"></td>
        <td>委托单位</td>
        <td><input name="client_name" class="textbox"> </td>
        <td>工程名称</td>
        <td><input name="project_name" class="textbox"></td>
        <td>合同编号</td>
        <td><input name="contract_num" class="textbox"></td>
    </tr>
    <tr>
        <?php if($type == 5): ?><td>付款日期</td>
            <td><input name="pay_from" class="easyui-datebox"></td>
            <td>到</td>
            <td><input name="pay_to"  class="easyui-datebox"> </td>

            <?php else: ?>
            <td>协议日期</td>
            <td><input name="date_from" class="easyui-datebox"></td>
            <td>到：</td>
            <td><input name="date_to" class="easyui-datebox"></td><?php endif; ?>
        <td>开具发票</td>
        <td>
            <select name="is_invoice" class="my-select" >
                <option value=""></option>
                <option value="0">未开发票</option>
                <option value="1">已开发票</option>
            </select>
        </td>
        <td><?php if($type == 5): ?>付款类型<?php endif; ?></td>
        <td>
            <?php if($type == 5): ?><select name="is_pay" class="my-select" >
                    <option value=""></option>
                    <option value="1">取报告付费</option>
                    <option value="3">协议结算</option>
                    <option value="2">立即支付</option>
                    <option value="4">挂账</option>
                </select><?php endif; ?>
        </td>
        <td></td>
        <td>
        </td>
        <td></td>
        <td>
        </td>
    </tr>
</table>

</form>



</div>



</body>
</html>