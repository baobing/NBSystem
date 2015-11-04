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
     * TODO 修改退回的协议,将协议发送财务室
     * @param rows 修改好的的报告信
     *
     */
   public function saveTest(){
       $db = M("nb_sample");
       $rows = $_POST["rows"];
       $db_prt = M("nb_protocol");
       $where1["protocol_num"] = $_POST["protocol_num"];
       //保存样品信息
       foreach($rows as $row){
           $dt = $db->create($row);
           $dt["step"] = 0;
           $db->save($dt);
       }
       $dt_prt = $db_prt->where($where1)->find();
       //计算现在的总价
       $sum_price = $db->where($where1)->sum("price");
       $dt_prt["price"] = $sum_price;
       $dt_prt["back_reason"] = $_POST["back_reason"];
       $dt_prt["is_finance"] = 1;
       $dt_prt["is_pay"] = 0;
       $flg = $db_prt->where($where1)->save($dt_prt);
       $this->ajaxReturn($flg);
   }
}