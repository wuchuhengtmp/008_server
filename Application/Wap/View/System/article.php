<!DOCTYPE html>
<html>
<head>
  <meta http-equiv="content-type" content="text/html; charset=UTF-8">
  <meta charset="utf-8">
  <title>{$msg['title']}</title>
  <meta name="description" content="">
  <meta name="viewport" content="initial-scale=1, width=device-width, maximum-scale=1, user-scalable=no">
  <meta name="apple-mobile-web-app-capable" content="yes">
  <meta name="apple-touch-fullscreen" content="yes">
  <meta name="apple-mobile-web-app-status-bar-style" content="black">
  <meta name="format-detection" content="telephone=no">
  <meta name="format-detection" content="address=no">
  <link href="__WAP_CSS__/mp_news.css" rel="stylesheet">
</head>
<body id="activity-detail">
<div class="rich_media container">
  <div class="header" style="display: none;"></div>
  <div class="rich_media_inner content" style="min-height: 831px;">
    <h2 class="rich_media_title" id="activity-name">{$msg['title']}</h2>
    <div class="rich_media_meta_list">
      <em id="post-date" class="rich_media_meta text">{$msg['pubtime']}</em>
      <em class="rich_media_meta text"></em>
      <a class="rich_media_meta link nickname js-no-follow js-open-follow" href="javascript:;" id="post-user"></a>
	</div>
	<div id="page-content" class="content">
	  <div id="img-content">
	    <div class="rich_media_content" id="js_content">{$msg['content']}</div>
	  </div>
	</div>
  </div>
</div>
<div style="display:none;"></div>
<script src="__JS__/jquery.min.js"></script>		
</body>
</html>