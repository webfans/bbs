/**
 * Created by bigface on 2017-01-17.
 */
window.onload=function () {
    var fm=document.getElementsByTagName('form')[0];
    var photopwd=document.getElementById('photopwd');
    //点击公开单选按钮
    fm[1].onclick=function () {
       photopwd.style.display='none';
    }
    //点击私密单选按钮
    fm[2].onclick=function () {
        photopwd.style.display='block';
    }
    //验证
    fm.onsubmit=function () {
        if(fm.photoname.value.length<2||fm.photoname.value.length>20){
            alert('相册名不能小于2位或大于20位');
            fm.photoname.value='';//清空
            fm.photoname.focus();//将光标移到该字段
            return false;
        }
        //只有点击了 私密 才校验密码
        //修改相册时，允许密码为空。只有密码不为空才校验
        if(photopwd.value!=null){
            if(fm[2].checked){
                if(fm.photopwd.value.length<6){
                    alert('密码不能小于6位');
                    fm.photopwd.value='';//清空
                    fm.photopwd.focus();//将光标移到该字段
                    return false;
                }
            }
        }

        return true;
    }
}