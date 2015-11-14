<?php
/**
 * Created by PhpStorm.
 */
namespace Home\Model;
use Think\Model;

class ProgressModel extends BaseModel {



    //协议书word展示
    public function showProtocol(){
        $id=$_GET['id'];
        $db_sample=M('nb_sample');
        $field=array("check_type","date","ins.company as inspected_name","price","protocol_num",
            "receive_person","nb_protocol.tel","witness_company","witness_contact",
            "nb_client.company as client_name","nb_common.name as project_name","send_person");
        $dt_protocol=D('Protocol')->getProtocolInfo(array("nb_protocol.id"=>intval($id)),$field,0);

        $field=array("delegate_cnt","make_num","molding_date","note","producing_area","sample_cnt",
            "sample_desc","sample_format","sample_loca","sample_name","stake_mark","test_basis","test_item","back_type");
        $dt_sample=$db_sample->where('protocol_num ="'.$dt_protocol['protocol_num'].'"')->field($field)->select();

        $this->getQR($dt_protocol['protocol_num']);
        $this->getBarcode($dt_protocol['protocol_num']);

        $curr=$_GET['curr'];
        $total=count($dt_sample);

        for($i=$curr;$i<count($dt_sample);$i++){
            $d=$i-$curr;
            foreach($dt_sample[$i] as $key=>$value){
                 $dt_s[$d][$key.$d]=$value;
            }
        }

        $dt_qrcode=array("qrcode"=>"[image]/Public/File/qrcode/".$dt_protocol['protocol_num'].".jpg[/image]");
        $dt_barcode=array("barcode"=>"[image]/Public/File/barcode/".$dt_protocol['protocol_num'].".png[/image]");
        $dt_total=array($dt_protocol,$dt_s[0],$dt_s[1],$dt_s[2],$dt_s[3],$dt_qrcode,$dt_barcode);
        $src="Public/File/system_file/protocol_word.doc";
        $temp['page']=$this->openWord($dt_total,null,$src,$src,"在线试验委托系统",5,$_SESSION['user']['name']);
        $temp['total']=$total;

        return $temp;
    }


    public function showTest($id){   //试验人员测试报告word展示
        $db_sample=M('nb_sample');

        $field=array("delegate_cnt","make_num","molding_date", "producing_area","nb_sample.sample_cnt", "sample_desc","sample_format",
            "sample_loca","sample_name","stake_mark","test_basis","test_item","check_type","equipment","nb_sample.sample_num","check_cnt","date");
        $dt_sample=$db_sample->join("nb_protocol on nb_protocol.protocol_num=nb_sample.protocol_num")
            ->where('nb_sample.id='.$id)->field($field)->find();

        $src="Public/File/system_file/test_word.doc";
        $des="Public/File/test/".$dt_sample["sample_num"].".doc";

        $btn=array();
        if($dt_sample['check_cnt']>0){
            array_push($btn,array('name'=>"保存",'fn'=>"SaveDocument","icon"=>1));
            array_push($btn,array('name'=>"隐藏痕迹",'fn'=>"HiddenRevisions","icon"=>5));
            array_push($btn,array('name'=>"显示痕迹",'fn'=>"ShowRevisions","icon"=>5));
            array_push($btn,array('name'=>"分层显示批注",'fn'=>"ShowHandDrawDispBar","icon"=>7));
        }
        unset($dt_sample['check_cnt']);

        $save=__ROOT__."/Sava/SaveFile.aspx";
        $btn_array=array("btn"=>$btn,"save"=>$save);
        $dt=array($dt_sample);
        $time=array("sample_num"=>4,"sample_name"=>2,"check_type"=>2);
        return $this->openWord($dt,$time,$des,$src,"在线试验委托系统",2,$_SESSION['user']['name'],$btn_array,true);
    }

