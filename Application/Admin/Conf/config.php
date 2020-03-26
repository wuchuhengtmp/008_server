<?php
return array(
		//'LAYOUT_ON' => true,  //开启布局模板功能
		//'LAYOUT_NAME' => 'Public/layout',  //设置布局入口文件名
		'AUTH_CONFIG' => array(
				'AUTH_ON' => true, //是否开启权限
				'AUTH_TYPE' => 1, //
				'AUTH_GROUP' => 'dmooo_admin_group', //用户组
				'AUTH_GROUP_ACCESS' => 'dmooo_admin', //用户-用户组关系表
				'AUTH_RULE' => 'dmooo_auth_rule', //权限规则表
				'AUTH_USER' => 'dmooo_admin'// 管理员表
		),
		//'DB_PATH_NAME'=> '/Public/db',        //备份目录名称,主要是为了创建备份目录；
		'DB_PATH'     => './Public/db/',     //数据库备份路径必须以 / 结尾；
		'DB_PART'     => '20971520',  //该值用于限制压缩后的分卷最大长度。单位：B；建议设置20M
		'DB_COMPRESS' => '1',         //压缩备份文件需要PHP环境支持gzopen,gzwrite函数        0:不压缩 1:启用压缩
		'DB_LEVEL'    => '9',         //压缩级别   1:普通   4:一般   9:最高
);