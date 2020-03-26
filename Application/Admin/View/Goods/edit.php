<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link href="__ADMIN_CSS__/bootstrap.min14ed.css?v=3.3.6" rel="stylesheet">
<link href="__ADMIN_CSS__/font-awesome.min93e3.css?v=4.4.0" rel="stylesheet">
<link href="__ADMIN_CSS__/plugins/iCheck/custom.css" rel="stylesheet">
<link href="__ADMIN_CSS__/animate.min.css" rel="stylesheet">
<link href="__ADMIN_CSS__/style.min862f.css?v=4.1.0" rel="stylesheet">

<script src="__ADMIN_JS__/jquery.min.js?v=2.1.4"></script>
<script src="__ADMIN_JS__/bootstrap.min.js?v=3.3.6"></script>
<script src="__ADMIN_JS__/plugins/iCheck/icheck.min.js"></script>

<!-- ueditor -->
<link rel="stylesheet" type="text/css" href="__PUBLIC__/ueditor/themes/default/css/ueditor.css" />
<script type="text/javascript" charset="utf-8" src="__PUBLIC__/ueditor/ueditor.config.js"></script>
<script type="text/javascript" charset="utf-8" src="__PUBLIC__/ueditor/ueditor.all.min.js"> </script>
<script type="text/javascript" charset="utf-8" src="__PUBLIC__/ueditor/lang/zh-cn/zh-cn.js"></script>
<!-- ueditor -->

<script>
//实例化编辑器
var ue = UE.getEditor('editor');

$(document).ready(function(){
	$(".i-checks").iCheck({checkboxClass:"icheckbox_square-green",radioClass:"iradio_square-green",})
});

function deloldvideo(goods_id)
{
	if(goods_id!='') {
		swal({
			title:"确定删除原视频吗？",
			text:"",
			type:"warning",
			showCancelButton:true,
			cancelButtonText:"取消",
			confirmButtonColor:"#DD6B55",
			confirmButtonText:"删除",
			closeOnConfirm:false
		},function(){
			$.ajax({
				type:"POST",
				url:'/dmooo.php/Goods/deloldvideo',
				dataType:"html",
				data:"goods_id="+goods_id,
				success:function(msg)
				{
				    if(msg=='1') {
						swal({
							title:"删除原视频成功！",
							text:"",
							type:"success"
						},function(){location.reload();})
					}else {
						swal({
							title:"删除失败！",
							text:"",
							type:"success"
						},function(){location.reload();})
					}
				}
			});
		})
	}
}
</script>
</head>

