<?php
namespace Home\Controller;
use Think\Controller;
class WangOfficeController extends BaseController {
    /**
     * 显示样品的修改页面
     */
   public function pageModify(){
        $this->display("pageModify");
   }

    /**
     *  样品修改完成 保存
     *  交由领导审核
     * 财务确认中不可提交其他协议中的任务
     */
   public function saveTest(){
       $db = M("nb_sample");
       $dt = $db->create();
       $dt["step"] = 0;

       $where1["protocol_num"] = $_POST["protocol_num"];
       $db_prt = M("nb_protocol");
       $dt_prt = $db_prt->where($where1)->find();

       $db->save($dt);//保存样品信息

       //计算现在的总价
       $sum_price = $db->where($where1)->sum("price");
       $dt_prt["price"] = $sum_price;
       $dt_prt["back_reason"] = $_POST["back_reason"];
       $dt_prt["is_back"] = 1;
       $dt_prt["is_pay"] = 0;
       $flg = $db_prt->where($where1)->save($dt_prt);
       $this->ajaxReturn($flg);
   }
}