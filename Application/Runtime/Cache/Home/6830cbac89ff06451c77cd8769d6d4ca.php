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
<script type="text/javascript" src="/NBSystem/Public/js/ajaxfileupload.js"></script>
<style> .panel{float: left}</style>
<div style="height: 95%;;width: 96%;position: fixed;">
    <table id="dg" title="账户管理" class="easyui-datagrid easyui-resizable"
           style="width:100%;min-height: 85%;;float: left;"

           url="/NBSystem/index.php/Home/User/dateList"
           toolbar="#toolbar"
           pagination="true"
           rownumbers="true"
           fitColumns="true"
           singleSelect="true"
           pageSize="<?php echo ($pageSize); ?>"
           pageList="<?php echo ($pageList); ?>"
           resizable="true"
            >
        <thead>
        <tr >
            <th field="account"  sortable="true">账户名</th>
            <th field="password" sortable="true" formatter="password_fm">密码</th>
            <th field="tel"  sortable="true">联系电话</th>
            <th field="name"  sortable="true">姓名</th>
            <th field="department_name" >部门</th>
            <th field="p1"  sortable="true" formatter="permission_fm">收发人员</th>
            <th field="p2"  sortable="true" formatter="permission_fm">部门主任</th>
            <th field="p3"  sortable="true" formatter="permission_fm">试验人员</th>
            <th field="p4"  sortable="true" formatter="permission_fm">审核人员</th>
            <th field="p5"  sortable="true" formatter="permission_fm">批准人员</th>
            <th field="p6"  sortable="true" formatter="permission_fm">财务人员</th>
            <th field="p7"  sortable="true" formatter="permission_fm">管理人员</th>
            <th field="p9"  sortable="true" formatter="permission_fm">系统管理员</th>
        </tr>
        </thead>
    </table>