    public function showCheck($id){  //批准人员和审核人员word展示
        $db_sample=M('nb_sample');

        $field=array("delegate_cnt","make_num","molding_date", "producing_area","nb_sample.sample_cnt", "sample_desc","sample_format",
            "sample_loca","sample_name","stake_mark","test_basis","test_item","check_type","equipment","sample_num");
        $dt_sample=$db_sample->join("nb_protocol on nb_protocol.protocol_num=nb_sample.protocol_num")
            ->where('nb_sample.id='.$id)->field($field)->find();
        $src="Public/File/system_file/test_word.doc";
        $des="/Public/File/test/".$dt_sample["sample_num"].".doc";
        unset($dt_sample["sample_num"]);
        $btn=array();
        $CustomToolbar=true; $Menubar=true;
        if($_GET['type']=='0'){
            array_push($btn,array('name'=>"保存",'fn'=>"SaveDocument","icon"=>1));
            array_push($btn,array('name'=>"显示痕迹",'fn'=>"ShowRevisions","icon"=>5));
            array_push($btn,array('name'=>"隐藏痕迹",'fn'=>"HiddenRevisions","icon"=>5));
            array_push($btn,array('name'=>"圈阅",'fn'=>"StartHandDraw","icon"=>3));
            array_push($btn,array('name'=>"键盘批注",'fn'=>"StartRemark","icon"=>3));
            array_push($btn,array('name'=>"分层显示批注",'fn'=>"ShowHandDrawDispBar","icon"=>7));
      /*      if($_GET["step"]==4){
                array_push($btn,array('name'=>"加盖印章/签字",'fn'=>"InsertSeal","icon"=>2));
                array_push($btn,array('name'=>"手写签字",'fn'=>"AddHandSign","icon"=>3));
                array_push($btn,array('name'=>"验证印章",'fn'=>"VerifySeal","icon"=>5));
            }*/
        }
        else{
            $CustomToolbar=false;
            $Menubar=false;
        }
        $save=__ROOT__."/Sava/SaveFile.aspx";
        $btn_array=array("btn"=>$btn,"save"=>$save);
        $dt=array($dt_sample);
        $time=array("sample_name"=>2,"check_type"=>2);
     //   dump($dt);
        return $this->openWord($dt,$time,$des,$src,"在线试验委托系统",0,$_SESSION['user']['name'],$btn_array,$CustomToolbar,$Menubar);
    }
    public function showSample(){   //展示最终报告word
        $id=$_GET['id'];
        $seq=$_GET['seq'];
        $db_sample=M('nb_sample');
        $field=array("delegate_cnt","make_num","molding_date", "producing_area","nb_sample.sample_cnt","sample_desc","sample_format",
            "sample_loca","sample_name","stake_mark","test_basis","test_item","equipment","protocol_num","sample_num","pass_person",
            "tester","check_person");
        $dt_sample=$db_sample->where('id='.$id)->field($field)->select();
        $db_user=M("nb_userinfo");
        $pass_person=$db_user->where("id=".$dt_sample[$seq]["pass_person"])->find();
        $pass_file=$pass_person["filename"];
        unset($dt_sample[$seq]["pass_person"]);
        $test_person=$db_user->where("id=".$dt_sample[$seq]["tester"])->find();
        $test_file=$test_person["filename"];
        unset($dt_sample[$seq]["tester"]);
        $check_person=$db_user->where("id=".$dt_sample[$seq]["check_person"])->find();
        $check_file=$check_person["filename"];
        unset($dt_sample[$seq]["check_person"]);
        $field=array("ins.company as inspected_name","send_person","witness_company","witness_contact",
            "nb_client.company as client_name","nb_common.name as project_name,check_type");
        $dt_protocol=D("protocol")->getProtocolInfo(array("nb_protocol.protocol_num"=>$dt_sample[$seq]["protocol_num"]),$field,0);
        unset($dt_sample[$seq]["protocol_num"]);

        $this->getQR($dt_sample[$seq]['sample_num']);

        $dt[0]=$dt_protocol;
        $dt[1]=$dt_sample[$seq];
        $dt[2]=array("sample_qrcode"=>"[image]/Public/File/qrcode/".$dt_sample[$seq]['sample_num'].".jpg[/image]");
        $dt[3]=array("pass_person"=>"[image]/Public/File/seal/".$pass_file."[/image]");
        $dt[4]=array("check_person"=>"[image]/Public/File/seal/".$check_file."[/image]");
        $dt[5]=array("test_person"=>"[image]/Public/File/seal/".$test_file."[/image]");
        $src="Public/File/system_file/test_word.doc";
        $des="Public/File/test/".$dt_sample[$seq]["sample_num"].".doc";

        $time=array("sample_name"=>2,"client_name"=>2,"inspected_name"=>2,"check_type"=>2,"sample_num"=>4);
        $temp['page']=$this->openWord($dt,$time,$des,$src,"在线试验委托系统",5,$_SESSION['user']['name']);
        $temp['total']=count($dt_sample);
        return $temp;
    }


