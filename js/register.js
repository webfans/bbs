/**
 * Created by bigface on 2016/11/25.
 */
//等待网页加载完毕再执行
window.onload=function () {
    var faceimg=document.getElementById('faceimg');
    faceimg.onclick=function () {
        window.open('face.php','face','width=400,height=400,top=0,left=0,scrollbars=1');
    };
    //验证码点击局部刷新
    /*vcode.onclick=function () {
        this.src='vcode.php?tm'+Math.random();
    };*/
    vcode();
    //表单验证
    //如果能用客户端验证就用客户端
    var fm=document.getElementsByTagName('form')[0];
   //alert(fm.username.value.length);
   fm.onsubmit=function () {
        //用户名验证
        //checkit('username',2,20,'同志，用户名不能小于2位或大于20位');
      if(fm.username.value.length<2||fm.username.value.length>20){
            alert('用户名不能小于2位或大于20位');
            fm.username.value='';//清空
            fm.username.focus();//将光标移到该字段
            return false;
        }
       //js的正则写法 /<>/.test()
       if(/[<>\'\"\ \ ]/.test(fm.username.value)){
           alert('用户名不能包含非法字符');
           fm.username.value='';//清空
           fm.username.focus();//将光标移到该字段
           return false;
       }
       //验证密码
       if(fm.password.value.length<6){
           alert('用户名密码不能小于6位');
           fm.password.value='';//清空
           fm.password.focus();//将光标移到该字段
           return false;
       }
        if(fm.password.value!=fm.notpassword.value){
            alert('两次密码输入不一致');
            fm.notpassword.value='';//清空
            fm.notpassword.focus();//将光标移到该字段
            return false;
        }
        //验证提示问题
       if(fm.question.value.length<2||fm.question.value.length>20){
           alert('密码提示不能小于2位或大于20位');
           fm.question.value='';//清空
           fm.question.focus();//将光标移到该字段
           return false;
       }
       //密码回答
       if(fm.answer.value.length<2||fm.answer.value.length>20){
           alert('密码回答不能小于2位或大于20位');
           fm.answer.value='';//清空
           fm.answer.focus();//将光标移到该字段
           return false;
       }
       //判断密码提示和回答是否相等
       if(fm.answer.value==fm.question.value){
           alert('密码和回答不能一样哦');
           fm.answer.value='';//清空
           fm.answer.focus();//将光标移到该字段
           return false;
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
       //验证码
       /*if (fm.vcode.value.length<4){
           alert('验证码必须是4位');
           fm.vcode.value='';
           fm.vode.focus();
           return false;
       }
       */



        return true;
    };
};