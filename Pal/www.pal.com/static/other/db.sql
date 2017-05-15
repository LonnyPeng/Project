﻿#数据库

#管理员表
CREATE TABLE `admin` (
  `aid` INT(11) UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT,
  `aname` VARCHAR(100) NOT NULL UNIQUE KEY,
  `apwd` VARCHAR(100) NOT NULL,
  `aadd` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `astatus` TINYINT(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

#通讯录表
CREATE TABLE `contract` (
  `cid` INT(11) UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT,
  `cname` VARCHAR(50) NOT NULL UNIQUE KEY,
  `csex` INT(1) NOT NULL,
  `cbirth` VARCHAR(50),
  `ctel` VARCHAR(100),
  `caddr` VARCHAR(200),
  `aadd` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `astatus` TINYINT(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



