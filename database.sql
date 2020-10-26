/*
 Navicat Premium Data Transfer

 Source Server         : Localhost
 Source Server Type    : MySQL
 Source Server Version : 100406
 Source Host           : localhost:3306
 Source Schema         : proteam

 Target Server Type    : MySQL
 Target Server Version : 100406
 File Encoding         : 65001

 Date: 25/10/2020 21:09:48
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for classes
-- ----------------------------
DROP TABLE IF EXISTS `classes`;
CREATE TABLE `classes`  (
  `cla_id` int(11) NOT NULL AUTO_INCREMENT,
  `cla_name` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `cla_description` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `cla_iduser` int(11) NULL DEFAULT NULL,
  `cla_key` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `cla_allowteam` int(11) NULL DEFAULT NULL,
  `cla_createdat` datetime(0) NULL DEFAULT NULL,
  `cla_updatedat` datetime(0) NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP(0),
  PRIMARY KEY (`cla_id`) USING BTREE,
  INDEX `cla_iduser`(`cla_iduser`) USING BTREE,
  CONSTRAINT `classes_ibfk_1` FOREIGN KEY (`cla_iduser`) REFERENCES `users` (`use_id`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for members
-- ----------------------------
DROP TABLE IF EXISTS `members`;
CREATE TABLE `members`  (
  `mem_id` int(11) NOT NULL AUTO_INCREMENT,
  `mem_email` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `mem_password` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `mem_name` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `mem_tokenrecovery` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `mem_idclass` int(11) NULL DEFAULT NULL,
  `mem_createdat` datetime(0) NULL DEFAULT NULL,
  `mem_updatedat` datetime(0) NULL DEFAULT NULL,
  PRIMARY KEY (`mem_id`) USING BTREE,
  INDEX `mem_idclass`(`mem_idclass`) USING BTREE,
  CONSTRAINT `members_ibfk_1` FOREIGN KEY (`mem_idclass`) REFERENCES `classes` (`cla_id`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for sessions
-- ----------------------------
DROP TABLE IF EXISTS `sessions`;
CREATE TABLE `sessions`  (
  `ses_id` int(11) NOT NULL AUTO_INCREMENT,
  `ses_name` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `ses_endat` datetime(0) NULL DEFAULT NULL,
  `ses_idclass` int(11) NULL DEFAULT NULL,
  `ses_createdat` datetime(0) NULL DEFAULT NULL,
  `ses_updatedat` datetime(0) NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP(0),
  PRIMARY KEY (`ses_id`) USING BTREE,
  INDEX `ses_idclass`(`ses_idclass`) USING BTREE,
  CONSTRAINT `sessions_ibfk_1` FOREIGN KEY (`ses_idclass`) REFERENCES `classes` (`cla_id`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for sessions_topics
-- ----------------------------
DROP TABLE IF EXISTS `sessions_topics`;
CREATE TABLE `sessions_topics`  (
  `top_id` int(11) NOT NULL AUTO_INCREMENT,
  `top_idsession` int(11) NULL DEFAULT NULL,
  `top_description` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  PRIMARY KEY (`top_id`) USING BTREE,
  INDEX `top_idsession`(`top_idsession`) USING BTREE,
  CONSTRAINT `sessions_topics_ibfk_1` FOREIGN KEY (`top_idsession`) REFERENCES `sessions` (`ses_id`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for sessions_topics_feedbacks
-- ----------------------------
DROP TABLE IF EXISTS `sessions_topics_feedbacks`;
CREATE TABLE `sessions_topics_feedbacks`  (
  `fee_id` int(11) NOT NULL AUTO_INCREMENT,
  `fee_idtopic` int(11) NULL DEFAULT NULL,
  `fee_idmember` int(11) NULL DEFAULT NULL,
  `fee_idmembertarget` int(11) NULL DEFAULT NULL,
  `fee_stars` int(11) NULL DEFAULT NULL,
  `fee_createdat` datetime(0) NULL DEFAULT NULL,
  `fee_updatedat` datetime(0) NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP(0),
  PRIMARY KEY (`fee_id`) USING BTREE,
  INDEX `fee_idtopic`(`fee_idtopic`) USING BTREE,
  INDEX `fee_idmember`(`fee_idmember`) USING BTREE,
  INDEX `fee_idmembertarget`(`fee_idmembertarget`) USING BTREE,
  CONSTRAINT `sessions_topics_feedbacks_ibfk_1` FOREIGN KEY (`fee_idtopic`) REFERENCES `sessions_topics` (`top_id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `sessions_topics_feedbacks_ibfk_2` FOREIGN KEY (`fee_idmember`) REFERENCES `members` (`mem_id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `sessions_topics_feedbacks_ibfk_3` FOREIGN KEY (`fee_idmembertarget`) REFERENCES `members` (`mem_id`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for teams
-- ----------------------------
DROP TABLE IF EXISTS `teams`;
CREATE TABLE `teams`  (
  `tea_id` int(11) NOT NULL AUTO_INCREMENT,
  `tea_name` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `tea_description` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `tea_idmember` int(11) NULL DEFAULT NULL,
  `tea_createdat` datetime(0) NULL DEFAULT NULL,
  `tea_updatedat` datetime(0) NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP(0),
  PRIMARY KEY (`tea_id`) USING BTREE,
  INDEX `tea_idmember`(`tea_idmember`) USING BTREE,
  CONSTRAINT `teams_ibfk_1` FOREIGN KEY (`tea_idmember`) REFERENCES `members` (`mem_id`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for teams_members
-- ----------------------------
DROP TABLE IF EXISTS `teams_members`;
CREATE TABLE `teams_members`  (
  `tme_id` int(11) NOT NULL AUTO_INCREMENT,
  `tme_idteam` int(11) NULL DEFAULT NULL,
  `tme_idmember` int(11) NULL DEFAULT NULL,
  PRIMARY KEY (`tme_id`) USING BTREE,
  INDEX `tme_idteam`(`tme_idteam`) USING BTREE,
  INDEX `tme_idmember`(`tme_idmember`) USING BTREE,
  CONSTRAINT `teams_members_ibfk_1` FOREIGN KEY (`tme_idteam`) REFERENCES `teams` (`tea_id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `teams_members_ibfk_2` FOREIGN KEY (`tme_idmember`) REFERENCES `members` (`mem_id`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for users
-- ----------------------------
DROP TABLE IF EXISTS `users`;
CREATE TABLE `users`  (
  `use_id` int(11) NOT NULL AUTO_INCREMENT,
  `use_email` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `use_password` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `use_name` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `use_tokenrecovery` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `use_createdat` datetime(0) NULL DEFAULT NULL,
  `use_updatedat` datetime(0) NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP(0),
  PRIMARY KEY (`use_id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

SET FOREIGN_KEY_CHECKS = 1;
