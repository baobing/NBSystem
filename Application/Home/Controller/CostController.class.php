<?php
namespace Home\Controller;
use Think\Controller;
class CostController extends BaseController {

    public function getRechargeList($id){
        $where='nb_recharge.client_id='.$id;
        $orderSql='nb_recharge.id desc';
        $field='nb_recharge.*,nb_userinfo.name as user_name';
        $db=M('nb_recharge')->join("nb_userinfo on nb_userinfo.id=nb_recharge.user_id");
        D('Base')->dataList($db,$where,$orderSql,$field);
    }
    public function saveRecharge($id=0){
        $db=M('nb_recharge');
        $dt=$db->create();
        $dt['user_id']=$_SESSION['user']['id'];
        $dt['rc_date']=date("Y-m-d H:i:s",time());
        if($id==0){
            $sql="UPDATE nb_client set balance = balance+".$dt['rc_cnt']." where id=".$dt['client_id'];
            M()->query($sql);
            $this->ajaxReturn($db->add($dt));
        }
    }
    public function pageSettle(){
        $this->display('page_settle');
    }
    public function pageRecharge(){
        $client=M('nb_client')->select();
        $this->assign("client",$client);
        $this->display('page_recharge');
    }
    public function pageCost(){
        $this->assign("step",1);
        $this->assign("type",$_GET['type']);
        $checker=M("nb_userinfo")->where("p7=1")->select();
        $this->assign("checker",$checker);
        $this->display('page_cost');
    }
    public function reduce(){    //打折申请
            $this->ajaxReturn(D('Cost')->reduce());
    }
    public function listPayed(){
        D('Cost')->listPayed();
    }
    public function pagePayed(){
        $this->display("page_payed");
    }
    public function getCompanyList(){
        D('Base')->dataList(M('nb_client'));
    }
    public function getProtocolList($id){
        $where='client='.$id.' and is_pay=2';
        $orderSql='id desc';
        D('Base')->dataList(M('nb_protocol'),$where,$orderSql);
    }
    public function getExcelCost(){
        D('Excel')->getExcelCost();
    }
    public function stepCost(){
        $this->ajaxReturn(D("Cost")->stepCost());
    }
    public function checkConfirm(){   //财务审批结果确认
        if($_POST["check_pay"]>10&&$_POST["check_pay"]<20){ //通过的话刷新
            $res = D("Cost")->checkConfirm();
        }else{                         //未通过清空之前的协议财务部分的信息
            $res=D("Cost")->clearCost();
        }
        $this->ajaxReturn($res);
    }
    public function saveSettle(){
        $this->ajaxReturn(D("Cost")->saveSettle());
    }
    public function modifyPrice(){   //完成付款协议费用修改
        $this->ajaxReturn(D("cost")->modifyPrice());
    }
    public  function stepTake(){   //交给审核者
        $this->ajaxReturn(D("cost")->stepTake());
    }
    public function second(){
        if($_POST["check_pay"]=="12")
          $this->ajaxReturn(D("cost")->secondPass());
        else
            $this->ajaxReturn(D("cost")->secondRefuse());
    }

    public function modifyInvoice(){  //修改发票号
        $db=M("nb_protocol");
        $dt=$db->create();
        $flg=$db->save($dt);
        $this->ajaxReturn($flg);
    }

    public function submitSettle(){  //财务人员确定使用协议结算 并且进入step1
        $this->ajaxReturn(D("Cost")->submitSettle());
    }

    /**
     * TODO 立即支付 初次提交
     */
    public function nowPay(){         //立即支付 更新财务信息 和 流程步骤

        if(0 == $_POST["is_reduce"]){
            $flg = D("Cost")->payNow();
        }else{
            $flg = D("Cost")->payDiscount();
        }
        $this->ajaxReturn($flg);
    }
    public function takePay(){
        $this->ajaxReturn(D("cost")->takePay());
    }
    public function detailPrice(){
        $where['protocol_num']=array("like",$_POST["protocol_num"]);
        $dt=M("nb_sample")->where($where)->field("test_item")->select();
        $this->ajaxReturn($dt);
    }
    /**
     * 退回报告 处理 提交审核
     */
    public function backTask(){
        $db_prt = M("nb_protocol");
        $dt_prt = $db_prt->create();
        $dt_prt["check_pay"] = 4;
        $flg = $db_prt->save($dt_prt);
        $this->ajaxReturn($flg);
    }
}