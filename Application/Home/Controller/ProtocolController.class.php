<?php
namespace Home\Controller;
use Think\Controller;
class ProtocolController extends BaseController {
    /**
     * @param string $protocol_num 协议书编号
     * @param int $type 0正常添加 1填写信息和预委托 2
     */
    public function addPage($protocol_num='',$type=0){ //预委托归为type等于1
        $protocol='{}';
        $webDB=M('web_config');
        $prgDB=M('nb_protocol');

        if($type==0){
            $p_num=$webDB->where('id=5')->find();   //协议书自增num的值加一并且发送到
            $this->assign('p_num',$p_num['content']);
            $p_num["content"]+=1;
            $webDB->save($p_num);
            $current=date('Y-m-d',time());      //当前日期发送到前台
            $this->assign('today',$current);
            $protocol=$prgDB->where("receive_person like '".$_SESSION["user"]["name"]."'")->order("id desc")->find();
            unset($protocol["protocol_num"]);

            //上一份协议不为空的情况下，相隔时间大于TIME_INTERVAL则前台不填重上一份协议信息
            if($protocol != null){
                if((time() - $protocol["unix_time"])/60 > C("TIME_INTERVAL")) {
                    $protocol = "{}";
                }
            }

        }
        else if($type==1){
            $protocol=$prgDB->where('protocol_num like "'.$protocol_num.'"')->find();
            $this->assign("protocol_step",$protocol["protocol_step"]);   //修改时如果是预委托 显示样品信息
        }
        else if($type==2){
            $protocol=$prgDB->where('protocol_num like "'.$protocol_num.'"')->find();
            $this->assign('id',$protocol['id']);
            $this->assign('isFinance',$protocol['is_finance']);
        }

        if($protocol_num != ''){  //之前选择的样品信息
            $whereSample["protocol_num"] = $protocol_num;
            $dbSample = M("nb_sample");
            $dtSample = $dbSample->where($whereSample)->select();
            $this->assign("dtSample",$dtSample);
        }


        $dtType=M("nb_industry_type")->select();         //行业类别信息
        $this->assign("industry",$dtType);
        $this->assign("industry_type",json_encode($dtType));


        $clientDB=M('nb_client');                        //客户信息
        $client=$clientDB->where('is_used=0 and type=0')->select();
        $this->assign('client',$client);


        $projectDB=M('nb_common');                       //项目信息
        $project=$projectDB->where('is_used=0 and type=1')->select();
        $this->assign('project',$project);

        $chargeDB=M('nb_contract_charge');                       //项目信息
        $charge=$chargeDB->order("contract_num asc")->select();
        $this->assign('charge',$charge);

        $db_user=M('nb_userinfo');                        //收样人
        $da_receive=$db_user->where('p1=1')->select();
        $this->assign('receive',$da_receive);
        $this->assign('user_name',$_SESSION['user']['name']);


        $this->assign('protocol',json_encode($protocol)); //协议发送
        $this->assign('protocol_num',$protocol_num);
        $this->assign('type',$type);
        if($_SESSION["advance"]!=null){
            $this->assign('advance',1);
        }

        $this->display('addpage');
    }
    public function submitProtocol($type){  //提交协议，1位第一次添加 2位修改协议提交

        $DB=M('nb_protocol');
        $data=$DB->create();
        $ids=$this->temp();
        $data["client"]=$ids["client"];
        $data["project_id"]=$ids["project"];
        $data["inspected"] = $ids["inspected"];
        if($_SESSION["advance"]!=null){
            $db_sample=M('nb_sample');
            $db_sample->where("protocol_num LIKE  '".$_POST['protocol_num']."'")->delete();//删除之前保存有关协议的所有样品信息
            $rows=$_POST['rows'];
            $db_sample->addAll($rows);
            $data['sample_cnt']=count($rows);      //样品总数量
            $data["protocol_step"]=1;
            $DB->where("protocol_num LIKE  '".$_POST['protocol_num']."'")->delete();
            $DB->add($data);
            $this->ajaxReturn($data["protocol_num"]);
        }else if($type==1){
            $webDB=M('web_config');
            $db_sample=M('nb_sample');
            $db_progress=M('nb_progress');
            $ids_type = $data["industry_type"]; //行业类别
            $dtType=M("nb_industry_type")->select();
            $typeWord = $dtType[$ids_type]["word"];

            if($_POST["protocol_num"][0] == "T"){
                $p_num = $webDB->where('id='.($ids_type+10))->find();//协议书自增num的值
                while(true){
                    $p_num['content'] += 1;
                    $webDB->save($p_num);
                    $protocol_num = $typeWord.date('Y',time()).'-'.substr(($p_num['content']+100000),1,5);//并发情况下唯一编号
                    $cnt=$DB->where("protocol_num like '".$protocol_num."'")->count();
                    if($cnt==0) break;
                }

            } else {
                $protocol_num=$_POST["protocol_num"];
            }
               $db_sample->where("protocol_num LIKE  '".$_POST['protocol_num']."'")->delete();//删除之前保存有关协议的所有样品信息
            $rows=$_POST['rows'];

            for($i=0;$i<count($rows);$i++){       //保存样品信息，并且更新每条样品信息的流程
                $s_num=$webDB->where(' id = '.($ids_type+100))->find();//生成唯一编号
                $s_num['content']+=1;
                $webDB->save($s_num);
                $sample_num=$typeWord.date('Y',time()).substr(($s_num['content']+100000),1,5);
                $rows[$i]["sample_num"]=$sample_num;
                $rows[$i]['step']=0;
                $rows[$i]["protocol_num"]=$protocol_num;
                $s_id=$db_sample->add($rows[$i]);
                //流程记录
                $dt_progress['sample_id']=$s_id;
                $dt_progress['content']=$data['receive_person']."收取样品";
                $db_progress->add($dt_progress);

            }


            $data['sample_cnt']=count($rows);      //样品总数量
            $data["protocol_num"]=$protocol_num;
            $data["protocol_step"]=0;
            $data["unix_time"]=time();
            $data["first_price"] = $data["price"];
            $DB->where("protocol_num LIKE  '".$_POST['protocol_num']."'")->delete();
            $id=$DB->add($data);

            $this->ajaxReturn($id);
        }else if($type==2){
            $data['id']=$_GET['id'];
            $DB->save($data);
            $this->ajaxReturn($data['id']);
        }
    }
    public function temp(){
        $client_name=trim($_POST["client"]);
        $project_name=trim($_POST["project_id"]);
        $inspected_name =trim($_POST["inspected"]);
        $db_client=M("nb_client");
        $db_project=M("nb_common");
        $dt_client=$db_client->where("company like '".$client_name."'")->find();
        if($dt_client==null){
            $data1["company"]=$client_name;
            $ids["client"]=$db_client->add($data1);
        }else{
            $ids["client"]=$dt_client["id"];
        }

        $dt_project=$db_project->where("name like '".$project_name."'")->find();
        if($dt_project==null){
            $data2["name"]=$project_name;
            $data2["type"]=1;
            $ids["project"]=$db_project->add($data2);
        }else{
            $ids["project"]=$dt_project["id"];
        }

        $dt_inspected = $db_client->where("company like '".$inspected_name."'")->find();
        if($dt_inspected==null){
            $data3["company"]=$inspected_name;
            $ids["inspected"]=$db_client->add($data3);
        }else{
            $ids["inspected"]=$dt_inspected["id"];
        }
        return $ids;
    }

