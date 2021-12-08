-- ----------------------------
--  Table structure for `{{%oauth_user}}`
-- ----------------------------
CREATE TABLE `{{%oauth_user}}` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT COMMENT '自增ID',
  `uuid` varchar(50) NOT NULL COMMENT '用户/系统标识',
  `remark` varchar(255) NOT NULL DEFAULT '' COMMENT '备注',
  `is_enable` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '启用状态',
  `public_key` text DEFAULT NULL COMMENT '公钥',
  `private_key` text DEFAULT NULL COMMENT '私钥',
  `private_password` varchar(50) NOT NULL DEFAULT '' COMMENT 'openssl的私钥密码',
  `expire_ip` varchar(255) NOT NULL DEFAULT '' COMMENT '有效IP地址',
  `expire_begin_date` date NOT NULL DEFAULT '1000-01-01' COMMENT '生效日期',
  `expire_end_date` date NOT NULL DEFAULT '1000-01-01' COMMENT '失效日期',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间',
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '更新时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_uuid` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='三方系统访问权限登记';

-- ----------------------------
--  Table structure for `{{%oauth_token}}`
-- ----------------------------
CREATE TABLE `{{%oauth_token}}` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT COMMENT '自增ID',
  `uuid` varchar(50) NOT NULL DEFAULT '' COMMENT '用户/系统标识',
  `access_token` varchar(255) NOT NULL DEFAULT '' COMMENT '访问token',
  `expire_at` datetime NOT NULL COMMENT '有效时间',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间',
  PRIMARY KEY (`id`),
  KEY `idx_uuid` (`uuid`),
  KEY `idx_expireAt` (`expire_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='三方系统访问token记录';

