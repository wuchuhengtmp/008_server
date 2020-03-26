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

</head>

<body class="gray-bg">
	<div class="wrapper wrapper-content animated fadeInRight">
		<div class="row">
			<div class="col-sm-12">
				<div class="ibox float-e-margins">
					<div class="ibox-title">
						<h3>当前位置： 商品管理 &raquo; 商品SKU配置 &raquo; {$GoodsMsg.goods_name}<a class="pull-right" href="__MODULE__/Goods/index/cat_id/{$GoodsMsg.cat_id}">返回商品列表 <i class="fa fa-angle-double-right"></i></a></h3>
					</div>
					<div class="ibox-content">
						<div class="">
							<form action="__ACTION__/goods_id/{$GoodsMsg.goods_id}" method="post">
							<table class="table table-bordered table-striped table-hover">
								<thead>
									<?php 
                                      //表头
                                      foreach ($GoodsMsg['sku_arr'] as $l)
                                      {
                                      	if($l['value_list']) {
                                      		$th.='<th>'.$l['attribute_name'].'</th>';
                                      	}
                                      }
                                      echo '<tr>
                                           '.$th.'
                                           <th>价格（元）</th>
                                           <th>库存</th>
                                      	   <th>赠送积分</th>
                                           <th>可抵扣积分</th>
                                           <th>图片</th>
                                       </tr>';
                                      ?>
								</thead>
								<tbody>
    								<?php 
    								foreach ($attribute_value_arr as $l)
    								{
    								    $value_str='';
    								    $sku_arr=array();
    								    foreach ($l as $k=>$v)
    								    {
    								        $value_str.='<td>'.$v.'</td>';
    								        $sku_arr[]=array(
    								            'attribute_id'=>$GoodsMsg['sku_arr'][$k]['attribute_id'],
    								            'value'=>$v
    								        );
    								    }
    								    //判断该条属性SKU是否已配置
    								    $sku=json_encode($sku_arr,JSON_UNESCAPED_UNICODE);
    								    $price=$GoodsMsg['price'];
    								    $inventory=1000;
    								    $give_point=$GoodsMsg['give_point'];
    								    $deduction_point=$GoodsMsg['deduction_point'];
    								    $img='';
    								    foreach ($skuList as $sl)
    								    {
    								        if($sku==$sl['sku'])
    								        {
    								            //已配置
    								            $price=$sl['price'];
    								            $inventory=$sl['inventory'];
    								            $give_point=$sl['give_point'];
    								            $deduction_point=$sl['deduction_point'];
    								            $img=$sl['img'];
    								        }
    								    }
    								    if($img) {
    								        $img_str='<img src="'.$img.'" height="50px">';
    								    }else {
    								        $img_str='';
    								    }
    								    echo '<tr>
           '.$value_str.'
           <td class="has-warning"><input type="text" name="price[]" value="'.$price.'" class="form-control" style="width:50px;text-align:center"/></td>
		   <td class="has-warning"><input type="text" name="inventory[]" value="'.$inventory.'" class="form-control" style="width:50px;text-align:center"/></td>
       	   <td class="has-warning"><input type="text" name="give_point[]" value="'.$give_point.'" class="form-control" style="width:50px;text-align:center"/></td>
           <td class="has-warning"><input type="text" name="deduction_point[]" value="'.$deduction_point.'" class="form-control" style="width:50px;text-align:center"/></td>
		   <td><input type="file" name="img[]" value="" />'.$img_str.'</td>
		 </tr>';
    								}
    								?>
									<tr>
										<td colspan="<?php echo $attribute_num+5;?>">
	        								<input type="reset" class="btn btn-primary pull-right" value="重置">
	        								<input type="submit" class="btn btn-primary pull-right" style="margin-right:10px" value="确认配置">
	        								
		  								</td>
	   								</tr>
								</tbody>
							</table>
							</form>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</body>
</html>