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
<script src="__ADMIN_JS__/plugins/layer/laydate/laydate.js"></script>

</head>

<body class="gray-bg">
	<div class="wrapper wrapper-content animated fadeInRight">
		<div class="row">
			<div class="col-sm-12">
				<div class="ibox float-e-margins">
					<div class="ibox-title">
						<h3>当前位置：会员管理 &raquo; 导出会员列表</h3>
					</div>
				</div>
			</div>
			<div class="row">
            <div class="col-sm-12">
                <div class="ibox float-e-margins">
                    <div class="ibox-content">
                        <form action="__ACTION__"  class="form-horizontal" method="post" enctype="multipart/form-data">
                        	<input type="hidden" name="tmp" value="1">
                            <div class="form-group">
                                <label class="col-sm-2 control-label">所属分组</label>
                                <div class="col-sm-10">
                                    <select class="form-control m-b" name="group_id">
                                    	<option value="">--请选择所属分组--</option>
                                         <?php 
                                         foreach($glist as $g) {
                                         	if($g['id']==$group_id) {
                                         		$select='selected';
                                         	}else {
                                                $select='';
                                            }
                                            echo '<option value="'.$g['id'].'" '.$select.'>--'.$g['title'].'--</option>';
                                          }
                                          ?>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">会员名</label>
                                <div class="col-sm-10">
                                    <input class="form-control" name="username" placeholder="">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">手机号码</label>
                                <div class="col-sm-10">
                                    <input class="form-control" name="phone" placeholder="">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">邮箱</label>
                                <div class="col-sm-10">
                                    <input class="form-control" name="email" placeholder="">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">注册开始日期</label>
                                <div class="col-sm-10">
                                    <input class="form-control layer-date" name="begin_time" placeholder="" onclick="laydate({istime: true, format: 'YYYY-MM-DD'})">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">注册结束日期</label>
                                <div class="col-sm-10">
                                    <input class="form-control layer-date" name="end_time" placeholder="" onclick="laydate({istime: true, format: 'YYYY-MM-DD'})">
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-sm-4 col-sm-offset-2">
                                    <button class="btn btn-primary" type="submit"><i class="fa fa-check"></i>&nbsp;导出会员</button>
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