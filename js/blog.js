/**
 * Created by bigface on 2016/12/20.
 */
window.onload=function () {
    var message=document.getElementsByName('message');
    var friend=document.getElementsByName('friend');
    var flower=document.getElementsByName('flower');
    //alert(message);
    for(var i=0;i<message.length;i++){
        message[i].onclick=function () {
           //alert(this.title);
            centerWindow('message.php?id='+this.title,'message',260,400);
        };
    }
    for(var i=0;i<friend.length;i++){
        friend[i].onclick=function () {
            //alert(this.title);
            centerWindow('friend.php?id='+this.title,'friend',260,400);
        };
    }
    for(var i=0;i<flower.length;i++){
        flower[i].onclick=function () {
            //alert(this.title);
            centerWindow('flower.php?id='+this.title,'flower',260,400);
        };
    }
};
function centerWindow(url,name,height,width) {
    //保证窗口始终在中间
    var top=(screen.height-width)/2;
    var left=(screen.width-height)/2;
    window.open(url,name,'height='+height+',width='+width+',top='+top+',left='+left+'');
}
