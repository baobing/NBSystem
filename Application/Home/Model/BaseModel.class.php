<?php
/**
 * Created by PhpStorm.
 */
namespace Home\Model;
use Think\Model;

class BaseModel extends Model {

    /**
     * $DB 数据库信息
     * $where 条件限制
     * $orderSql 订单sql
     * $field  显示字段
     * */
    public function dataList($DB,$where='',$orderSql='',$field=''){
        
        $page=$_POST['page'];
        $rows=$_POST['rows'];
        $order=$_POST['order'];
        $sort=$_POST['sort'];
        if(isset($sort)&&!empty($sort)){
            if($orderSql!='') $orderSql.=',';
            $orderSql.= $sort." ".$order;
        }
        if(isset($page)&&!empty($page))
            $first=($page-1)*$rows;

        $list=$DB->limit($first,$rows)->order( $orderSql)->field($field)->where($where)->select();
        $total=$DB->where($where)->count();
        if($list==null) $list='';
        $data['total']=$total;
        $data['rows']=$list;
        $data['where']=$where;
        echo json_encode($data);
    }
    /**
     * $DB 数据库信息
     * $where 条件限制
     * $orderSql 订单sql
     * $field  显示字段
     * */
    public function dataList1($DB,$where='',$orderSql='',$field=''){

        $page=$_POST['page'];
        $rows=$_POST['rows'];
        $order=$_POST['order'];
        $sort=$_POST['sort'];
        if(isset($sort)&&!empty($sort)){
            if($orderSql!='') $orderSql.=',';
            $orderSql.= $sort." ".$order;
        }
        if(isset($page)&&!empty($page))
            $first=($page-1)*$rows;

        $list=$DB->limit($first,$rows)->order( $orderSql)->field($field)->where($where)->select();

        if($list==null) $list='';
        $data['rows']=$list;
        $data['where']=$where;
        return $data;
    }
    /**
     * $data 二维码数据和名称
     * */
    public function getQR($data){
        $filePath="./Public/File/qrcode/$data.jpg";
        if(file_exists($filePath)) return ;
        vendor("phpqrcode.phpqrcode");
        $level = 'L';
        $size = 4;
        \QRcode::png($data,$filePath , $level, $size,0,true);
    }
    /**
     *
     * $db数据库连接后的结果
     * $where_filed  限制字段
     * $mul 选择多行 还是一行
     * $field 字段
     * $order 排序
     * $where_sql 指定查询语句
     *
     **/
    public function getBaseInfo($db,$where_filed="",$mul=1,$field="",$order='',$where_sql=""){
        if($where_filed!=""){
            foreach($where_filed as $key=>$vale){
                if($where_sql!='') $where_sql.=' and ';
                if(is_numeric($vale)) $where_sql.=$key.'='.$vale;
                else $where_sql.=$key.'="'.$vale.'"';
            }
        }
        //    dump($where_sql);
        if($mul!=0){
            $dt=$db->where($where_sql)->field($field)->order($order)->select();
        }else{
            $dt=$db->where($where_sql)->field($field)->order($order)->find();
        }
        return $dt;
    }
    /**
     *dt 数据
     * time 多次出现数据的名称及次数
     * des 保存位置
     * src 模板文件所在
     * title word标题
     * operator 操作员
     *
     */
    public  function openWord($dt,$time,$des,$src,$title,$model,$operator,$btn=null,$CustomToolbar=false,$Menubar=false){

        $doc = new \COM("PageOfficeASP.WordWriter.WordDocument");
        if(!file_exists($des)) {
            copy($src,$des);
        }

        foreach($dt as $value){     //数据填充
            foreach($value as $key1=>$value1){
              //  if($key1=="sample_format3") dump($key1."  ".$value1);
                if(isset($time[$key1])){
                    for($i=0;$i<$time[$key1];$i++){
                        $doc->OpenDataRegion("PO_".$key1.$i)->Value =iconv("UTF-8","GBK",$value1) ;
                    }
                }else
                    $doc->OpenDataRegion("PO_".$key1)->Value =iconv("UTF-8","GBK",$value1) ;
            }
        }

        $PageOfficeCtrl = new \COM("PageOfficeASP.PageOfficeCtrl");

        if($btn!=null){                //按钮添加
            foreach($btn['btn'] as $key=>$value){
                $PageOfficeCtrl->AddCustomToolButton(iconv("UTF-8","GBK",$value['name']), $value['fn'], $value['icon']);
            }
            if($btn['save']!=null){
                $PageOfficeCtrl->SaveFilePage =$btn["save"];
            }
        }

        $PageOfficeCtrl->OCXCodeBase = "pageoffice/posetup.exe#version=2,0,2,2";
        $PageOfficeCtrl->Caption =iconv("UTF-8","GBK",$title) ;
        $PageOfficeCtrl->ServerPage =  __ROOT__."/pageoffice/server.aspx";
        $PageOfficeCtrl->SetWriter($doc);
        $PageOfficeCtrl->UserAgent = $_SERVER['HTTP_USER_AGENT'];
        $PageOfficeCtrl->WebOpen(__ROOT__."/".$des, $model, iconv("UTF-8","GBK",$operator));
        $PageOfficeCtrl->CustomToolbar=$CustomToolbar;
        $PageOfficeCtrl->Menubar=$Menubar;
        return $PageOfficeCtrl;

    }
    /**
     * 非规定格式excel
     *
     * */

