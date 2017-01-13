/**
 * Created by bigface on 2016/11/25.
 */
window.onload=function () {
    var img=document.getElementsByTagName('img');
    for (var i=0;i<img.length;i++){
        img[i].onclick=function () {
            _opener(this.alt);
        };
    }

};
//改变父窗口texearea的值
function _opener(son_alt){
    opener.document.getElementsByTagName('form')[0].content.value+='[img]'+son_alt+'[/img]'
}
