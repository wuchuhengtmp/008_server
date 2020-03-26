// JavaScript Document
var wait=60;
document.getElementById("btn").disabled = false;  
document.getElementById("btn2").disabled = false;  
function time(o) {
        if (wait == 0) {
            o.removeAttribute("disabled");          
            o.value="免费获取验证码";
            wait = 60;
        } else {
            o.setAttribute("disabled", true);
            o.value="重新发送(" + wait + ")";
            wait--;
            setTimeout(function() {
                time(o)
            },
            1000)
        }
    }
//document.getElementById("btn").onclick=function(){time(this);}
