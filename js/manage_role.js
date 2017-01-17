/**
 * Created by bigface on 2017-01-16.
 */
window.onload=function () {
    var deluser=document.getElementsByName('deluser');
    //lert(deluser.length);
        for(var i=0;i<deluser.length;i++){
            var username=deluser[i].value;
            deluser[i].onclick=function () {
            if(confirm('同志，您确定要删除这位用户吗？')){
                return true;
            }else{
                return false;
            }
        }
    }

}