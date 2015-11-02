/**
 * Created by Administrator on 2015/5/13.
 */
String.prototype.trim=function() {    //trim函数
    return this.replace(/(^\s*)|(\s*$)/g,'');
};
function showSuccess(){
    var options = {
        title: "操作提示",
        msg: "操作成功！",
        showType: 'show',
        timeout: 2000
    };
    $.messager.show(options);
}
function showFail(){
    var options = {
        title: "操作提示",
        msg: "<div style='color: red;'>操作失败！</div>",
        showType: 'show',
        timeout: 2000
    };
    $.messager.show(options);
}
$(function(){
    var buttons = $.extend([], $.fn.datebox.defaults.buttons);
    buttons.splice(1, 0, {
        text: '清空',
        handler: function(target){
            $(target).datebox("setValue","");
            $(this).closest("div.combo-panel").panel("close");
        }
    });
    $('.easyui-datebox').datebox({buttons: buttons});
    $(".datebox :text").attr("readonly","readonly");
});
function toFiveBit(num){
    var t=parseInt(num)+100001;
    var str='1'+t;
    num=str.substr(2,5);
    return num;
}
function mySerializeObject(form){

    var obj={};
    $ .each( $(form).serializeArray() ,function(index ,param){
        if (!(param.name in obj)){
            obj[param.name ]=param.value;
        }
    });
    return obj;
}
function isSelected(dg){
    var row=$(dg).datagrid('getSelected');
    if(row==null) {
        $.messager.alert("操作提示","未选中其中一行！");
        return ;
    }
    return row;
}
function confirmPost(dg,content,url,post_data,fn,var_data){ //

    if(fn==null){
        fn=function(res){
            if(res){
                $(dg).datagrid('reload');
                showSuccess();
            }else{
                showFail();
            }
        };
    }
    $.messager.confirm("操作提示",content,function(data){
        if(var_data!=null){
            for(var i in var_data  ){
                var item=var_data[i];
                if(item['type']==0){
                    post_data[item['key']]=$(item['value']).val();
                }else if(item['type']==1){    //复选框
                    var note_refuse=[];
                    $("[name='"+item['value']+"]:checked").each(function(){
                        note_refuse.push($(this).val());
                    });
                    var str=note_refuse.join(",");
                    post_data[item['key']]=str;
                }

            }
        }
        if(data){
            $.post(url,post_data,fn);
        }
    });
}

function serializeObject( fm){
    var obj={};
    $ .each( $(fm ).serializeArray() ,function(index ,param){
        if (!(param.name in obj)){
            obj[param.name ]=param.value;
        }
    });
    return obj;
}
function sampleSearchClick(){
    var data=serializeObject("#toolbar");
    var date_from=data["dead_from"];
    var date_to=data["dead_to"];
    if((date_from=="")^(date_to=="")){
        $.messager.alert("操作失误","日期不全！");return;
    }
    if(date_from>date_to){
        $.messager.alert("操作失误","日期区间有误！");return;
    }
    $("#s_dg").datagrid("load",data);
}
function firstMultiSelect(){
    var rows=$("#s_dg").datagrid("getChecked");
    if(rows==null) {
        $.messager.alert("操作提示","请先选择一行！！");
        return ;
    }
    var ids=[];
    for(var i=0;i<rows.length;i++){
        ids.push(rows[i]["id"]);
    }
    return ids;
}
function refuseTest(url){
    var ids=firstMultiSelect();
    $.messager.confirm("操作提示", "<div><input type='checkbox' name='cb_refuse' value='详见报告'checked/>详见报告</div>" +
    "<div><input type='checkbox' name='cb_refuse' value='内容不详 '/>内容不详</div>", function (flg) {
        var note_refuse=[];
        $("[name='cb_refuse']:checked").each(function(){
            note_refuse.push($(this).val());
        });
        var str=note_refuse.join(",");
        if (flg) {
            $.post(url,{ids:ids,step:2,refuse_note:str},function(data){
                if(data) {
                    $("#s_dg").datagrid('reload');
                    showSuccess();
                } else {
                    showFail();
                }
            });
        }
    });
}

function fmStep(value,row,index){
    if(value==0){
        return "退回状态";
    }else if(value==0){
        return "初始状态";
    }else if(value==1){
        return "待分配";
    }else if(value==2){
        return "试验中";
    }else if(value==3){
        return "审核中";
    }else if(value==4){
        return "待批准";
    }else if(value==5){
        return "完成";
    }else if(value==6){
        return "已取走";
    }else if(value==7){
        return "已邮寄";
    }
}

function fm_ip(value){
    if(value==0) return "";
    if(value==1) return "取报告付款";
    if(value==2) return "立即支付";
    if(value==3) return "协议结算";
    if(value==4) return "挂账";
}
function fm_terms_pay(value){
    if(value==0) return "";
    if(value==1) return "现金";
    if(value==2) return "刷卡";
}
function fm_payed(value){
    if(value==0) return "否";
    if(value==1) return "是";
}
function fm_industry_type(value){
    return industry_type[value]["content"];
}
function fmIsPay(value){
    if(value==0) return "未处理";
    else if(value==1) return "取报告付款";
    else if(value==2) return "立即付款";
    else if(value==3) return "协议结算";
    if(value==4) return "挂账";
}
function fm_check_pay(value){
    if(value<20) return "<div class='icon-ok' style='width: 20px;height: 20px;'></div>";
    if(value<30) return "<div class='icon-no' style='width: 20px;height: 20px;'></div>";
}
function checkCnt(val){
    if(val==0) return "初次试验";
    return "第"+val+"次修改"
}
function fm_tester(val){
    if(val==null) return "未分配";
    return val;
}
function fm_finish(val){
    if(val=="") return "未完成";
    return val;
}
function checkCnt(val){
    if(val==0) return "初次试验";
    return "第"+val+"次修改"
}
function fm_ck(val){
    switch (val){
        case '1': return "取报告付款审批";break;
        case '2': return "打折审批";break;
        case '3': return "挂账审批";break;
        case '4': return "退回报告审批";break;
    }
}
function showTask(url){
    var rows=$("#s_dg").datagrid("getChecked");
    if(rows==null) {
        $.messager.alert("操作提示","请选择一行");
        return ;
    }
    if(rows.length>4){
        $.messager.alert("操作提示","对不起，选择不能多于四行！");
        return ;
    }
    var ids=[];
    for(var i in rows){
        ids.push(rows[i]["id"]);
    }

    var  str=ids.join(",");
    var iWidth=1200; //弹出窗口的宽度;
    var iLeft = (window.screen.availWidth-10-iWidth)/2; //获得窗口的水平位置;
    var iHeight=window.screen.availHeight-100; //弹出窗口的高度;
     url+="/ids/"+str;
    window.open(url, "check_sample"+rows[0].sample_num, "height="+iHeight+",width=1200,left="+iLeft);

}

function showTest(url){
    var row=$("#s_dg").datagrid("getSelected");
    if(row==null) {
        $.messager.alert("操作提示","请先选择一行！！");
        return ;
    }
    if(row){
        var iWidth=1200; //弹出窗口的宽度;
        var iLeft = (window.screen.availWidth-10-iWidth)/2; //获得窗口的水平位置;
        var iHeight=window.screen.availHeight-100; //弹出窗口的高度;
        window.open(url+"/id/"+row.id, "sample"+row.sample_num,
            "height="+iHeight+",width=1200, top=0, left=20");
    }
}

