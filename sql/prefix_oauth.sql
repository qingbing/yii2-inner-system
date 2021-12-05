-- ----------------------------
--  Table structure for `{{%oauth_user}}`
-- ----------------------------
CREATE TABLE `{{%oauth_user}}` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT COMMENT '自增ID',
  `uuid` varchar(50) NOT NULL DEFAULT '' COMMENT '用户/系统标识',
  `flag` varchar(255) NOT NULL DEFAULT '' COMMENT '允许访问的系统标识,多个用|分割',
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

insert into `{{%oauth_user}}` ( `uuid`, `flag`, `public_key`, `private_key`, `private_password`, `expire_ip`, `expire_begin_date`, `expire_end_date`, `created_at`, `updated_at`)
values
( 'configure', 'inner', 'MIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQC/rHe57ewHFpVX8lSwd9swNYBhQn5kIo7HMdOgjMEfsIj0FZTFDbyXwnlrLIsMPlARJ/D3v5c5b7fCREIiuVtl0DEG9h0Au5S/y09YURNxENqENPQP+p4j427rY1OGP7PWl1T4Hthmmz6c5WjvdbGKBNmA3XyTN9Pk44oiyqHOzwIDAQAB', 'MIIC1DBOBgkqhkiG9w0BBQ0wQTApBgkqhkiG9w0BBQwwHAQIRPuEh/nLAOYCAggAMAwGCCqGSIb3DQIJBQAwFAYIKoZIhvcNAwcECGkn+A4jkFlvBIICgFcIQ4nuPo5IYAD1WdvN3vZ8La6/wbmM7BnJxBvvSTTzzOuBEycPLRYqaiCspgRfoyPNPHjvoyaz6gq4K/HxtVG7XExPIt8vkofvTjE9cLsy74SvQJqKOOfFDG7h+KE+3g+Q86QJGdZ4a6YjlKl/Vat2wq/9kpgv2pwLUnUD9PgtZgMSp566XBA5j034RfU98uR7Cai4+tuiZ9pgqJua2Qok4i73/Y8e11Xfrl/WI7uqYhJsx3ckM3D7Oq5NzH5rD1iuKCGCZutqM0F6PYYucQznWg9zBKhIKajs7TJoTp6Csur/+Ot11DqZNUOzk3qR+bGPbcT362//zvPnnvFO3QAfSTbD7zkX1mZ2nJdvYsy6iYsLb+0RbyyeHD6EHzJM/GuOdeNY3NE1atW9o7iYkwjt2GnHm3XsFPOxEYNrw9c3XaLIGbkqn9xuRw/FgejG0mCcYJDZEFEj9GWKQp3zfkaMmuhCCPUjtPoY2+dYwYmJGo+stBbBD7rLbqu/cgO+U+poUrda3NJVkJzvYA15v2L44dNQLC5rjYT2S2KDNNQdfJGBQ/kjtCUuAU+Vn9Uit8mTcSUrFmKpe3J3uuSvcUnBj6dWw/HGlLXpKsafn7pQrhF9/BLw0vD5wHmagNh3V/7yKKAgptCYa2wiFHICZTpmmnUhChX1Inni/ANR8JV72pZ9jTdvahSGi+jkH9XjFN0+kUmkzNoYsZoFAnOlQXgbG2G64G1KJEHkgQFKxOdIu4lv8A8qwBQTFO/uV1233VF26SHSx2OZF8m9Zl9ptK44xLM0tPhjKKcKuSUGmfEtuSwwjZwaaiv3kz+UQDoPz+PcJZ8k1XG8ezDThiwxX5U=', 'phpcorner.net', '', '1000-01-01', '1000-01-01', '2021-11-06 22:17:21', '2021-11-06 22:19:38');