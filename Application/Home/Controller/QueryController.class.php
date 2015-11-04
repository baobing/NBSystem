<?php
namespace Home\Controller;
use Think\Controller;
class QueryController extends BaseController {
    function protocolPage(){
        $dtType=M("nb_industry_type")->select();         //行业类别信息
        $this->assign("industry",$dtType);
        $this->assign("industry_type",json_encode($dtType));
        $this->assign('type',0);
        $this->display('page');
    }

    /**
     * type = 1  最终报告和待打印报告
     * type = 5 收发登记报表
     * type = 6  未打印
     * is_printed = 1 表示已经最终报告 =0表示待打印报告
     * */
    function samplePage($type=1){
        $this->assign('type',$type);
        $this->assign("is_printed",$_GET["is_printed"]);
        $this->display('page');
    }
    function pageAdvance(){
        $this->assign('type',2);
        $this->display('page');
    }
    function getProtocolList(){
        if(isset($_GET["nums"])){
            D('Query')->getProtocolList(0,1);
        }else{
            D('Query')->getProtocolList();
        }

    }

    function getSampleList(){
        D('Query')->getSampleList();
    }
    function getListPrgSam(){ //试验流程样品信息datagrid
        D('Query')->getListPrgSam();
    }
    function  getListSamNT(){//试验流程样品信息datagrid不含测试人员
        D('Query')->getListSamNT();
    }
    public function getListSample(){
        D('Query')->getListSample();
    }
    public function getRechargeList(){
        D('Query')->getRechargeList();
    }
    public function getExcelPrt(){
        D("Excel")->getExcelPrt();
    }
    public function getExcelSample(){
        D("Excel")->getExcelSample();
    }
    public function sampleDetail(){
        $where["protocol_num"]=$_GET["protocol_num"];
        $dt_sample=M("nb_sample")->where($where)->select();
        $str = '<table style="width:1000px" >';
        $str .= "<tr><th>样品编号</th><th>样品名称</th><th>检测项目</th><th>样品规格</th></tr>";

        for($i = 0;$i < count($dt_sample);$i++){
            $str .= "<tr>";
            $str .= "<td style='width: 150px;'>".$dt_sample[$i]["sample_num"]."</td>";
            $str .= "<td style='width: 150px;'>".$dt_sample[$i]["sample_name"]."</td>";
            $str .= "<td style='width: 450px;'>".$dt_sample[$i]["test_detail"]."</td>";
            $str .= "<td style='width: 150px;'>".$dt_sample[$i]["sample_format"]."</td>";
            $str .= "<tr/>";
        }
        $str.="<table/>";
        echo $str;
    }

    /**
     * 已经打印
     */
    public function printed(){
        $db = M("nb_sample");
        $data["id"]  = $_POST["id"];
        $data["is_printed"] = 1;
        $flg =  $db->save($data);
        $this->ajaxReturn($flg);
    }

    /**
     * 设置优先试验 参数 id
     */
    public function setPriority(){
        $db = M("nb_sample");
        $dt["id"] = $_POST["id"];
        $dt["priority"] =1;
        $flg = $db->save($dt);
        $this->ajaxReturn($flg);
    }

    /**
     * TODO 将协议书移交到财务室
     * @param id  协议书的id
     */
    public function toFinance(){
        $db = M("nb_protocol");
        $dt["id"] = $_POST["id"];
        $dt["is_finance"] = 1;
        $flg = $db->save($dt);
        $this->ajaxReturn($flg);
    }
}