<?php
/**
 * Created by PhpStorm.
 */
namespace Home\Model;
use Common\Model;

class CostModel extends BaseModel {

    /**
     * @return mixed
     * leader 审批者
     */
    function reduce(){ //打折结算 交给审核者
        $dt=M('nb_protocol')->create();
        $dt["time_pay"]=date("Y-m-d H:i:s",time());
        $dt["operator_pay"]=$_SESSION["user"]["id"];
        return M('nb_protocol')->save($dt);
    }

    function stepCost(){   //协议结算更新协议操作员时间、付款类型
        $db_prt=M('nb_protocol');
        $dt_prt=$db_prt->create();
        $dt_prt["operator_pay"]=$_SESSION["user"]["id"];
        $dt_prt["time_pay"]=date("Y-m-d H:i:s",time());
        return $db_prt->save($dt_prt);
    }


    /**
     * @return mixed
     *
     */
    function clearCost(){     //审批被拒绝以后清空数据
        $db_prt=M('nb_protocol');
        $dt_prt=$db_prt->create();
        $dt_prt["operator_pay"]=$_SESSION["user"]["id"];
        $dt_prt["time_pay"]=date("Y-m-d H:i:s",time());
        return  $db_prt->save($dt_prt);
    }



    /**
     * @return bool
     * 协议领导审核通过
     * 打折 和 退回协议中的立即支付 添加现金流转
     *
     */
    public function checkConfirm(){   //通过审批的协议 步骤更新 ，打折需加付费记录
        $M = M("Rollback");
        $M->startTrans();//开启事务

        $id=$_POST["id"];
        $check_pay=$_POST["check_pay"];
        $db_prt=M('nb_protocol');
        $dt_prt=$db_prt->create();
        $dt_prt["check_pay"]=0;
        $dt_prt["payed_price"] += doubleval($_POST["plan_pay"]);
        $dt_prt["plan_pay"] = 0;        //缴纳费用清空
        $dt_prt["is_back"] = 0;         //退回使用
        $dt_prt["time_pay"]=date("Y-m-d H:i:s",time());
        $dt_prt["operator_pay"]=$_SESSION["user"]["id"];
        $flg = $db_prt->save($dt_prt);
        if(!$flg) {
            $M->rollback();
            return $flg;
        }
        //步骤更新
        $flg=D("progress")->stepAssign($id);
        if(!$flg) {
            $M->rollback();
            return $flg;
        }

        if($check_pay==12 || $check_pay ==14){
            $dt_item = $db_prt->where("id=".$id)->find();
            $dt_item["plan_pay"] = $_POST["plan_pay"];
            $flg = $this->addRC($dt_item);
        }
        if(!$flg) {
            $M->rollback();
            return $flg;
        }else{
            $M->commit();//提交事务成功
            return true;
        }

    }

    /**
     * todo 添加付费日志
     * @param $dt_prt
     * @return mixed
     */
    public function addRC($dt_prt){ //付款日志更新;
        $db_rc = M("nb_recharge");
        $dt_rc["person_pay"]=$dt_prt["person_pay"];
        $dt_rc["plan_pay"] = $dt_prt["plan_pay"]; //必须是付款 不能是0
        $dt_rc["protocol_id"] = $dt_prt["id"];
        $dt_rc["user_id"] = $dt_prt["operator_pay"];
        $dt_rc["time_pay"] = $dt_prt["time_pay"];
        $dt_rc["terms_pay"] = $dt_prt["terms_pay"];
        $dt_rc["invoice_num"] = $dt_prt["invoice_num"];
        $flg = $db_rc->add($dt_rc);
        return $flg;
    }


    public function submitSettle(){   ////财务人员确定使用协议结算 并且进入step1
        $M = M("Rollback");
        $M->startTrans();//开启事务

        $db_prt=M('nb_protocol');
        $dt_prt=$db_prt->create();
        $dt_prt["operator_pay"]=$_SESSION["user"]["id"];
        $dt_prt["time_pay"]=date("Y-m-d H:i:s",time());
        $flg=$db_prt->save($dt_prt);
        if(!$flg) return false;

        $flg = D("Progress")->stepAssign($_POST["id"]);
        if(!$flg){
            $M->rollback();//事务有错回滚
            return false;
        }
        $M->commit();//提交事务成功
        return true;
    }
    function saveSettle(){           //提交协议结算信息,已付费
        $M = M("Rollback");
        $M->startTrans();//开启事务

        $db_prt=M("nb_protocol");
        $where["id"]=array("in",$_POST["ids"]);
        $dt_prt=$db_prt->where($where)->order("id desc")->select();
        //如果为输入发票号，则发票号不变，其他情况不需要
        if(empty($_POST["invoice_num"])){
            unset($_POST["invoice_num"]);
        }
        for($i=0;$i<count($dt_prt);$i++ ){
            $value=$dt_prt[$i];
            $flg=$this->refreshFiance($value["id"],$_POST["plan_pay"][$i]);
            if(!$flg) {
                $M->rollback();//事务有错回滚
                return false;
            }
        }
        if(!$flg){
            $M->rollback();//事务有错回滚
            return false;
        }
        $M->commit();//提交事务成功
        return true;
    }

