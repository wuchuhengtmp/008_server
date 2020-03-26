# 版本升级修改
## sql
alter table `dmooo_user_group` add(
    `self_rate` int(11) default 30,
    `referrer_rate` int(11) default 30,
    `referrer_rate2` int(11) default 30)

## 黑卡功能

CREATE TABLE IF NOT EXISTS `dmooo_card_category` (
  `id` int(11) NOT NULL auto_increment,
  `category_name` varchar(255) NOT NULL DEFAULT '',
  `desc` varchar(50) NOT NULL DEFAULT '',
  `add_time` int(11) DEFAULT NULL DEFAULT '',
  `status` int(11) DEFAULT '1',
  `order` int(11) DEFAULT '10',
  primary key `id`(`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


INSERT INTO `dmooo_card_category` (`id`, `category_name`, `desc`, `add_time`, `status`, `order`) VALUES
(1,'优惠充值', '聚便宜', '1573637062', 1, '0'),
(2,'美食餐饮', '品牌优选', '1573637062', 1, '0'),
(3,'购物休闲', '钜折扣', '1573637062', 1, '0'),
(4,'旅行出游', '享生活', '1573637062', 1, '0'),
(5,'生活服务', '超便利', '1573637062', 1, '0'),
(6,'车主福利', '爱车人', '1573637062', 1, '0');



CREATE TABLE IF NOT EXISTS `dmooo_card_privilege` (
  `id` int(11) NOT NULL auto_increment,
  `url` varchar(255) NOT NULL default '',
  `cate_id` int(11) NOT NULL default '0',
  `logo` varchar(100) NOT NULL default '',
  `title` varchar(100) NOT NULL default '',
  `sub_title` varchar(100) NOT NULL default '',
  `sort` int(11) NOT NULL default '10',
  `addtime` int(11) NOT NULL default '0',
  `status` int(11) NOT NULL DEFAULT '1',
  `new` int(11) NOT NULL DEFAULT '0',
  primary key `id`(`id`),
  key `cate_id`(`cate_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `ims_hccard_privilege` (`id`, `url`, `logo`, `title`, `sub_title`, `sort`, `addtime`, `status`, `new`) VALUES
(10,'https://music.163.com/m/at/mailbox', 'http://b2dfdc47e670.66bbn.com/uploads/20190907/ee58096dcc5679d2a754993e3a034b9c.png', '网易云音乐', '8.8折优惠券', 1, '1583460112', 1, 0),
(11,'https://music.163.com/m/at/mailbox', 'http://b2dfdc47e670.66bbn.com/uploads/20190907/ee58096dcc5679d2a754993e3a034b9c.png', '网易云音乐', '8.8折优惠券', 1, '1583460112', 1, 0),
(12,'https://music.163.com/m/at/mailbox', 'http://b2dfdc47e670.66bbn.com/uploads/20190907/ee58096dcc5679d2a754993e3a034b9c.png', '网易云音乐', '8.8折优惠券', 1, '1583460112', 1, 0),
(13,'https://music.163.com/m/at/mailbox', 'http://b2dfdc47e670.66bbn.com/uploads/20190907/ee58096dcc5679d2a754993e3a034b9c.png', '网易云音乐', '8.8折优惠券', 1, '1583460112', 1, 0),
(14,'https://music.163.com/m/at/mailbox', 'http://b2dfdc47e670.66bbn.com/uploads/20190907/ee58096dcc5679d2a754993e3a034b9c.png', '网易云音乐', '8.8折优惠券', 1, '1583460112', 1, 0),
(15,'https://music.163.com/m/at/mailbox', 'http://b2dfdc47e670.66bbn.com/uploads/20190907/ee58096dcc5679d2a754993e3a034b9c.png', '网易云音乐', '8.8折优惠券', 1, '1583460112', 1, 0),
(16,'https://music.163.com/m/at/mailbox', 'http://b2dfdc47e670.66bbn.com/uploads/20190907/ee58096dcc5679d2a754993e3a034b9c.png', '网易云音乐', '8.8折优惠券', 1, '1583460112', 1, 0),
(17,'https://music.163.com/m/at/mailbox', 'http://b2dfdc47e670.66bbn.com/uploads/20190907/ee58096dcc5679d2a754993e3a034b9c.png', '网易云音乐', '8.8折优惠券', 1, '1583460112', 1, 0),
(18,'https://music.163.com/m/at/mailbox', 'http://b2dfdc47e670.66bbn.com/uploads/20190907/ee58096dcc5679d2a754993e3a034b9c.png', '网易云音乐', '8.8折优惠券', 1, '1583460112', 1, 0),
(19,'https://music.163.com/m/at/mailbox', 'http://b2dfdc47e670.66bbn.com/uploads/20190907/ee58096dcc5679d2a754993e3a034b9c.png', '网易云音乐', '8.8折优惠券', 1, '1583460112', 1, 0),
(20,'https://music.163.com/m/at/mailbox', 'http://b2dfdc47e670.66bbn.com/uploads/20190907/ee58096dcc5679d2a754993e3a034b9c.png', '网易云音乐', '8.8折优惠券', 1, '1583460112', 1, 0),
(21,'https://music.163.com/m/at/mailbox', 'http://b2dfdc47e670.66bbn.com/uploads/20190907/ee58096dcc5679d2a754993e3a034b9c.png', '网易云音乐', '8.8折优惠券', 1, '1583460112', 1, 0),
(22,'https://music.163.com/m/at/mailbox', 'http://b2dfdc47e670.66bbn.com/uploads/20190907/ee58096dcc5679d2a754993e3a034b9c.png', '网易云音乐', '8.8折优惠券', 1, '1583460112', 1, 0);

