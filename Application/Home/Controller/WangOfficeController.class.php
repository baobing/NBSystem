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
       $this->ajaxReturn(D("Wang")->saveTest());
   }
}