    /**
     * TODO 立即支付不打折 更新协议 付费日志 和 任务状态
     * @return bool
     */
    public function payNow(){
        $M = M("Rollback");
        $M->startTrans();//开启事务

        $id = $_POST["id"];
        $plan_pay = $_POST["plan_pay"];
        //协议信息更新
        $db_prt=M('nb_protocol');
        $dt_prt=$db_prt->create();
        $dt_prt["operator_pay"]=$_SESSION["user"]["id"];
        $dt_prt["time_pay"]=date("Y-m-d H:i:s",time());
        $dt_prt["payed_price"] = $plan_pay;
        $dt_prt["plan_pay"] = 0;
        $dt_prt["check_pay"] = 0;
        $dt_prt["is_pay"] = 2;
        $dt_prt["payed"] = 1;
        $flg=$db_prt->save($dt_prt);
        if(!$flg){
            $M->rollback();//事务有错回滚
            return false;
        }
        //付费日志更新
        $dt_item = $db_prt->where('id='.$id)->find();
        $dt_item["plan_pay"] = $plan_pay;
        $flg = $this->addRC($dt_item);
        if(!$flg){
            $M->rollback();//事务有错回滚
            return false;
        }
        //任务书分配
        $flg = D("Progress")->stepAssign($id);
        if(!$flg){
            $M->rollback();//事务有错回滚
            return false;
        }
        $M->commit();//提交事务成功
        return true;
    }
    /**
     * TODO 立即支付打折 更新协议信息
     * @return bool
     */
    public function payDiscount(){
        $db_prt=M('nb_protocol');
        $dt_prt=$db_prt->create();
        $dt_prt["operator_pay"]=$_SESSION["user"]["id"];
        $dt_prt["time_pay"]=date("Y-m-d H:i:s",time());
        $dt_prt["check_pay"] = 2;
        $dt_prt["is_pay"] = 2;
        $flg = $db_prt->save($dt_prt);
        return $flg;
    }
    /**
     * TODO 取报告 交给 审核者
     */
    function stepTake(){     //交给审核者取报告、挂账
        $db_prt=M('nb_protocol');
        $dt_prt=$db_prt->create();
        $dt_prt["operator_pay"]=$_SESSION["user"]["id"];
        $dt_prt["time_pay"]=date("Y-m-d H:i:s",time());
        $dt_prt["check_pay"] = 1;
        $dt_prt["is_pay"] = 1;
        return $db_prt->save($dt_prt);
    }
    /**
     * TODO 返回报告 交给 审核者
     */
    function stepBack(){     //交给审核者取报告、挂账
        $db_prt=M('nb_protocol');
        $dt_prt=$db_prt->create();
        $dt_prt["operator_pay"]=$_SESSION["user"]["id"];
        $dt_prt["time_pay"]=date("Y-m-d H:i:s",time());
        $dt_prt["plan_pay"] = $_POST["plan_pay"];
        $dt_prt["check_pay"] = 4;
     //   $dt_prt["is_pay"] = 5;
        return $db_prt->save($dt_prt);
    }
    /**
     *  todo 取报告全额付款
     */
    function payTake(){
        $M = M("Rollback");
        $M->startTrans();//开启事务

        $id = $_POST["id"];
        $plan_pay = $_POST["plan_pay"];
        //协议信息更新
        $db_prt=M('nb_protocol');
        $dt_prt=$db_prt->create();
        $dt_prt["operator_pay"]=$_SESSION["user"]["id"];
        $dt_prt["time_pay"]=date("Y-m-d H:i:s",time());
        $dt_prt["payed_price"] = $plan_pay;
        $dt_prt["plan_pay"] = 0;
        $dt_prt["check_pay"] = 0;
        $dt_prt["is_pay"] = 1;
        $dt_prt["payed"] = 1;
        $flg=$db_prt->save($dt_prt);
        if(!$flg) {
            $M->rollback();//事务有错回滚
            return false;
        }
        //付费日志更新
        $dt_item = $db_prt->where('id='.$id)->find();
        $dt_item["plan_pay"] = $plan_pay;
        $flg = $this->addRC($dt_item);
        if(!$flg){
            $M->rollback();//事务有错回滚
            return false;
        }
        $M->commit();//提交事务成功
        return true;
    }

