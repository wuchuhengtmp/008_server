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
    <link rel="stylesheet" href="__LAYUIADMIN__/layui/css/layui.css" media="all">
    <link rel="stylesheet" href="__LAYUIADMIN__/style/admin.css" media="all">
<script src="__ADMIN_JS__/jquery.min.js?v=2.1.4"></script>
<script src="__ADMIN_JS__/plugins/iCheck/icheck.min.js"></script>
<script src="__ADMIN_JS__/plugins/layer/laydate/laydate.js"></script>
<!-- ueditor -->
<link rel="stylesheet" type="text/css" href="__PUBLIC__/ueditor/themes/default/css/ueditor.css" />
<script type="text/javascript" charset="utf-8" src="__PUBLIC__/ueditor/ueditor.config.js"></script>
<script type="text/javascript" charset="utf-8" src="__PUBLIC__/ueditor/ueditor.all.min.js"> </script>
<script type="text/javascript" charset="utf-8" src="__PUBLIC__/ueditor/lang/zh-cn/zh-cn.js"></script>
<!-- ueditor -->
<script>
//实例化编辑器
var ue = UE.getEditor('editor');

$(document).ready(function(){$(".i-checks").iCheck({checkboxClass:"icheckbox_square-green",radioClass:"iradio_square-green",})});
</script>
</head>

<body class="gray-bg">
<div class="layui-fluid">
    <div class="layui-row layui-col-space15">
        <div class="layui-col-md12">
            <div class="layui-card">
                <div class="ibox-title">
						<h3>当前位置：营销中心 &raquo; 拉新活动 &raquo; 编辑活动<a class="pull-right" href="__CONTROLLER__/index">返回上一页 <i class="fa fa-angle-double-right"></i></a></h3>
					</div>
				</div>
			</div>
        <div class="layui-row layui-col-space15">
            <div class="layui-col-md12">
                <div class="layui-card">
                    <div class="layui-card-body">
                        <form action="__ACTION__/id/{$id}"  class="form-horizontal" method="post" enctype="multipart/form-data">
                            <div class="layui-form-item">
                                <label class="layui-form-label" style="width: 100px;">名称</label>
                                <div class="layui-input-block" style="width: 92%;">
                                    <input type="text" class="layui-input" name="name" value="{$data.name}" placeholder="必填，请输入活动名称" >
                                </div>
                            </div>
                            <div class="layui-form-item">
                                <label class="layui-form-label" style="width: 100px;">活动时段</label>
                                <div class="layui-input-block" style="width: 92%;">
                                    <input type="text" class="layui-input layer-date" name="start_time" value="{$data.start_time}" placeholder="" onclick="laydate({istime: true, format: 'YYYY-MM-DD hh:mm:ss'})"> -
                                    <input type="text" class="layui-input layer-date" name="end_time" value="{$data.end_time}" placeholder="" onclick="laydate({istime: true, format: 'YYYY-MM-DD hh:mm:ss'})">
                                </div>
                            </div>
                            <div class="layui-form-item">
                                <label class="layui-form-label" style="width: 100px;">兑现时段</label>
                                <div class="layui-input-block" style="width: 92%;">
                                    <input type="text" class="layui-input layer-date" name="exs_time" value="{$data.exs_time}" placeholder="" onclick="laydate({istime: true, format: 'YYYY-MM-DD hh:mm:ss'})"> -
                                    <input type="text" class="layui-input layer-date" name="exe_time" value="{$data.exe_time}" placeholder="" onclick="laydate({istime: true, format: 'YYYY-MM-DD hh:mm:ss'})">
                                </div>
                            </div>
                            <div class="layui-form-item">
                                <label class="layui-form-label" style="width: 100px;">等级个数</label>
                                <div class="layui-input-block" style="width: 92%;">
                                    <input type="text" class="layui-input" name="lv_num" value="{$data.lv_num}" placeholder="必填，请输入等级个数" >
                                </div>
                            </div>
                            <div class="layui-form-item">
                                <label class="layui-form-label" style="width: 100px;">计算奖励方式</label>
                                <div class="layui-input-block" style="width: 92%;">
                                <?php
                                $ex_type = $data['ex_type'];
                                // 设置奖励方式下拉框
                                if ($ex_type == 1) {
                                    $selected1 = "selected";
                                } else if ($ex_type == 2){
                                    $selected2 = "selected";
                                }
                                ?>
                                    <select class="layui-input m-b" name="ex_type">
                                    	<option value="1" {$selected1}>按人数*区间奖励数量</option>
                            			<option value="2" {$selected2}>按等级直接拿区间奖励数量</option>
                                    </select>
                                </div>
                            </div>
                            <div class="layui-form-item">
                                <label class="layui-form-label" style="width: 100px;">内容</label>
                                <div class="layui-input-block" style="width: 92%;">
                                	<script name="content" id="editor" type="text/plain" style="height:300px;">
                                    <?php 
                                    $content=htmlspecialchars_decode(html_entity_decode($data['content']));
		                            $content=str_replace("&#39;", '"', $content);
		                            echo $content;?>
                                    </script>
                                </div>
                            </div>
<!--                            <div class="layui-form-item">-->
<!--                                <div class="col-sm-4 col-sm-offset-2">-->
<!--                                    <button class="btn btn-primary" type="submit"><i class="fa fa-check"></i>&nbsp;编辑</button>-->
<!--                                </div>-->
<!--                            </div>-->
                            <div class="layui-form-item layui-layout-admin">
                                <div class="layui-input-block">
                                    <button class="layui-btn" type="submit"><i class="fa fa-check"></i>&nbsp;编辑</button>
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