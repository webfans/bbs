/**
 * Created by bigface on 2017-01-17.
 */
window.onload=function () {
    var upload=document.getElementById('upload');
    var fm=document.getElementsByTagName('form')[0];
    upload.onclick=function () {
        centerWindow('upload.php?dir='+this.title,'upload','100','400');
    }
    fm.onsubmit=function () {
        //图片名验证
        if (fm.imgname.value.length < 2 || fm.imgname.value.length > 20) {
            alert('用户名不能小于2位或大于20位');
            fm.imgname.focus();//将光标移到该字段
            return false;
        }
        if (fm.imgurl.value=='') {
            alert('图片url不能为空');
            fm.imgurl.focus();//将光标移到该字段
            return false;
        }

    }
}
//保证窗口始终在中间
function centerWindow(url,name,height,width) {
    var top=(screen.height-width)/2;
    var left=(screen.width-height)/2;
    window.open(url,name,'height='+height+',width='+width+',top='+top+',left='+left+'');
}

