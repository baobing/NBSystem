<?php
namespace Home\Controller;
use Think\Controller;
class CostController extends BaseController {
    /**
     * todo 批量处理界面
     */
    public function pageBatch(){
        $this->display("page_batch");
    }
    public function getRechargeList($id){
        $where='nb_recharge.client_id='.$id;
        $orderSql='nb_recharge.id desc';
        $field='nb_recharge.*,nb_userinfo.name as user_name';
        $db=M('nb_recharge')->join("nb_userinfo on nb_userinfo.id=nb_recharge.user_id");
        D('Base')->dataList($db,$where,$orderSql,$field);
    }


    /**
     * todo 改变余额
     */
    public function saveBalance(){

        $flg = D("Cost")->saveBalance();
        $this->ajaxReturn($flg);
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

    /**
     * todo XXXXXXXX
     */
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

    /**
     * todo 财务确认 很杂的函数 最好进行拆分
     */
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

    /**
     * TODO 取报告提交审核
     */
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

    /**
     * todo 取报告付费
     */
    public function takePay(){

        if(0 == $_POST["is_reduce"]){
            echo "111";
            $flg = D("Cost")->payTake();
            echo $flg;
        }else{
            echo "222";
            $flg = D("Cost")->payTakeDiscount();
        }
        $this->ajaxReturn($flg);
    }

    /**
     * todo 挂账审核
     */
    public function stepHung(){
        $this->ajaxReturn(D("cost")->stepHung());
    }
    public function detailPrice(){
        $where['protocol_num']=array("like",$_POST["protocol_num"]);
        $dt=M("nb_sample")->where($where)->field("test_item")->select();
        $this->ajaxReturn($dt);
    }
    /**
     * todo 确认通过挂账
     */
    public function saveHungPay(){
        $this->ajaxReturn(D("cost")->saveHungPay());
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
    /**
     * todo 返回日志信息
     */
    public function getBalanceLog(){
        $rows = M("nb_balance_log")->join("nb_client as c on c.id = nb_balance_log.company_id")
                ->field("nb_balance_log.*,company")->where("company_id = ".$_GET["id"])->select();
        $data["rows"] = $rows;
        $data["total"] = count($rows);
        $this->ajaxReturn($data);
    }
    /**
     * todo 批量处理
     */
    public function batchPay(){
        $this->ajaxReturn(D("cost")->batchPay());
    }
}