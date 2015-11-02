<?php
/**
 * Created by PhpStorm.
 */
namespace Home\Model;
use Common\Model;

class IndexModel extends BaseModel {

     public function getMenus($permission){
         $temp =array(
             "menus"=>array(
                array("menuid"=>"1","icon"=>"icon","menuname"=>"收发人员操作",
                    "menus"=>array(
                        array("menuid"=>"11","menuname"=>"<span class='tab-title'>新的协议书</span>","icon"=>"icon-edit","url"=>__APP__.'/Home/Protocol/addPage'),
                        array("menuid"=>"12","menuname"=>"<span class='tab-title'>预委托列表</span>","icon"=>"icon-search","url"=>__APP__.'/Home/Query/pageAdvance'),
                        array("menuid"=>"11","menuname"=>"<span class='tab-title'>退回报告</span>","icon"=>"icon-edit","url"=>__APP__.'/Home/WangOffice/pageModify'),
                        array("menuid"=>"12","menuname"=>"<span class='tab-title'>协议书列表</span>","icon"=>"icon-search","url"=>__APP__.'/Home/Query/protocolPage'),
                        array("menuid"=>"16","menuname"=>"<span class='tab-title'>收发登记表</span>","icon"=>"icon-search","url"=>__APP__.'/Home/Query/samplePage/type/5'),
                        array("menuid"=>"13","menuname"=>"<span class='tab-title'>待打印报告</span>","icon"=>"icon-search","url"=>__APP__.'/Home/Query/samplePage/is_printed/0/type/6'),
                        array("menuid"=>"14","menuname"=>"<span class='tab-title'>最终报告</span>","icon"=>"icon-search","url"=>__APP__.'/Home/Query/samplePage/is_printed/1'),
                        array("menuid"=>"15","menuname"=>"<span class='tab-title'>客户审核</span>","icon"=>"icon-large-clipart","url"=>__APP__.'/Home/Advance/pagePass'),

                    )
                ),
                array("menuid"=>"2","icon"=>"icon","menuname"=>"部门主任操作",
                    "menus"=>array(
                        array("menuid"=>"21","menuname"=>"<span class='tab-title'>待分配任务</span><span id='cnt3' style='color: red;margin-left: 10px;'></span>","icon"=>"icon-large-chart","url"=>__APP__."/Home/Progress/pageAssign/type/0"),
                        array("menuid"=>"22","menuname"=>"<span class='tab-title'>已分配任务</span>","icon"=>"icon-large-chart","url"=>__APP__."/Home/Progress/pageAssign/type/1"),
                    )
                ),
                array("menuid"=>"3","icon"=>"icon","menuname"=>"试验人员操作",
                    "menus"=>array(
                        array("menuid"=>"31","menuname"=>'<span class="tab-title">初始报告</span> <span id="cnt4"  style="color: red;margin-left:18px;"></span>',"icon"=>"large-chart","url"=>__APP__."/Home/Progress/testPage/type/0"),
                        array("menuid"=>"32","menuname"=>'<span class="tab-title">待修改报告</span><span id="cnt5" style="color: red;margin-left: 10px;"></span>',"icon"=>"large-chart","url"=>__APP__."/Home/Progress/testPage/type/1"),
                        array("menuid"=>"33","menuname"=>"<span class='tab-title'>已完成报告</span>","icon"=>"icon-large-chart","url"=>__APP__."/Home/Progress/testPage/type/2"),
                    )
                ),
                array("menuid"=>"4","icon"=>"icon","menuname"=>"审核人员操作",
                    "menus"=>array(
                        array("menuid"=>"41","menuname"=>'<span class="tab-title">待审核报告</span> <span id="cnt6"  style="color: red;margin-left:10px;"></span>',"icon"=>"icon-large-chart","url"=>__APP__."/Home/Progress/checkPage/type/0"),
                        array("menuid"=>"42","menuname"=>"<span class='tab-title'>已审核报告</span>","icon"=>"icon-large-chart","url"=>__APP__."/Home/Progress/checkPage/type/1"),
                    )
                ),
                array("menuid"=>"5","icon"=>"icon","menuname"=>"批准人员操作",
                    "menus"=>array(
                        array("menuid"=>"51","menuname"=>'<span class="tab-title">待批准报告</span><span id="cnt7" style="color: red;margin-left: 10px;"></span>',"icon"=>"icon-large-chart","url"=>__APP__."/Home/Progress/pagePass/type/0"),
                        array("menuid"=>"52","menuname"=>"<span class='tab-title'>已批准报告</span>","icon"=>"icon-large-chart","url"=>__APP__."/Home/Progress/pagePass/type/1")/*,
                        array("menuid"=>"53","menuname"=>"印章管理","icon"=>"icon-large-clipart","url"=>__ROOT__.'/pageoffice/seal.aspx')*/
                    )
                ),
                 array("menuid"=>"6","icon"=>"icon","menuname"=>"财务人员操作",
                     "menus"=>array(
                         array("menuid"=>"61","menuname"=>'<span class="tab-title">处理协议 </span><span id="cnt1" style="color: red;margin-left: 17px;"></span>',"icon"=>"icon-large-chart","url"=>__APP__."/Home/Cost/pageCost/type/0"),
                         array("menuid"=>"62","menuname"=>"<span class='tab-title'>审批中协议</span>","icon"=>"icon-large-chart","url"=>__APP__."/Home/Cost/pageCost/type/1"),
                         array("menuid"=>"63","menuname"=>'<span class="tab-title">已审批协议</span> <span id="cnt2"  style="color: red;margin-left:5px;"></span>',"icon"=>"icon-large-chart","url"=>__APP__."/Home/Cost/pageCost/type/2"),
                         array("menuid"=>"64","menuname"=>"<span class='tab-title'>取报告付款</span>","icon"=>"icon-large-chart","url"=>__APP__."/Home/Cost/pageCost/type/3"),
                         array("menuid"=>"67","menuname"=>"<span class='tab-title'>挂账协议</span>","icon"=>"icon-large-chart","url"=>__APP__."/Home/Cost/pageCost/type/6"),
                         array("menuid"=>"65","menuname"=>"<span class='tab-title'>协议结算</span>","icon"=>"icon-large-chart","url"=>__APP__."/Home/Cost/pageCost/type/4"),
                         array("menuid"=>"66","menuname"=>"<span class='tab-title'>已付款协议</span>","icon"=>"icon-large-chart","url"=>__APP__."/Home/Cost/pageCost/type/5"),
                         array("menuid"=>"69","menuname"=>"<span class='tab-title'>合同信息</span>","icon"=>"icon-large-clipart","url"=>__APP__.'/Home/System/pageContract/type/1'),
                     )
                 ),
                array("menuid"=>"9","icon"=>"icon","menuname"=>"管理人员操作",
                    "menus"=>array(
                        array("menuid"=>"93","menuname"=>'<span class="tab-title">财务审批</span> <span id="cnt8"  style="color: red;margin-left:0px;"></span>',"icon"=>"icon-man","url"=>__APP__."/Home/Manger/pageCheckPay"),
                        array("menuid"=>"91","menuname"=>"<span class='tab-title'>试验信息汇总</span>","icon"=>"icon-chart","url"=>__APP__."/Home/Manger/pageTask"),
                        array("menuid"=>"97","menuname"=>"<span class='tab-title'>试验流转情况</span>","icon"=>"icon-chart","url"=>__APP__."/Home/Manger/pageProgress"),
                        array("menuid"=>"94","menuname"=>"<span class='tab-title'>收费信息报表</span>","icon"=>"icon-chart","url"=>__APP__."/Home/Manger/pageFinance"),
                        array("menuid"=>"92","menuname"=>"<span class='tab-title'>收费日志报表</span>","icon"=>"icon-chart","url"=>__APP__."/Home/Manger/pageRecharge"),
                       /*array("menuid"=>"93","menuname"=>"取报告付款审批","icon"=>"icon-man","url"=>__APP__."/Home/Manger/pageCheckPay/type/0"),
                        array("menuid"=>"94","menuname"=>"费用打折审批","icon"=>"icon-man","url"=>__APP__."/Home/Manger/pageCheckPay/type/1"),
                        array("menuid"=>"98","menuname"=>"挂账审批","icon"=>"icon-man","url"=>__APP__."/Home/Manger/pageCheckPay/type/2"),*/
                        /*array("menuid"=>"95","menuname"=>"修改协议","icon"=>"icon-edit","url"=>__APP__."/Home/Query/protocolPage"),*/
                        /*array("menuid"=>"96","menuname"=>"修改未发报告","icon"=>"icon-edit","url"=>__APP__."/Home/Manger/pageModify"),*/
                        array("menuid"=>"96","menuname"=>"<span class='tab-title'>修改报告</span>","icon"=>"icon-edit","url"=>__APP__."/Home/Manger/pageModify"),

                    )
                ),
                 array("menuid"=>"10","icon"=>"icon","menuname"=>"报告实时状态",
                     "menus"=>array(
                         array("menuid"=>"101","menuname"=>"报告实时状态","icon"=>"icon-search","url"=>__APP__.'/Home/Query/samplePage/type/3'),
                     )
                 ),
                array("menuid"=>"7","icon"=>"icon","menuname"=>"系统管理",
                     "menus"=>array(
                         /*array("menuid"=>"71","menuname"=>"印章管理","icon"=>"icon-large-clipart","url"=>__ROOT__.'/pageoffice/seal.aspx'),*/
                         array("menuid"=>"71","menuname"=>"<span class='tab-title'>样品描述</span>","icon"=>"icon-large-clipart","url"=>__APP__.'/Home/System/pageUploadSample/type/1'),
                         array("menuid"=>"73","menuname"=>"<span class='tab-title'>检测项目</span>","icon"=>"icon-large-clipart","url"=>__APP__.'/Home/System/pageUploadSample/type/0'),
                         array("menuid"=>"76","menuname"=>"<span class='tab-title'>部门信息</span>","icon"=>"icon-large-clipart","url"=>__APP__.'/Home/System/commonPage/type/3'),
                         array("menuid"=>"77","menuname"=>"<span class='tab-title'>行业类别</span>","icon"=>"icon-large-clipart","url"=>__APP__.'/Home/System/pageIndustry'),
                         /*array("menuid"=>"78","menuname"=>"合同信息","icon"=>"icon-large-clipart","url"=>__APP__.'/Home/System/pageContract')*/
                     )
                 ),
                 array("menuid"=>"11","icon"=>"icon","menuname"=>"收发管理",
                     "menus"=>array(
                      /*   array("menuid"=>"111","menuname"=>"样品描述","icon"=>"icon-large-clipart","url"=>__APP__.'/Home/System/pageUploadSample/type/1'),*/
                         array("menuid"=>"112","menuname"=>"<span class='tab-title'>客户信息</span>","icon"=>"icon-large-clipart","url"=>__APP__.'/Home/System/commonPage/type/0'),
                         array("menuid"=>"113","menuname"=>"<span class='tab-title'>工程信息</span>","icon"=>"icon-large-clipart","url"=>__APP__.'/Home/System/commonPage/type/1'),/*,
                         array("menuid"=>"15","menuname"=>"协议编号","icon"=>"icon-large-clipart","url"=>__APP__.'/Home/System/itemFile')*/
                         array("menuid"=>"113","menuname"=>"<span class='tab-title'>合同信息</span>","icon"=>"icon-large-clipart","url"=>__APP__.'/Home/System/pageContract/type/1'),
                         array("menuid"=>"113","menuname"=>"<span class='tab-title'>单位信息</span>","icon"=>"icon-large-clipart","url"=>__APP__.'/Home/System/commonPage/type/6'),
                         array("menuid"=>"77","menuname"=>"<span class='tab-title'>行业类别</span>","icon"=>"icon-large-clipart","url"=>__APP__.'/Home/System/pageIndustry'),
                         array("menuid"=>"71","menuname"=>"<span class='tab-title'>样品描述</span>","icon"=>"icon-large-clipart","url"=>__APP__.'/Home/System/pageUploadSample/type/1'),
                         array("menuid"=>"73","menuname"=>"<span class='tab-title'>检测项目</span>","icon"=>"icon-large-clipart","url"=>__APP__.'/Home/System/pageUploadSample/type/0'),
                       /*  array("menuid"=>"73","menuname"=>"短信设置","icon"=>"icon-large-clipart","url"=>__APP__.'/Home/System/pageSms'),*/
                         array("menuid"=>"17","menuname"=>"<span class='tab-title'>部门主任设置</span>","icon"=>"icon-large-clipart","url"=>__APP__.'/Home/System/minsterPage'),
                     )
                 ),
                array("menuid"=>"8","icon"=>"icon","menuname"=>"用户管理",
                    "menus"=>array(
                        array("menuid"=>"81","menuname"=>"<span class='tab-title'>用户管理</span>","icon"=>"icon-man","url"=>__APP__."/Home/User/index"),
                        array("menuid"=>"82","menuname"=>"<span class='tab-title'>登录日志</span>","icon"=>"icon-man","url"=>__APP__."/Home/User/loginfoPage")
                    )
                )
            )
        );
         $menus=array("menus"=>array());
         if($_SESSION["user"]["p0"]==1){
             $menus["menus"]=$temp["menus"];
         }else {
             for($i=1;$i<=9;$i++){
                 if($_SESSION["user"]["p".$i]==1){
                     array_push($menus["menus"],$temp["menus"][$i-1]);
                     if($i==9){
                         $cnt=count($temp["menus"])-1;
                         array_push($menus["menus"],$temp["menus"][$cnt]);
                     }
                     if($i==1){
                         $cnt=count($temp["menus"])-2;
                         array_push($menus["menus"],$temp["menus"][$cnt]);
                     }
                 }

             }
         }
         return $menus;
     }
} 