<?php
namespace Home\Controller;
use Think\Controller;
class IndexController extends BaseController {

    public function index(){

        if($_SESSION['user']==null)
            $this->display('login');
        else{
            $this->assign('user',$_SESSION['user']);
            $menus=D("Index")->getMenus();
            $this->assign("menus",json_encode($menus));
            $this->display('home_page');
        }

    }

    public function submitInfo(){
        $DB=M('nb_userinfo');
        $user=$DB->where('account="'.$_POST['account'].'"')->find();
        if($user==null||$user==false){
            $this->ajaxReturn(false);
            return ;
        }
        $password=$user['password'];
        $salt=$user['salt'];
        $str=md5($_POST['password']);
        $u_password=md5($str.''.$salt);
        if($u_password==$password){
            $Log=M('nb_loginfo');
            $Data['log_date']=date('Y-m-d H:i:s',time());
            $Data['account']=$_POST['account'];
            $Log->add($Data);
            session_start();
            session('user',$user);
            $this->ajaxReturn(true);
        }
        else $this->ajaxReturn(false);
    }
    public function logout(){
        $_SESSION['user']=null;
        $this->redirect('login');
    }
    public function  getCnt(){
        $where1["protocol_step"]=0;

        $where1["is_finance"] = 1;
        $where1["check_pay"] = 0;
        $where1['_query'] = 'is_pay=0&is_back=1&_logic=or';                //待处理
        $data[1]=M('nb_protocol')->where($where1)->count();

        $where2["check_pay"]=array("gt",10);     //审批完成
        $data[2]=M('nb_protocol')->where($where2)->count();

        $where3["step"]=array("eq",1);     //待分配
        if($_SESSION["user"]["p0"]==0) {
            $where3["assign_person"]=$_SESSION["user"]["id"];
        }
        $data[3]=M('nb_sample')->where($where3)->count();

        $where4["step"]=array("eq",2);     //待检测
        $where4["check_cnt"]=0;
        if($_SESSION["user"]["p0"]==0) {
            $where4["tester"]=$_SESSION["user"]["id"];
        }
        $data[4]=M('nb_sample')->where($where4)->count();

        $where5["step"]=array("eq",2);     //待修改
        $where5["check_cnt"]=array("gt",0);
        if($_SESSION["user"]["p0"]==0) {
            $where5["tester"]=$_SESSION["user"]["id"];
        }
        $data[5]=M('nb_sample')->where($where5)->count();

        $where6["step"]=array("eq",3);     //待审核
        if($_SESSION["user"]["p0"]==0) {
            $where6["check_person"]=$_SESSION["user"]["id"];
        }
        $data[6]=M('nb_sample')->where($where6)->count();

        $where7["step"]=array("eq",4);     //待批准
        if($_SESSION["user"]["p0"]==0) {
            $where7["pass_person"]=$_SESSION["user"]["id"];
        }
        $data[7]=M('nb_sample')->where($where7)->count();

        $where8="check_pay > 0 and check_pay < 10";     //取报告审批
        if($_SESSION["user"]["p0"]==0){
            $where8["leader"]=$_SESSION["user"]["id"];
        }
        $data[8]=M('nb_protocol')->where($where8)->count();
        $this->ajaxReturn($data);

        $where9="is_back = 1";     //取报告审批
        $data[9]=M('nb_sample')->where($where9)->count();
        $this->ajaxReturn($data);
    }
}