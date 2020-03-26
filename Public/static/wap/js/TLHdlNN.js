
(function () {
    //获取地址栏参数
    try{
        function find(str, cha, num) {
            var x = str.indexOf(cha);
            for (var i = 0; i < num; i++) {
                x = str.indexOf(cha, x + 1);
            }
            return x;
        }

        //获取参数
        function getparams() {
            var url = window.location.href;
            var data;
            var _index = find(url, "/", 2);
            data = url.substring(_index + 1, url.lastIndexOf("."));
            data = data.split("/");
            return data
        }

        var params = getparams();
        //console.log(params[0].split('-')[1].split('.')[0], 'params');
        //var invitecode = params[0].split('-')[1].split('.')[0] || "";
        var invitecode =$('#inviteCode').val();
        invitecode = decodeURIComponent(invitecode);
        if (!invitecode) alert('邀请码为空')
        var evenClick = false;

        var ua = navigator.userAgent.toLowerCase();
        if (ua.match(/iphone/i) == "iphone" || ua.match(/ipad/i) == "ipad") {
            $(".copy_btn_android").hide();
            $(".copy_btn_ios").show();
            var clipboard = new Clipboard(".copy_btn_ios");
            clipboard.on("success", function (e) {
                $(".copy_btn_ios").html("复制成功");
                $(".m-fixed-tips").show();
                setTimeout(function () {
                    $(".m-fixed-tips").hide();
                }, 1500);
                e.clearSelection();
                evenClick = true;
            });
            clipboard.on("error", function (e) {
                $(".copy_btn_ios").html("复制失败");
                alert("可能由于手机浏览器的版本问题，您并不能进行复制，请手动长按复制");
                evenClick = true;
            });
        } else {
            $(".copy_btn_android").show();
            $(".copy_btn_ios").hide();
            var clipboard = new Clipboard(".copy_btn_android");
            clipboard.on("success", function (e) {
                $(".copy_btn_android").html("复制成功");
                e.clearSelection();
                $(".m-fixed-tips").show();
                setTimeout(function () {
                    $(".m-fixed-tips").hide();
                }, 1500);
                evenClick = true;
            });
            clipboard.on("error", function (e) {
                $(".copy_btn_android").html("复制失败");
                alert("可能由于手机浏览器的版本问题，您并不能进行复制，请手动长按复制");
                evenClick = true;
            });
        }

        if (invitecode) {
            $("#copytext").html(invitecode);
            $("#copy_key_android").text(invitecode);
            $("#copys-input").html(invitecode);

        } else {
            $("#copytext").html(tkl_text);
            $("#copy_key_android").text(tkl_text);
            $("#copys-input").html(tkl_text);
            alert(tkl_text)
        }

        var uA = navigator.userAgent;
        var android = "#";

        var isIos = /(iPhone|iPad|iPod|iOS)/i.test(navigator.userAgent);
        $(".m-down").click(function () {
            if (!evenClick) {
                evenClick = true;
                $(".m-fixed-tips2").show();
                setTimeout(function () {
                    $(".m-fixed-tips2").hide();
                }, 1500);
            } else {
                // $(".m-down").attr("href", android);

                var u = navigator.userAgent,
                    isAndroid = u.indexOf('Android') > -1 || u.indexOf('Adr') > -1,
                    isiOS = !!u.match(/\(i[^;]+;( U;)? CPU.+Mac OS X/),
                    urls = {
                        'android': $('#down_android').val(),
                        'ios': $('#down_ios').val(),
                    };
                if (isAndroid) {
                    window.location.href = urls.android;
                } else if (isiOS) {
                    window.location.href = urls.ios;
                }

            }

        });


    }catch(e){


        function find(str, cha, num) {
            var x = str.indexOf(cha);
            for (var i = 0; i < num; i++) {
                x = str.indexOf(cha, x + 1);
            }
            return x;
        }

        //获取参数
        function getparams() {
            var url = window.location.href;
            var data;
            var _index = find(url, "/", 2);
            data = url.substring(_index + 1, url.lastIndexOf("."));
            data = data.split("/");
            return data
        }

        var params = getparams();
        //console.log(params[0].split('-')[1].split('.')[0], 'params');
        //var invitecode = params[0].split('-')[1].split('.')[0] || "";
        var invitecode =$('#inviteCode').val();
        invitecode = decodeURIComponent(invitecode);
        if (!invitecode) alert('邀请码为空')
        var evenClick = false;
        var copy_btn_android=document.getElementById("copy_btn_android");
        var copy_btn_ios=document.getElementById('copy_btn_ios');
        var m_fixed_tips=document.getElementById('m-fixed-tips');
        var ua = navigator.userAgent.toLowerCase();
        if (ua.match(/iphone/i) == "iphone" || ua.match(/ipad/i) == "ipad") {
            copy_btn_android.style.display='none';
            copy_btn_ios.style.display='inline-block';
            var clipboard = new Clipboard(".copy_btn_ios");
            clipboard.on("success", function (e) {

                copy_btn_ios.innerHTML="复制成功";
                document.getElementById("m-fixed-tips").style.display='block';
                setTimeout(function () {
                    document.getElementById("m-fixed-tips").style.display='none';
                }, 1500);
                e.clearSelection();
                evenClick = true;

            });
            clipboard.on("error", function (e) {
                copy_btn_ios.innerHTML="复制失败";
                alert("可能由于手机浏览器的版本问题，您并不能进行复制，请手动长按复制");
                evenClick = true;
            });
        } else {
            copy_btn_android.style.display='inline-block';
            copy_btn_ios.style.display='none';
            var clipboardAnd = new Clipboard(".copy_btn_android");
            clipboardAnd.on("success", function (e) {
                copy_btn_android.innerHTML="复制成功";
                e.clearSelection();
                m_fixed_tips.style.display='block';
                setTimeout(function () {
                    m_fixed_tips.style.display='none';
                }, 1500);
                evenClick = true;
            });
            clipboardAnd.on("error", function (e) {
                copy_btn_android.innerHTML="复制失败";
                alert("可能由于手机浏览器的版本问题，您并不能进行复制，请手动长按复制");
                evenClick = true;
            });
        }

        if (invitecode) {
            document.getElementById("copytext").innerHTML=invitecode;
            document.getElementById("copy_key_android").innerText=invitecode;
            document.getElementById("copys-input").innerHTML=invitecode;

        } else {
            alert('邀请码获取失败')
            //document.getElementById("copytext").innerHTML=tkl_text;
            //document.getElementById("copy_key_android").innerText=tkl_text;
            // document.getElementById("copys-input").innerHTML(tkl_text);

        }


        document.getElementById("m-down").onclick(function () {
            if (!evenClick) {
                evenClick = true;
                document.getElementById("m-fixed-tips2").style.display='block';
                setTimeout(function () {
                    document.getElementById("m-fixed-tips2").style.display='none';
                }, 1500);
            } else {
                // $(".m-down").attr("href", android);

                var u = navigator.userAgent,
                    isAndroid = u.indexOf('Android') > -1 || u.indexOf('Adr') > -1,
                    isiOS = !!u.match(/\(i[^;]+;( U;)? CPU.+Mac OS X/),
                    urls = {
                        'android': $('#down_android').val(),
                        'ios': $('#down_ios').val(),
                    };
                if (isAndroid) {
                    window.location.href = urls.android;
                } else if (isiOS) {
                    window.location.href = urls.ios;
                }

            }

        });


    }


})();

