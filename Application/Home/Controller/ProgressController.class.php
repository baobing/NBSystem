<?php
namespace Home\Controller;
use Think\Controller;
class ProgressController extends BaseController {

    public function _initialize(){
        $dt_user=M("nb_userinfo")->where("id=".$_SESSION["user"]["id"])->find();
        if($dt_user["p0"] == 1){
            $dt=M("nb_userinfo")->where("p3=1")->select();
        }else{
            $dt=M("nb_userinfo")->where("p3=1 and department=".$dt_user["department"])->select();
        }
        $this->assign("tester",$dt);
        $dt_checker=M("nb_userinfo")->where("p4=1")->select();
        $this->assign("checker",$dt_checker);
        $dt_assign=M("nb_userinfo")->where("p2=1 and id!=".$_SESSION["user"]["id"])->select();
        $this->assign("assigner",$dt_assign);
    }
    public function showProtocol(){
        $curr=$_GET['curr'];
        $temp=D('Progress')->showProtocol();
        $this->assign('PageOfficeCtrl',$temp['page']);
        $total=$temp['total'];
        $this->assign('total',intval(($total-1)/4+1));
        $pre=$curr-4;
        $next=$curr+4;
        if($next>=$total){
            $next=-4;
        }
        $curr+=4;
        $this->assign('type',10);
        $this->assign("id",$_GET['id']);
        $this->assign('curr',$curr/4);
        $this->assign('pre',$pre);
        $this->assign('next',$next);
        $this->display('page_word');
    }
    public  function  showTask(){
        $PageOfficeCtrl=D('Progress')->showTask();

        $this->assign('PageOfficeCtrl',$PageOfficeCtrl);
        $this->display('page_word');
    }
    public function testPage(){

        $this->assign('type',$_GET['type']);
        $this->assign('step',2);
        $this->display('page_test');
    }
    public function checkPage(){
        $userDB=M('nb_userinfo');
        $passer=$userDB->where("p5=1")->select(); //批准人员
        $this->assign('passer',$passer);
        $type=$_GET['type'];
        $this->assign('type',$type);
        $this->assign('step',3);
        $this->display('page_check');
    }
    public function pagePass(){
        $this->assign('type',$_GET['type']);
        $this->assign('step',4);
        $this->display('page_pass');
    }
    public function showTest($id){   //测试人员打开word
        $this->getTopPage($id);
        $PageOfficeCtrl=D('Progress')->showTest($id);
        $this->assign('PageOfficeCtrl',$PageOfficeCtrl);
        $type=$_GET['type'];
        $this->assign('type',$type);
        $this->assign('step',2);
        
        $this->assign('id',$_GET['id']);
        $this->display('Progress/page_word');
    }


