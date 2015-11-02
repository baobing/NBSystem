<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
<head lang="en">
    <meta charset="UTF-8">
    <link href="/NBSystem/Public/Flat-UI/dist/css/flat-ui.css" rel="stylesheet">
    <script type="text/javascript" src="/NBSystem/Public/js/jquery-1.4.4.min.js"></script>
    <script type="text/javascript" src="/NBSystem/Public/js/jquery.validate.js"></script>
    <title><?php echo ($company); ?></title>
    <style>
        .hp-body{width: 350px;margin-left: auto;margin-right: auto;margin-top: 100px;}
        .login-div{width:350px;height: 220px;float: right;border: #777777 1px solid;border-radius: 10px;padding: 10px;}
        .slide-img{width: 150px;height: 150px;float: left;margin-left: 50px;margin-top: 50px;}
        .login-div >div{width: 400px;height: 30px;line-height: 30px;margin-top: 10px;}
        .login-div span{width: 70px;display: block;float: left;margin-left: 30px;color: #6a6a6a}
        .login-div input{float: left;width: 150px;padding: 3px;height: 20px;}
        .login-div .submit-btn{width: 90px;height: 30px;line-height: 30px;margin-right:140px;float: right;margin-top: 15px; }
        /*  .error-label{background: url("/NBSystem/Public/Baobing/easyui-master/themes/icons/no.png") no-repeat ;}*/
        .login-div span{width: 70px;display: block;float: left;margin-left: 30px;color: #6a6a6a}
        span .error{
            background:url("/NBSystem/Public/easyui/themes/icons/no.png") no-repeat 0px 0px;
            padding-left: 16px;
        }
    </style>
    <style>

        .login-div >div{width: 400px;height: 30px;line-height: 30px;margin-top: 10px;}
        .login-div span{width: 70px;display: block;float: left;margin-left: 30px;color: #6a6a6a}
        .login-div input{float: left;width: 150px;padding: 3px;height: 20px;}
        .login-div .submit-btn{width: 90px;height: 30px;line-height: 30px;margin-right:140px;float: right;margin-top: 15px; }
        .login-div span{width: 70px;display: block;float: left;margin-left: 30px;color: #6a6a6a}
        span .error{
            background:url("/NBSystem/Public/easyui-master/themes/icons/no.png") no-repeat 0px 0px;
            padding-left: 16px;
        }


        /*--------------------*/

        #login_form
        {
            background-color: #fff;
            background-image: -webkit-gradient(linear, left top, left bottom, from(#fff), to(#eee));
            background-image: -webkit-linear-gradient(top, #fff, #eee);
            background-image: -moz-linear-gradient(top, #fff, #eee);
            background-image: -ms-linear-gradient(top, #fff, #eee);
            background-image: -o-linear-gradient(top, #fff, #eee);
            background-image: linear-gradient(top, #fff, #eee);
            height: 240px;
            width: 400px;
            margin: 50px 0 0 -230px;
            padding: 30px;
            position: absolute;

            left: 50%;
            z-index: 0;
            -moz-border-radius: 3px;
            -webkit-border-radius: 3px;
            border-radius: 3px;
            -webkit-box-shadow:
            0 0 2px rgba(0, 0, 0, 0.2),
            0 1px 1px rgba(0, 0, 0, .2),
            0 3px 0 #fff,
            0 4px 0 rgba(0, 0, 0, .2),
            0 6px 0 #fff,
            0 7px 0 rgba(0, 0, 0, .2);
            -moz-box-shadow:
            0 0 2px rgba(0, 0, 0, 0.2),
            1px 1px   0 rgba(0,   0,   0,   .1),
            3px 3px   0 rgba(255, 255, 255, 1),
            4px 4px   0 rgba(0,   0,   0,   .1),
            6px 6px   0 rgba(255, 255, 255, 1),
            7px 7px   0 rgba(0,   0,   0,   .1);
            box-shadow:
            0 0 2px rgba(0, 0, 0, 0.2),
            0 1px 1px rgba(0, 0, 0, .2),
            0 3px 0 #fff,
            0 4px 0 rgba(0, 0, 0, .2),
            0 6px 0 #fff,
            0 7px 0 rgba(0, 0, 0, .2);
        }
        #login_form:before
        {
            content: '';
            position: absolute;
            z-index: -1;
            border: 1px dashed #ccc;
            top: 5px;
            bottom: 5px;
            left: 5px;
            right: 5px;
            -moz-box-shadow: 0 0 0 1px #fff;
            -webkit-box-shadow: 0 0 0 1px #fff;
            box-shadow: 0 0 0 1px #fff;
        }

        /*--------------------*/
        h1
        {
            text-shadow: 0 1px 0 rgba(255, 255, 255, .7), 0px 2px 0 rgba(0, 0, 0, .5);
            text-transform: uppercase;
            text-align: center;
            color: #666;
            margin: 0 0 30px 0;
            letter-spacing: 4px;
            font: normal 26px/1 Verdana, Helvetica;
            position: relative;
        }

        /*--------------------*/

        fieldset
        {
            border: 0;
            padding: 0;
            margin: 0;
        }

        #inputs input
        {
            padding: 15px 15px 15px 30px;
            margin: 0 0 10px 0;
            width: 323px; /* 353 + 2 + 45 = 400 */
            border: 1px solid #ccc;
            -moz-border-radius: 5px;
            -webkit-border-radius: 5px;
            border-radius: 5px;
            -moz-box-shadow: 0 1px 1px #red inset, 0 1px 0 #fff;
            -webkit-box-shadow: 0 1px 1px #ccc inset, 0 1px 0 #fff;
            box-shadow: 0 1px 1px #ccc inset, 0 1px 0 #fff;
        }

        #account
        {
            background-position: 5px -2px !important;
        }

        #password
        {
            background-position: 5px -52px !important;
        }

        #inputs input:focus
        {
            background-color: #fff;
            border-color: #e8c291;
            outline: none;
            -moz-box-shadow: 0 0 0 1px #e8c291 inset;
            -webkit-box-shadow: 0 0 0 1px #e8c291 inset;
            box-shadow: 0 0 0 1px #e8c291 inset;
        }

        /*--------------------*/
        #actions
        {
            margin: 25px 0 0 0;
        }


    </style>
</head>
<body style=";margin: 0px auto;background: #f8f8f8">
     <img src="/NBSystem/Public/pic/banner.jpg" style="width: 100%;height: 200px;"/>
     <div style="width: 1000px; position: absolute;top: 0px;left: 50%;margin-left: -480px; ">
         <span style="line-height: 50px;font-size: 40px;font-weight: 700;color: #ffffff;display: inline-block; margin-top: 50px;">在线试验委托系统</span>
         <span style="font-size: 35px;font-weight: 600;display: inline-block;float: right;color: #0000AA">——<?php echo ($company); ?></span><br>
     </div>
     <form id="login_form" style="font-family: 'microsoft yahei';">
         <h1 style="font-family: 'microsoft yahei';">欢迎使用</h1>
         <fieldset id="inputs">
             <input id="account"  name="account" type="text" placeholder="Username" autofocus="" required=""><span style="padding-left: 10px"></span>
             <input id="password" name="password" type="password" placeholder="Password" required=""><span style="padding-left: 10px"></span>
         </fieldset>
         <fieldset id="actions">
             <a onclick="submitInfo()" id="submit_btn" class="btn btn-large btn-block btn-info" style="cursor: pointer">登录</a>
            <span id="error_span" style="color: red ;width: 140px;display: none;padding-left: 20px;" >
                账号或密码错误
            </span>
         </fieldset>
     </form>
     <a href="/NBSystem/index.php/Home/Advance" style="position: absolute;right: 10px;bottom: 20px;">预委托</a>
    <script>
        $(document).ready(function(){
            $("#login_form").validate({
                debug:true,
                errorElement: "em",
                errorPlacement: function(error, element) {
                    error.appendTo( element.next("span") );
                },
                errorClass:'error',
                messages: {
                    account:{required: ""},
                    password:{required: ""}
                }
            });
        });
        function submitInfo(){
            $('#error_span').hide();
            if( $("#login_form").valid()){
                $('#submit_btn').text('正在登录...');
                $.ajax({
                    type: 'POST',
                    url:'/NBSystem/index.php/Home/Index/submitInfo',
                    data:{account:$('#account').val(),password:$('#password').val()},
                    success: function(data){
                        if(data!=0){
                            window.location.href='/NBSystem/index.php/Home/Index/index';
                        }else{
                            $('#error_span').show();
                            $('#submit_btn').text('登录');

                        }
                    },
                    dataType: 'json'
                });
            }
        }
    </script>
</body>
</html>