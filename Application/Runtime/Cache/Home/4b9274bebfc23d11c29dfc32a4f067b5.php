<?php if (!defined('THINK_PATH')) exit();?>
<!DOCTYPE html>
<html>
<head lang="en">
    <meta charset="UTF-8">
    <title><?php echo ($company); ?></title>
    <link rel="stylesheet" type="text/css" href="/Public/css/default.css">
    <link rel="stylesheet" type="text/css" href="/Public/easyui/themes/icon.css">
    <link rel="stylesheet" type="text/css" href="/Public/easyui/themes/bootstrap/easyui.css">
    <link rel="stylesheet" type="text/css" href="/Public/easyui/themes/color.css">
    <link rel="stylesheet" type="text/css" href="/Public/easyui/demo/demo.css">


    <script type="text/javascript" src="/Public/js/jquery-1.4.4.min.js"></script>
    <script type="text/javascript" src="/Public/easyui/jquery.easyui.min.js"></script>
    <script type="text/javascript" src="/Public/easyui/locale/easyui-lang-zh_CN.js"></script>
    <script type="text/javascript" src="/Public/js/base.js"></script>
    <style>
        .panel{top:0px;}
    </style>
</head>
<body style="margin: 0px;padding: 0px;">

<script language="javascript" type="text/javascript">
    $(function(){
        var height=$(window).height();
        $("#div_page").css("height",height+"px");
    });
    function SaveDocument() {
        document.getElementById("PageOfficeCtrl1").WebSave();
    }

    //显示痕迹
    function ShowRevisions() {
        document.getElementById("PageOfficeCtrl1").ShowRevisions = true;
    }

    //隐藏痕迹
    function HiddenRevisions() {
        document.getElementById("PageOfficeCtrl1").ShowRevisions = false;
    }

    //领导圈阅签字
    function StartHandDraw() {
        document.getElementById("PageOfficeCtrl1").HandDraw.SetPenWidth(5);
        document.getElementById("PageOfficeCtrl1").HandDraw.Start();
    }
    // 插入键盘批注
    function StartRemark() {
        var appObj = document.getElementById("PageOfficeCtrl1").WordInsertComment();
    }
    //分层显示手写批注
    function ShowHandDrawDispBar() {
        document.getElementById("PageOfficeCtrl1").HandDraw.ShowLayerBar(); ;
    }

    //全屏/还原
    function IsFullScreen() {
        document.getElementById("PageOfficeCtrl1").FullScreen = !document.getElementById("PageOfficeCtrl1").FullScreen;
    }

    //显示标题
    function ShowTitle() {
        alert("该菜单的标题是：" + document.getElementById("PageOfficeCtrl1").caption);
    }
    function InsertSeal() {
        try {
            document.getElementById("PageOfficeCtrl1").ZoomSeal.AddSeal("<?php echo ($name); ?>");
        }
        catch (e) { }
    }
    function AddHandSign() {
        document.getElementById("PageOfficeCtrl1").ZoomSeal.AddHandSign("<?php echo ($name); ?>");
    }
    function VerifySeal() {
        document.getElementById("PageOfficeCtrl1").ZoomSeal.VerifySeal();
    }
    function ChangePsw() {
        document.getElementById("PageOfficeCtrl1").ZoomSeal.ShowSettingsBox();
    }
</script>
<?php if($type == 10): ?><div style="width:100%;height: 35px;background: #000000">
    <div style="width: 1000px;margin-left: auto;margin-right: auto;padding-top: 5px;">
        <div id="next" style="float: right" class="easyui-linkbutton" iconCls="icon-redo" >下一页</div>
        <div id="pre" style="float: right;margin-right: 20px;" class="easyui-linkbutton" iconCls="icon-undo">上一页</div>
        <div style="font-size: 15px;color: white;">一共<?php echo ($total); ?>页:当前第<?php echo ($curr); ?>页</div>
    </div>
</div>
<script>
    $(function(){
        if('<?php echo ($next); ?>'=='-4'){
            $('#next').linkbutton('disable');
        }else{
            $('#next').click(function(){
                location.href="/index.php/Home/Progress/showProtocol/id/<?php echo ($id); ?>/curr/<?php echo ($next); ?>";
            })
        }
        if('<?php echo ($pre); ?>'=='-4'){
            $('#pre').linkbutton('disable');
        }else{
            $('#pre').click(function(){
                location.href="/index.php/Home/Progress/showProtocol/id/<?php echo ($id); ?>/curr/<?php echo ($pre); ?>";
            })
        }
    });
