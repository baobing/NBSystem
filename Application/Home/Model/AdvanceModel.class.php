<?php
/**
 * Created by PhpStorm.
 */
namespace Home\Model;
use Common\Model;

class AdvanceModel extends BaseModel {

    function submitInfo(){
        $DB=M('nb_advance_user');
        $user=$DB->where('account="'.$_POST['account'].'"')->find();
        if($user==null||$user==false){
            echo  0; return ;
        }

        $salt=$user['salt'];
        $str=md5($_POST['password']);
        $u_password=md5($str.''.$salt);
        if($u_password==$user['password']){
            if($user['is_pass']==1){
                session_start();
                session('advance',$user);
                echo 1; return ;
            }else if($user['is_pass']==0){
                echo -1; return ;
            }else if($user['is_pass']==2){
                echo -2; return ;
            }
        }
        echo 0; return ;
    }
    public function insert(){
        $DB=M('nb_advance_user');
        $user=$DB->create();
        $DB->salt=mt_rand(0,9999);
        $str=md5($_POST['password']);
        $DB->password=md5($str.''.$DB->salt);
        if($_POST==null){
            return false;
        }
        $DB->register_date=date("Y-m-d H:i:s",time());
        $flg=$DB->add();
      /*  if($flg){
            session_start();
            session('advance',$user);
        }*/
        return $flg;

    }
    public function  listAdvance(){

        $where=$this->getPostData($_POST);
        $order='id desc';
        $field='nb_advance_user.*,nb_userinfo.name';
        $DB=M("nb_advance_user")->join("left join nb_userinfo on nb_userinfo.id=nb_advance_user.operator");
        D("Base")->dataList($DB,$where,$order,$field);
    }
    public function getPostData($Data){
        if(isset($Data['account'])&&$Data['account']!=''){
            $where['nb_advance_user.account']= array("like",'%'.$Data['account'].'%');
        }
        if(isset($Data['real_name'])&&$Data['real_name']!=''){
            $where['nb_advance_user.real_name']= array("like",'%'.$Data['real_name'].'%');
        }
        if(isset($Data['tel'])&&$Data['tel']!=''){
            $where['nb_advance_user.tel']= array("like",'%'.$Data['tel'].'%');
        }
        if(isset($Data['email'])&&$Data['email']!=''){
            $where['nb_advance_user.email']= array("like",'%'.$Data['email'].'%');
        }
        if(isset($Data['is_pass'])&&$Data['is_pass']!=''){
            $where['nb_advance_user.is_pass']= array("eq",$Data['is_pass']);
        }
        if(isset($Data['date_from'])&&$Data['date_from']!=''&&isset($Data['date_to'])&&$Data['date_to']!=''){
            $where['register_date']=array( array("egt",$Data['date_from']),array("elt",$Data['date_to']));
        }
        return $where;
    }
} 