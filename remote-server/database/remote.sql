/*
 Navicat Premium Dump SQL

 Source Server         : remote
 Source Server Type    : MySQL
 Source Server Version : 50726 (5.7.26)
 Source Host           : localhost:3306
 Source Schema         : remote

 Target Server Type    : MySQL
 Target Server Version : 50726 (5.7.26)
 File Encoding         : 65001

 Date: 03/09/2024 19:29:25
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for rd_clients
-- ----------------------------
DROP TABLE IF EXISTS `rd_clients`;
CREATE TABLE `rd_clients`  (
  `id` int(10) UNSIGNED NOT NULL COMMENT '识别码',
  `uid` char(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `pc_name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '设备名称',
  `max_address` varchar(45) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'MAC地址',
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL COMMENT '密码',
  `ip_address` varchar(45) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '内网IP',
  `note` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL COMMENT '备注',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`pc_name`, `max_address`) USING BTREE,
  UNIQUE INDEX `rd_clients_id_unique`(`id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of rd_clients
-- ----------------------------
INSERT INTO `rd_clients` VALUES (794414902, 'f3f509cf-52bd-4ac9-81f9-f3a8a7e441ec', 'A038', '70:4d:7b:b7:64:5d', NULL, '10.2.0.38', NULL, '2024-08-24 11:54:44', '2024-09-03 15:49:21');

-- ----------------------------
-- Table structure for rd_failed_jobs
-- ----------------------------
DROP TABLE IF EXISTS `rd_failed_jobs`;
CREATE TABLE `rd_failed_jobs`  (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `connection` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of rd_failed_jobs
-- ----------------------------

-- ----------------------------
-- Table structure for rd_migrations
-- ----------------------------
DROP TABLE IF EXISTS `rd_migrations`;
CREATE TABLE `rd_migrations`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 5 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of rd_migrations
-- ----------------------------
INSERT INTO `rd_migrations` VALUES (1, '2014_10_12_000000_create_users_table', 1);
INSERT INTO `rd_migrations` VALUES (2, '2014_10_12_100000_create_password_resets_table', 1);
INSERT INTO `rd_migrations` VALUES (3, '2019_08_19_000000_create_failed_jobs_table', 1);
INSERT INTO `rd_migrations` VALUES (4, '2024_08_23_175910_create_clients_table', 1);

-- ----------------------------
-- Table structure for rd_password_resets
-- ----------------------------
DROP TABLE IF EXISTS `rd_password_resets`;
CREATE TABLE `rd_password_resets`  (
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  INDEX `rd_password_resets_email_index`(`email`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of rd_password_resets
-- ----------------------------

-- ----------------------------
-- Table structure for rd_users
-- ----------------------------
DROP TABLE IF EXISTS `rd_users`;
CREATE TABLE `rd_users`  (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `uid` char(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `nickname` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `username` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(60) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `ip` varchar(45) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `status` tinyint(1) UNSIGNED NOT NULL DEFAULT 1,
  `errors` tinyint(1) UNSIGNED NOT NULL DEFAULT 0,
  `login_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 4 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Records of rd_users
-- ----------------------------
INSERT INTO `rd_users` VALUES (1, 'f3f509cf-52bd-4ac9-81f9-f3a8a7e441ec', '荒野猎人', 'admin', '$2y$10$zrAUWCV92bpYJoVG4THkb.ee7APgCgUW7J9SJt8ZutR4PAMb6u3YO', '10.2.0.38', 1, 0, '2024-09-03 14:12:53', NULL, '2024-08-23 19:02:12', '2024-09-03 14:12:53');
INSERT INTO `rd_users` VALUES (2, 'a94abfb9-9a41-42f2-9462-f3ce7748fb74', 'test001', 'test001', '$2y$10$1V0tm4SyQN6CGozmT.GOheCd61QoNxEGAPCVAObydaufbWOAm9JIC', '10.2.0.38', 1, 0, '2024-09-03 18:25:52', NULL, '2024-09-03 18:06:38', '2024-09-03 18:25:52');
INSERT INTO `rd_users` VALUES (3, '9e1aa325-a607-4843-b5b7-b4fc1f517084', 'test002', 'test002', '$2y$10$pbUt0XZ.u.AYq1BEh31EcOtknYj0R4YiuI5YNkSh10KPiPzG66bs6', NULL, 1, 0, NULL, NULL, '2024-09-03 18:06:55', '2024-09-03 18:06:55');

SET FOREIGN_KEY_CHECKS = 1;