    public function showTask(){          //任务书展示
        $ids=$_GET["ids"];
        $db_sample=M('nb_sample');
        $arr_id=explode(",",$ids);

        $where["nb_sample.id"]=array("in",$arr_id);
        $field=array("nb_sample.sample_num","sample_name","check_type","sample_format","producing_area",
            "stake_mark","molding_date","nb_sample.sample_cnt",
            "sample_desc","test_item","test_basis","note","back_type","nb_protocol.date","receive_person"
             );
        $dt_sample=$db_sample->join("nb_protocol on nb_protocol.protocol_num=nb_sample.protocol_num")
            ->where($where)->field($field)->select();
        for($i=0;$i<count($dt_sample);$i++){
            foreach($dt_sample[$i] as $key=>$value){
                $dt_s[$i][$key.$i]=$value;
            }
            $dt_s[$i]["today"]=date("Y-m-d",time());
        }
        $src='Public/File/system_file/task.doc';
        $temp=$this->openWord($dt_s,null,$src,$src,"在线试验委托系统",5,$_SESSION['user']['name']);
        return $temp;
    }


    public function refreshStep(){

        if($_POST["final"]==1){
            return $this->stepFinal();
        }
        if($_POST['step']==1){            //财务部门确认协议的付款方式
            return $this->stepAssign();
        }if($_POST['step']==2){           //部门主任分配任务
            return $this->stepTest();
        }if($_POST['step']==3){           //传递给审阅人员
            return $this->stepCheck();
        }if($_POST['step']==4) {//传递给批准人员
            return $this->stepPass();
        }if($_POST["step"]==5){//完成试验
            return $this->stepFinish();
        }
        $dbSample=M("nb_sample");
        $dtSample=$dbSample->create();
        return $dbSample->save($dtSample);

    }

    /**
     * @param int $id
     * @return bool
     */
    function stepAssign($id=0){    //财务部门确认协议的付款方式
        $db_prt=M('nb_protocol');
        $dt_item=$db_prt->where('id='.$id)->find();
        $db_sample=M('nb_sample');   //样品进度更新,流程刷新
        $where['protocol_num']=array("like",$dt_item["protocol_num"]);
        $dt_sample=$db_sample->where($where)->field('id,assign_person,is_back')->select();
        foreach($dt_sample  as $key=>&$value){
            if($dt_item["is_back"] == 1 && $dt_sample["is_back"]==0)  { //只有退回的报告能重新流转
                continue;
            }
            echo $key["id"]."  ";
            $value["is_back"] = 0;
            $value['step']=1;
            $flg=$db_sample->save($value);
            if(!$flg) return false;
            $dt_pro['sample_id']=$value['id'];
            $dt_user=M("nb_userinfo")->where("id=".$value["assign_person"])->find();
            $dt_pro['content']=$dt_user["name"]."分配任务";
            $flg=M('nb_progress')->add($dt_pro);
            if(!$flg) return false;

            //发送短信
            $this->progressSms($dt_user["tel"],$value["id"]);
        }
        return true;
    }
    function stepTest(){
        for($i=0;$i<count($_POST["ids"]);$i++){
            $id=$_POST["ids"][$i];
            $data=M('nb_sample')->where('id='.$id)->find();
            $data['step']=$_POST['step'];      //步骤
            if(isset($_POST['refuse_note'])){
                $data['check_cnt']+=1;
                $data['refuse_note']=$_POST['refuse_note'];
            }else{
                $data['tester']=$_POST['tester'][0];
            }
            $flg=M('nb_sample')->save($data);
            if(!$flg) return $flg;


            $dt_pro['sample_id']=$id;
            $dt_user=M("nb_userinfo")->where("id=".$data["tester"])->find();
            if(isset($_POST['refuse_note'])){
                $dt_pro['content']=$dt_user['name']."修改报告";
            }else
                $dt_pro['content']=$dt_user['name']."初次试验";
            $db_progress=M('nb_progress');
            $flg=$db_progress->add($dt_pro);
            if(!$flg) return $flg;

            //发送短信
            $this->progressSms($dt_user["tel"],$id);
        }
        return true;
    }
    function stepCheck(){
        $ids=$_POST["ids"];

        for($i=0;$i<count($ids);$i++){
            $id=$ids[$i];
            $data=M('nb_sample')->create();
            $data["id"]=$id;
            $flg=M('nb_sample')->save($data);
            if(!$flg) return $flg;

            $dt_user=M("nb_userinfo")->where("id=".$_POST['check_person'])->find();
            $dt_pro['sample_id']=$id;
            $dt_pro['content']= $dt_user["name"]."审阅报告";
            $flg=M('nb_progress')->add($dt_pro);
            if(!$flg) return $flg;

            //发送短信
            $this->progressSms($dt_user["tel"],$id);
        }

        return true;
    }
    function stepPass(){
        $ids=$_POST["ids"];
        for($i=0;$i<count($ids);$i++) {
            $id = $ids[$i];
            $data = M('nb_sample')->create();
            $data["id"]=$id;
            $flg = M('nb_sample')->save($data);
            if (!$flg) return $flg;

            $dt_user = M("nb_userinfo")->where("id=" . $_POST['pass_person'])->find();
            $dt_pro['sample_id'] = $id;
            $dt_pro['content'] = $dt_user["name"]."批阅报告";
            $flg = M('nb_progress')->add($dt_pro);
            if (!$flg) return $flg;
        }
        return true;
    }
    function stepFinish(){
        $ids=$_POST["ids"];
        for($i=0;$i<count($ids);$i++) {
            $id = $ids[$i];
            $data = M('nb_sample')->create();
            $data["id"]=$id;
            $data["finish_date"] = date("Y-m-d H:i:s", time());
            $flg = M('nb_sample')->save($data);
            if (!$flg) return $flg;

            $dt_pro['sample_id'] = $id;
            $dt_pro['content'] = "报告完成";
            $flg = M('nb_progress')->add($dt_pro);
            if (!$flg) return $flg;
        }
        return true;
    }
    function stepFinal(){
        $ids=$_POST["ids"];

        for($i=0;$i<count($ids);$i++){
            $id=$ids[$i];
            $data=M('nb_sample')->create();
            $data["id"]=$id;
            $flg=M('nb_sample')->save($data);
            if(!$flg) return $flg;

            $dt_user=M("nb_userinfo")->where("id=".$_SESSION["user"]["id"])->find();
            $dt_pro['sample_id']=$id;
            $dt_pro['content']= $dt_user["name"]."最终报告";
            $flg=M('nb_progress')->add($dt_pro);
            if(!$flg) return $flg;
        }
        return true;
    }

