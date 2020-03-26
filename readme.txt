最后更新日期：2019-09-06
将会员邀请码初始化：
UPDATE dmooo_user_auth_code SET is_used='N',user_id=NULL WHERE is_used='Y'



自动执行任务：
0,9,18,27,36,45,54 * * * * /usr/bin/curl http://ceshi.taokeyun.cn/app.php/Task/treatOrder
1,10,19,28,37,46,55 * * * * /usr/bin/curl http://ceshi.taokeyun.cn/app.php/Task/treatOrder2
0,5,10,15,20,25,30,35,40,45,50,55,58 * * * * /usr/bin/curl http://ceshi.taokeyun.cn/app.php/Task/treatOrderR
1,7,13,19,25,31,37,43,49,55 * * * * /usr/bin/curl http://ceshi.taokeyun.cn/app.php/Task/treatOrderR2
10 0 * * * /usr/bin/curl http://ceshi.taokeyun.cn/app.php/Task/treatOrderYesterday
0,9,18,27,36,45,54 * * * * /usr/bin/curl http://ceshi.taokeyun.cn/app.php/Task/treatPddOrder
0,9,18,27,36,45,54,59 * * * * /usr/bin/curl http://ceshi.taokeyun.cn/app.php/Task/treatJdOrder
