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
<style>

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
<div class="panel-outside"  >
    <div class="easyui-panel" title="样品详细信息文件更新"style="height: 120px;width: 700px;">
        <div style="width: 500px;height: 50px;margin-top: 30px;margin-left: 50px;">
            <form
                 <?php if($type == 0): ?>action="/index.php/Home/System/saveFile"
                     <?php else: ?>
                    action="/index.php/Home/System/uploadSampleInfo"<?php endif; ?>
                  enctype="multipart/form-data" method="post" >
            <input class="easyui-filebox" name="file" data-options="prompt:'选择文件...'" style="width:300px">
            <input type="submit" value="上传"  class="textbox c6" style="width: 80px;"/>
            </form>
        </div>
    </div>
</div>

<div class="panel-outside" style="position: fixed;height: 70%;">

        <table id="dg"  style="width: 700px;;min-height: 90%;"
               class="easyui-datagrid" url="/index.php/Home/System/getFileList/type/<?php echo ($type); ?>"
               toolbar="#toolbar" pagination="true"
               rownumbers="true" fitColumns="true"
               singleSelect="true" pageSize="<?php echo ($pageSize); ?>" pageList="<?php echo ($pageList); ?>" resizable="true">
            <thead>
            <tr >
                <th field="name">文件名称</th>
                <th field="username">上传者</th>
                <th field="file_date" >上传日期</th>
            </tr>
            </thead>
        </table>

</div>



</body>
</html>