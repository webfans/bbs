/**
 * Created by bigface on 2017/1/3.
 */
window.onload=function () {
    vcode();
    var ubb=document.getElementById('ubb');
    var ubbimg=ubb.getElementsByTagName('img');
    var fm=document.getElementsByTagName('form')[0];
    var fonts=document.getElementById('font');
    var color=document.getElementById('color');
    var htmls=document.getElementsByTagName('html')[0];

    var q=document.getElementById('q');
    var qa=q.getElementsByTagName('a');
    qa[0].onclick=function () {
        window.open('q.php?num=48&path=images/qpic/1/','qpic','width=400,height=400,scrollbars=1');
    }
    qa[1].onclick=function () {
        window.open('q.php?num=10&path=images/qpic/2/','qpic','width=400,height=400,scrollbars=1');
    }
    qa[2].onclick=function () {
        window.open('q.php?num=39&path=images/qpic/3/','qpic','width=400,height=400,scrollbars=1');
    }
    /*在整个网页的任意地方点击一下，让font隐藏*/
    htmls.onmouseup=function(){
        fonts.style.display='none';
        color.style.display='none';
    };
    /*字体大小*/
    ubbimg[0].onclick=function () {
        fonts.style.display='block';
    };
    ubbimg[2].onclick=function () {
        contents('[b][/b]');
    };
    ubbimg[3].onclick=function () {
        contents('[b][/b]');
    };
    ubbimg[3].onclick=function () {
        contents('[i][/i]');
    };
    ubbimg[4].onclick=function () {
        contents('[u][/u]');
    };
    ubbimg[5].onclick=function () {
        contents('[s][/s]');
    };
    ubbimg[7].onclick=function () {
        color.style.display='block';
        //将光标定位在颜色输入框
        fm.t.focus();
    };
    ubbimg[8].onclick=function () {
        var url=prompt('请输入网址：','http://');
        if(url){
            if(!/^https?:\/\/(\w+\.)?[\w\-\.]+(\.\w+)+$/.test(url)){
                contents('[url]'+url+'[/url]');
            }else {
                alert('网址不合法');
            }
        }

    };
    ubbimg[9].onclick=function () {
        var email=prompt('请输入网址：','@');
        if(email){
            if(/^[\w\-\.]+@[\w\-\.]+(\.\w+)+$/.test(email)){
                contents('[url]'+email+'[/url]');
            }else {
                alert('电子邮件不合法');
            }
        }

    };
    ubbimg[10].onclick=function () {
        var image=prompt('请输入网址：','http://');
        if(image){
            contents('[url]'+image+'[/url]');
        }
    };
    ubbimg[11].onclick=function () {
        var flash=prompt('请输入网址：','http://');
        if(flash){
            if(!/^https?:\/\/(\w+\.)?[\w\-\.]+(\.\w+)+$/.test(flash)){
                contents('[url]'+flash+'[/url]');
            }else {
                alert('视频网址不合法');
            }
        }

    };
    ubbimg[18].onclick=function () {
        fm.content.rows+=2;
    };
    ubbimg[19].onclick=function () {
        fm.content.rows-=2;
    };

    function contents(string) {
        fm.content.value+=string;
    };
    fm.t.onclick=function () {
        showcolor(this.value);
    };

}
function font(size) {
    //fm=document.getElementsByTagName('form')[0];
    document.getElementsByTagName('form')[0].content.value+='[size='+size+'][/size]';
}
function showcolor(color) {
    document.getElementsByTagName('form')[0].content.value+='[color='+color+'][/color]';
}
