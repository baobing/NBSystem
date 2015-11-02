/**
 * Created by Administrator on 2015/5/10.
 */
/**
 *  页面加载等待页面
 *
 * @author gxjiang
 * @date 2010/7/24
 *
 */
var height = window.screen.height-25;
var width = window.screen.width;
var leftW = width/2-250;



var _html = "<div id='loading' style='position:absolute;left:0;width:100%;height:"+height+"px;top:0;background:#4b72a4;z-index:999;'>\
 <div style='position:absolute;  cursor1:wait;left:"+leftW+"px;top:200px;width:auto;height:16px;padding:12px 5px 10px 30px;\
 background:#fff url(/Public/easyui/themes/default/images/loading.gif) no-repeat scroll 5px 10px;border:2px solid #ccc;color:#000;'>\
 正在加载，请等待...\
 </div></div>";

window.onload = function(){
    var _mask = document.getElementById('loading');
    _mask.parentNode.removeChild(_mask);
}


document.write(_html);