    /**
     *  此方法弃用
     */
    public function getSample(){
        $DB=M('nb_sample');
        $page=$_POST['page'];
        $rows=$_POST['rows'];
        $order=$_POST['order'];
        $sort=$_POST['sort'];
        $orderSql="id asc ";

        if(isset($_GET['protocol_num'])&&$_GET['protocol_num']!=''){
           $wSql='protocol_num like "'.$_GET['protocol_num'].'"';
        }else if(isset($_GET['method'])){
            $wSql="(".$_SESSION['user']['p4']."=1 or ";
           if($_GET['method']=='test'){
               $wSql.='tester="'.$_SESSION['user']['name'].'") and ';
               if($_GET['type']==0){
                   $wSql.='check_cnt=0 and step=1';
               }else if($_GET['type']==1){
                   $wSql.='check_cnt>0 and step=1';
               }
               else{
                   $wSql='step>1';
               }
           }else if($_GET['method']=='check'){
               $wSql.='check_person="'.$_SESSION['user']['name'].'") and ';
               if($_GET['type']==0){
                   $wSql.='step=2 ';
               }else if($_GET['type']==1){
                   $wSql.='step>2';
               }
           }
        }
        else return ;

        if(isset($sort)&&!empty($sort)){$orderSql.=",".$sort." ".$order;}
        if(isset($page)&&!empty($page)) $first=($page-1)*$rows;
        $list=$DB->limit($first,$rows)->order( $orderSql)->where($wSql)->select();
        $total=$DB->where($wSql)->count();
        if($list==null) $list='';
        $data['total']=$total;
        $data['rows']=$list;
        $data['where']=$wSql;
        echo json_encode($data);
    }
    public function saveTempPro(){
        $DB=M('nb_protocol');
        $Data=$DB->create();
        $ids=$this->temp();
        $Data["client"]=$ids["client"];
        $Data["project_id"]=$ids["project"];
        $Data["inspected"] = $ids["inspected"];
        $Data["protocol_step"]=2;
        $DB->where('protocol_num like "'.$Data['protocol_num'].'"')->delete();
        $this->ajaxReturn($DB->add($Data));
    }
    public function delProtocol(){
        $DB=M('nb_protocol');
        $dt_protocol=$DB->where("id=".$_POST["id"])->find();
        $protocol_num=$dt_protocol["protocol_num"];
        if($DB->where('id='.$_POST['id'])->delete()){
            $db_sample=M("nb_sample");
            $dt_sample=$db_sample->where("protocol_num='".$protocol_num."'")->select();
            foreach($dt_sample as $value){
                $filename="./Public/File/test/".$value["sample_num"].".doc";
                if( is_file( $filename )) {unlink($filename);}
                $db_sample->where("id=".$value["id"])->delete();
            }
            $this->ajaxReturn(true);
        }else{
            $this->ajaxReturn(false);
        }
    }
    public function delSample(){
        $flg=M("nb_sample")->where("id=".$_POST["id"])->delete();
        $this->ajaxReturn($flg);
    }
    public  function  validProtocolNum(){
        $protocol_num=$_POST['protocol_num'];

        if(M('nb_protocol')->where("protocol_num='".$protocol_num."'")->count()==0){
            $this->ajaxReturn(true);
        }else{
            $this->ajaxReturn(false);
        }
    }
    public  function  validSampleNum(){
        $sample_num=$_POST['sample_num'];
        if(M('nb_sample')->where("sample_num='".$sample_num."'")->count()==0){
            $this->ajaxReturn(true);
        }else{
            $this->ajaxReturn(false);
        }
    }
    /**
     * 合同号 得到协议信息
     */
    public function getInfoByContractNum(){
        $db = M("nb_contract_charge");
        $where["contract_num"] = $_POST["contract_num"];
        $dt = $db->where($where)->find();
        $this->ajaxReturn($dt);
    }
}