    /**
     * @param $phone
     * @param $sampleId
     * 根据样本编号 和 电话号码 发送短信
     */
    function progressSms($phone,$sampleId){
        $dtConfig = M("web_config")->where("id =1 or id=2")->order("id asc")->select();
        if($dtConfig[0]["content"] == "1"){
            $content = $dtConfig[1]["content"];
            $dt = M("nb_sample")->where("id=".$sampleId)->find();
            $sampleNum = $dt["sample_num"];
            $content = str_replace("【样品编号】",$sampleNum,$content);
         //   D("Base")->send($phone,$content);
        }

    }
    /**
     * todo  质检报告退回给收发室
     * is_back = 1;
     * step = -1;
     * 协议设置为退回 状态
     */
    public function testBack(){
        $M = M("Rollback");
        $M->startTrans();//开启事务
        $ids = $_POST["ids"];
        $db = M("nb_sample");
        $nums = array();
        foreach($ids as $id){
            $dt_sample["id"] = $id;
            $dt_sample["step"] = -1;
            $dt_sample["is_back"] = 1;
            $flg = $db->save($dt_sample);
            if(!$flg){
                echo 1;
                $M->rollback();//事务有错回滚
                return false;
            }
            //改变协议状态
            $dtTmp = $db->where ("id = ".$id)->find();
            array_push($nums, $dtTmp["protocol_num"]);
        }
        $db_protocol = M("nb_protocol");
        $dt_protocol["is_back"] = 1;
        $dt_protocol["is_finance"] = 0;
        $dt_protocol["time_pay"] = date("Y-m-d H:i:s",time());
        $where_prt["protocol_num"] = array("in",$nums);
        $flg = $db_protocol->where($where_prt)->save($dt_protocol);
        if(!$flg){
            $M->rollback();//事务有错回滚
            return json_encode($dt_protocol);
        }
        $M->commit();//提交事务成功
        return true;
    }
} 