</div>
<form id="toolbar">
    <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-add" plain="true" onclick="newUser()">新的账号</a>
    <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-edit" plain="true" onclick="editUser()">修改信息</a>
    <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-edit" plain="true" onclick="modifyPw()">修改密码</a>
    <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-remove" plain="true" onclick="destroyUser()">删除账号</a>
    <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-search" plain="true" onclick="searchClick()">搜索</a>

    <table style="width: 700px;">
        <tr>
            <td style="width: 50px;">姓名：</td>
            <td><input name="name" class="textbox"></td>
            <td style="width: 50px;">&nbsp;&nbsp;部门：</td>
            <td>
                <select  name="department" class="my-select" >
                    <option value="">全部</option>
                    <?php if(is_array($dpt)): $i = 0; $__LIST__ = $dpt;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><option value="<?php echo ($vo["id"]); ?>"><?php echo ($vo["name"]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
                </select>.
            </td>
            <td style="width: 70px">人员类别：</td>
            <td>
                <select  name="permission" class="my-select"  >
                    <option value="">全部</option>
                    <option value="1">收发人员</option>
                    <option value="2">部门主任</option>
                    <option value="3">试验人员</option>
                    <option value="4">审核人员</option>
                    <option value="5">批准人员</option>
                    <option value="6">财务人员</option>
                    <option value="7">管理人员</option>
                    <option value="9">系统管理员</option>
                </select>.
            </td>
        </tr>
    </table>
</form>

<div id="new_dlg" class="easyui-dialog  " style="width:400px;height:450px;padding:10px 20px"
     closed="true" buttons="#dlg-buttons3">
    <div class="ftitle">账号信息</div>
    <form id="new_fm" method="post" novalidate enctype="multipart/form-data" >
        <div class="fitem">
            <label>账户名:</label>
            <input id="account"name="account" class="easyui-validatebox textbox" style="height: 20px;">
        </div>
        <div class="fitem">
            <label>密码:</label>
            <input id="password"type="password"name="password" class="easyui-validatebox textbox"
                   style="height: 20px;" oninput="$('#repassword').validatebox('validate')">
        </div>
        <div class="fitem">
            <label>密码确认:</label>
            <input id="repassword"type="password" class="easyui-validatebox textbox" style="height: 20px;" >
        </div>
        <div class="fitem">
    <label>联系电话:</label>
    <input name="tel" class="textbox" >
</div>
<div class="fitem">
    <label>姓名:</label>
    <input id="name" name="name" class="easyui-validatebox  textbox" required="true">
</div>
<div class="fitem">
    <label>部门:</label>
    <select  name="department" class="  textbox"  style="padding-right: 20px;" >
        <?php if(is_array($dpt)): $i = 0; $__LIST__ = $dpt;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><option value="<?php echo ($vo["id"]); ?>"><?php echo ($vo["name"]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
    </select>
</div>
<div class="fitem">
    <div style="float: left;width: 80px;">权限:</div>
    <table>
        <tr>
            <td>收发人员</td>
            <td><input name="p1" value="1" type="checkbox" style="width: 30px;"></td>
            <td>部门主任</td>
            <td><input name="p2" value="1" type="checkbox" style="width: 30px;"></td>
        </tr>

        <tr>
            <td>试验人员</td>
            <td><input name="p3" value="1" type="checkbox" style="width: 30px;"></td>
            <td>审核人员</td>
            <td><input name="p4" value="1" type="checkbox" style="width: 30px;"></td>
        </tr>
        <tr>
            <td>批准人员</td>
            <td><input name="p5" value="1" type="checkbox" style="width: 30px;"></td>
            <td>财务人员</td>
            <td><input name="p6" value="1" type="checkbox" style="width: 30px;"></td>
        </tr>
        <tr>
            <td>管理人员</td>
            <td><input name="p7" value="1" type="checkbox" style="width: 30px;"></td>
            <td>系统管理员</td>
            <td><input name="p9" value="1" type="checkbox" style="width: 30px;"></td>
        </tr>
    </table>
</div>
        <div class="fitem">
            <label>签名:</label>
            <input id="add_file" type="file" name="add_file" value="选择签名文件" style="width:210px;">
        </div>
    </form>
</div>
<div id="dlg-buttons3">
    <a href="javascript:void(0)" class="easyui-linkbutton c6" iconCls="icon-ok" onclick="saveNewUser()" style="width:90px">保存</a>
    <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-cancel" onclick="javascript:$('#new_dlg').dialog('close')"
       style="width:90px">取消</a>
</div>
<div id="modify_pw_dlg" class="easyui-dialog  " style="width:300px;height:300px;padding:10px 20px"
     closed="true" buttons="#dlg-buttons1">
    <div class="ftitle">修改密码</div>
    <form id="modify_pw_fm" method="post" enctype="multipart/form-data" method="post" novalidate>

        <div class="fitem">
            <label>新的密码:</label>
            <input id="m_password" name="password"type="password" class="easyui-validatebox textbox" required=""
                   style="height: 20px;" oninput="$('#m_repassword').validatebox('validate')">
        </div>
        <div class="fitem">
            <label>密码确认:</label>
            <input id="m_repassword" type="password" class="easyui-validatebox textbox"required="" style="height: 20px;" >
        </div>
    </form>
</div>
<div id="dlg-buttons1">
    <a href="javascript:void(0)" class="easyui-linkbutton c6" iconCls="icon-ok" onclick="savePW()" style="width:90px">保存</a>
    <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-cancel" onclick="javascript:$('#modify_pw_dlg').dialog('close')"
       style="width:90px">取消</a>
</div>
<div id="modify_if_dlg" class="easyui-dialog  " style="width:400px;height:500px;padding:10px 20px"
     closed="true" buttons="#dlg-buttons2">
    <div class="ftitle">修改账号信息</div>
    <form id="modify_if_fm" method="post" novalidate>
        <div class="fitem">
    <label>联系电话:</label>
    <input name="tel" class="textbox" >
</div>
<div class="fitem">
    <label>姓名:</label>
    <input id="name" name="name" class="easyui-validatebox  textbox" required="true">
</div>
<div class="fitem">
    <label>部门:</label>
    <select  name="department" class="  textbox"  style="padding-right: 20px;" >
        <?php if(is_array($dpt)): $i = 0; $__LIST__ = $dpt;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><option value="<?php echo ($vo["id"]); ?>"><?php echo ($vo["name"]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
    </select>
</div>
<div class="fitem">
    <div style="float: left;width: 80px;">权限:</div>
    <table>
        <tr>
            <td>收发人员</td>
            <td><input name="p1" value="1" type="checkbox" style="width: 30px;"></td>
            <td>部门主任</td>
            <td><input name="p2" value="1" type="checkbox" style="width: 30px;"></td>
        </tr>

        <tr>
            <td>试验人员</td>
            <td><input name="p3" value="1" type="checkbox" style="width: 30px;"></td>
            <td>审核人员</td>
            <td><input name="p4" value="1" type="checkbox" style="width: 30px;"></td>
        </tr>
        <tr>
            <td>批准人员</td>
            <td><input name="p5" value="1" type="checkbox" style="width: 30px;"></td>
            <td>财务人员</td>
            <td><input name="p6" value="1" type="checkbox" style="width: 30px;"></td>
        </tr>
        <tr>
            <td>管理人员</td>
            <td><input name="p7" value="1" type="checkbox" style="width: 30px;"></td>
            <td>系统管理员</td>
            <td><input name="p9" value="1" type="checkbox" style="width: 30px;"></td>
        </tr>
    </table>
</div>
        <div class="fitem">
            <label>签名:</label>
            <input id="modify_file" name="modify_file" type="file" value="选择签名文件" style="width:210px;">
        </div>
        <img id="seal_img"   style="margin-left: 85px;"/>
    </form>
</div>
<div id="dlg-buttons2">
    <a href="javascript:void(0)" class="easyui-linkbutton c6" iconCls="icon-ok" onclick="saveUser()" style="width:90px">保存</a>
    <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-cancel" onclick="javascript:$('#modify_if_dlg').dialog('close')"
       style="width:90px">取消</a>
</div>
<script type="text/javascript">

    $(document).ready(function(){
        $("#dg").datagrid({ onDblClickRow:function(){editUser()}});
        $('#account').validatebox({
            required:true,
            validType:["remote['/NBSystem/index.php/Home/User/validUser','account']",],
            invalidMessage:'账号被注册！'
        });
        $('#repassword').validatebox({
            required:true,
            validType:'repassword["1"]'
        });
        $('#password').validatebox({
            required:true,
            validType:'length[6,15]',
            invalidMessage:'密码长度在6-15之间！'
        });
        $('#m_repassword').validatebox({
            required:true,
            validType:'m_repassword["1"]'
        });
        $('#m_password').validatebox({
            required:true,
            validType:'length[6,15]',
            invalidMessage:'密码长度在6-15之间！'
        });
    /*    $('#name').validatebox({
            required:true,
            validType:["remote['/NBSystem/index.php/Home/User/validName','name']"],
            invalidMessage:'姓名已存在'
        });*/

    });
    $.extend($.fn.validatebox.defaults.rules,{
        repassword:{
            validator:function(v,p){
                return v==$('#password').val();
            },message:'密码不一致！'
        },
        m_repassword:{
            validator:function(v,p){
                return v==$('#m_password').val();
            },message:'密码不一致！'
        }
    });
    function password_fm(value,row,index){
        return '*******';
    }
    function permission_fm(value,row,index){
        if(value==0) return "<span style='color: limegreen'>否</span>";
        if(value==1) return "<span style='color: red'>是</span>";

    }
    function searchClick(){
        var data=serializeObject("#toolbar");

        $("#dg").datagrid("load",data);
    }
    var url,modify;
    function newUser(){

        $('#new_dlg').dialog('open').dialog('setTitle','添加账户');
        $('#new_fm').form('clear');
        $('#new_fm [name="permission"]').val(1);
        url = '/NBSystem/index.php/Home/User/insert';
    }
    function modifyPw(){
        var row = $('#dg').datagrid('getSelected');
        if (row){
            $('#modify_pw_dlg').dialog('open').dialog('setTitle','修改密码');
            $('#modify_pw_fm').form('load',row);
            url = '/NBSystem/index.php/Home/User/modifyAccount/id/'+row.id;
        }
        $('#m_repassword').val('');
        $('#m_password').val('');
    }
    function savePW(){
        //   alert(url);
        $('#modify_pw_fm').form('submit',{
            url: url,
            onSubmit: function(){
                return $(this).form('validate');
            },
            success: function(result){
                $('#modify_pw_dlg').dialog('close');        // close the dialog
                $('#dg').datagrid('reload');

            }
        });
    }
    function editUser(){

        var row = $('#dg').datagrid('getSelected');
        if (row){
            $('#modify_if_dlg').dialog('open').dialog('setTitle','修改账户');
            $('#modify_if_fm').form('load',row);
            $("#seal_img").attr("src","/NBSystem/Public/File/seal/"+row.filename);
            url = '/NBSystem/index.php/Home/User/modifyAccount/id/'+row.id;
          /*  $('#m_name').validatebox({
                required:true,
                validType:["remote['/NBSystem/index.php/Home/User/validName/id/"+row.id+"','name']"],
                invalidMessage:'姓名已存在'
            });*/
        }
    }


    function saveUser(){

        if($('#modify_if_fm').form('validate')){
           var fn=function(data) {

                   $('#modify_if_dlg').dialog('close');
                   $('#dg').datagrid('reload');
           }
            var data1=serializeObject('#modify_if_fm');
            var row=$("#dg").datagrid("getSelected");
            var id=row["id"];
            $.post(url,data1,function(data){
                if($("#modify_file").val()=="") {
                    $('#modify_if_dlg').dialog('close');
                    $('#dg').datagrid('reload');
                }
                $.ajaxFileUpload({
                    url:"/NBSystem/index.php/Home/User/uploadSeal/file/modify_file/id/"+id,
                    type: 'post',
                    secureuri: false,               //一般设置为false
                    fileElementId: "modify_file", // 上传文件的id、name属性名
                    dataType: 'json',               //返回值类型，一般设置为json、application/json
                    success:fn
                });
            });
        }
    }


    function saveNewUser(){
       if(!$("#new_fm").form('validate'))return ;
        var  data1=$("#new_fm").serializeArray();
        var fn=function (data){

                $('#new_dlg').dialog('close');        // close the dialog
                $('#dg').datagrid('reload');
        };
        $.post(url,data1,function(id){
            if($("#add_file").val()=="") {
                $('#new_dlg').dialog('close');        // close the dialog
                $('#dg').datagrid('reload');
            }
            $.ajaxFileUpload({
                url:"/NBSystem/index.php/Home/User/uploadSeal/file/add_file/id/"+id,
                type: 'post',
                secureuri: false,               //一般设置为false
                fileElementId: "add_file", // 上传文件的id、name属性名
                dataType: 'json',               //返回值类型，一般设置为json、application/json
                success:fn
            });
        })


    }
    function destroyUser(){
        var row = $('#dg').datagrid('getSelected');

        if (row){
            if (row.is_admin==1){
                $.messager.alert('操作提示','<span style="color: red">该账号不能被删除！！</span>');
                return;
            }
            $.messager.confirm('操作确认','确定删除'+row.account+'?',function(r){
                if (r){
                    $.post('/NBSystem/index.php/Home/User/delete',
                            {id:row.id},function (data, textStatus){
                                $('#dg').datagrid('reload');
                            }, 'json');
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
        width:160px;
    }
</style>


</body>
</html>