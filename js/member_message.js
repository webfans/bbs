/**
 * Created by bigface on 2016/12/25.
 */
window.onload=function () {
    var checkall=document.getElementById('checkall');
    var fm=document.getElementsByTagName('form')[0];
    checkall.onclick=function () {
        //form.elements获取fomr表单里的所有元素
        //alert(myform.elements.length);
        for (var i=0;i<fm.elements.length;i++){
            //实现全选功能 checked选中
            if(fm.elements[i].name!='checkall'){
                fm.elements[i].checked=fm.checkall.checked;
            }
        }
    };
    fm.onsubmit=function () {
        if(confirm('同志，您确定要删除这些精彩的短信吗？')){
            return true;
        }else{
            return false;
        }
    }
}