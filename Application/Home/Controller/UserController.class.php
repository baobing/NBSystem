<?php
namespace Home\Controller;
use Think\Controller;
class UserController extends BaseController {
    public function index(){
        $dt_department=M("nb_common")->where("type=3 and is_used=0")->select();
        $this->assign("dpt",$dt_department);
        $this->display('index');
    }
    public function logInfoPage(){
        $this->display('loginfo');
    }
    public function dateList(){
        $DB=M('nb_userinfo')->join("left join nb_common on nb_common.id=nb_userinfo.department");
        $page=$_POST['page'];
        $rows=$_POST['rows'];
        $order=$_POST['order'];
        $sort=$_POST['sort'];
        $orderSql="";
        $where=$this->getPostData();
      //  $where["nb_userinfo.id"]=array("gt",1);
        if(isset($sort)&&!empty($sort)){
            $orderSql=$sort." ".$order;
        }
        if(isset($page)&&!empty($page))
            $first=($page-1)*$rows;
        $list=$DB->where($where)->limit($first,$rows)->order( $orderSql)->field("nb_userinfo.*,nb_common.name as  department_name")->select();
        $total=$DB->where($where)->count();
        $data['total']=$total;
        if($list==null) $list="";
        $data['rows']=$list;
        $data["where"]=$where;
        echo json_encode($data);
    }
    public function getPostData(){
        if(isset($_POST["name"])&&!empty($_POST["name"])){
            $where["nb_userinfo.name"]=array("like","%".$_POST["name"]."%");
        }
        if(isset($_POST["department"])&&!empty($_POST["department"])){
            $where["nb_userinfo.department"]=array("eq",$_POST["department"]);
        }
        if(isset($_POST["permission"])&&!empty($_POST["permission"])){
            $where["nb_userinfo.p".$_POST["permission"]]=array("eq",1);
        }
        return $where;
    }
    public function insert(){  //添加新的用户 数据库已经默认普通员工
        $DB=M('nb_userinfo');
        $DB->create();
        $DB->salt=mt_rand(0,9999);
        $str=md5($_POST['password']);
        $DB->password=md5($str.''.$DB->salt);
        echo $DB->add();
    }
    public function modifyAccount($id){
        $DB=M('nb_userinfo');
        echo json_encode($_POST);
        if(isset($_POST['tel'])||isset($_POST['name'])){
            $item=$DB->create();
            for($i=1;$i<=9;$i++){
                if(!isset($_POST["p".$i]))$item["p".$i]=0;
            }
        }
        if(isset($_POST['password'])&&!empty($_POST['password'])){
            $item['salt']=mt_rand(0,9999);
            $str=md5($_POST['password']);
            echo $str;
            $item['password']=md5($str.''.$item['salt']);
        }
        echo $DB->where('id='.$id)->save($item);
    }
    public function uploadSeal($file,$id){
        $date=date("Y_m_d_H_i_s",time());
        $filename=$date."_seal";
        $des= './Public/File/seal/';
        $info=D("Base")->saveFile($filename,$des,null);
        $item["filename"]=$info[$file]["savename"];
        $item["id"]=$id;

        $DB=M('nb_userinfo');
        echo $DB->where('id='.$id)->save($item);
    }
    public function delete(){
        $DB=M('nb_userinfo');

        $user=$DB->where('id='.$_POST['id'])->find();
        if($user['is_admin']==1){
            return;
        }
        $DB->id=$_POST['id'];
        echo $DB->delete();

    }
    public function validUser(){
        $DB=M('nb_userinfo');
        if(isset($_GET['modify'])){
            if($_GET['modify']==$_POST['account']) {echo json_encode(true);return ;}
            $count=$DB->where('account= "'.$_POST['account'].'"')->count();
            if($count==0){echo json_encode(true);return ;}
            echo json_encode(false); return ;
        }

        if($DB->where('account="'.$_POST['account'].'"')->count()>0)
        {echo json_encode(false); return ;}
        echo json_encode(true);

    }
    public  function validName(){
        $DB=M('nb_userinfo');
        if(isset($_GET['id'])){
            $dt=$DB->where('name= "'.$_POST['name'].'"')->find();
            if($dt!=null&&$_GET['id']!=$dt['id']){
                echo json_encode(false); return ;
            }
            echo json_encode(true);
            return ;
        }
        if($DB->where('name="'.$_POST['name'].'"')->count()>0)
        {echo json_encode(false); return ;}
        echo json_encode(true);
    }
    public function loginfo($account){
        $DB=M('nb_loginfo');
        $page=$_POST['page'];
        $rows=$_POST['rows'];
        $order=$_POST['order'];
        $sort=$_POST['sort'];
        $orderSql="id desc";
        if(isset($sort)&&!empty($sort)){
            $orderSql=$sort." ".$order;
        }
        if(isset($page)&&!empty($page))
            $first=($page-1)*$rows;
        if($account==-1){
            $sql='';
        }else{$sql='account="'.$account.'"';}
        $list=$DB->limit($first,$rows)->order( $orderSql)->where($sql)->select();
        $total=$DB->where($sql)->count();
        $data['total']=$total;
        if($list==null)  $data['rows']='';
        else $data['rows']=$list;
        echo json_encode($data);
    }
    public function validPW(){
        $db=M("nb_userinfo");
        $user = $db->where("id=".$_SESSION["user"]["id"])->find();
     //   echo json_encode($user);
     //   echo json_encode($user);
        $password = $user['password'];
        $salt = $user['salt'];
        $str = md5($_POST['o_password']);
        $u_password = md5($str.$salt);
      //  echo $u_password."  ".$password."  ".$_POST["o_password"];
        if($u_password == $password){
        //    echo 1;
            return 1;
        }
       return 0;
    }
    public function submitPW(){
        if(!$this->validPW()){
            $this->ajaxReturn(0);
        }
        $DB=M('nb_userinfo');
        $dt["id"]=$_SESSION["user"]["id"];
        $dt["salt"]=mt_rand(0,9999);
        $str=md5($_POST['password']);
        $dt["password"] = md5($str.$dt["salt"]);
        $flg = $DB->save($dt);
        $this->ajaxReturn($flg);
    }
}