    public function getExcelNF($head,$p_head,$data,$p_data,$name,$str_array,$filename,$title_data){
        import("Org.Util.PHPExcel");
        //要导入的xls文件，位于根目录下的Public文件夹

        $PHPExcel=new \PHPExcel();
        //如果excel文件后缀名为.xls，导入这个类
        import("Org.Util.PHPExcel.Reader.Excel5");
        $PHPReader=new \PHPExcel_Reader_Excel5();
        //载入文件
        $PHPExcel=$PHPReader->load($filename);

        $date = date("Y-m-d H:i:s",time());
        $fileName = $date."".$name.".xls";
        //创建PHPExcel对象，注意，不能少了\

        //设置表头
        $objActSheet = $PHPExcel->getActiveSheet();
        foreach($p_head as $key=>$value){
            $temp=$this->typeChange($key,$head[$key]);
            $objActSheet->setCellValue($value,$temp);
        }

/*        if($title_data != null){
            $span=0;
            foreach($title_data as $key=>$value){
                $j = chr($span+ord('A'));
                $objActSheet->setCellValue($j."4",$title_data[$key]);
                $span++;
            }
        }*/
        $column = 5;
        foreach($data as $keySeq => $rows){ //行写入
            $span=0;
            foreach($p_data as $value){// 列写入
                $j = chr($span+ord('A'));
                //  dump($j.$column."   ".$rows[$value]);

                $str='';
                if(isset($str_array[$value])){
                    $str='  ';
                }
                $temp=$this->typeChange($value,$rows[$value]);
                $objActSheet->setCellValue($j.$column,$str.$temp);
                $span++;
            }
            $column++;
        }

        $fileName = iconv("utf-8", "gb2312", $fileName);
        //重命名表
        // $objPHPExcel->getActiveSheet()->setTitle('test');
        //设置活动单指数到第一个表,所以Excel打开这是第一个表
        $PHPExcel->setActiveSheetIndex(0);
        header('Content-Type: application/vnd.ms-excel');
        header("Content-Disposition: attachment;filename=\"$fileName\"");
        header('Cache-Control: max-age=0');

        $objWriter = \PHPExcel_IOFactory::createWriter($PHPExcel, 'Excel5');
        $objWriter->save('php://output'); //文件通过浏览器下载
        exit;
    }
    function typeChange($key,$value){
        if($key == "is_invoice"){
            if($value==0) return "未开发票";
            if($value==1) return "已开发票";
        }
        if($key=="is_pay"){
            if($value==0) return "";
            if($value==1) return "取报告付款";
            if($value==2) return "立即支付";
            if($value==3) return "协议结算";
            if($value==4) return "挂账";
        }
        if($key=="payed"){
            if($value==0) return "否";
            if($value==1) return "是";
        }
        if($key=="terms_pay"){
            if($value==0) return "";
            if($value==1) return "现金";
            if($value==2) return "刷卡";
        }
        if($key=="step"){
            if($value==-1){
                return "退回报告";
            }else if($value==0){
                return "初始状态";
            }else if($value==1){
                return "待分配";
            }else if($value==2){
                return "试验中";
            }else if($value==3){
                return "审核中";
            }else if($value==4){
                return "待批准";
            }else if($value==5){
                return "完成";
            }else if($value==6){
                return "已取走";
            }else if($value==7){
                return "已邮寄";
            }
        }
        if($key=="report_step"){
            if($value==-1){
                return "未完成";
            }else if($value==5){
                return "已完成";
            }else if($value==6){
                return "已取走";
            }else if($value==7){
                return "已邮寄";
            }
        }
        if($key=="industry_type"){
           $item=M("nb_industry_type")->where("type=".$value)->find();
            return $item["content"];
        }
        if($key=="tester"){
            $item=M("nb_userinfo")->where("id=".$value)->find();
            return $item["name"];
        }
        return $value;
    }
    /**
     *从excel       中得到信息
     * $filename    文件地址
     * $file_array  数据库中列名
     * $i           excel中第i个sheet
     * $start       开始列
     * $end         结束列
    */
    function getInfoFromExcel($filename,$file_array,$i,$start,$end){
        import("Org.Util.PHPExcel");
        //创建PHPExcel对象，注意，不能少了\
        $PHPExcel=new \PHPExcel();
        //如果excel文件后缀名为.xls，导入这个类
        import("Org.Util.PHPExcel.Reader.Excel5");

        $PHPReader=new \PHPExcel_Reader_Excel5();
        //载入文件
        $PHPExcel=$PHPReader->load($filename);
        $currentSheet=$PHPExcel->getSheet($i);//获取总列数

        $allColumn=$currentSheet->getHighestColumn();//获取总行数

        $allRow=$currentSheet->getHighestRow();
        $arr=null;
        //循环获取表中的数据，$currentRow表示当前行，从哪行开始读取数据，索引值从0开始
        $flg=false;
        for($currentRow=2;$currentRow<=$allRow;$currentRow++){
            //从哪列开始，A表示第一列
            for($currentColumn=$start;$currentColumn<=$end;$currentColumn++){
                //数据坐标
                $address=$currentColumn.$currentRow;
                if($currentSheet->getCell('A'.$currentRow)->getValue()=="") {
                    $flg=true;break;
                }
                //读取到的数据，保存到数组$arr中
                $col_index=$file_array[ord($currentColumn)-ord($start)];
                $cellValue = trim((string)$currentSheet->getCell($address)->getValue());
                $arr[$currentRow-2][$col_index] = $cellValue;
            }
            if($flg)break;
        }
        return $arr;
    }
    /**
     *上传文件
     * $filename 文件别名
     * $desc     保存位置
     * $type     文件类型
     */
    function saveFile($filename,$desc,$type=array('xls')){
        $upload = new \Think\Upload();// 实例化上传类
        $upload->maxSize   =     3145728 ;// 设置附件上传大小
     //   $upload->exts      =     $type;// 设置附件上传类型
        $upload->rootPath  =     $desc; // 设置附件上传根目录
        $upload->saveName  =     $filename;
        $upload->autoSub   = false;
        // 上传文件
        $info   =   $upload->upload();
        return $info;
    }

