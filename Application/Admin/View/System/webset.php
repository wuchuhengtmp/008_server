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
    <script src="__ADMIN_JS__/bootstrap.min.js?v=3.3.6"></script>
</head>
<body class="gray-bg">
<div class="layui-fluid">
    <div class="layui-row layui-col-space15">
        <div class="layui-col-md12">
            <div class="layui-card">
                <div class="ibox-title">
                    <h3>当前位置：系统设置 &raquo; 站点设置</h3>
                </div>
            </div>
        </div>
        <div class="layui-row layui-col-space15">
            <div class="layui-col-md12">
                <div class="layui-card">
                    <div class="layui-card-body">
                        <div class="layui-tab">
                            <ul class="nav nav-tabs">
                                <li class="active">
                                    <a data-toggle="tab" href="#tab-1" aria-expanded="true">APP配置</a>
                                </li>
                                <li class="">
                                    <a data-toggle="tab" href="#tab-2" aria-expanded="false">网站配置</a>
                                </li>
                            </ul>
                            <form action="__ACTION__"  class="form-horizontal" method="post" enctype="multipart/form-data">
                                <input type="hidden" name="old_app_name" value="{$msg['app_name']}">
                                <input type="hidden" name="old_version_ios" value="{$msg['version_ios']}">
                                <input type="hidden" name="old_version_android" value="{$msg['version_android']}">
                                <input type="hidden" name="old_down_ios" value="{$msg['down_ios']}">
                                <input type="hidden" name="old_down_android" value="{$msg['down_android']}">
                                <input type="hidden" name="old_update_content_ios" value="{$msg['update_content_ios']}">
                                <input type="hidden" name="old_update_content_android" value="{$msg['update_content_android']}">

                                <input type="hidden" name="old_web_url" value="{$msg['web_url']}">
                                <input type="hidden" name="old_web_title" value="{$msg['web_title']}">
                                <input type="hidden" name="old_keywords" value="{$msg['keywords']}">
                                <input type="hidden" name="old_description" value="{$msg['description']}">
                                <input type="hidden" name="old_copyright" value="{$msg['copyright']}">
                                <input type="hidden" name="old_web_title_en" value="{$msg['web_title_en']}">
                                <input type="hidden" name="old_keywords_en" value="{$msg['keywords_en']}">
                                <input type="hidden" name="old_description_en" value="{$msg['description_en']}">
                                <input type="hidden" name="old_copyright_en" value="{$msg['copyright_en']}">
                                <div class="tab-content">
                                    <!-- APP配置  -->
                                    <div id="tab-1" class="tab-pane active" style="padding-top: 10px">
                                        <div class="layui-form-item">
                                            <label class="layui-form-label" style="width: 8%;">App名称</label>
                                            <div class="layui-input-block">
                                                <input type="text" class="layui-input" name="app_name" value="{$msg['app_name']}" placeholder="" style="width: 92%;">
                                            </div>
                                        </div>
                                        <div class="layui-form-item">
                                            <label class="layui-form-label" style="width: 8%;">苹果版本号</label>
                                            <div class="layui-input-block">
                                                <input type="text" class="layui-input" name="version_ios" value="{$msg['version_ios']}" placeholder="" style="width: 92%;">
                                            </div>
                                        </div>
                                        <div class="layui-form-item">
                                            <label class="layui-form-label" style="width: 8%;">安卓版本号</label>
                                            <div class="layui-input-block">
                                                <input type="text" class="layui-input" name="version_android" value="{$msg['version_android']}" placeholder="" style="width: 92%;">
                                            </div>
                                        </div>
                                        <div class="layui-form-item">
                                            <label class="layui-form-label" style="width: 8%;">苹果下载地址</label>
                                            <div class="layui-input-block">
                                                <input type="text" class="layui-input" name="down_ios" value="{$msg['down_ios']}" placeholder="" style="width: 92%;">
                                            </div>
                                        </div>
                                        <div class="layui-form-item">
                                            <label class="layui-form-label" style="width: 8%;">安卓下载地址</label>
                                            <div class="layui-input-block">
                                                <input type="text" class="layui-input" name="down_android" value="{$msg['down_android']}" placeholder="" style="width: 92%;">
                                            </div>
                                        </div>
                                        <div class="layui-form-item">
                                            <label class="layui-form-label" style="width: 8%;">苹果新版本更新内容</label>
                                            <div class="layui-input-block">
                                                <textarea name="update_content_ios" placeholder="" class="layui-input" style="width: 92%;">{$msg['update_content_ios']}</textarea>
                                            </div>
                                        </div>
                                        <div class="layui-form-item">
                                            <label class="layui-form-label" style="width: 8%;">安卓新版本更新内容</label>
                                            <div class="layui-input-block">
                                                <textarea name="update_content_android" placeholder="" class="layui-input" style="width: 92%;">{$msg['update_content_android']}</textarea>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- APP配置  -->

                                    <!-- 网站配置  -->
                                    <div id="tab-2" class="tab-pane" style="padding-top: 10px">
                                        <div class="layui-form-item">
                                            <label class="layui-form-label" style="width: 8%;">网站标题</label>
                                            <div class="layui-input-block">
                                                <input type="text" class="layui-input" name="web_title" value="{$msg['web_title']}" style="width: 92%;">
                                            </div>
                                        </div>
                                        <div class="layui-form-item">
                                            <label class="layui-form-label" style="width: 8%;">网站logo</label>
                                            <div class="layui-input-block">
                                                <img src="__ADMIN_IMG__/logo.png" width="72">
                                                <span class="help-block m-b-none text-danger">格式要求PNG，文件大小72x72px</span>
                                            </div>
                                        </div>
                                        <div class="layui-form-item">
                                            <label class="layui-form-label" style="width: 8%;">上传新logo</label>
                                            <div class="layui-input-block">
                                                <input type="file" class="layui-input" name="logo" value="" style="width: 92%;">
                                            </div>
                                        </div>
                                        <div class="layui-form-item">
                                            <label class="layui-form-label" style="width: 8%;">英文网站标题</label>
                                            <div class="layui-input-block">
                                                <input type="text" class="layui-input" name="web_title_en" value="{$msg['web_title_en']}" style="width: 92%;">
                                            </div>
                                        </div>
                                        <div class="layui-form-item">
                                            <label class="layui-form-label" style="width: 8%;">官网网址/IP地址</label>
                                            <div class="layui-input-block">
                                                <input type="text" class="layui-input" name="web_url" value="{$msg['web_url']}" style="width: 92%;">
                                            </div>
                                        </div>
                                        <div class="layui-form-item">
                                            <label class="layui-form-label" style="width: 8%;">SEO关键字(keyword)</label>
                                            <div class="layui-input-block">
                                                <textarea name="keywords" placeholder="" class="layui-input" style="width: 92%;">{$msg['keywords']}</textarea>
                                            </div>
                                        </div>
                                        <div class="layui-form-item">
                                            <label class="layui-form-label" style="width: 8%;">SEO描述(description)</label>
                                            <div class="layui-input-block">
                                                <textarea name="description" placeholder="" class="layui-input" style="width: 92%;">{$msg['description']}</textarea>
                                            </div>
                                        </div>
                                        <div class="layui-form-item">
                                            <label class="layui-form-label" style="width: 8%;">SEO版权(copyright)</label>
                                            <div class="layui-input-block">
                                                <textarea name="copyright" placeholder="" class="layui-input" style="width: 92%;">{$msg['copyright']}</textarea>
                                            </div>
                                        </div>
                                        <div class="layui-form-item">
                                            <label class="layui-form-label" style="width: 8%;">英文SEO关键字(keyword)</label>
                                            <div class="layui-input-block">
                                                <textarea name="keywords_en" placeholder="" class="layui-input" style="width: 92%;">{$msg['keywords_en']}</textarea>
                                            </div>
                                        </div>
                                        <div class="layui-form-item">
                                            <label class="layui-form-label" style="width: 8%;">英文SEO描述(description)</label>
                                            <div class="layui-input-block">
                                                <textarea name="description_en" placeholder="" class="layui-input" style="width: 92%;">{$msg['description_en']}</textarea>
                                            </div>
                                        </div>
                                        <div class="layui-form-item">
                                            <label class="layui-form-label" style="width: 8%;">英文SEO版权(copyright)</label>
                                            <div class="layui-input-block">
                                                <textarea name="copyright_en" placeholder="" class="layui-input" style="width: 92%;">{$msg['copyright_en']}</textarea>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- 网站配置  -->
                                    <!--                        <div class="form-group">-->
                                    <!--                             <div class="col-sm-4 col-sm-offset-2">-->
                                    <!--                                 <button class="btn btn-primary" type="submit"><i class="fa fa-check"></i>&nbsp;编辑</button>-->
                                    <!--                                 <button class="btn btn-white" type="reset"><i class="fa fa-refresh"></i>&nbsp;重置</button>-->
                                    <!--                             </div>-->
                                    <!--                         </div>-->
                                    <div class="layui-form-item layui-layout-admin">
                                        <div class="layui-input-block">
                                            <button type="submit" class="layui-btn"><i class="fa fa-check"></i>&nbsp;编辑</button>
                                            <button type="reset" class="layui-btn layui-btn-primary"><i class="fa fa-refresh"></i>&nbsp;重置</button>
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
</div>
</body>
</html>