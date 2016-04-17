--演示数据库 by George Hao

create database zxkm;
SET NAMES UTF8;

use zxkm;

CREATE TABLE IF NOT EXISTS `zxkm_member_base` (
  `member_id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '会员id',
  `nick_name` varchar(50) NOT NULL DEFAULT '' COMMENT '昵称',
  `member_img` varchar(128) DEFAULT NULL COMMENT '用户头像',
  `mobile` varchar(12) NOT NULL,
  `password` varchar(32) NOT NULL DEFAULT '0' COMMENT '登录密码',
  `rank_id` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '会员级别id',
  `lasttime` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '上次登录时间戳',
  `addtime` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '注册时间时间戳',
  `sex` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '性别，0-女，1-男',
  `birthday` varchar(13) NOT NULL DEFAULT '0000-00-00' COMMENT '出生日期',
  `job` varchar(10) NOT NULL DEFAULT '' COMMENT '职业名称',
  `buy_num` int(10) unsigned DEFAULT '0' COMMENT '购买次数',
  `store_num` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '到店次数',
  `balance` decimal(10,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '红包余额',
  `luckybag_total` decimal(20,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '红包总额',
  `location` varchar(100) DEFAULT NULL COMMENT '所在地',
  `signature` varchar(300) DEFAULT NULL COMMENT '个性签名',
  PRIMARY KEY (`member_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='会员信息基础表' AUTO_INCREMENT=1 ;

--
-- 转存表中的数据 `oao_member_base`
--

INSERT INTO `zxkm_member_base` (`member_id`, `nick_name`, `member_img`, `mobile`, `password`, `rank_id`, `lasttime`, `addtime`, `sex`, `birthday`, `job`, `buy_num`, `store_num`, `balance`, `luckybag_total`, `location`, `signature`) VALUES
(1, 'oao_182****9002', NULL, '18201239002', '203efe78613e6760415995d4641be5eb', 1, 1446645072, 1446600260, 0, '0000-00-00', '', 0, 0, '192.00', '200.00', NULL, NULL),
(2, 'oao_135****3490', NULL, '13501153490', '203efe78613e6760415995d4641be5eb', 1, 1446619613, 1446616144, 0, '0000-00-00', '', 0, 0, '0.00', '0.00', NULL, NULL),
(3, 'oao_133****2632', NULL, '13381252632', '203efe78613e6760415995d4641be5eb', 1, 1446617288, 1446616432, 0, '0000-00-00', '', 0, 0, '199.00', '200.00', NULL, NULL),
(4, 'oao_137****3979', NULL, '13720013979', '203efe78613e6760415995d4641be5eb', 1, 1446618032, 1446616638, 0, '0000-00-00', '', 0, 0, '200.00', '200.00', NULL, NULL),
(5, 'oao_150****4202', NULL, '15011454202', '203efe78613e6760415995d4641be5eb', 1, 1446694646, 1446616638, 0, '0000-00-00', '', 0, 0, '195.00', '200.00', NULL, NULL),
(6, 'oao_159****5277', NULL, '15901215277', '203efe78613e6760415995d4641be5eb', 1, 1446617099, 1446616643, 0, '0000-00-00', '', 0, 0, '192.00', '200.00', NULL, NULL),
(7, '薇薇', '', '18610813752', '203efe78613e6760415995d4641be5eb', 1, 1446616772, 1446616649, 0, '', '喵', 0, 0, '200.00', '200.00', '', ''),
(8, 'oao_186****0406', '/Uploads/Default/144661831234594.jpg', '18618380406', '203efe78613e6760415995d4641be5eb', 1, 1446645354, 1446616656, 1, '1967-09-02', '自由', 0, 0, '260.00', '260.00', '北京', '无欲则刚'),
(9, 'oao_188****7857', NULL, '18811077857', '203efe78613e6760415995d4641be5eb', 1, 1446617065, 1446616660, 0, '0000-00-00', '', 0, 0, '230.00', '230.00', NULL, NULL),
(10, 'oao_156****6683', NULL, '15652786683', '203efe78613e6760415995d4641be5eb', 1, 1446618192, 1446616663, 0, '0000-00-00', '', 0, 0, '200.00', '200.00', NULL, NULL),
(11, 'oao_158****7431', NULL, '15810687431', '203efe78613e6760415995d4641be5eb', 1, 1446617841, 1446616670, 0, '0000-00-00', '', 0, 0, '200.00', '200.00', NULL, NULL),
(12, 'oao_153****8637', NULL, '15321158637', '203efe78613e6760415995d4641be5eb', 1, 1446619403, 1446616674, 0, '0000-00-00', '', 0, 0, '192.00', '200.00', NULL, NULL),
(13, 'oao_136****2191', NULL, '13691382191', '203efe78613e6760415995d4641be5eb', 1, 1446692347, 1446616678, 0, '0000-00-00', '', 0, 0, '200.00', '200.00', NULL, NULL),
(14, 'oao_136****4821', NULL, '13693104821', '203efe78613e6760415995d4641be5eb', 1, 1446631322, 1446616679, 0, '0000-00-00', '', 0, 0, '195.00', '200.00', NULL, NULL),
(15, 'oao_186****0975', NULL, '18680220975', '203efe78613e6760415995d4641be5eb', 1, 1446619795, 1446616681, 0, '0000-00-00', '', 0, 0, '180.00', '200.00', NULL, NULL),
(16, '小飞', '', '15010081392', '203efe78613e6760415995d4641be5eb', 1, 1446616762, 1446616685, 0, '1991-06-18', '', 0, 0, '285.00', '285.00', '', ''),
(17, '岩岩', '', '18810372778', '203efe78613e6760415995d4641be5eb', 1, 1446705453, 1446616690, 0, '1991-11-04', 'chanpin', 0, 0, '190.10', '200.00', '上班', '相信自己'),
(18, 'oao_153****7913', NULL, '15313387913', '203efe78613e6760415995d4641be5eb', 1, 1446619730, 1446616716, 0, '0000-00-00', '', 0, 0, '230.00', '230.00', NULL, NULL),
(19, 'oao_138****5207', NULL, '13801115207', '203efe78613e6760415995d4641be5eb', 1, 1446616827, 1446616780, 0, '0000-00-00', '', 0, 0, '200.00', '200.00', NULL, NULL),
(20, 'oao_186****3640', NULL, '18600113640', '203efe78613e6760415995d4641be5eb', 1, 1446617126, 1446616810, 0, '0000-00-00', '', 0, 0, '200.00', '200.00', NULL, NULL),
(21, 'oao_186****2875', NULL, '18610122875', '203efe78613e6760415995d4641be5eb', 1, 1446617417, 1446616977, 0, '0000-00-00', '', 0, 0, '200.00', '200.00', NULL, NULL),
(22, 'oao_170****2017', NULL, '17001102017', '203efe78613e6760415995d4641be5eb', 1, 1446617057, 1446617027, 0, '0000-00-00', '', 0, 0, '0.00', '0.00', NULL, NULL),
(23, 'oao_135****9235', NULL, '13522209235', '203efe78613e6760415995d4641be5eb', 1, 1446643034, 1446617594, 0, '0000-00-00', '', 0, 0, '0.00', '0.00', NULL, NULL),
(24, 'oao_137****1203', NULL, '13717721203', '203efe78613e6760415995d4641be5eb', 1, 1446653372, 1446618159, 0, '0000-00-00', '', 0, 0, '200.00', '200.00', NULL, NULL),
(25, 'oao_155****1468', NULL, '15551711468', '203efe78613e6760415995d4641be5eb', 1, 1446621194, 1446618175, 0, '0000-00-00', '', 0, 0, '197.00', '200.00', NULL, NULL),
(26, 'oao_183****9122', NULL, '18301219122', '203efe78613e6760415995d4641be5eb', 1, 1446623210, 1446618305, 0, '0000-00-00', '', 0, 0, '200.00', '200.00', NULL, NULL),
(27, 'oao_182****6873', NULL, '18293436873', '203efe78613e6760415995d4641be5eb', 1, 1446619242, 1446619242, 0, '0000-00-00', '', 0, 0, '200.00', '200.00', NULL, NULL),
(28, 'oao_151****0044', NULL, '15132540044', '203efe78613e6760415995d4641be5eb', 1, 1446620029, 1446619894, 0, '0000-00-00', '', 0, 0, '230.00', '230.00', NULL, NULL),
(29, 'oao_187****6227', NULL, '18712896227', '203efe78613e6760415995d4641be5eb', 1, 1446624338, 1446624316, 0, '0000-00-00', '', 0, 0, '200.00', '200.00', NULL, NULL),
(30, 'oao_183****4547', NULL, '18301304547', '203efe78613e6760415995d4641be5eb', 1, 1446624760, 1446624760, 0, '0000-00-00', '', 0, 0, '200.00', '200.00', NULL, NULL),
(31, 'oao_186****2402', NULL, '18611422402', '203efe78613e6760415995d4641be5eb', 1, 1446631457, 1446631457, 0, '0000-00-00', '', 0, 0, '0.00', '0.00', NULL, NULL),
(32, 'oao_186****7806', NULL, '18610647806', '203efe78613e6760415995d4641be5eb', 1, 1446709553, 1446632247, 0, '0000-00-00', '', 0, 0, '200.00', '200.00', NULL, NULL),
(33, 'oao_135****3969', NULL, '13522123969', '203efe78613e6760415995d4641be5eb', 1, 1446633391, 1446633391, 0, '0000-00-00', '', 0, 0, '0.00', '0.00', NULL, NULL);



-- ----------------------------
-- Table structure for `zxkm_admin`
-- ----------------------------
DROP TABLE IF EXISTS `zxkm_admin`;
CREATE TABLE `zxkm_admin` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT COMMENT '管理员ID',
  `account` varchar(32) DEFAULT NULL COMMENT '管理员账号',
  `password` varchar(36) DEFAULT NULL COMMENT '管理员密码',
  `login_time` int(11) DEFAULT NULL COMMENT '最后登录时间',
  `login_count` mediumint(8) NOT NULL COMMENT '登录次数',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '账户状态，禁用为0   启用为1',
  `create_time` int(11) DEFAULT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of zxkm_admin
-- ----------------------------
INSERT INTO `zxkm_admin` VALUES ('1', 'admin', '21232f297a57a5a743894a0e4a801fc3', '1448606155', '335', '1', '1437979578');

-- ----------------------------
-- Table structure for `zxkm_auth_group`
-- ----------------------------
DROP TABLE IF EXISTS `zxkm_auth_group`;
CREATE TABLE `zxkm_auth_group` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `title` char(100) NOT NULL DEFAULT '',
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `rules` char(80) NOT NULL DEFAULT '',
  `create_time` int(11) DEFAULT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of zxkm_auth_group
-- ----------------------------
INSERT INTO `zxkm_auth_group` VALUES ('30', '超级管理组', '1', '1,2,3,14,15,16,17,18,19,21,25,4,5,6,9,11,24,35,36,37,38,12,13,22,23', '1445158837');

-- ----------------------------
-- Table structure for `zxkm_auth_group_access`
-- ----------------------------
DROP TABLE IF EXISTS `zxkm_auth_group_access`;
CREATE TABLE `zxkm_auth_group_access` (
  `uid` smallint(5) unsigned NOT NULL,
  `group_id` smallint(5) unsigned NOT NULL,
  UNIQUE KEY `uid_group_id` (`uid`,`group_id`),
  KEY `uid` (`uid`),
  KEY `group_id` (`group_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of zxkm_auth_group_access
-- ---------------------------
INSERT INTO `zxkm_auth_group_access` VALUES ('1', '30');
INSERT INTO `zxkm_auth_group_access` VALUES ('2', '31');
INSERT INTO `zxkm_auth_group_access` VALUES ('3', '33');
INSERT INTO `zxkm_auth_group_access` VALUES ('4', '31');
INSERT INTO `zxkm_auth_group_access` VALUES ('5', '31');

-- ----------------------------
-- Table structure for `zxkm_auth_rule`
-- ----------------------------
DROP TABLE IF EXISTS `zxkm_auth_rule`;
CREATE TABLE `zxkm_auth_rule` (
  `id` smallint(6) NOT NULL AUTO_INCREMENT,
  `name` varchar(80) NOT NULL DEFAULT '',
  `title` varchar(20) NOT NULL DEFAULT '',
  `type` tinyint(1) NOT NULL DEFAULT '1',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '状态',
  `condition` char(100) NOT NULL DEFAULT '',
  `pid` smallint(5) NOT NULL COMMENT '父级ID',
  `sort` tinyint(4) NOT NULL DEFAULT '50' COMMENT '排序',
  `create_time` int(11) NOT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of zxkm_auth_rule
-- ----------------------------
INSERT INTO `zxkm_auth_rule` VALUES ('36', 'Admin/admin_list', '管理员列表', '1', '1', '', '35', '50', '1444546437');
INSERT INTO `zxkm_auth_rule` VALUES ('35', 'Admin/index', '系统管理', '1', '1', '', '0', '50', '1444582187');
INSERT INTO `zxkm_auth_rule` VALUES ('37', 'Admin/auth_group', '用户组', '1', '1', '', '35', '50', '1445439984');
INSERT INTO `zxkm_auth_rule` VALUES ('38', 'Admin/auth_rule', '权限菜单', '1', '1', '', '35', '50', '1445439984');
