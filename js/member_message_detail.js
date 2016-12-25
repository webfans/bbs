/**
 * Created by bigface on 2016/12/25.
 */
window.onload=function () {
    var goback=document.getElementById('gobacklist');
    var del=document.getElementById('delete');
    goback.onclick=function () {
        history.back();
    };
    del.onclick=function () {
        if(confirm('你确定要删除信息吗？')){
            location.href='?action=delete&msgid='+this.title;
        }
    };
}