<?php
/**
 * Created by PhpStorm.
 */
namespace Home\Model;
use Common\Model;

class ExcelModel extends BaseModel {

    function getExcelCost(){
        $data=D("Query")->getProtocolList(1);
        $p_data=array("protocol_num","date","client_name","inspected","project_name","contract_num",
            "price","is_pay","plan_pay","operator_name","person_pay","invoice_num","send_person","receive_person","time_pay");
        $head=$_SESSION['payed'];
        $p_head=array("protocol_num"=>"B1","client_name"=>"D1","inspected"=>"F1","project_name"=>"H1","contract_num"=>"J1",
            "rc_from"=>"B2","rc_to"=>"D2","is_invoice"=>"F2","is_pay"=>"H2");
        $str_array=array("tel"=>1);
        $filename="./Public/File/system_file/costlist.xls";
        D('Base')->getExcelNF($head,$p_head,$data,$p_data,"cost_list",$str_array,$filename);
    }

    function getExcelPrt(){
        $data=D("Query")->getProtocolList(3,1);
        $p_data=array("date","protocol_num","client_name","project_name","send_person","tel","price","inspected","check_type",
            "witness_contact","witness_company","witness_tel","receive_person","report_cnt","is_conform","back_type",
            "take_type","mail_contact","mail_tel","mail_number","mail_address");
        $head=$_SESSION['prt'];
        $p_head=array("protocol_num"=>"B1","client_name"=>"D1","project_name"=>"F1","send_person"=>"H1",
            "date_from"=>"B2","date_to"=>"D2","is_pay"=>"F2");
        $str_array=array("tel"=>1);
        $filename="./Public/File/system_file/prtlist.xls";
        D('Base')->getExcelNF($head,$p_head,$data,$p_data,"cost_list",$str_array,$filename);
    }
    function getExcelFinance(){
        $data=D("Query")->getSampleList(3);
        $p_data=array("protocol_num","sample_num","date","client_name","inspected_name","project_name", "nb_protocol.price","discount_price",
            "payed_price","contract_num","invoice_num","first_price","back_reason","payed","is_pay","terms_pay","test_detail",
            "send_person","tel","receive_person","time_pay","operator_name");
        $head=$_SESSION['finance'];
        $p_head=array("client_name"=>"B1","inspected"=>"D1","project_name"=>"F1","payed"=>"H1",
            "date_from"=>"B2","date_to"=>"D2","is_pay"=>"F2","terms_pay"=>"H2");
        $str_array=array("tel"=>1);
        $filename="./Public/File/system_file/financelist.xls";
        D('Base')->getExcelNF($head,$p_head,$data,$p_data,"finance_list",$str_array,$filename);
    }
    function getExcelTask(){
        $data=D("Query")->getSampleList(1);
        $p_data=array("sample_num","step","deadline","finish_date","tester_name","check_cnt","protocol_num","date",
            "client_name","project_name","is_pay","sample_name","test_item","test_basis");
        $head=$_SESSION['task'];
        $p_head=array("sample_num"=>"B1","sample_name"=>"D1","test_item"=>"F1","tester"=>"H1",
            "dead_from"=>"B2","dead_to"=>"D2","protocol_num"=>"F2","client_name"=>"H2");
        $str_array=array("tel"=>1);
        $filename="./Public/File/system_file/tasklist.xls";
        //dump($head);
        D('Base')->getExcelNF($head,$p_head,$data,$p_data,"task_list",$str_array,$filename);
    }
    function getExcelSample(){
        $data=D("Query")->getSampleList(2);

        if(!isset($_GET["testDetail"])){  //�����Ŀ������ʾϸ��
            $p_data=array("date","protocol_num","sample_num","sample_name","client_name","project_name",
                "send_person","tel","price", "test_item","receive_person","step","is_pay","take_type","take_person",
                "mail_contact","mail_tel","mail_number","mail_address","send_num");
        }else{
            $p_data=array("date","protocol_num","sample_num","sample_name","client_name","project_name",
                "send_person","tel","price", "test_detail","receive_person","step","is_pay","take_type","take_person",
                "mail_contact", "mail_tel","mail_number","mail_address","send_num");
        }

        $head=$_SESSION['sample'];
        $p_head=array("client_name"=>"B1","project_name"=>"D1","send_person"=>"F1","check_type"=>"H1",
            "date_from"=>"B2","date_to"=>"D2","sample_name"=>"F2","report_step"=>"H2");
        $str_array=array("tel"=>1);
        $filename="./Public/File/system_file/samplelist.xls";

        D('Base')->getExcelNF($head,$p_head,$data,$p_data,"task_list",$str_array,$filename);
    }
    function getExcelRC(){
        $data=D("Query")->getRechargeList(1);
        $p_data=array("protocol_num","date","client_name","inspected_name","project_name","price","plan_pay","discount","invoice_num",
            "name","time_pay","person_pay","is_pay","terms_pay");
        $head=$_SESSION['RC'];
        $p_head=array("protocol_num"=>"B1","client_name"=>"D1","inspected"=>"F1","project_name"=>"H1",
            "rc_from"=>"B2","rc_to"=>"D2","is_pay"=>"F2","terms_pay"=>"H2");
        $str_array=array("tel"=>1);
        $filename="./Public/File/system_file/rechargelist.xls";
        //dump($head);
        D('Base')->getExcelNF($head,$p_head,$data,$p_data,"RC_list",$str_array,$filename);
    }
} 