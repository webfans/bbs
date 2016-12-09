/**
 点击验证码局部刷新
 */

function vcode() {
    var vcode=document.getElementById('vcode');
    vcode.onclick=function () {
        this.src='vcode.php?tm'+Math.random();
    };
}
