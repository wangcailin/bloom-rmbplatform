/*
 Navicat Premium Data Transfer

 Source Server         : localhost_3306
 Source Server Type    : MySQL
 Source Server Version : 50639
 Source Host           : localhost:3306
 Source Schema         : fastadmin

 Target Server Type    : MySQL
 Target Server Version : 50639
 File Encoding         : 65001

 Date: 11/05/2018 11:10:42
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for bloom_rmbplatform
-- ----------------------------
DROP TABLE IF EXISTS `bloom_rmbplatform`;
CREATE TABLE `bloom_rmbplatform` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `firstname` varchar(100) NOT NULL COMMENT '姓',
  `lastname` varchar(100) NOT NULL COMMENT '名',
  `company` varchar(100) NOT NULL COMMENT '公司',
  `job` varchar(100) NOT NULL COMMENT '职位',
  `city` varchar(100) NOT NULL COMMENT '城市',
  `country` varchar(100) NOT NULL COMMENT '国家',
  `email` varchar(100) NOT NULL COMMENT '邮箱',
  `tel` varchar(32) NOT NULL COMMENT '电话',
  `service` varchar(100) DEFAULT NULL COMMENT '所需服务',
  `site` varchar(100) DEFAULT NULL COMMENT '地点',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

SET FOREIGN_KEY_CHECKS = 1;