    public function showCheck($id){
        $this->getTopPage($id);
        $PageOfficeCtrl=D('Progress')->showCheck($id);
        $this->assign('PageOfficeCtrl',$PageOfficeCtrl);

        $this->assign('type',$_GET['type']);
        $this->assign('step',$_GET["step"]);
        $this->assign('id',$_GET['id']);
        $this->assign("name",$_SESSION["user"]["name"]);

        $db_passer=M('nb_userinfo');
        $dt_passer=$db_passer->where("p5=1")->select();
        $this->assign("passer",$dt_passer);
        $this->display('page_word');
    }
    public function costPage(){
        $type=$_GET['type'];
        $this->assign('type',$type);
        $this->display('cost_page');
    }
    public function Payed(){   //0未付费 1付费 2定期结算
        $db_pro=M('nb_protocol');
        $da_pro=$db_pro->create();
        if($da_pro['is_pay']=='2'){
            $sql="UPDATE nb_client set balance = balance+".$_POST['reduce_price']." where id=".$_POST['client_id'];
            M()->query($sql);
        }
        $this->ajaxReturn($db_pro->save($da_pro));
    }
    /*
     *  TODO 质检报告头部流转过程数去获取 发送前台
     *  $id 代表样品在数据库的主键id值
     * */
    public function getTopPage($id){
        $db_sample=M('nb_sample');
        $dt_sample=$db_sample->where('id='.$id)->find();
        $this->assign("sample_num",$dt_sample['sample_num']);

        $progress=M('nb_progress')->where('sample_id='.$id)->order("id asc")->select();
        $this->assign("progress",$progress);
    }
    public function showSample(){
        $seq=$_GET['seq'];
        $temp=D('Progress')->showSample();
        $this->assign('PageOfficeCtrl',$temp['page']);
        $total=$temp['total'];
        $this->assign('total',$total);

        $pre=$seq-1;
        $next=$seq+1;
        if($next==$total){
            $next=-1;
        }
        $seq++;
        $this->assign('type',40);
        $this->assign("id",$_GET['id']);
        $this->assign('curr',$seq);
        $this->assign('pre',$pre);
        $this->assign('next',$next);
        $this->display('page_word');
    }
    public function pageAssign(){

        $this->assign("step",1);
        $this->assign('type',$_GET["type"]);
        $this->display("page_assign");
    }
    public function refreshStep(){       //检测和审阅时修改进度及完成数量等相关信息
       $this->ajaxReturn(D('Progress')->refreshStep());
    }
    public function checkFile(){
        $ids=$_POST["ids"];
        for($i=0;$i<count($ids);$i++) {
            $id=$ids[$i];
            $data=M('nb_sample')->where("id=".$id)->find();
            if(!file_exists("./Public/File/test/".$data["sample_num"].".doc")) {
                echo -1;
                return ;
            }
        }
    }
    public function Transfer(){
        $data["assign_person"]=$_POST["assign_person"][0];
        $dt_user = M("nb_userinfo")->where("id = ".$data["assign_person"])->find();
        foreach($_POST["ids"] as $v){
            $data["id"]=$v;
            $flg=M("nb_sample")->save($data);
            if(!$flg) $this->ajaxReturn(false);
            //发送短信
            D("Progress")->progressSms($dt_user["tel"],$v);
        }
        $this->ajaxReturn(true);
    }
    /*
     * TODO 编写28天报告 copy原来报告一份
     * $id 报告id
     *
     * */
    public function edit28Report($id){
        $dbSample = M("nb_sample");
        $suffix = "a";   //28天报告后缀
        $dtSample = $dbSample -> where("id=".$id) ->find();
        $src = "./Public/File/test/".$dtSample["sample_num"].".doc";
        $dt28Sample = $dbSample -> where("sample_num like '".$dtSample["sample_num"].$suffix."'") ->find();

        if($dt28Sample  == null){
            unset($dtSample["id"]);   //数据库中copy数据做相应更改
            $dtSample["is_modify"] = 0;
            $dtSample["is_printed"] = 0;
            $dtSample["step"] = C("STEP_TEST");
            $dtSample["check_cnt"] = 0;
            $dtSample["sample_num"] = $dtSample["sample_num"].$suffix;
            $new_id = $dbSample ->add($dtSample);

            $des = "./Public/File/test/".$dtSample["sample_num"].".doc";
            if(!file_exists($des)&&file_exists($src)){ //文件copy
                copy($src,$des);
            }

            $dbPrg = M("nb_progress");
            $dtPrg = $dbPrg -> where("sample_id = ".$id) ->limit(0,3)->select();

            foreach($dtPrg as $temp){
                unset($temp["id"]);
                $temp["sample_id"] = $new_id;
                $dbPrg ->add($temp);
            }
          //  dump($dtPrg);
        } else{
            $new_id = $dt28Sample["id"];
        }


        $this->showTest($new_id);
    }
    /**
     * testBack 质检报告退回给收发室
     * is_back = 1;
     * step = -1;
     */
    public function testBack(){
        $ids = $_POST["ids"];
        $db = M("nb_sample");
        foreach($ids as $id){
            $dt["id"] = $id;
            $dt["step"] = -1;
            $dt["is_back"] = 1;
            $db->save($dt);
        }
        $this->ajaxReturn(true);
    }
    /**
     *  质检报告退回给部门主任
     */
    public function testBackLeader(){
        $db = M("nb_sample");
        $data = $_POST["data"];
        foreach($data as $value){
            $dt["id"] = $value["id"];
            $dt["step"] = 1;
            $db->save($dt);
            $des="Public/File/test/".$value["sample_num"].".doc";
            if(file_exists($des)){
                unlink($des);
            }
        }
        $this->ajaxReturn(true);
    }
}



