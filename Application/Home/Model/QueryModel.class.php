<?php
/**
 * Created by PhpStorm.
 */
namespace Home\Model;
use Common\Model;

class QueryModel extends BaseModel {

    function getProtocolList($is_excel=0,$is_nums=0){
        $DB=M('nb_protocol')->join("nb_client on nb_client.id=nb_protocol.client")
            ->join("nb_client as ins on ins.id = nb_protocol.inspected")
            ->join("nb_common on nb_common.id=nb_protocol.project_id")
            ->join("left join nb_contract_charge on nb_contract_charge.contract_num=nb_protocol.contract_num")
            ->join("left join nb_userinfo as operator on operator.id=nb_protocol.operator_pay");
        $field="nb_protocol.*,nb_client.company as client_name,ins.company as inspected,
          nb_common.name as project_name,operator.name as  operator_name,discount";
        $order='nb_protocol.id desc';
        $where["protocol_step"]=0;
        if($is_excel==1){
            $where=$this->getPostData($_SESSION["payed"],$where);
            $where=$this->getGetData($where);
            $list=$DB->where($where)->order($order)->field($field)->select();
            return $list;
        }
        if($is_excel==2){
            $where=$this->getPostData($_SESSION["finance"],$where);
            $where=$this->getGetData($where);
            $list=$DB->where($where)->order($order)->field($field)->select();

            return $list;
        }
        if($is_excel==3){
            $where=$this->getPostData($_SESSION["prt"],$where);
            $where=$this->getGetData($where);
            $list=$DB->where($where)->order($order)->field($field)->select();
            return $list;
        }
        if(isset($_GET["payed"])){
            $_SESSION["payed"]=$_POST;
        }
        if(isset($_GET["prt"])){
            $_SESSION["prt"]=$_POST;
        }
        if(isset($_GET["finance"])){
            $_SESSION["finance"]=$_POST;
        }
        $where=$this->getPostData($_POST,$where);
        $where=$this->getGetData($where);
        $data=$this->dataList1($DB,$where,$order,$field);
        $data["total"]=M('nb_protocol')->join("nb_client on nb_client.id=nb_protocol.client")
            ->join("nb_client as ins on ins.id = nb_protocol.inspected")
            ->join("nb_common on nb_common.id=nb_protocol.project_id")
            ->join("left join nb_contract_charge on nb_contract_charge.contract_num=nb_protocol.contract_num")
            ->join("left join nb_userinfo as operator on operator.id=nb_protocol.operator_pay")
            ->where($where)->count();
        echo json_encode($data);
    }

    function getSampleList($is_excel=0){           //有协议信息

        $DB=M('nb_sample')->join('nb_protocol  on nb_protocol.protocol_num=nb_sample.protocol_num')
            ->join("nb_client as ins on ins.id=nb_protocol.inspected")
            ->join("nb_client on nb_client.id=nb_protocol.client")
            ->join("nb_common on nb_common.id=nb_protocol.project_id")
            ->join("left join nb_userinfo on nb_userinfo.id=tester");
        $order='nb_sample.id desc';
        $field='nb_protocol.id as p_id ,nb_sample.*,nb_protocol.*,nb_common.name as project_name,ins.company as inspected_name,
          nb_sample.id as s_id,nb_client.company as client_name,nb_userinfo.name as tester_name';
        $where["protocol_step"]=0;
        if(isset($_GET["is_printed"]))   //报告是否被打印判断
        {
            $where["is_printed"] = $_GET["is_printed"];
        }
        if(isset($_GET["is_modify"])){  //报告是否打印
            $where["nb_sample.is_modify"]=array("eq",$_GET["is_modify"]);
        }
        if(isset($_GET["finished"])){  //已完成报告
            $where["nb_sample.step"]=array("egt",C("STEP_FINISH"));
        }
        if(isset($_GET["step"])){  //已完成报告
            $where["nb_sample.step"]=array("eq",$_GET["step"]);
        }
        if($is_excel==1){
            $where=$this->getPostData($_SESSION["task"],$where);
            $list=$DB->where($where)->order($order)->field($field)->select();
            return $list;
        }
        if($is_excel==2){
            $where=$this->getPostData($_SESSION["sample"],$where);
            $list=$DB->where($where)->order($order)->field($field)->select();
            return $list;
        }
        if($is_excel==3){
            $where=$this->getPostData($_SESSION["finance"],$where);
            $list=$DB->where($where)->order($order)->field($field)->select();
            return $list;
        }
        if(isset($_GET["task"])){
            $_SESSION["task"]=$_POST;
        }
        if(isset($_GET["sample"])){
            $_SESSION["sample"]=$_POST;
        }
        if(isset($_GET["finance"])){
            $_SESSION["finance"]=$_POST;
        }
        $where=$this->getPostData($_POST,$where);

        $data=$this->dataList1($DB,$where,$order,$field);
        $data["total"]=M('nb_sample')->join('nb_protocol  on nb_protocol.protocol_num=nb_sample.protocol_num')
            ->join("nb_client as ins on ins.id=nb_protocol.inspected")
            ->join("nb_client on nb_client.id=nb_protocol.client")
            ->join("nb_common on nb_common.id=nb_protocol.project_id")
            ->where($where)->count();

        echo json_encode($data);
    }
    function getListSample(){               //无协议信息

        $DB=M('nb_sample')->join("left join nb_userinfo on nb_userinfo.id = nb_sample.tester");

        $where=$this->getPostData($_POST,null);
        if(isset($_GET["permission"])&&($_SESSION["user"]["is_admin"]==0)){
            if($_GET["permission"]==2){
                $where["assign_person"]=$_SESSION["user"]["id"];
            }else if($_GET["permission"]==3){
                $where["tester"]=$_SESSION["user"]["id"];
            }else if($_GET["permission"]==4){
                $where["check_person"]=$_SESSION["user"]["id"];
            }else if($_GET["permission"]==5){
                $where["pass_person"]=$_SESSION["user"]["id"];
            }
        }

        $where = $this -> getSampleGetData($where);

        $field='nb_sample.*,nb_userinfo.name as tester_name';
        $order='nb_sample.id desc';
        $this->dataList($DB,$where,$order,$field);
    }

