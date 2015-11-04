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
    var industry_type=<?php echo ($industry_type); ?>;
    var pnum='<?php echo ($p_num); ?>';
    var selectIndex=-1;
    $(function(){

        var buttons = $.extend([], $.fn.datebox.defaults.buttons);
        buttons.splice(1, 0, {
            text: '清空',
            handler: function(target){
                $(target).datebox("setValue","");
                $(this).closest("div.combo-panel").panel("close");
            }
        });

        $('.easyui-datebox').datebox({buttons: buttons});
        $(".datebox :text").attr("readonly","readonly");
        $('.easyui-datebox').datebox('setValue','<?php echo ($today); ?>');

        $('#p_dg').datagrid({
            url:"/NBSystem/index.php/Home/Protocol/getSample/protocol_num/<?php echo ($protocol_num); ?>",
          //  data:dataSample,
            onLoadSuccess:function(data){
                getPrice(data['rows']);
                if(data['total']==0){
                    $('#save_btn').linkbutton('disable');
                    $('#save_btn').unbind( "click" );
                }else{
                    $('#save_btn').click(function(){
                        saveBtnClick();
                    });
                }
            },
            onDblClickRow:function(rowIndex){
                if(selectIndex!=-1){
                    $('#p_dg').datagrid('endEdit',selectIndex);
                }
                $('#p_dg').datagrid('beginEdit',rowIndex);
                selectIndex=rowIndex;
            },
            onClickRow:function(rowIndex){
                if(selectIndex!=-1){
                    $('#p_dg').datagrid('endEdit',selectIndex);
                }
            }
        });


        if('<?php echo ($type); ?>'=='0'||"<?php echo ($type); ?>"=="3"){                    //区分新表单还是已经填写一部分的表单
            $('#save_btn').linkbutton('disable');
            $('#add_btn').linkbutton('disable');
            $('.easyui-datebox').datebox('setValue','<?php echo ($today); ?>');
            $('#protocol_num').val("T"+(new Date().getFullYear())+'-');
            $('#pro_btn').click(function(){
                proBtnClick();
            });
            $('#receive_person').val('<?php echo ($user_name); ?>');
        }else {
            $('#pro_btn').linkbutton('disable');
            $('#add_btn').click(function(){
                addBtnClick();
            });
        }
        $("#take_type").change(function(){
            if($(this).val()=="邮寄"){
                $('#mail_table').show();
            }else{
                $('#mail_table').hide();
            }
        });
        if($('#take_type').val()=="邮寄"){
            $('#mail_table').show();
        }else{
            $('#mail_table').hide();
        }

        //合同编号 选择事件绑定
        $("#client,#project_id,#inspected").combobox();
        $("#contract_num").combobox({
            onChange:function(newValue,oldValue){
               getInfoByContractNum(newValue);
             }
        });
        $("#contract_num").combobox("setText","");


        //添加上次的信息
        var protocol = <?php echo ($protocol); ?>;
        $('#fm').form('load',protocol);
        if(protocol != null){
            $("#client").combobox("setValue",protocol["client"]);
            $("#inspected").combobox("setValue",protocol["inspected"]);
            $("#project_id").combobox("setValue",protocol["project_id"]);
            $("#contract_num").combobox("setText",protocol["contract_num"]);
        }
    });

    function proBtnClick(){
        var temp=toFiveBit(pnum);
        var protocol_num="";
        var str=$('#protocol_num').val();
        if(str.charAt(0)=="T"){
            protocol_num=$('#protocol_num').val() + temp;
        }else {
            protocol_num=str;
        }
        $.post('/NBSystem/index.php/Home/Protocol/validProtocolNum',{protocol_num:protocol_num},function(data){
            if(data){
                $('#protocol_num').val(protocol_num);
                $('#add_btn').linkbutton('enable');
                $('#add_btn').click(function(){
                    addBtnClick();
                });
                $('#pro_btn').linkbutton('disable');
                $('#pro_btn').unbind('click');
                $('#protocol_num').attr("readonly","readonly");
            }else{
                $.messager.alert("操作提示",protocol_num+"协议书已存在");
            }
        });
    }
    function saveBtnClick(){
        if(!$('#fm').form('validate')) {
            return ;
        }
     //   debugger;
        if(selectIndex!=-1){
            $('#p_dg').datagrid('endEdit',selectIndex);
        }

        var obj=new Object();
        $.each($('#fm').serializeArray(),function(index,param){
            if(!(param.name in obj)){
                obj[param.name]=param.value;
            }
        });

        obj["client"]=$("#client").combobox("getText");
        obj["project_id"]=$("#project_id").combobox("getText");
        obj["inspected"]=$("#inspected").combobox("getText");
        obj["contract_num"]=$("#contract_num").combobox("getText");

        var notEmptyArr = ["witness_contact","send_person","witness_company","tel","witness_tel"];
        for(var i in notEmptyArr){
            if($("input[name='"+notEmptyArr[i]+"']").val() == ""){
                obj[notEmptyArr[i]] = "/"
            }
        }

        var rowData=$('#p_dg').datagrid('getData');
        obj["rows"]=rowData["rows"];


        var url='/NBSystem/index.php/Home/Protocol/submitProtocol/type/<?php echo ($type); ?>';
        if('<?php echo ($type); ?>'=='2'){
            url='/NBSystem/index.php/Home/Protocol/submitProtocol/type/<?php echo ($type); ?>/id/<?php echo ($id); ?>';
        }
        debugger;
        $.post(url,obj,function(id){
            if(id){
                if("<?php echo ($advance); ?>"=="1"){              //预委托提交页面跳转
                    location.href='/NBSystem/index.php/Home/Advance/submitSuccess/protocol_num/'+id;
                }
                else if('<?php echo ($type); ?>'=='2'){           //修改协议保存结束跳转
                    location.href='/NBSystem/index.php/Home/Query/protocolPage';
                }else if('<?php echo ($type); ?>'=='1'){
                    $('#save_btn').unbind("click");
                    $('#save_btn').linkbutton('disable');
                    $.messager.confirm("操作提示", "数据成功保存，打开【委托协议书】吗？", function (data) {
                        if (data) {
                            var iWidth=1200; //弹出窗口的宽度;
                            var iLeft = (window.screen.availWidth-10-iWidth)/2; //获得窗口的水平位置;
                            var iHeight=window.screen.availHeight-100; //弹出窗口的高度;
                            window.open("/NBSystem/index.php/Home/Progress/showProtocol/id/"+id+"/curr/0","protocol"+id,
                                    "height="+iHeight+",width=1200,left="+iLeft);
                        }
                        if("<?php echo ($protocol_step); ?>"!="1"){
                            location.href="/NBSystem/index.php/Home/Protocol/addPage";
                        }else if("<?php echo ($protocol_step); ?>"=="1"){
                            location.href='/NBSystem/index.php/Home/Query/pageAdvance';
                        }
                    });
                }
                showSuccess();
            }else{
               showFail();
            }
        });

    }
    function addBtnClick(){
        var rowData=$('#p_dg').datagrid('getData');
        var url="/NBSystem/index.php/Home/Sample/selectSample/type/"+$('#industry_type').val()
                        +'/protocol_num/'+$('#protocol_num').val();
        var data=serializeObject("#fm");
        data["client"]=$("#client").combobox("getText");
        data["project_id"]=$("#project_id").combobox("getText");
        data["inspected"]=$("#inspected").combobox("getText");
        data["contract_num"]=$("#contract_num").combobox("getText");
        $.post('/NBSystem/index.php/Home/Protocol/saveTempPro',data);
        location.href=url;


    }
    function getPrice(rowData){
        var rowNum=rowData.length;
        var sum=0;
        for(var i=0;i<rowNum;i++){
            sum+=parseFloat(rowData[i]['price']);
        }
        $('#price').val(sum);
    }
    function saveCookies(){

        var data={'witness_contact':$('input[name="witness_contact"]').val(),
            'send_person':$('input[name="send_person"]').val(),
            'witness_company':$('input[name="witness_company"]').val(),
            'witness_tel':$('input[name="witness_tel"]').val(),
            'tel':$('input[name="tel"]').val()
        };

        $.cookie('protocol_cookie',data,{ expires: 7, path: '/' }); // 存储 cookie

    }
    function delBtnClick(){

        var row=$('#p_dg').datagrid('getSelected');
        if(row==null){
            $.messager.confirm("操作提示", "请您先选择一行");
            return;
        }        var index=$('#p_dg').datagrid('getRowIndex',row);
        $.messager.confirm("操作提示", "确定删除样品"+row.sample_num+"吗？", function (data) {
            if (data) {
              //  $('#p_dg').datagrid('deleteRow',index);
                $('#p_dg').datagrid('load');
                $.post("/NBSystem/index.php/Home/Protocol/delSample",{id:row.id},function(res){
                    if(res) showSuccess();
                    else showFail();
                });
            }

        });
    }
    function getInfoByContractNum(newValue){
        var obj ={
            contract_num:newValue
        };
        $.post("/NBSystem/index.php/Home/Protocol/getInfoByContractNum",obj,function(data){
            $("#client").combobox("setValue",data["client_id"]);
            $("#inspected").combobox("setValue",data["inspected_id"]);
            $("#project_id").combobox("setValue",data["project_id"]);
            $("#send_person").val(data["contact"]);
            $("#tel").val(data["tel"]);
        });
    }
