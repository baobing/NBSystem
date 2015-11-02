<?php
namespace Home\Controller;
use Think\Controller;
class BaseController extends Controller {
    public function __construct(){
        parent::__construct();
        $item=M('web_config')->where('id=4')->find();
        $this->assign("company",$item['content']);
     //   $this->assign("permission",$_SESSION['user']['permission']);
        $this->assign("user",$_SESSION["user"]);
        $this->assign("pageList","[10,15]");
        $this->assign("pageSize","10");
        $dtType=M("nb_industry_type")->select();
        $this->assign("industry_type",json_encode($dtType));
        if($_SESSION['user']==null&&$_SESSION["advance"]==null){
            if(CONTROLLER_NAME!="Index"&&CONTROLLER_NAME!="Advance") {
                $this->error("未登录！！");
            }
        }else if($_SESSION['advance']!=null){
            if(CONTROLLER_NAME!="Protocol"&&CONTROLLER_NAME!="Advance"&&CONTROLLER_NAME!="Sample") {
                $this->error("网址出错！！");
            }
        }

    }
}