</script>
    <?php elseif($step == 2 or $step == 3 or $step == 4): ?>
    <style>
    #header {
        background:#28333c; color:White;  float:left; width:100%;height: 100px;;
    }

</style>
<script>
    var id="<?php echo ($id); ?>";
    $(function(){
        $('#btn_pass').click(function(){
            passTest();
        });
        $('#btn_refuse').click(function(){
            refuseTest();
        });
        $('#btn_finish').click(function(){
            finishTest();
        });
    });
    function passTest(){

        if(confirm("确认完成试验么？")) {
            var step;
            if("<?php echo ($step); ?>"=="3") step=4;
            else if("<?php echo ($step); ?>"==4) step=5;
            $.post("/index.php/Home/Progress/refreshStep",{ids:[id],step:step},function(data){
                if(data) {
                    showSuccess();
                } else {
                    showFail();
                }
            });
        }

    }
    function refuseTest(){

        if("确认驳回报告么？"){
            $.post("/index.php/Home/Progress/refreshStep",{ids:[id],step:2,refuse_note:"详见报告"},function(data){
                if(data) {
                    showSuccess();
                } else {
                    showFail();
                }
            });
        }

    }
    function finishTest(){
        if(confirm("确认完成报告，并且交给"+$("#check_person").find("option:selected").text()+"审核？")){
            $.post("/index.php/Home/Progress/refreshStep",{ids:[id],step:3,check_person:$("#check_person").val()},function(data){
                if(data) {
                    showSuccess();
                } else {
                    showFail();
                }
            });
        }
    }
    function toPass(){
        if(confirm("确认完成报告，并且交给"+$("#passer").val()+"审核？")){
            $.post("/index.php/Home/Progress/refreshStep",{ids:[id],step:3,passer:$("#passer").val()},function(data){
                if(data) {
                    showSuccess();
                } else {
                    showFail();
                }
            });
        }
    }
</script>
<div id="header">
    <div style="width: 1000px;margin-right: auto;margin-left: auto;margin-top:10px; ">
        <div style="height: 25px;">
            <div style="width: 300px;float: left"><span style="font-weight: 600;font-size: 15px;">文章名称：</span><?php echo ($sample_num); ?>检验报告 </div>
            <div style="width: 400px;float: right;height: 30px;">
                <?php if($step == 2): ?><!--  <label for="check_person" style="margin-left: 10px;">选择审批人员:</label>
                    <select id="check_person" name="check_person"  class="my-select" style="margin-left: 10px;margin-top: 3px;">
                        <?php if(is_array($checker)): $i = 0; $__LIST__ = $checker;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><option value="<?php echo ($vo["id"]); ?>"><?php echo ($vo["name"]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
                    </select>
                    <a  id="btn_finish" class="easyui-linkbutton c1" iconCls="icon-ok " style="float: right">完成试验</a>--><?php endif; ?>

                <?php if($step == 3 or $step == 4): ?><!--   <a  id="btn_refuse" class="easyui-linkbutton c6" iconCls="icon-no" style="float: right;">驳回报告</a>
                    <a  id="btn_pass" class="easyui-linkbutton c1" iconCls="icon-ok " style="float: right">通过报告</a>--><?php endif; ?>
                <?php if($step == 3): ?><!--  <div style="float: right;width: 200px;">
                        <label for="passer" style="margin-left: 10px;">选择批准人员:</label>
                        <select id="passer" class="my-select" style="margin-left: 10px;margin-top: 3px;">
                            <?php if(is_array($passer)): $i = 0; $__LIST__ = $passer;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><option value="<?php echo ($vo["name"]); ?>"><?php echo ($vo["name"]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
                        </select>
                    </div>--><?php endif; ?>
            </div>
        </div>

        <div style="width: 1000px;word-break: break-all; word-wrap:break-word;clear: left;margin-top: 10px;">
            <span style="font-weight: 600;font-size: 15px;">文件流转：</span>
            <?php if(is_array($progress)): $k = 0; $__LIST__ = $progress;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($k % 2 );++$k; if($k != 1): ?>-><?php endif; ?>
                <?php echo ($vo["content"]); endforeach; endif; else: echo "" ;endif; ?>
        </div>
    </div>
</div>
    <?php elseif($type == 40): endif; ?>
<div id="div_page"  style="width: 1000px;margin-right: auto;margin-left: auto;" >
    <?php echo $PageOfficeCtrl->getDocumentView("PageOfficeCtrl1") ?>
</div>
</body>
</html>