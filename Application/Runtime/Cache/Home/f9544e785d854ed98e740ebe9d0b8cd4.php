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
    var selectIndex=-1;
    String.prototype.trim=function() {
        return this.replace(/(^\s*)|(\s*$)/g,'');
    };
    var spertorChar = "|";
    var separatorChar="  ";
    $(function(){
        $('#item_dg').datagrid({
               onDblClickRow:function(rowIndex, rowData){
                $('#res_dg').datagrid('appendRow',{
                    test_item: rowData.test_item,
                    test_basis: rowData.test_basis,
                    test_cnt: 1,
                    price:rowData.price,
                    equipment:rowData.equipment,
                    unit:rowData.unit

                })
            }
        });
        $('#res_dg').datagrid({
            onDblClickRow:function(rowIndex, rowData){
                $('#res_dg').datagrid('deleteRow',rowIndex);
            },onClickCell:function(rowIndex, field, value){
                if(selectIndex!=-1){
                $('#res_dg').datagrid('endEdit',selectIndex);
            }
                if(field=="test_cnt"){

                    $('#res_dg').datagrid('beginEdit',rowIndex);
                    selectIndex=rowIndex;
                }

            }
        });

        $('.easyui-datebox').datebox('setValue','<?php echo ($today); ?>');
        $(".datebox :text").removeAttr("readonly");
        $("#deadline").datebox("setValue","");
        $('.my-ul li').click(function(){
            $(this).parent().find('li').removeClass('li-select');
            $(this).addClass('li-select');
            $('#sample_name').val($(this).text());
            $('#item_dg').datagrid('load',{name:$(this).text()});
            var name = $(this).text();
            //得到样品描述信息
            $.post("/NBSystem/index.php/Home/Sample/getSampleInfo",{name:$(this).text()},function(data){
                if(data){
                    $("#fm").form("load",data);
                    var sample_format=data["sample_format"];
                    var arr_sample_format=sample_format.split(spertorChar);
                    var data_sample_format=[];
                    for(var i in arr_sample_format){
                        data_sample_format.push({value:arr_sample_format[i],text:arr_sample_format[i]});
                    }
                    $("#sample_format").combobox("loadData",data_sample_format);
                    $("#sample_format").combobox("setValue",data_sample_format[0]["value"]);

                    var sample_desc=data["sample_desc"];
                    var arr_sample_desc=sample_desc.split(spertorChar);
                    var data_sample_desc=[];
                    for(var i in arr_sample_desc){
                        data_sample_desc.push({value:arr_sample_desc[i],text:arr_sample_desc[i]});
                    }
                    $("#sample_desc").combobox("loadData",data_sample_desc);
                    $("#sample_desc").combobox("setValue",data_sample_desc[0]["value"]);

                    var sample_cnt=data["sample_cnt"];
                    var arr_sample_cnt=sample_cnt.split(spertorChar);
                    var data_sample_cnt=[];
                    for(var i in arr_sample_cnt){
                        data_sample_cnt.push({value:arr_sample_cnt[i],text:arr_sample_cnt[i]});
                    }
                    $("#sample_cnt").combobox("loadData",data_sample_cnt);
                    $("#sample_cnt").combobox("setValue",data_sample_cnt[0]["value"]);
                }else{

                    $('#sample_name').val(name);
                    $("#sample_format").combobox("loadData",[{}]);
                    $("#sample_desc").combobox("loadData",[{}]);
                    $("#sample_cnt").combobox("loadData",[{}]);
                    $("#sample_format").combobox("setText","");
                    $("#sample_desc").combobox("setText","");
                    $("#sample_cnt").combobox("setText","");
                    $("#note").val("");
                }
            });
        });
        $('#test_item_ul  li:eq(0)').click();

        $('#add_btn').click(function(){
            addBtnClick();
        });
        $('#del_btn').click(function(){
            delBtnClick();
        });
        $('#save_btn').click(function(){
            saveBtnClick();
        });

    });
    function  addBtnClick(){
        var row=$('#item_dg').datagrid('getSelected');
        if(row==null){
            $.messager.confirm("操作提示", "请您先选择一行");
            return;
        }
        $('#res_dg').datagrid('appendRow',row);
    }
    //删除按钮点击事件
    function  delBtnClick(){
        var row=$('#res_dg').datagrid('getSelected');
        if(row==null){
            $.messager.confirm("操作提示", "请您先选择一行");
            return;
        }
        var index=$('#res_dg').datagrid('getRowIndex',row);
        $('#res_dg').datagrid('deleteRow',index);

    }
    //检测项目选择结果框 退出编辑模式
    function endEditAll(){
        //全部退出编辑状态
        var data = $('#res_dg').datagrid('getData');
        for(var i in data["rows"] ){
            $('#res_dg').datagrid('endEdit',i);
        }
    }
    function saveBtnClick(){
        if(!$('#fm').form('validate')) {
            return ;
        }
        var data1=$('#res_dg').datagrid('getData');
        if(!data1['total']){
            $.messager.alert("操作提示","检测项目未选择！");
            return ;
        }

        endEditAll();

        var data=$('#fm').serializeArray();
        var row=$('#item_dg').datagrid('getSelected');
        var table=$('#res_dg').datagrid('getData');
        var test_basis="";
        var test_equipment = "";
        for(var i=0;i<table["rows"].length;i++){
            var tem_str = table['rows'][i]["test_basis"];
            var tem_str1 = table['rows'][i]["equipment"];
            tem_str = tem_str.trim();
            tem_str1 = tem_str1.trim();
            if(test_basis.indexOf(tem_str)==-1){
                if(test_basis!="") test_basis+=" ";
                test_basis+=tem_str;
            }
            if(test_equipment.indexOf(tem_str1)==-1){
                if(test_equipment != ""){
                    test_equipment += " ";
                }
                test_equipment += tem_str1;
            }
        }
        var str='';var cnt=0;
        var detail='';
        //检测项目拼接  费用总和计算
        for(var i=0;i<table['total'];i++){
            if(str!='') {
                detail+=separatorChar;
                str+=separatorChar;
            }
            detail+=table['rows'][i]['test_item'];
            if(table["rows"][i]["test_cnt"] > 1){
                detail += '('+table['rows'][i]['price']+'×'+table["rows"][i]["test_cnt"]+')';

            }else{
                detail += '('+table['rows'][i]['price']+')';
            }
            str+=table['rows'][i]['test_item'];

            cnt+=parseFloat(table['rows'][i]['price'])*parseFloat(table["rows"][i]["test_cnt"]);
        }
        
        var temp={name:'test_item',value:str};
        data.push(temp);
        temp={name:'test_detail',value:detail};
        data.push(temp);
        temp={name:'test_basis',value:test_basis};
        data.push(temp);
        temp={name:'price',value:cnt};
        data.push(temp);
        temp={name:'equipment',value:test_equipment};
        data.push(temp);
        temp={name:'unit','value':row.unit};
        data.push(temp);

        temp={name:'sample_format',value:$("#sample_format").combobox("getText")};
        data.push(temp);
        temp={name:'sample_cnt',value:$("#sample_cnt").combobox("getText")};
        data.push(temp);
        temp={name:'sample_desc',value:$("#sample_desc").combobox("getText")};
        data.push(temp);
        for(var p in data){
            if(data[p]["name"]!="deadline" && data[p]["value"] == "") {
                data[p]["value"] = "/";
            }else if(data[p]["name"]== "delegate_cnt") {
                data[p]["value"]+=$("#delegate_unit").val();
            }

        }

        $.post('/NBSystem/index.php/Home/Sample/submitSample', data,
                function(data){
                    if(data){
                        location.href='/NBSystem/index.php/Home/Protocol/addPage/protocol_num/<?php echo ($protocol_num); ?>/type/1'
                    }else{
                        $.messager.alert("操作提示", "操作失败！");

                    }
                },'JSON');
    }