    public  function getBarcode($filename){
        $filePath = 'Public/File/barcode/'.$filename.".png";
        if(file_exists($filePath)) return ;
        // 引用class文件夹对应的类
        require_once(__ROOT__.'/ThinkPHP/Library/Vendor/barcode/class/BCGFontFile.php');
        require_once(__ROOT__.'/ThinkPHP/Library/Vendor/barcode/class/BCGColor.php');
        require_once(__ROOT__.'/ThinkPHP/Library/Vendor/barcode/class/BCGDrawing.php');
        require_once(__ROOT__.'/ThinkPHP/Library/Vendor/barcode/class/BCGcode39.barcode.php');

// 加载字体大小
        $font = new \BCGFontFile('ThinkPHP/Library/Vendor/barcode/font/Arial.ttf', 18);

//颜色条形码
        $color_black = new \BCGColor(0, 0, 0);
        $color_white = new \BCGColor(255, 255, 255);

        $drawException = null;
        try {
            $code = new \BCGcode39();
            $code->setScale(1.5);
            $code->setThickness(13); // 条形码的厚度
            $code->setForegroundColor($color_black); // 条形码颜色
            $code->setBackgroundColor($color_white); // 空白间隙颜色
            $code->setFont($font); //
            $code->parse($filename); // 条形码需要的数据内容
        } catch(Exception $exception) {
            $drawException = $exception;
        }

//根据以上条件绘制条形码
        $drawing = new \BCGDrawing('Public/File/barcode/'.$filename.".png", $color_white);
        if($drawException) {
            $drawing->drawException($drawException);
        } else {
            $drawing->setBarcode($code);
            $drawing->draw();
        }

// 生成PNG格式的图片
        header('Content-Type: image/png');
        $drawing->finish(\BCGDrawing::IMG_FORMAT_PNG);
    }

