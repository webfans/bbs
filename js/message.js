/**
 * Created by bigface on 2016/12/21.
 */
window.onload=function () {
    vcode();
    var fm=document.getElementsByTagName('form')[0];
    fm.onsubmit=function () {
        //验证码必是4位
        if (fm.vcode.value.length<4){
            alert('验证码必须是4位');
            fm.vode.focus();
            return false;
        }
        //发送内容校验
        if(fm.contents.value.length<5||fm.contents.value.length>200){
            alert('短信内容不能小于2位或大于200位');
            fm.contents.focus();//将光标移到该字段
            return false;
        }
        return true;
    }
}