</script>
<style>
    .textbox{
        height: 20px;
        margin: 0px;
        padding: 0px 2px;
        box-sizing: content-box;
    }
    .my-btn-rignt{
        float: right;width: 100px;height: 30px;
    }
    .my-select{
        width: 150px;border-radius: 3px;height: 22px;border: solid 1px #aaaaaa;
    }
</style>
<div style="margin-left: auto;margin-right: auto;width: 1000px;">

    <form id="fm" action="/NBSystem/index.php/Home/Protocol/submitProtocol" method="post" >
        <div class="panel-outside" id="div11">
            <div  class="easyui-panel" title="委托内容" style="height:160px;width: 100%;">
                <table style="width: 100%;margin-top: 10px;">
                    <tr>
                        <td>协议书编号</td>
                        <td>
                            <input name="protocol_num" id="protocol_num"type="text"  class="textbox" style="width: 200px;float: left;" required=""
                            <?php if($type != 0): ?>readonly<?php endif; ?>>
                            <div id="pro_btn" class="easyui-linkbutton" data-options="iconCls:'icon-add'"style="width: 70px;height: 24px;float: left;">添加</div>
                        </td>
                        <td>协议日期</td>
                        <td><input name="date"type="text" class="easyui-datebox" style="width:150px"></td>
                        <td>行业类别</td>
                        <td>
                            <select name="industry_type" id="industry_type" class="my-select">
                                <?php if(is_array($industry)): $i = 0; $__LIST__ = $industry;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><option value="<?php echo ($vo["type"]); ?>"><?php echo ($vo["content"]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td>委托单位</td>
                        <td>
                            <select id="client"  name="client"  panelHeight="auto" style="width: 350px;" onselect="getContractNum()" required="">
                                <?php if(is_array($client)): $i = 0; $__LIST__ = $client;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><option value="<?php echo ($vo["id"]); ?>"><?php echo ($vo["company"]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
                            </select>
                        </td>
                        <td >检验类别</td>
                        <td>
                            <select name="check_type"   style="width: 150px;">
                                <option value="委托">委托</option>
                                <option value="抽检">抽检</option>
                                <option value="比对">比对</option>
                            </select>
                        </td>
                        <td>见证人</td>
                        <td> <input name="witness_contact" type="text"  class="textbox" style="width: 145px;"></td>
                    </tr>
                    <tr>
                    <tr>
                        <td>受检单位</td>
                        <td>
                            <select id="inspected" name="inspected"  panelHeight="auto" style="width: 350px;" required="">
                                <?php if(is_array($client)): $i = 0; $__LIST__ = $client;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><option value="<?php echo ($vo["id"]); ?>"><?php echo ($vo["company"]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
                            </select>
                        </td>
                        <td >送样人</td>
                        <td>
                            <input id="send_person"name="send_person" type="text"  class="textbox" style="width: 145px;">
                        </td>
                        <td>见证单位</td>
                        <td> <input name="witness_company" type="text"  class="textbox" style="width: 145px;"></td>
                    </tr>
                    <tr>
                        <td>工程名称</td>
                        <td>
                            <select id="project_id" name="project_id"  panelHeight="auto" style="width: 350px;" required="">
                                <?php if(is_array($project)): $i = 0; $__LIST__ = $project;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><option value="<?php echo ($vo["id"]); ?>"><?php echo ($vo["name"]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
                            </select>
                        </td>
                        <td >联系电话</td>
                        <td>
                            <input id="tel" name="tel" type="text"  class="textbox" style="width: 145px;">
                        </td>
                        <td>联系电话</td>
                        <td> <input name="witness_tel" type="text"  class="textbox" style="width: 145px;"></td>
                    </tr>
                    </tr>
                </table>
            </div>
        </div>
        <div class="panel-outside">
            <div  class="easyui-panel" title="检测报告" style="height:100px;width: 100%;" >
                <table style="width:45%;border-right: solid 1px #dddddd;float: left;margin-top: 10px;" >
                    <tr>
                        <td>报告提取方式</td>
                        <td>
                            <select id="take_type" name="take_type" class="my-select" style="width: 100px;">
                                <option value="自取">自取</option>
                                <option value="邮寄">邮寄</option>
                            </select>
                        </td>
                        <td >报告数量</td>
                        <td>
                            <input name="report_cnt" type="text"  class="easyui-textbox" style="width: 100px;" value="2"></td>
                        </td>

                    </tr>
                    <tr>
                        <td>是否做符合性声明</td>
                        <td>
                            <select name="is_conform" class="my-select" style="width: 100px;">
                                <option value="否">否</option>
                                <option value="是">是</option>
                            </select>
                        </td>

                    </tr>
                    </tr>
                </table>
                <table id="mail_table" style="width: 47%;float: left;margin-left: 20px;margin-top: 10px;" >
                    <tr>
                        <td style="width: 15%;">收件人</td>
                        <td style="width: 25%;"><input name="mail_contact" type="text"  class="textbox" style="width: 90%;" ></td>

                        <td style="width: 15%;">邮编</td>
                        <td style="width: 30%;"><input name="mail_number" type="text"  class="textbox" style="width: 90%;" ></td>
                    </tr>
                    <tr>
                        <td >联系电话</td>
                        <td><input name="mail_tel" type="text"  class="textbox" style="width: 90%;" ></td>
                        <td  colspan="1">邮寄地址</td>
                        <td colspan="3"> <input name="mail_address" type="text"  class="textbox" style="width: 90%;" ></td>

                    </tr>
                </table>
            </div>
        </div>

        <div class="panel-outside" >
            <div >
            <div style="float: left;width: 85%;">
    <table id="p_dg" style="width:100%;height: 180px;"
           rownumbers="true"  singleSelect="true"
           title="样品列表"
           pageSize="<?php echo ($pageSize); ?>" pageList="<?php echo ($pageList); ?>">
        <thead>
        <th width="100px" field ="sample_num" >样品编号</th>
        <th width="100px" field ="sample_name" editor="{type:'text'}">样品名称</th>
        <th width="100px" field ="test_item"   editor="{type:'text'}">检测项目</th>
        <th width="100px" field ="test_detail"   editor="{type:'text'}">检测项目(详细)</th>
        <th width="100px" field ="test_basis"  editor="{type:'text'}">检测依据</th>
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

            <div style="width: 15%; float: left;height: 200px;overflow: hidden;">
                <a  id="add_btn" class="easyui-linkbutton" style="width: 100px;height: 30px;float: right;" >添加样品</a>
                <div id="del_btn" class="easyui-linkbutton"  onclick="delBtnClick()"
                     style="width: 100px;height: 30px;margin-top: 15px;float: right;">删除样品</div>
                <div style="width: 100px;margin-top: 15px;float: right;">
                    <div style="width: 100px;height: 90px;" class="easyui-panel" title="试验费用">
                        <input name="price" id="price" style="width: 70px;margin-left: 10px; margin-top: 15px;
                border-radius: 3px;height: 25px;border: 1px solid #999999;padding-left: 5px;" readonly>
                    </div>
                </div>
            </div>
        </div>
        <div style="width: 100%;height: 200px;">
            <div style="float: left">
                <div class="my-lable">合同编号：</div>
                <select id="contract_num" name="contract_num"  style="float: left" panelHeight ="auto" >
                    <?php if(is_array($charge)): $k = 0; $__LIST__ = $charge;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($k % 2 );++$k;?><option value="<?php echo ($vo["contract_num"]); ?>" ><?php echo ($vo["contract_num"]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
                </select>
            </div>
            <?php if($advance != 1): ?><div class="my-lable" style="margin-left: 10px;">收样人：</div>
                <select id="receive_person" name="receive_person" class="my-select" style="float: left;height: 25px; padding: 3px;">
                    <option value="" ></option>
                    <?php if(is_array($receive)): $i = 0; $__LIST__ = $receive;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><option value="<?php echo ($vo["name"]); ?>" ><?php echo ($vo["name"]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
                </select><?php endif; ?>

            <?php if($type == 2): ?><a  href='/NBSystem/index.php/Home/Query/protocolPage' class="easyui-linkbutton my-btn-right" data-options="iconCls:'icon-back'"  >取消</a>
                <?php elseif($type == 1 and $protocol_step == 1): ?>
                <a  href='/NBSystem/index.php/Home/Query/pageAdvance' class="easyui-linkbutton my-btn-right" data-options="iconCls:'icon-back'"  >取消</a>
                <?php else: ?>
                <a  href='/NBSystem/index.php/Home/Protocol/addPage' class="easyui-linkbutton my-btn-right" data-options="iconCls:'icon-back'"  >取消</a><?php endif; ?>

            <div id="save_btn"class="easyui-linkbutton my-btn-right" data-options="iconCls:'icon-save'">保存</div>
        </div>
    </form>
</div>


</body>
</html>