</script>
<style>
    .my-ul{
        list-style: none;
        margin: 0px;
        padding: 5px;;
    }
    .my-ul li{
        margin: 0px;;
    }
    .my-ul li:hover{
        background: #dddddd;;
        cursor: default;;
    }
    .li-select{
        background: #f40;color: #ffffff;
    }
    .outside{
        height: 200px;  margin-left: 10px;margin-top: 10px;float: left;
    }
    .ul-set{
        width: 20%;overflow-y: scroll; border: solid 1px #aaaaaa;border-radius: 5px;
    }

</style>
<div style="margin-left: auto;margin-right: auto;width: 1000px;">
    <form id="fm" action="/NBSystem/index.php/Home/Sample/submitSample" method="post">
       <table>
           <tr>
               <td>
                   <div class="my-lable" >协议书编号：</div>
               </td>
               <td>
                   <input name="protocol_num" class="textbox"  readonly value="<?php echo ($protocol_num); ?>">
               </td>
               <td>
                   <div class="my-lable">样品编号：</div>
               </td>
               <td>
                   <input name="sample_num" id="sample_num" class="textbox" readonly value="T<?php echo ($sample_num); ?>">
               </td>
           </tr>

       </table>

        <div class="panel-outside" style="height: 250px;">
            <div class="easyui-panel" title="检测项目选择" style="height: 250px;">
                <div class="outside ul-set">
                    <ul id="test_item_ul"class="my-ul" style="">
                        <?php if(is_array($group_name)): $k = 0; $__LIST__ = $group_name;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($k % 2 );++$k; if($k == 1): ?><li class="li-select"><?php echo ($vo["item_name"]); ?></li>
                                <?php else: ?><li><?php echo ($vo["item_name"]); ?></li><?php endif; endforeach; endif; else: echo "" ;endif; ?>
                    </ul>
                </div>
                <div class="outside" style="width: 30%;">
                    <table id="item_dg" style="height: 100%;width: 100%"
                           url="/NBSystem/index.php/Home/Sample/getTestItem/type/<?php echo ($type); ?>"
                           rownumbers="true"  singleSelect="true">
                        <thead>
                        <th field="test_item" width="100px;" editor="{type:'text'}" >
                            检测项目
                        </th>
                        <th field="test_basis" width="100px;" >
                            检测依据
                        </th>
                        <th field="price" width="80px;">
                            收费标准
                        </th>
                        <th field="equipment" hidden="true">
                            设备
                        </th>
                        <th field="unit" hidden="true" >
                            单位
                        </th>
                        </thead>
                    </table>
                </div>
                <div class="outside" style="width: 7%">
                    <div id="add_btn" class="easyui-linkbutton" iconCls="icon-in" style="margin-top: 50px;height: 30px;width: 60px;">
                        添加
                    </div>
                    <div id="del_btn" class="easyui-linkbutton" data-options="iconCls:'icon-out'" style="margin-top: 50px;height: 30px;width: 60px;">
                        去除
                    </div>
                </div>
                <div class="outside" style="width: 35%;border-radius: 5px;">
                    <table id="res_dg" style="height: 100%;width: 100%"
                           rownumbers="true"  singleSelect="true">
                        <thead>
                        <th field="test_item" width="90px;">
                            检测项目
                        </th>
                        <th field="test_basis" width="90px;">
                            检测依据
                        </th>
                        <th field="test_cnt" width="50px;" editor="{type:'text'}">
                            数量
                        </th>
                        <th field="price"    width="70px;">
                            收费标准
                        </th>
                        </thead>
                    </table>
                </div>
            </div>
        </div>

        <div class="panel-outside" >
            <div  class="easyui-panel" title="委托内容" style="height:160px;padding:10px;">
                <table style="width: 100%">
                    <tr>
                        <td>样品名称</td>
                        <td>
                            <input name="sample_name" id="sample_name" type="text"  class="easyui-validatebox textbox" value="<?php echo ($group_name[0]['item_name']); ?>" required="">
                        </td>
                        <td>规格/牌号</td>
                        <td>
                            <input name="sample_format" id="sample_format" class="easyui-combobox"
                                   valueField="value" textField="text" style="width: 150px;" panelHeight="auto">
                        </td>
                        <td>厂家/产地</td>
                        <td>
                            <input name="producing_area" type="text"  class="textbox">
                        </td>
                    </tr>
                    <tr>
                        <td>现场桩号/结构部位</td>
                        <td>
                            <input name="stake_mark" type="text"  class="easyui-validatebox textbox" required="">
                        </td>
                        <td >取样/成型日期</td>
                        <td>
                            <input name="molding_date" type="text" class="easyui-datebox" style="width:150px">
                        </td>
                        <td>取样地点</td>
                        <td>
                            <input name="sample_loca" type="text"  class="textbox" value="现场">
                        </td>
                    </tr>
                    <tr>
                    <tr>
                        <td>样品数量</td>
                        <td>
                            <input name="sample_cnt" id="sample_cnt" class="easyui-combobox"
                                   valueField="value" textField="text"panelHeight="auto" style="width: 150px;" >
                        </td>
                        <td >样品描述</td>
                        <td>
                            <input name="sample_desc" id="sample_desc" class="easyui-combobox"
                                   valueField="value" textField="text"panelHeight="auto" style="width: 150px;" >
                        </td>
                        <td>附加说明</td>
                        <td>
                            <input name="note" id="note" type="text"  class="textbox">
                        </td>
                    </tr>
                    <tr>
                        <td>代表数量</td>
                        <td>
                            <input name="delegate_cnt" type="text"  class=" textbox" style="width: 80px;float: left;">
                            <select id = "delegate_unit" style="width: 65px;" class="my-select">
                                <?php if(is_array($unitInfo)): $i = 0; $__LIST__ = $unitInfo;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><option value="<?php echo ($vo["name"]); ?>"><?php echo ($vo["name"]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
                            </select>
                        </td>
                        <td >生产批号</td>
                        <td>
                            <input name="make_num" class="textbox" />
                        </td>
                        <td >退样方式</td>
                        <td>
                            <select name="back_type" class="my-select" style="width: 150px;">
                                <option value="代为处理">代为处理</option>
                                <option value="客户自取">客户自取</option>
                                <option value="其他">其他</option>
                            </select>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
        <div class="panel-outside" style="height: 50px;margin-top: 20px;">
            <div style="float: left">
                <lable   > 截止日期：</lable>
                <input id="deadline" name="deadline" type="text" class="easyui-datebox" style="width:150px;">
            </div>
            <div style="float: left;margin-left: 30px;">
                <lable > 部门主任：</lable>
                <select name="assign_person" type="text" class="textbox" style="width:150px;">
                    <?php if(is_array($assign_person)): $i = 0; $__LIST__ = $assign_person;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><option value="<?php echo ($vo["id"]); ?>">
                            <?php echo ($vo["name"]); ?>
                        </option><?php endforeach; endif; else: echo "" ;endif; ?>
                </select>
            </div>
            <a  class="easyui-linkbutton" href=" /NBSystem/index.php/Home/Protocol/addPage/protocol_num/<?php echo ($protocol_num); ?>/type/1"
                data-options="iconCls:'icon-cancel'" style="height: 30px;float: right;width: 100px;">
                取消
            </a>
            <div id="save_btn" class="easyui-linkbutton" data-options="iconCls:'icon-ok'" style="height: 30px; float: right;width: 100px;">
                确认
            </div>
        </div>
    </form>
</div>


</body>
</html>