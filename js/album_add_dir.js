/**
 * Created by bigface on 2017-01-17.
 */
window.onload=function () {
    var fm=document.getElementsByTagName('form')[0];
    var albumpwd=document.getElementById('albumpwd');
    //点击公开单选按钮
    fm[1].onclick=function () {
       albumpwd.style.display='none';
    }
    //点击私密单选按钮
    fm[2].onclick=function () {
        albumpwd.style.display='block';
    }
    //验证
    fm.onsubmit=function () {
        if(fm.albumname.value.length<2||fm.albumname.value.length>20){
            alert('相册名不能小于2位或大于20位');
            fm.albumname.value='';//清空
            fm.albumname.focus();//将光标移到该字段
            return false;
        }
        //只有点击了 私密 才校验密码
        if(fm[2].checked){
            if(fm.albumpwd.value.length<6){
                alert('密码不能小于6位');
                fm.albumpwd.value='';//清空
                fm.albumpwd.focus();//将光标移到该字段
                return false;
            }
        }
        return true;
    }
}