    /**
     * 新输入的工程名 和 客户名 添加数据库中
     * @return mixed
     */
    public function temp(){
        if(isset($_POST["client"])){
            $client_name=trim($_POST["client"]);
            $db_client=M("nb_client");
            $dt_client=$db_client->where("company like '".$client_name."'")->find();
            if($dt_client==null){
                $data1["company"]=$client_name;
                $ids["client"]=$db_client->add($data1);
            }else{
                $ids["client"]=$dt_client["id"];
            }
        }
        if(isset($_POST["project_id"])){
            $project_name=trim($_POST["project_id"]);
            $db_project=M("nb_common");
            $dt_project=$db_project->where("name like '".$project_name."'")->find();
            if($dt_project==null){
                $data2["name"]=$project_name;
                $data2["type"]=1;
                $ids["project"]=$db_project->add($data2);
            }else{
                $ids["project"]=$dt_project["id"];
            }
        }
        return $ids;
    }
    /**
     * @param $phone  电话号码
     * @param $content 发送内容
     */
    public function send($phone,$content){

        $flag = 0;

        //     $content = iconv( "UTF-8", "gb2312//IGNORE" ,'您的验证码是55783，请妥善保管【易餐.千红】');
        $content = iconv( "UTF-8", "gb2312//IGNORE" ,$content);
        //要post的数据
        $argv = array(
            'sn'=>'DXX-WSS-10C-05978', //提供的账号
            'pwd'=>"36F5DB12A50AD9CC0FC0E1AA5338E460", //此处密码需要加密 加密方式为 md5(sn+password) 32位大写
            'mobile'=>$phone,//手机号 多个用英文的逗号隔开 post理论没有长度限制.推荐群发一次小于等于10000个手机号
            'content'=>$content,//短信内容
            'ext'=>'',
            'rrid'=>'',//默认空 如果空返回系统生成的标识串 如果传值保证值唯一 成功则返回传入的值
            'stime'=>''//定时时间 格式为2011-6-29 11:09:21
        );
//构造要post的字符串
        $params = '';
        foreach ($argv as $key=>$value) {
            if ($flag!=0) {
                $params .= "&";
                $flag = 1;
            }
            $params.= $key."="; $params.= urlencode($value);
            $flag = 1;
        }
        $length = strlen($params);
        //创建socket连接
        $fp = fsockopen("sdk2.entinfo.cn",8060,$errno,$errstr,10) or exit($errstr."--->".$errno);
        //构造post请求的头
        $header = "POST /webservice.asmx/mt HTTP/1.1\r\n";
        $header .= "Host:sdk2.entinfo.cn\r\n";
        $header .= "Content-Type: application/x-www-form-urlencoded\r\n";
        $header .= "Content-Length: ".$length."\r\n";
        $header .= "Connection: Close\r\n\r\n";
        //添加post的字符串
        $header .= $params."\r\n";
        //发送post的数据
        fputs($fp,$header);
        $inheader = 1;
        while (!feof($fp)) {
            $line = fgets($fp,1024); //去除请求包的头只显示页面的返回数据
            if ($inheader && ($line == "\n" || $line == "\r\n")) {
                $inheader = 0;
            }
            if ($inheader == 0) {
                // echo $line;
            }
        }
        //<string xmlns="http://tempuri.org/">-5</string>
        $line=str_replace("<string xmlns=\"http://tempuri.org/\">","",$line);
        $line=str_replace("</string>","",$line);
        $result=explode("-",$line);
        // echo $line."-------------";
        if(count($result)>1){
            return false ;
        }

        return true;
    }
} 