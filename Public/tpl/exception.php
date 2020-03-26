<?php
    if(C('LAYOUT_ON')) {
        echo '{__NOLAYOUT__}';
    }
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>错误提示页面</title>
    <style type="text/css">
    *{ padding: 0; margin: 0; }
    body{ font-family: '微软雅黑'; color: #333; font-size: 16px; }
    .message{width: 1000px;margin:auto;border:1px solid #1B8F24;margin-top: 30px;}
    .head{width: 100%;height: 30px;background: rgb(190, 228, 144);text-align: center;padding-top: 5px;}
    .content{width: 100%;padding:0 10px}
    .content .detail{line-height:30px}
    </style>
</head>
<body>
<div class="message">
    <div class="head"><span><?php echo strip_tags($e['message']);?></span></div>
    <div class="content">
      <?php if(isset($e['file'])) {?>
       <p class="detail">错误位置：<?php echo $e['file'] ;?></p>
       <p class="detail">错误行数：<?php echo $e['line'];?></p>
      <?php }?>
    <?php if(isset($e['trace'])) {?>
    <p class="detail">异常的详细Trace信息：</p>
    <p class="detail"><?php echo nl2br($e['trace']);?></p>
    <?php }?>
     <br />
    </p>
    </div>
</div>
</body>
</html>