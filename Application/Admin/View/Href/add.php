<!--图片预览插件-->
<script src="__JS__/uploadPreview.js" type="text/javascript"></script>
<script>
window.onload = function () {
    new uploadPreview({ UpBtn: "img", DivShow: "imgdiv", ImgShow: "imgShow" });
}
</script>
<!--图片预览插件-->
<section class="rt_wrap content">
 <div class="rt_content">
     <section>
      <h2><strong style="color:grey;">当前位置：内容管理 &raquo; 友情链接 &raquo; {$cat_title} &raquo; 添加链接</strong><a class="back" href="__CONTROLLER__/index/cat_id/{$cat_id}">返回友情链接列表 &raquo;</a></h2>
      <form action="__ACTION__/cat_id/{$cat_id}" method="post" enctype="multipart/form-data">
      <ul class="ulColumn2">
       <li>
        <span class="item_name" style="width:120px;">链接名称：</span>
        <input type="text" class="textbox textbox_295" name="title" placeholder=""/>
       </li>
       <li style="overflow:hidden;">
        <span class="item_name fl" style="width:120px;margin-right:4px">上传图片：</span>
        <label class="uploadImg fl" style="margin-right:4px">
         <input type="file" name="img" id="img"/>
         <span>上传图片</span>
        </label>
        <div id="imgdiv fl"><img id="imgShow" height="100" /></div>
       </li>
       <li>
        <span class="item_name" style="width:120px;">排序：</span>
        <input type="text" class="textbox textbox_295" name="sort" placeholder="" style="width:92px;"/>
        <span class="errorTips">数字越大越排在前</span>
       </li>
       <li>
        <span class="item_name" style="width:120px;">超链接：</span>
        <input type="text" class="textbox textbox_295" name="href" placeholder=""/>
       </li>
       <li>
        <span class="item_name" style="width:120px;"></span>
        <input type="submit" class="link_btn" value="添加友情链接"/>
        <input type="reset" class="link_btn" value="重置"/>
       </li>
      </ul>
      </form>
     </section>
     <!--tabStyle-->
 </div>
</section>
