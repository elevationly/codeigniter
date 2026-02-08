//登陆页
$(function(){
    var nums= Math.floor(Math.random()*13+1); 　//输出1～13之间的随机整数
    $('.background').attr('src','/statics/login/normal/skin/img/login/'+nums+'.jpg');
});
//验证账号密码
/*
function login(){
    var user = $('#user').val();
    var pwd = $('#pwd').val();
    if(user === ""){
        alert("请输入用户名!")
    }else if(pwd=== ""){
        alert("请输入密码!")
    }else{
        $.post("/home/index/check_user",{"user":user,"pwd":pwd}, function(zt) {
            if(zt==='error'){
                alert("账号或密码错误,请您核实!")
            }else if(zt==='success'){
                location.reload();
            }else{
                alert("服务器响应超时!")
            }
        });
    }
}
*/