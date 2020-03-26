<link rel="stylesheet" type="text/css" href="__PUBLIC__/ueditor/themes/default/css/ueditor.css" />
<script type="text/javascript" charset="utf-8" src="__PUBLIC__/ueditor/ueditor.config.js"></script>
<script type="text/javascript" charset="utf-8" src="__PUBLIC__/ueditor/ueditor.all.min.js"> </script>
<script type="text/javascript" charset="utf-8" src="__PUBLIC__/ueditor/lang/zh-cn/zh-cn.js"></script>
<script type="text/javascript">
//实例化编辑器
var ue = UE.getEditor('editor');

$(document).ready(function(){
	//tab切换
	$(".admin_tab li a").click(function(){
	    var liindex = $(".admin_tab li a").index(this);
	    $(this).addClass("active").parent().siblings().find("a").removeClass("active");
	    $(".admin_tab_cont").eq(liindex).fadeIn(150).siblings(".admin_tab_cont").hide();
	});
});
</script>
<section class="rt_wrap content">
 <div class="rt_content">
     <section>
      <h2><strong style="color:grey;">当前位置：商品管理 &raquo; 商品回收站</strong><a class="back" href="__CONTROLLER__/recycle">返回上一页 &raquo;</a></h2>
      <ul class="admin_tab">
       <li><a class="active">商品基本信息</a></li>
       <li><a>商品详情</a></li>
      </ul>
      <!-- 商品基本信息  -->
      <div class="admin_tab_cont" style="display:block;">
      <ul class="ulColumn2">
       <li>
        <span class="item_name" style="width:130px;">商品名称：</span>
        <input type="text" class="textbox textbox_295" name="goods_name" value="{$msg['goods_name']}" placeholder=""/>
       </li>
       <li>
        <span class="item_name" style="width:130px;">商品图片：</span>
        <?php if($msg['img']){echo '<img src="'.$msg['img'].'" height="100"/>';}else {echo '暂无';} ?>
       </li>
       <li>
        <span class="item_name" style="width:130px;">参考价格：</span>
        <input type="text" class="textbox textbox_295" name="old_price" value="{$msg['old_price']}" placeholder="" style="width:92px;"/>
       </li>
       <li>
        <span class="item_name" style="width:130px;">实际价格：</span>
        <input type="text" class="textbox textbox_295" name="price" value="{$msg['price']}" placeholder="" style="width:92px;"/>
        <span class="errorTips">精确到分，如：6.88</span>
       </li>
       <li>
        <span class="item_name" style="width:130px;">库存数量：</span>
        <input type="text" class="textbox textbox_295" name="inventory" value="{$msg['inventory']}" placeholder="" style="width:92px;"/>
       </li>
       <li>
        <span class="item_name" style="width:130px;">开启会员等级折扣：</span>
        <label class="single_selection"><input type="radio" name="is_discount" value="Y" <?php if($msg['is_discount']=='Y') echo 'checked'; ?> />开启</label>
        <label class="single_selection"><input type="radio" name="is_discount" value="N" <?php if($msg['is_discount']=='N') echo 'checked'; ?> />关闭</label>
       </li>
       <li>
        <span class="item_name" style="width:130px;">实际销售量：</span>
        {$msg['sales_volume']}
       </li>
       <li>
        <span class="item_name" style="width:130px;">虚拟销售量：</span>
        <input type="text" class="textbox textbox_295" name="virtual_volume" value="{$msg['virtual_volume']}" placeholder="" style="width:92px;"/>
        <span class="errorTips">在实际销售量的基础上加上虚拟销售量</span>
       </li>
       <li>
        <span class="item_name" style="width:130px;">排序：</span>
        <input type="text" class="textbox textbox_295" name="sort" value="{$msg['sort']}" placeholder="" style="width:92px;"/>
        <span class="errorTips">数字越大越排在前</span>
       </li>
       <li>
        <span class="item_name" style="width:130px;">浏览量：</span>
        <input type="text" class="textbox textbox_295" name="clicknum" value="{$msg['clicknum']}" placeholder="" style="width:92px;"/>
       </li>
       <li>
        <span class="item_name" style="width:130px;">所属商品分类：</span>
         <select class="select" name="cat_id">
         <?php
         foreach ($catlist as $v)
         {
         	if($v['cat_id']==$msg['cat_id'])
         	{
         		$select='selected';
         	}else {
               $select='';
            }
            echo '<option value="'.$v['cat_id'].'" style="margin-left:55px;" '.$select.'>'.$v['lefthtml'].''.$v['cat_name'].'</option>';
         }
         ?>
         </select>
       </li>
       <li>
        <span class="item_name" style="width:130px;">上架/下架：</span>
        <label class="single_selection"><input type="radio" name="is_show" value="Y" <?php if($msg['is_show']=='Y') echo 'checked'; ?> />上架</label>
        <label class="single_selection"><input type="radio" name="is_show" value="N" <?php if($msg['is_show']=='N') echo 'checked'; ?> />下架</label>
       </li>
       <li>
        <span class="item_name" style="width:130px;">是否推荐商品：</span>
        <label class="single_selection"><input type="radio" name="is_top" value="Y" <?php if($msg['is_top']=='Y') echo 'checked'; ?> />是</label>
        <label class="single_selection"><input type="radio" name="is_top" value="N" <?php if($msg['is_top']=='N') echo 'checked'; ?> />否</label>
       </li>
       <li>
        <span class="item_name" style="width:130px;">是否特价商品：</span>
        <label class="single_selection"><input type="radio" name="is_sale" value="Y" <?php if($msg['is_sale']=='Y') echo 'checked'; ?> />是</label>
        <label class="single_selection"><input type="radio" name="is_sale" value="N" <?php if($msg['is_sale']=='N') echo 'checked'; ?> />否</label>
       </li>
       </ul>
       </div>
       <!-- 商品基本信息  -->
       
       <!-- 商品详情  -->
       <div class="admin_tab_cont">
       <ul class="ulColumn2">
       <li>
        <span class="item_name" style="width:130px;">简要说明：</span>
        <textarea name="description" placeholder="" class="textarea" style="width:500px;height:100px;">{$msg['description']}</textarea>
       </li>
       <li>
        <span class="item_name" style="width:130px;">内容：</span>
        <label class="single_selection"><script name="content" id="editor" type="text/plain" style="width:800px;height:300px;"><?php echo htmlspecialchars_decode(html_entity_decode($msg['content']));?></script></label>
       </li>
      </ul>
      </div>
     </section>
     <!--tabStyle-->
 </div>
</section>
