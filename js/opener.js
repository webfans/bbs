/**
 * Created by bigface on 2016/11/25.
 */
window.onload=function () {
    var img=document.getElementsByTagName('img');
    //var textface=opener.document.getElementById('faceimg_value');
    for (var i=0;i<img.length;i++){
        img[i].onclick=function () {
            _opener(this.alt);
            var fm=opener.document.getElementsByTagName('form')[0];
            fm.textface.value=this.alt;
            //下面的这种方法chrome无法通过，IE firefox通过
            //opener.document.getElementById('faceimg_value').value=this.alt;
           // alert(opener.document.getElementById('faceimg_value').value);
        };
    }

};
function _opener(son_src){
    //opener 表示父窗口.document表示文档
    var parent_faceimg=opener.document.getElementById('faceimg');
    //alert(faceimg);
    //改变父窗口img的src值,实现切换图片
    parent_faceimg.src=son_src;
    //var textface=opener.document.getElementById('faceimg_value');
    //textface.value=son_src;
    //opener.document.register.textface.value=son_src;

}
