<?php
namespace Home\Controller;
use Think\Controller;
class SystemController extends BaseController {
    /**
     * 发送短信 相关设置页面
     */
    public function pageSms(){
        $db = M("web_config");
        $dt = $db->where("id=1 or id =2")->order("id asc")->select();
        $this->assign("is_sms",$dt[0]["content"]);
        $this->assign("sms_content",$dt[1]["content"]);
        $this->display("page_sms");
    }

    /**
     * 短信相关内容设置
     */
    public function setSms(){
        $db = M("web_config");
        $dt1["id"] = 1;
        $dt1["content"] = $_POST["is_sms"];
        $db->save($dt1);

        $dt1["id"] = 2;
        $dt1["content"] = $_POST["sms_content"];
        $db->save($dt1);
        $this->ajaxReturn(true);
    }
    public function itemFile(){
        $DB=M('web_config');
        $data=$DB->select();
        $this->assign('data',$data);
      //  $this->assign('permission',$_SESSION['user']['permission']);
        $this->display('update_file');
    }
    public function saveFile(){
        $date=date('Y_m_d_H_i_s',time());
        $fileName='_'.$date.'TestItem';
        $upload = new \Think\Upload();// 实例化上传类
        $upload->maxSize   =     3145728 ;// 设置附件上传大小
        $upload->exts      =     array('xls');// 设置附件上传类型
        $upload->rootPath  =     './Public/File/sample/'; // 设置附件上传根目录
        $upload->saveName  =     $fileName;
        $upload->autoSub   = false;
        // 上传文件
     //   dump( $date.'TestItem');
        $info   =   $upload->upload();
        if(!$info) {// 上传错误提示错误信息
            $this->error($upload->getError());
        }else{// 上传成功
            $webDB=M('web_config');
            $name=$webDB->where('id=3')->find();
            $name['content']=$fileName;
            $webDB->save($name);

            $dt_file["name"]=$info["file"]["name"];
            $dt_file["savename"]=$info["file"]["savename"];
            $dt_file["file_date"]=$dt_file["file_date"]=date("Y-m-d H:i:s",time());
            $dt_file["user_id"]=$_SESSION["user"]["id"];
            $dt_file["file_type"]=0;
            M("nb_file_info")->add($dt_file);


            $this->getExcelData();
            $this->success('上传成功！',"",10);

        }
    }

