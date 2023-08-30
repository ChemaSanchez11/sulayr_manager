/*
 Navicat Premium Data Transfer

 Source Server         : LocalHost [Root]
 Source Server Type    : MySQL
 Source Server Version : 100428
 Source Host           : localhost:3306
 Source Schema         : sulayr_manager

 Target Server Type    : MySQL
 Target Server Version : 100428
 File Encoding         : 65001

 Date: 30/08/2023 22:06:17
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for ip_visit
-- ----------------------------
DROP TABLE IF EXISTS `ip_visit`;
CREATE TABLE `ip_visit`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `id_visit` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `ip` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `count` int NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 13 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of ip_visit
-- ----------------------------
INSERT INTO `ip_visit` VALUES (9, '8', '127.0.0.1', 4);
INSERT INTO `ip_visit` VALUES (12, '8', '192.168.1.179', 1);

-- ----------------------------
-- Table structure for proyects
-- ----------------------------
DROP TABLE IF EXISTS `proyects`;
CREATE TABLE `proyects`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `shortname` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `domain` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `data` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '{\"database\":true,\"centro_red_domain\":true,\"cache_data\":true,\"ssl_certificate\":true}',
  `active` int NOT NULL DEFAULT 1,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 2 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of proyects
-- ----------------------------
INSERT INTO `proyects` VALUES (1, 'leobus', 'Leobus', 'https://leobus-travel.com/', '{\"database\":true,\"centro_red_domain\":true,\"cache_data\":true,\"ssl_certificate\":true}', 1);

-- ----------------------------
-- Table structure for visit
-- ----------------------------
DROP TABLE IF EXISTS `visit`;
CREATE TABLE `visit`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `id_proyect` int NOT NULL,
  `total` int NOT NULL DEFAULT 1,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 9 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of visit
-- ----------------------------
INSERT INTO `visit` VALUES (8, 1, 7);

SET FOREIGN_KEY_CHECKS = 1;
