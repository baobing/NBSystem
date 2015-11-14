<?php
/**
 * Created by PhpStorm.
 */
namespace Home\Model;
use Think\Model;

class WangModel extends Model {

    /**
     * TODO 修改退回的协议,将协议发送财务室
     * @param rows 修改好的的报告信
     *
     */
    public function saveTest(){
        $M = M("Rollback");
        $M->startTrans();//开启事务
        $db = M("nb_sample");
        $rows = $_POST["rows"];
        $where1["protocol_num"] = $rows[0]["protocol_num"];
        //保存样品信息
        foreach($rows as $key=>$value){
            $dt = $db->create($value);
            $dt["step"] = 0;
            $flg = $db->save($dt);
            if(!$flg){
                $M->rollback();//事务有错回滚
                return $flg;
            }
        }
        //计算现在的总价
        $db_prt = M("nb_protocol");
        $dt_prt = $db_prt->where($where1)->field("id")->find();
        $sum_price = $db->where($where1)->sum("price");
        $dt_prt["price"] = $sum_price;
        $dt_prt["back_reason"] = $_POST["back_reason"];
        $dt_prt["is_finance"] = 0;

        $flg = $db_prt->save($dt_prt);
        if(!$flg){
            $M->rollback();//事务有错回滚
            return false;
        }
        $M->commit();//提交事务成功
        return true;
    }
} 