    /**
     * @throws \PHPExcel_Exception
     * 得到检测项目的信息  并且存入数据库
     */
    public function getExcelData(){


        $itemDB=M('nb_test_item');
        $itemDB->where('id>0')->delete();
        $webDB=M('web_config');
        $webItem=$webDB->where('id=3')->find();
        $filename="./Public/File/sample/".$webItem['content'].".xls";
        import("Org.Util.PHPExcel");
        //创建PHPExcel对象，注意，不能少了\
        $PHPExcel=new \PHPExcel();
        //如果excel文件后缀名为.xls，导入这个类
        import("Org.Util.PHPExcel.Reader.Excel5");

        $PHPReader=new \PHPExcel_Reader_Excel5();
        //载入文件
        $PHPExcel=$PHPReader->load($filename);


        $typeDB=M("nb_industry_type");                                        //清空industry type 表
        $typeDB->where("id>0")->delete();
        $strName=$PHPExcel->getSheetNames();
        for($i=0;$i<count($strName);$i++){
            $typeDt["content"]=$strName[$i];
            $typeDt["type"]=$i;
            $typeDt["word"]=chr($i+ord("A"));
            $typeDB->add($typeDt);
        }


        //获取表中的第一个工作表，如果要获取第二个，把0改为1，依次类推
        $file_array=array('item_name','test_item','test_basis','equipment','unit','price');
        $sheetCount=$PHPExcel->getSheetCount();
        for($i=0;$i<$sheetCount;$i++){
            $currentSheet=$PHPExcel->getSheet($i);//获取总列数

            $allColumn=$currentSheet->getHighestColumn();//获取总行数

            $allRow=$currentSheet->getHighestRow();
            $arr=null;
            //循环获取表中的数据，$currentRow表示当前行，从哪行开始读取数据，索引值从0开始
            $flg=false;
            for($currentRow=2;$currentRow<=$allRow;$currentRow++){
                //从哪列开始，A表示第一列

                for($currentColumn='B';$currentColumn<='G';$currentColumn++){
                    //数据坐标
                    $address=$currentColumn.$currentRow;
                    if($currentSheet->getCell('A'.$currentRow)->getValue()=="") {
                        $flg=true;
                        break;
                    }
                    //读取到的数据，保存到数组$arr中
                    $col_index=$file_array[ord($currentColumn)-ord('B')];
                    $arr[$currentRow-2][$col_index]=(string)$currentSheet->getCell($address)->getValue();
                }
                if($flg)break;
                $arr[$currentRow-2]['type']=$i;
            }

            $itemDB->addAll($arr);
            echo "<h2>".$strName[$i]."录入".count($arr)."条记录<h2/><br/>";

            //协议号 和 样本号 更新
            $db_config = M("web_config");
            $count = $db_config->where("id = ".($i+10))->count();
            if($count == 0){
                $dt_config["content"] = 0;
                $dt_config["name"] = "protocol_num".$i;
                $dt_config["id"] = $i+10;
                $db_config->add($dt_config);
            }

            $count = $db_config->where("id = ".($i+100))->count();
            if($count == 0){
                $dt_config["content"] = 0;
                $dt_config["name"] = "sample_num".$i;
                $dt_config["id"] = $i+100;
                $db_config->add($dt_config);
            }
        }
        $totalCnt = $itemDB ->count();
        echo "<h2>总共录入".$totalCnt."条记录<h2/><br/>";
    }

    /**
     * @param int $type
     * type = 6  单位信息
     */
    public function commonPage($type=0){
        $this->assign('type',$type);
        $this->display('info_list');
    }
    public function getUnitInfo($type=0){
        if($type==0){
            $DB=M('nb_client');
        }else{
            $DB=M('nb_common');
        }
        $page=$_POST['page'];
        $rows=$_POST['rows'];
        $order=$_POST['order'];
        $sort=$_POST['sort'];
        $orderSql="";
        $wSql='is_used=0 and type='.$type;
        if(isset($sort)&&!empty($sort)){$orderSql=$sort." ".$order;}
        if(isset($page)&&!empty($page)) $first=($page-1)*$rows;
        $list=$DB->limit($first,$rows)->order( $orderSql)->where($wSql)->select();
        $total=$DB->where($wSql)->count();
        $data['total']=$total;
        $data['rows']=$list;
        echo json_encode($data);
    }

    /**
     * @param int $type
     * todo 返回给combobox
     */
    public function getUnitList($type = 0){
        if($type==0){
            $DB=M('nb_client');
        }else{
            $DB=M('nb_common');
        }
        $order=$_POST['order'];
        $sort=$_POST['sort'];
        $orderSql="";
        $wSql='is_used=0 and type='.$type;
        if(isset($sort)&&!empty($sort)){$orderSql=$sort." ".$order;}
        $list=$DB->order( $orderSql)->where($wSql)->select();
        echo json_encode($list);
    }
    public function  submitUnit($type=0,$id=0){
        if($type==0){
            $DB=M('nb_client');
        }else{
            $DB=M('nb_common');
        }

        $Data=$DB->create();
        $Data['type']=$type;
        if($id==0){
            if($DB->add($Data)){
                $this->ajaxReturn(true);
            }else{
                $this->ajaxReturn(false);
            }
        }else{
            if($DB->where('id='.$id)->save($Data)){
                $this->ajaxReturn(true);
            }else{
                $this->ajaxReturn(false);
            }
        }
    }
    public function deleteUnit($type=0,$id=0){
        if($type==0){
            $DB=M('nb_client');
        }else{
            $DB=M('nb_common');
        }
        $Data['is_used'] = 1;
        if($DB->where('id='.$id)->save($Data)){
            $this->ajaxReturn(true);
        }else{
            $this->ajaxReturn(false);
        }
    }
    public function saveConfig(){
        $DB=M('web_config');
        $data['content']=$_POST['pnum'];
        $DB->where('id=1')->save($data);
        $data['content']=$_POST['snum'];
        $DB->where('id=2')->save($data);
        if(isset($_POST["company_name"])&&!empty($_POST["company_name"])){
            $data['content']=$_POST['company_name'];
            $DB->where('id=4')->save($data);
        }

        $this->ajaxReturn(true);
    }