<body class="gray-bg">
	<div class="wrapper wrapper-content animated fadeInRight">
		<div class="row">
			<div class="col-sm-12">
				<div class="ibox float-e-margins">
					<div class="ibox-title">
						<h3>当前位置：商品管理 &raquo; 编辑商品<a class="pull-right" href="__CONTROLLER__/index/cat_id/{$msg['cat_id']}">返回上一页 <i class="fa fa-angle-double-right"></i></a></h3>
					</div>
				</div>
			</div>
			<div class="row">
            <div class="col-sm-12">
                <div class="ibox float-e-margins">
                    <div class="ibox-content">
                    <ul class="nav nav-tabs">
                        <li class="active">
                        	<a data-toggle="tab" href="#tab-1" aria-expanded="true">商品基本信息</a>
                        </li>
                        <li class="">
                        	<a data-toggle="tab" href="#tab-2" aria-expanded="false">商品详情</a>
                        </li>
                        <li class="">
                        	<a data-toggle="tab" href="#tab-3" aria-expanded="false">属性配置</a>
                        </li>
                    </ul>
                    <form action="__ACTION__/goods_id/{$msg['goods_id']}"  class="form-horizontal" method="post" enctype="multipart/form-data">
                       <input type="hidden" name="oldimg" value="{$msg['img']}">
                       <input type="hidden" name="oldcontent" value='{$msg.content}'>
      				<div class="tab-content">
      					<!-- 商品基本信息  -->
      					<div id="tab-1" class="tab-pane active" style="padding-top: 10px">
                            <div class="form-group">
                                <label class="col-sm-2 control-label">商品名称</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" name="goods_name" value="{$msg['goods_name']}" placeholder="">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">商品编码</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" name="goods_code" value="{$msg['goods_code']}"> 
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">商品图片</label>
                                <div class="col-sm-10">
                                    <?php 
                                    if($msg['img']){
                                        echo '<img src="'.$msg['img'].'" height="100"/>';
                                    }else {
                                        echo '暂无';
                                    } ?>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">上传新图片</label>
                                <div class="col-sm-10">
                                    <input type="file" name="img" class="form-control">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">视频</label>
                                <div class="col-sm-10">
                                    <?php 
                                    if($msg['video']){
                                        echo '<a target="_blank" href="'.$msg['video'].'">'.$msg['video'].'</a>
                                        <button class="btn btn-primary" type="button" onclick="deloldvideo('.$msg['goods_id'].')">删除原视频</button>';
                                    }else {
                                        echo '暂无';
                                    } 
                                    ?>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">上传新视频</label>
                                <div class="col-sm-10">
                                    <input type="file" name="video" class="form-control">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">所属厂家/品牌</label>
                                <div class="col-sm-10">
                                    <select class="form-control m-b" name="brand_id">
                                    <option value="">-请选择所属厂家/品牌-</option>
                                       <?php 
                                       foreach ($BrandList as $l) {
                                           if($l['brand_id']==$msg['brand_id'])
                                           {
                                               $select_b='selected';
                                           }else {
                                               $select_b='';
                                           }
                                           echo '<option value="'.$l['brand_id'].'" '.$select_b.'>'.$l['name'].'</option>';
                                       }
                                       ?>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">参考价格</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" name="old_price" value="{$msg['old_price']}"> 
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">实际价格</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" name="price" value="{$msg['price']}"> 
                                    <span class="help-block m-b-none text-danger">精确到分，如：6.88</span>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">总库存数量</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" name="inventory" value="{$msg['inventory']}"> 
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">赠送积分</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" name="give_point" value="{$msg['give_point']}"> 
                                    <span class="help-block m-b-none text-danger">会员购买商品后返回相应积分，填写0代表不赠送</span>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">可抵扣积分</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" name="deduction_point" value="{$msg['deduction_point']}"> 
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">开启会员等级折扣</label>
                                <div>
                                    <div class="radio i-checks">
                                        <label>
                                        	<input type="radio" name="is_discount" value="Y" <?php if($msg['is_discount']=='Y') echo 'checked'; ?> > <i></i>开启
                                        	<input type="radio" name="is_discount" value="N" <?php if($msg['is_discount']=='N') echo 'checked'; ?> > <i></i>关闭
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">虚拟销售量</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" name="virtual_volume" value="{$msg['virtual_volume']}"> 
                                    <span class="help-block m-b-none text-danger">在实际销售量的基础上加上虚拟销售量</span>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">排序</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" name="sort" value="{$msg['sort']}"> 
                                    <span class="help-block m-b-none text-danger">数字越大越排在前</span>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">浏览量</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" name="clicknum" value="{$msg['clicknum']}" placeholder="">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">所属商品分类</label>
                                <div class="col-sm-10">
                                    <select class="form-control m-b" name="cat_id">
                                    <?php
                                    foreach ($catlist as $v) {
                                        if($v['cat_id']==$msg['cat_id']) {
                                            $select='selected';
                                        }else {
                                            $select='';
                                        }
                                        echo '<option value="'.$v['cat_id'].'" style="margin-left:55px;" '.$select.'>'.$v['lefthtml'].''.$v['cat_name'].'</option>';
                                    }
                                    ?>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">上架/下架</label>
                                <div>
                                    <div class="radio i-checks">
                                        <label>
                                        	<input type="radio" name="is_show" value="Y" <?php if($msg['is_show']=='Y') echo 'checked'; ?> > <i></i>上架
                                        	<input type="radio" name="is_show" value="N" <?php if($msg['is_show']=='N') echo 'checked'; ?> > <i></i>下架
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">是否推荐商品</label>
                                <div>
                                    <div class="radio i-checks">
                                        <label>
                                        	<input type="radio" name="is_top" value="Y" <?php if($msg['is_top']=='Y') echo 'checked'; ?> > <i></i>是
                                        	<input type="radio" name="is_top" value="N" <?php if($msg['is_top']=='N') echo 'checked'; ?> > <i></i>否
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">是否特价商品</label>
                                <div>
                                    <div class="radio i-checks">
                                        <label>
                                        	<input type="radio" name="is_sale" value="Y" <?php if($msg['is_sale']=='Y') echo 'checked'; ?> > <i></i>是
                                        	<input type="radio" name="is_sale" value="N" <?php if($msg['is_sale']=='N') echo 'checked'; ?> > <i></i>否
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- 商品基本信息  -->
                        
                        <!-- 商品详情  -->
                        <div id="tab-2" class="tab-pane" style="padding-top: 10px">
                            <div class="form-group">
                                <label class="col-sm-2 control-label">简要说明</label>
                                <div class="col-sm-10">
                                    <textarea name="description" placeholder="" class="form-control" style="height:100px;">{$msg['description']}</textarea> 
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">内容</label>
                                <div class="col-sm-10">
                                	<script name="content" id="editor" type="text/plain" style="height:300px;"><?php echo htmlspecialchars_decode(html_entity_decode($msg['content']));?></script>
                                </div>
                            </div>
                        </div>
                        <!-- 商品详情  -->
                        
                        <!-- 属性配置  -->
                        <div id="tab-3" class="tab-pane" style="padding-top: 10px">
                            <div class="form-group">
                                <label class="col-sm-2 control-label">是否开启属性配置</label>
                                <div>
                                    <div class="radio i-checks">
                                        <label>
                                        	<input type="radio" name="is_sku" value="Y" <?php if($msg['is_sku']=='Y'){echo 'checked';}?> > <i></i>开启
                                        	<input type="radio" name="is_sku" value="N" <?php if($msg['is_sku']=='N'){echo 'checked';}?> > <i></i>不开启
                                        	<span class="help-block m-b-none text-danger">如需配置商品属性规则，请选择开启配置</span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <?php 
                            $sku=json_decode($msg['sku_str'],true);
                            $i=0;
                            foreach ($AttributeList as $l)
                            {
                                $i++;
                                if($i==1)
                                {
                                    $str='属性配置：';
                                }else {
                                    $str='';
                                }
                                //属性值列表
                                $vllist='';
                                foreach ($sku as $sl)
                                {
                                    if($l['goods_attribute_id']==$sl['attribute_id'])
                                    {
                                        //已配置属性值列表
                                        $value_list=$sl['value_list'];
                                    }
                                }
                                //该条是默认选中，便于传递属性分类
                                $vllist='<input type="checkbox" name="attribute['.$l['goods_attribute_id'].'][]" value="---" checked style="display:none">';
                                foreach ($l['valuelist'] as $vl)
                                {
                                    //判断已选中的属性值
                                    if(in_array($vl['name'], $value_list))
                                    {
                                        //已选中
                                        $check='checked';
                                        $check_value_arr[]=$vl['name'];
                                    }else {
                                        //未选中
                                        $check='';
                                    }
                                    $vllist.='<input type="checkbox" name="attribute['.$l['goods_attribute_id'].'][]" value="'.$vl['name'].'" '.$check.'> '.$vl['name'].'&nbsp;';
                                }
                                //自定义属性值
                                if($check_value_arr)
                                {
                                    $value_diy_arr=array_diff($value_list, $check_value_arr);
                                }else {
                                    $value_diy_arr=$value_list;
                                }
                                $value_diy=implode('-', $value_diy_arr);
                                echo '<div class="form-group">
                     <label class="col-sm-2 control-label">'.$str.'</label>
		             '.$l['goods_attribute_name'].'：
		             '.$vllist.'
		          </div>
		          <div class="form-group">
                    <label class="col-sm-2 control-label"></label>
		                              自定义属性值：<input type="text" class="textbox textbox_295" name="attribute_diy['.$l['goods_attribute_id'].']" value="'.$value_diy.'" placeholder=""/>
		            <span class="errorTips">可新增额外属性值，多个值之间用"-"符号连接，如：属性值1-属性值2</span>
		          </div>';
                            }
                                 ?>
                        </div>
                        <!-- 属性配置  -->
                        
                        <div class="form-group">
                                <div class="col-sm-4 col-sm-offset-2">
                                    <button class="btn btn-primary" type="submit"><i class="fa fa-check"></i>&nbsp;编辑商品</button>
                                    <button class="btn btn-white" type="reset"><i class="fa fa-refresh"></i>&nbsp;重置</button>
                                </div>
                            </div>
                    </div>
                    </form>
                    </div>
                </div>
            </div>
        </div>
		</div>
	</div>
</body>
</html>