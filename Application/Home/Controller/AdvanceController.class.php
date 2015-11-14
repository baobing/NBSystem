<?php
namespace Home\Controller;
use Think\Controller;
class AdvanceController extends BaseController {

    public function index(){
        if($_SESSION["advance"]==null){
            $this->display("login");
        }else{
            $this->redirect("Protocol/addpage");
        }
    }
    public function register(){
        $this->display("register");
    }
    public function submitInfo(){
       D("Advance")->submitInfo();
    }
    public function insert(){   //新用户注册
        $flg=D("Advance")->insert();
        $this->ajaxReturn($flg);
    }
    public function pageSuccess(){
        $DB=M('nb_advance_user');
        $user = $DB->where("id = ".$_GET["id"])->find();
        $this->assign("user",$user);
        $this->display("success");
    }
    public function validUser(){
        $where["account"]=array("like",$_POST["account"]);
        $cnt=M("nb_advance_user")->where($where)->count();
        if($cnt==0) $flg=true;
        else $flg=false;
        echo json_encode(array('valid' =>$flg));
    }
    public function logout(){
        $_SESSION['advance']=null;
        $this->redirect('login');
    }
    public function submitSuccess(){
        $this->assign("protocol_num",$_GET["protocol_num"]);
        $this->display("OK");
    }
    public function listAdvance(){
        D("Advance")->listAdvance();
    }
    public function checked(){
        $db=M("nb_advance_user");
        $dt=$db->create();
        $dt["operator"]=$_SESSION["user"]["id"];
        $dt["pass_date"]=date("Y-m-d H:i:s",time());
        $flg=$db->save($dt);
        $this->ajaxReturn($flg);
    }
    public function deleteAdvance(){
        $db=M("nb_advance_user");
        $flg=$db->where("id=".$_POST["id"])->delete();
        $this->ajaxReturn($flg);
    }
}