    /**
     * @param $type
     * 样品描述更新页面
     */
    public function pageUploadSample($type){
          $this->assign("type",$type);
          $this->display("sample_info");
    }

    /**
     * 样品描述更新
     */
    public function uploadSampleInfo(){
        $date=date("Y_m_d_H_i_s",time());
        $filename=$date."_sample_info";
        $des= './Public/File/sample_info/';
        $info=D("Base")->saveFile($filename,$des);
        if($info){
            $dt_file["name"]=$info["file"]["name"];
            $dt_file["savename"]=$info["file"]["savename"];
            $dt_file["file_date"]=date("Y-m-d H:i:s",time());
            $dt_file["user_id"]=$_SESSION["user"]["id"];
            $dt_file["file_type"]=1;
            M("nb_file_info")->add($dt_file);

            $file_array=array("sample_name","sample_desc","sample_cnt","sample_format","note");
            $dt_sample=D("Base")->getInfoFromExcel($des.$dt_file["savename"],$file_array,0,'A','E');
            $db_sample=M("nb_sample_info");
            $db_sample->where("id>0")->delete();
            $db_sample->addAll($dt_sample);
            echo "<h2>总共录入".count($dt_sample)."条记录<h2/><br/>";
            $this->success("上传成功","",10);
        }  else{
            $this->error("上传出错");
        }
    }
    function getFileList(){
        $DB=M("nb_file_info")->join("nb_userinfo on nb_userinfo.id=nb_file_info.user_id");
        $where["file_type"]=$_GET["type"];
        $order="nb_file_info.id desc";
        $field="nb_file_info.*,nb_userinfo.name as username";
        D("Base")->dataList($DB,$where,$order,$field);
    }

    /**
     * 合同信息界面
     * $type
     * 0 协议合同 1挂账合同
     */
    public  function pageContract($type){
        $where_common["type"] = 1;
        $where_common["is_used"] = 0;
        $project=M("nb_common")->where($where_common)->select();
        $this->assign("project",$project);

        $field = array("id","company");
        $where["is_used"] = 0;
        $company = M("nb_client")->where($where)->field($field)->select();
        $this->assign("company",$company);
        $this->assign("type",$type);
        $this->display("page_contract");
    }

    /**
     * TODO 得到合同信息
     * @param
     * $type 0协议合同 1 挂账合同
     */
    function getContractList($type){

        $DB = M("nb_contract_charge")->join("nb_common on nb_contract_charge.project_id = nb_common.id")
            ->join("left join  nb_userinfo on nb_userinfo.id = nb_contract_charge.user_id")
            ->join("left join  nb_client as client on client.id = nb_contract_charge.client_id")
            ->join("left join  nb_client as inspected on inspected.id = nb_contract_charge.inspected_id");
        $field="nb_contract_charge.*,nb_common.name,nb_userinfo.name as operator_name,client.company as client,inspected.company as inspected";
        $order="nb_contract_charge.id desc";

        D("Base")->dataList($DB,"",$order,$field);
    }

