/**
 * Created by bigface on 2016/12/18.
 */
window.onload=function () {
    vcode();
    var fm=document.getElementsByTagName('form')[0];
    fm.onsubmit=function () {
        //验证密码
        if(fm.password.value!=''){
            if(fm.password.value.length<6){
                alert('用户名密码不能小于6位');
                fm.password.value='';//清空
                fm.password.focus();//将光标移到该字段
                return false;
            }
        }
        //邮箱
        if(!/^[\w\-\.]+@[\w\-\.]+(\.\w+)+$/.test(fm.email.value)){
            alert('邮箱格式不正确');
            //fm.email.value='';//清空
            fm.email.focus();//将光标移到该字段
            return false;
        }
        //QQ
        if(fm.qq.value!==''){
            if(!/^[1-9]{1}[0-9]{4,9}$/.test(fm.qq.value)){
                alert('QQ格式不正确');
                fm.qq.value='';//清空
                fm.qq.focus();//将光标移到该字段
                return false;
            }
        }
        //网址验证
        if(fm.url.value!==''){
            if(!/^https?:\/\/(\w+\.)?[\w\-\.]+(\.\w+)+$/.test(fm.url.value)){
                alert('网址格式不正确');
                fm.url.value='';//清空
                fm.url.focus();//将光标移到该字段
                return false;
            }
        }
        //验证码必是4位
        if (fm.vcode.value.length<4){
             alert('验证码必须是4位');
             fm.vcode.value='';
             fm.vode.focus();
             return false;
        }
        return true;


    }
}