    /**
     * @param $is_excel 是否生成excel
     * 收发等级日志
     */
    function getRechargeList($is_excel){
        $DB=M('nb_recharge')->join("nb_protocol on nb_recharge.protocol_id=nb_protocol.id")
           ->join("nb_client as ins on ins.id=nb_protocol.inspected")
            ->join("nb_client on nb_client.id=nb_protocol.client")
            ->join("nb_common on nb_common.id=nb_protocol.project_id")
            ->join("nb_userinfo on nb_userinfo.id = nb_recharge.user_id");
        $where["protocol_step"]=0;
        $order="nb_recharge.id desc";
        $field="nb_recharge.*,nb_protocol.date,protocol_num,nb_client.company as client_name,ins.company as inspected_name,
        nb_userinfo.name,nb_common.name as project_name,is_pay,price,inspected";
        if($is_excel==1){
            $where=$this->getPostData($_SESSION["RC"],$where);
            $list=$DB->where($where)->order($order)->field($field)->select();
            return $list;
        }
        if(isset($_GET["RC"])){
            $_SESSION["RC"]=$_POST;
        }
        $where=$this->getPostData($_POST,$where);
        $data=$this->dataList1($DB,$where,$order,$field);
        $data["total"]=M('nb_recharge')->join("nb_protocol on nb_recharge.protocol_id=nb_protocol.id")
            ->join("nb_client on nb_client.id=nb_protocol.client")
            ->join("nb_client as ins on nb_client.id=nb_protocol.inspected")
            ->join("nb_common on nb_common.id=nb_protocol.project_id")
            ->join("nb_userinfo on nb_userinfo.id=nb_recharge.user_id")->where($where)->count();
        echo json_encode($data);
    }
    function getSampleGetData($where){
        if(isset($_GET['step'])){
            $where['nb_sample.step']=$_GET['step'];
        }
        if(isset($_GET['check_cnt'])){
            $where['nb_sample.check_cnt']=$_GET['check_cnt'];
        }
        if(isset($_GET['egt_check_cnt'])){
            $where['nb_sample.check_cnt']=array("egt",$_GET['egt_check_cnt']);
        }
        if(isset($_GET["assigned"])){
            $where["nb_sample.step"]=array("gt",1);
        }
        if(isset($_GET["tested"])){
            $where["nb_sample.step"]=array("gt",2);
        }
        if(isset($_GET["checked"])){
            $where["nb_sample.step"]=array("gt",3);
        }
        if(isset($_GET["passed"])){
            $where["nb_sample.step"]=array("gt",4);
        }
        if(isset($_GET["is_modify"])){
            $where["nb_sample.is_modify"]=array("eq",$_GET["is_modify"]);
        }
        return $where;
    }
    function getGetData($where){
        if(isset($_GET["is_finance"])){
            $where['is_finance']=$_GET['is_finance'];
        }
        if(isset($_GET["permission"])&&$_GET["permission"]==7){
            if($_SESSION["user"]["is_admin"]==0)
                $where["leader"]=$_SESSION["user"]["id"];
        }
        if(isset($_GET["protocol_step"])){
            $where['protocol_step']=$_GET['protocol_step'];
        }
        if(isset($_GET['is_pay'])){
            $where['is_pay']=$_GET['is_pay'];
        }
        if(isset($_GET['check_pay'])){
            $where['check_pay']=$_GET['check_pay'];
        }
        if(isset($_GET['checking'])){ //审批中的协议
            $where['check_pay']=array(array('gt',0),array('lt',10));
            //$where["payed"]=0;
        }
        if(isset($_GET['checked'])){ //审批完成的协议
            $where['check_pay']=array(array('gt',10),array('lt',30));
          //  $where["payed"]=0;
        }
        if(isset($_GET["terms_pay"])){
            $where['terms_pay']=$_GET['terms_pay'];
        }
        if(isset($_GET["payed"])){
            $where['payed']=array("eq",$_GET["payed"]);
        }
        return $where;
    }
    function getPostData($Data,$where){

        if(isset($Data['is_invoice'])&&$Data['is_invoice']!=''){
            if($Data['is_invoice']=="0")
               $where['invoice_num']= array("eq","");
            else $where['invoice_num']= array("neq","");
        }
        if(isset($Data['client_name'])&&$Data['client_name']!=''){
            $where['nb_client.company']= array("like",'%'.$Data['client_name'].'%');
        }
        if(isset($Data['project_name'])&&$Data['project_name']!=''){
            $where['nb_common.name']= array("like",'%'.$Data['project_name'].'%');
        }
        if(isset($Data['contract_num'])&&$Data['contract_num']!=''){
            $where['nb_protocol.contract_num']= array("like",'%'.$Data['contract_num'].'%');
        }
        if(isset($Data['protocol_num'])&&$Data['protocol_num']!=''){
            $where['nb_protocol.protocol_num']= array("like",'%'.$Data['protocol_num'].'%');
        }
        if(isset($Data['inspected'])&&$Data['inspected']!=''){
            $where['nb_protocol.inspected']= array("like",'%'.$Data['inspected'].'%');
        }
        if(isset($Data['send_person'])&&$Data['send_person']!=''){
            $where['send_person']= array("like",'%'.$Data['send_person'].'%');
        }
        if(isset($Data['check_type'])&&$Data['check_type']!=''){
            $where['check_type']= array("like",'%'.$Data['check_type'].'%');
        }
        if(isset($Data['sample_num'])&&$Data['sample_num']!=''){
            $where['nb_sample.sample_num']= array("like",'%'.$Data['sample_num'].'%');
        }
        if(isset($Data['sample_name'])&&$Data['sample_name']!=''){
            $where['nb_sample.sample_name']= array("like",'%'.$Data['sample_name'].'%');
        }
        if(isset($Data['receive_person'])&&$Data['receive_person']!=''){
            $where['receive_person']= array("like",'%'.$Data['receive_person'].'%');
        }
        if(isset($Data['is_pay'])&&$Data['is_pay']!=''){
            $where['is_pay']= $Data['is_pay'];
        }
        if(isset($Data['payed'])&&$Data['payed']!=''){
            $where['payed']= $Data['payed'];
        }
        if(isset($Data['industry_type'])&&$Data['industry_type']!=''){
            $where['industry_type']= $Data['industry_type'];
        }
        if(isset($Data['terms_pay'])&&$Data['terms_pay']!=''){
            $where['nb_protocol.terms_pay']= $Data['terms_pay'];
        }
        if(isset($Data['test_item'])&&$Data['test_item']!=''){
            $where['test_item']= array("like",'%'.$Data['test_item'].'%');
        }
        if(isset($Data['tester_name'])&&$Data['tester_name']!=''){
            $where['tester_name']= array("like",'%'.$Data['tester_name'].'%');
        }
        if(isset($Data['tester'])&&$Data['tester']!=''){
            $where['tester']= $Data['tester'];
        }
        if(isset($Data['report_step'])&&$Data['report_step']!=''){
            if($Data["report_step"]==-1)
                  $where['step']= array("lt",5);
            else $where['step']=$Data["report_step"];
        }
        if(isset($Data['is_take'])&&$Data['is_take']!=''){
            if($Data['is_take']==1){
                $where['step'] = array("egt",C("STEP_TAKE"));
            }
            else{
                $where['step'] = array("lt",C("STEP_TAKE"));
            }
        }
        if(isset($Data['date_from'])&&$Data['date_from']!=''&&isset($Data['date_to'])&&$Data['date_to']!=''){
            $where['date']=array( array("egt",$Data['date_from']),array("elt",$Data['date_to']));
        }
        if(isset($Data['pay_from'])&&$Data['pay_from']!=''&&isset($Data['pay_to'])&&$Data['pay_to']!=''){
            $where['time_pay']= array(array("egt",$Data['pay_from']),array("elt",$Data['pay_to']));
        }
        if(isset($Data['dead_from'])&&$Data['dead_from']!=''&&isset($Data['dead_to'])&&$Data['dead_to']!=''){
            $where['deadline']= array(array("egt",$Data['dead_from']),array("elt",$Data['dead_to']));
        }
        if(isset($Data['finish_from'])&&$Data['finish_from']!=''&&isset($Data['finish_to'])&&$Data['finish_to']!=''){
            $where['nb_sample.finish_line']= array(array("egt",$Data['finish_from']),array("elt",$Data['finish_to']));
        }
        return $where;
    }
} 