    /**
     * @param int $id
     * @param $type 0协议合同 1折扣合同
     * 协议提交
     */
    public function submitContract($id = 0,$type){
        $db = M("nb_contract_charge");

        $this->notExitAdd();
        $dt = $db->create();

        $dt["user_id"] = $_SESSION["user"]["id"];
        $dt["sign_date"] = date("Y-m-d H:i:s",time());
        if($id==0){
            $where_delete["client_id"] =$_POST["company_id"];
            $where_delete["project_id"] =$_POST["project_id"];
            $where_delete["inspected_id"] =$_POST["inspected_id"];
            $dt_delete= $db ->where($where_delete)->find();
            if($dt_delete != null){
                $db->where("id = ".$dt_delete["id"])->delete();
            }
            $flg=$db->add($dt);
        }else{
            $dt["id"]=$id;
            $flg = $db->save($dt);
        }
        $this->ajaxReturn($flg);
    }
    public function deleteContract($type){
        if($type == 1){
            $db=M("nb_contract_charge");
        }
        $flg = $db->where("id=".$_GET["id"])->delete();
        $this->ajaxReturn($flg);
    }
    public function pageIndustry(){
        $this->display("page_industry");
    }
    public function listIndustry(){
        $db=M("nb_industry_type");
        D("Base")->dataList($db,'','id asc','');
    }
    public function submitIndustry(){
        $db=M("nb_industry_type");
        $dt=$db->create();
        $dt["id"]=$_GET["id"];
        $flg=$db->save($dt);
        $this->ajaxReturn($dt);
    }
    /**
     * 得到部长信息
     */
    public function getMinisterInfo(){
        $db = M("nb_userinfo");
        $where["p2"] =1;
        $dt = $db->join("left join nb_common on nb_common.id=nb_userinfo.department")->
        where($where)->order("minister_seq asc")->
        field("nb_userinfo.*,nb_common.name as department_name")->select();
        $data["rows"] = $dt;
        $data["total"] = count($dt);
        $this->ajaxReturn($data);
    }
    /**
     * todo 修改部长的排序界面
     */
    public function minsterPage(){
        $db = M("nb_industry_type");
        $dt_industry = $db->select();
        $this->assign("industry",$dt_industry);
        $this->assign("industryJson",json_encode($dt_industry));
        $this->display("page_minster");
    }
    /**
     * todo 修改部长的排序
     */
    public function modifySeq(){
        $db = M("nb_userinfo");
        $dt = $db->create();
        $flg = $db->save($dt);
        $this->ajaxReturn($flg);

    }
    /**
     * todo 验证工程名称 委托单位 受检单位 是否存在 不存在添加
     */
    public function notExitAdd(){


        if(isset($_POST["client_name"])){
            $db_client=M("nb_client");
            $client_name=trim($_POST["client_name"]);
            $dt_client=$db_client->where("company like '".$client_name."'")->find();
            if($dt_client==null){
                $data1["company"]=$client_name;
                $_POST["client_id"]=$db_client->add($data1);
            }else{
                $_POST["client_id"]=$dt_client["id"];
            }
        }

        if(isset($_POST["inspected_name"])){
            $db_client = M("nb_client");
            $inspected_name = trim($_POST["inspected_name"]);
            $dt_inspected = $db_client->where("company like '".$inspected_name."'")->find();
            if($dt_inspected == null){
                $data3["company"] = $inspected_name;
                $_POST["inspected_id"] = $db_client->add($data3);
            }else{
                $_POST["inspected_id"] = $dt_inspected["id"];
            }
        }

        if(isset($_POST["project_name"])){
            $project_name=trim($_POST["project_name"]);
            $db_project=M("nb_common");
            $dt_project = $db_project->where("name like '".$project_name."'")->find();
            if($dt_project==null){
                $data2["name"]=$project_name;
                $data2["type"]=1;
                $_POST["project_id"]=$db_project->add($data2);
            }else{
                $_POST["project_id"]=$dt_project["id"];
            }
        }
    }
}