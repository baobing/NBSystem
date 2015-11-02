<?php
namespace Home\Controller;
use Think\Controller;
class SampleController extends BaseController {
    /**
     * @param $type
     * @param $protocol_num 协议编号
     * 样品添加页面
     */
    public function selectSample($type,$protocol_num){
        $current=date('Y-m-d',time());
        $this->assign('today',$current);

        $itemDB=M('nb_test_item');                        //检测名称发送到前台
        $groupName=$itemDB->where('type='.$type)->group('item_name')->order('item_name asc')->select();
        $this->assign('group_name',$groupName);
   //     dump($groupName);
        $descDB=M('nb_common');
        $desc=$descDB->where('is_used=0 and type=2')->select();
        $this->assign('desc',$desc);


        $this->assign('type',$type);
        $this->assign('protocol_num',$protocol_num);

        $webDB=M('web_config');
        $s_num=$webDB->where('id=6')->find();   //协议书自增num的值加一并且发送到
        $s_num["content"]+=1;
        $webDB->save($s_num);
        $sample_num=date('Y',time()).substr(($s_num['content']+100000),1,5);
        $this->assign('sample_num',$sample_num);

        //部门主任排序后结果
        $dt_assign=M("nb_userinfo")->where("p2=1 and minster_industry_type like '%".$type.",%'")->order("minister_seq asc")->select();
        $this->assign("assign_person",$dt_assign);

        //单位信息
        $unitInfo = M("nb_common")->where("is_used=0 and type = 6")->select();
        $this->assign("unitInfo",$unitInfo);
        $this->display('select');
    }
    public function getTestItem($type){  //根据检测名称得到符合的检测项目列表
        $itemDB=M('nb_test_item');
        $list=$itemDB->where('type='.$type.' and item_name LIKE "'.$_POST['name'].'"')->order('id asc')->select();
        $this->ajaxReturn($list);
    }
    public function submitSample($type=0){  //样品提交

        $DB=M('nb_sample');
        $data=$DB->create();
        if($type==0&&$DB->add($data)){
            $this->ajaxReturn(true);
        }else if($type==1&&$DB->save($data)){
            $this->ajaxReturn(true);
        }else {
            $this->ajaxReturn(false);
        }
    }
    public function getSampleInfo(){
        $where["sample_name"]=array("like",$_POST["name"]);
        $dt=M("nb_sample_info")->where($where)->find();
        $this->ajaxReturn($dt);
    }
}