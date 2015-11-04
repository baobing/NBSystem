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
       $where1["protocol_num"] = $rows[0]["protocol_num"];
       //保存样品信息
       foreach($rows as $key=>$value){
           $dt = $db->create($value);
           $dt["step"] = 0;
           $flg = $db->save($dt);
           if(!$flg){
               $this->ajaxReturn(flase);
           }
       }
       //计算现在的总价
       $db_prt = M("nb_protocol");
       $dt_prt = $db_prt->where($where1)->field("id")->find();
       $sum_price = $db->where($where1)->sum("price");
       $dt_prt["price"] = $sum_price;
       $dt_prt["back_reason"] = $_POST["back_reason"];
       $dt_prt["is_finance"] = 1;
       $dt_prt["is_pay"] = 0;

       $flg = $db_prt->save($dt_prt);
       $this->ajaxReturn($flg);
   }
}