    /**
     * todo 取报告打折审批
     * @return mixed
     */
    public function payTakeDiscount(){
        $db_prt=M('nb_protocol');
        $dt_prt=$db_prt->create();
        $dt_prt["operator_pay"]=$_SESSION["user"]["id"];
        $dt_prt["time_pay"]=date("Y-m-d H:i:s",time());
        $dt_prt["check_pay"] = 2;
        $dt_prt["is_pay"] = 1;
        $flg = $db_prt->save($dt_prt);
        return $flg;
    }
    /**
     * @return mixed
     * todo takePay第二次打折通过
     */
    public function secondPass(){
        $M = M("Rollback");
        $M->startTrans();//开启事务

        $id = $_POST["id"];
        $db_prt = M('nb_protocol');
        $dt_prt= $db_prt->where("id = ".$id)->find();
        $plan_pay = $dt_prt["plan_pay"];
        $dt_prt["check_pay"]=0;
        $dt_prt["payed_price"] += doubleval($plan_pay);
        $dt_prt["plan_pay"] = 0;
        $dt_prt["is_back"] = 0;
        $dt_prt["payed"]=1;
        $dt_prt["time_pay"]=date("Y-m-d H:i:s",time());
        $dt_prt["operator_pay"]=$_SESSION["user"]["id"];
        $flg=$db_prt->save($dt_prt);
        if(!$flg) {
            $M->rollback();
            return $flg;
        }
        $dt_prt["plan_pay"] = $plan_pay;
        $flg = $this->addRC($dt_prt);

        if(!$flg){
            $M->rollback();//事务有错回滚
            return false;
        }
        $M->commit();//提交事务成功
        return true;
    }
    /**
     * @return mixed
     * todo takePay第二次打折拒绝
     */
    public function secondRefuse(){
        $id=$_POST["id"];
        $db_prt=M('nb_protocol');
        $dt_prt=$db_prt->where("id=".$id)->find();
        $dt_prt["check_pay"] = 0;
        $dt_prt["discount_price"] = 0;
        $dt_prt["plan_pay"] = 0;
        $dt_prt["time_pay"]=date("Y-m-d H:i:s",time());
        $dt_prt["operator_pay"]=$_SESSION["user"]["id"];
        $flg = $db_prt->save($dt_prt);
        return $flg;
    }
    /**
     * todo 挂账提交审核
     */
    public function stepHung(){
        $db_prt=M('nb_protocol');
        $dt_prt=$db_prt->create();
        $dt_prt["operator_pay"]=$_SESSION["user"]["id"];
        $dt_prt["time_pay"]=date("Y-m-d H:i:s",time());
        $dt_prt["check_pay"] = 3;
        $dt_prt["is_pay"] = 4;
        return $db_prt->save($dt_prt);
    }
    /**
     * todo 确认通过挂账
     */
    public function saveHungPay(){
        $M = M("Rollback");
        $M->startTrans();//开启事务

        $id = $_POST["id"];
        $check_pay = $_POST["check_pay"];

        $db_prt = M('nb_protocol');
        $dt_prt = $db_prt->create();
        $dt_prt["check_pay"] = 0;
        $dt_prt["payed_price"] = 0;
        $dt_prt["plan_pay"] = 0;        //缴纳费用清空
        $dt_prt["is_back"] = 0;         //退回使用
        $dt_prt["time_pay"] = date("Y-m-d H:i:s", time());
        $dt_prt["operator_pay"] = $_SESSION["user"]["id"];
        $flg = $db_prt->save($dt_prt);
        if (!$flg) {
            $M->rollback();//事务有错回滚
            return $flg;
        }
        //步骤更新
        $flg = D("progress")->stepAssign($id);
        if(!$flg){
            $M->rollback();//事务有错回滚
            return false;
        }
        $M->commit();//提交事务成功
        return true;
    }
    /**
     * @param $id
     * @param $plan_pay  计划收取费用
     * @return mixed
     * TODO 更新协议付费信息 以及收发记录
     */
    function refreshFiance($id,$plan_pay){

        $db_prt=M('nb_protocol');
        $dt_prt=$db_prt->create();
        $dt_prt["operator_pay"]=$_SESSION["user"]["id"];
        $dt_prt["time_pay"]=date("Y-m-d H:i:s",time());
        $dt_prt["id"]=$id;
        $dt_prt["payed_price"] += $plan_pay;
        $dt_prt["plan_pay"] = 0;
        $dt_prt["check_pay"]= 0;
        $flg=$db_prt->save($dt_prt);
        if(!$flg) return false;

        $dt_item=$db_prt->where('id='.$id)->find();
        $db_rc=M("nb_recharge");    //付款日志更新;
        $dt_rc=$db_rc->create();
        $dt_rc["plan_pay"] = $plan_pay;
        $dt_rc["protocol_id"]=$dt_item["id"];
        $dt_rc["user_id"]=$dt_item["operator_pay"];
        $dt_rc["time_pay"]=$dt_item["time_pay"];
        $dt_rc["person_pay"]=$dt_item["person_pay"];
        $dt_rc["terms_pay"]=$dt_item["terms_pay"];
        $dt_rc["invoice_num"]=$dt_item["invoice_num"];
        $flg=$db_rc->add($dt_rc);
        return $flg;
    }
    /**
     * todo 批量处理协议
     */
    public function batchPay(){
        $M = M("Rollback");
        $M->startTrans();//开启事务
        $list = $_POST["list"];
        foreach($list as $v){
            //协议信息保存
            $id = $v["id"];
            $plan_pay = $v["price"];
            $db_prt = M('nb_protocol');
            if(isset($_POST["invoice_num"]) && !empty(invoice_num) ){
                $dt_prt["invoice_num"] = $_POST["invoice_num"];
            }
            $dt_prt["id"] = $id;
            $dt_prt["check_pay"]=0;
            $dt_prt["payed_price"] = $plan_pay;
            $dt_prt["plan_pay"] = 0;
            $dt_prt["is_back"] = 0;
            $dt_prt["payed"]=1;
            $dt_prt["time_pay"]=date("Y-m-d H:i:s",time());
            $dt_prt["operator_pay"]=$_SESSION["user"]["id"];
            $flg = $db_prt->save($dt_prt);
            if(!$flg) {
                $M->rollback();//事务有错回滚
                return $flg;
            }

            $dt_prt_temp = $db_prt->where("id = ".$id)->find();
            $dt_prt_temp["plan_pay"] = $plan_pay;
            $flg = $this->addRC($dt_prt_temp);
            if(!$flg) {
                $M->rollback();//事务有错回滚
                return $flg;
            }
        }
        //添加余额
        $sql = "update nb_client set balance = balance - ".$_POST["pay_count"]." where id = ".$_POST["company_id"];
        $flg = M()->execute($sql);
        if(!$flg){
            $M->rollback();//事务有错回滚
            return $flg;
        }
        $db_log = M("nb_balance_log");
        $dt_log["company_id"] = $_POST["company_id"];
        $dt_log["pay_count"] = $_POST["pay_count"];
        $dt_log["pay_terms"] = 0;
        $dt_log["type"] = 1;
        $dt_log["pay_person"] = $_SESSION["user"]["name"];
        $dt_log["create_time"] = date("Y-m-d H:i:s",time());
        $dt_log["update_time"] = date("Y-m-d H:i:s",time());
        $flg = $db_log->add($dt_log);
        if(!$flg){
            $M->rollback();//事务有错回滚
            return false;
        }
        $M->commit();//提交事务成功
        return true;
    }

    /**
     * todo 改变余额
     */
    public function saveBalance(){
        $M = M("Rollback");
        $M->startTrans();//开启事务
        $sql = "update nb_client set balance = balance + ".$_POST["pay_count"]." where id = ".$_POST["company_id"];
        $flg = M()->execute($sql);
        if(!$flg){
            $M->rollback();//事务有错回滚
            return false;
        }
        $db = M("nb_balance_log");
        $dt = $db->create();
        $dt["type"] = 0;
        $dt["is_pay"] = 0;
        $dt["create_time"] = date("Y-m-d H:i:s",time());
        $dt["update_time"] = date("Y-m-d H:i:s",time());
        $flg = $db->add($dt);
        if(!$flg){
            $M->rollback();//事务有错回滚
            return false;
        }
        $M->commit();//提交事务成功
        return true;
    }
}