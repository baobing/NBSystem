<?php
namespace Home\Controller;
use Think\Controller;
class MangerController extends BaseController {

    public   function pageCheckPay(){
       //   $this->assign("type",$_GET["type"]);
          $this->display("page_checkpay");
      }
    public  function  submitResult(){
        $db=M("nb_protocol");
        $dt=$db->create();
        $this->ajaxReturn($db->save($dt));
    }
    public function pageTask(){
        $userDB=M('nb_userinfo');
        $tester=$userDB->where("p3=1")->select();
        $this->assign('tester',$tester);
        $this->display("page_task");
    }
    public function pageFinance(){
        $this->display("page_finance");
    }
    public function pageRecharge(){

        $this->display("page_recharge");
    }
    public function getExcelFinance(){
        D("Excel")->getExcelFinance();
    }
    public function getExcelTask(){
        D("Excel")->getExcelTask();
    }
    public function getExcelRC(){
        D("Excel")->getExcelRC();
    }
    public function pageProgress(){
        $this->display("page_progress");
    }
    public function pageModify(){
        $this->assign("STEP_TAKE",C("STEP_TAKE"));
        $this->display("page_modify");
    }
    public function sampleDetail(){
        $where["sample_id"]=$_GET["id"];
        $dt_prg=M("nb_progress")->where($where)->select();
        $str='<div style="width: 900px;color: red;">';
        $str.=$dt_prg[0]["content"];
        for($i=1;$i<count($dt_prg);$i++){
            $str.=" -> ".$dt_prg[$i]["content"];
        }
        $str.='</div>';
        echo $str;
    }
    /**
     *  TODO 修改已发出的质检报告
     *  @PARAM $id 报告id
     */
    public function modifyTaked($id){
        $dbSample = M("nb_sample");

        $dtSample = $dbSample -> where("id=".$id) ->find();
        $dtSample["is_modify"] = "1";
        $dbSample ->save($dtSample);
        $src = "./Public/File/test/".$dtSample["sample_num"].".doc";
        unset($dtSample["id"]);
        $dtSample["is_modify"] = 0;
        $dtSample["is_printed"] = 0;
        $dtSample["step"]=C("STEP_FINISH");
        $dtSample["sample_num"] = $dtSample["sample_num"]."G";
        $new_id = $dbSample ->add($dtSample);
        $des = "./Public/File/test/".$dtSample["sample_num"].".doc";
        if(!file_exists($des)&&file_exists($src)){
            copy($src,$des);
        }
        A("Progress")->showTest($new_id);
    }
}