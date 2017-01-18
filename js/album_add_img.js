/**
 * Created by bigface on 2017-01-17.
 */
window.onload=function () {
    var upload=document.getElementById('upload');
    upload.onclick=function () {
        centerWindow('upload.php?dir='+this.title,'upload','100','400');
    }
}
//保证窗口始终在中间
function centerWindow(url,name,height,width) {
    var top=(screen.height-width)/2;
    var left=(screen.width-height)/2;
    window.open(url,name,'height='+height+',width='+width+',top='+top+',left='+left+'');
}

