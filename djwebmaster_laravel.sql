SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Records of ws_articles
-- ----------------------------
BEGIN;
INSERT INTO `ws_articles` VALUES (1, 1, '标题标题标题标题标题标题', '内容内容内容内容', 'https://randomuser.me/api/portraits/men/85.jpg', '[\"\\u6587\\u7ae02\",\"\\u6d4b\\u8bd5\",\"\\u5566\\u5566\"]', 0, 0, 0, '[]', '127.0.0.1', NULL, '2019-03-05 11:43:01', '2019-04-10 15:52:09');
INSERT INTO `ws_articles` VALUES (2, 1, '新的文章', '试试看新文章\n---\n\n如果说nbalalala\n\n666', '', '[\"\\u6587\\u7ae0\",\"\\u6742\\u8c08\",\"\\u751f\\u6d3b\",\"\\u6807\\u7b7e\"]', 0, 0, 0, '[]', '127.0.0.1', '2019-04-10 15:23:53', '2019-04-10 15:18:10', '2019-04-10 15:23:53');
INSERT INTO `ws_articles` VALUES (3, 1, '吧啦啦啦啦22222', '啦啦啦啦\n-----\n\n吧啦啦啦\n\n1. 列表1\n2. 二\n3. 三四五\n\n如果修改成功是不是就会自动关闭模态窗呢', '', '[\"\\u5566\\u5566\\u5566\",\"\\u751f\\u6d3b\",\"\\u5386\\u53f2\"]', 0, 0, 0, '[]', '127.0.0.1', NULL, '2019-04-10 15:25:13', '2019-04-10 15:41:27');
INSERT INTO `ws_articles` VALUES (4, 1, 'asdasda', 'sdasdasd\n---\nasdasdasd', '', '[\"asd\",\"asdad\"]', 0, 0, 0, '[]', '127.0.0.1', NULL, '2019-04-10 16:03:47', '2019-04-10 16:03:47');
COMMIT;

-- ----------------------------
-- Records of ws_menus
-- ----------------------------
BEGIN;
INSERT INTO `ws_menus` VALUES (1, 'Admin', 'main', '控制台概览', '/', NULL, 0, 0, '', 'dashboard', 1, '2019-03-04 20:28:24', '2019-03-04 23:53:31');
INSERT INTO `ws_menus` VALUES (2, 'Admin', 'main', '部落格', '/blog/article', NULL, 0, 0, '', 'description', 1, '2019-03-04 20:29:18', '2019-03-04 20:29:18');
INSERT INTO `ws_menus` VALUES (3, 'Admin', 'main', '浏览', '/blog/article/', 2, 0, 0, '', 'list', 1, '2019-03-04 20:29:41', '2019-03-04 20:29:41');
INSERT INTO `ws_menus` VALUES (4, 'Admin', 'main', '查看', '/blog/article/show', 3, 0, 0, '', 'visibility', 1, '2019-03-04 20:30:13', '2019-03-04 20:30:13');
INSERT INTO `ws_menus` VALUES (5, 'Admin', 'main', '删除', '/blog/article/delete', 3, 0, 0, '', '', 1, '2019-03-04 20:30:17', '2019-03-04 20:30:17');
INSERT INTO `ws_menus` VALUES (6, 'Admin', 'main', '保存', '/blog/article/update', 3, 0, 0, '', '', 1, '2019-03-04 20:30:21', '2019-03-04 20:30:21');
INSERT INTO `ws_menus` VALUES (7, 'Admin', 'main', '用户管理', '/user/user', NULL, 0, 0, '', 'person', 1, '2019-04-10 11:25:17', '2019-04-10 11:26:14');
INSERT INTO `ws_menus` VALUES (8, 'Admin', 'main', '浏览', '/user/user/', 7, 0, 0, '', 'list', 1, '2019-04-10 11:25:56', '2019-04-10 11:25:56');
INSERT INTO `ws_menus` VALUES (9, 'Admin', 'main', '个人资料', '/user/user/my', 7, 0, 0, '', 'description', 1, '2019-04-10 20:17:38', '2019-04-10 20:17:38');
INSERT INTO `ws_menus` VALUES (10, 'Admin', 'main', '操作记录', '/user/log/', NULL, 0, 0, '', 'mouse', 1, '2019-04-10 20:40:07', '2019-04-10 20:40:07');
COMMIT;

-- ----------------------------
-- Records of ws_model_has_roles
-- ----------------------------
BEGIN;
INSERT INTO `ws_model_has_roles` VALUES (1, 'User', 1);
COMMIT;

-- ----------------------------
-- Records of ws_permissions
-- ----------------------------
BEGIN;
INSERT INTO `ws_permissions` VALUES (1, NULL, '/user/userprofile/index', 'web', '2019-03-04 20:25:02', '2019-03-04 20:25:02', '用户管理首页', 'Admin', NULL);
INSERT INTO `ws_permissions` VALUES (2, 3, '/article/article/index', 'web', '2019-03-04 20:33:20', '2019-03-04 20:34:55', '文章首页', 'Admin', NULL);
COMMIT;

-- ----------------------------
-- Records of ws_role_has_permissions
-- ----------------------------
BEGIN;
INSERT INTO `ws_role_has_permissions` VALUES (1, 1);
INSERT INTO `ws_role_has_permissions` VALUES (2, 1);
COMMIT;

-- ----------------------------
-- Records of ws_roles
-- ----------------------------
BEGIN;
INSERT INTO `ws_roles` VALUES (1, '管理员', 'admin', 'Admin', 'web', '2019-03-04 20:25:20', '2019-03-04 20:25:20', '', 1, NULL);
COMMIT;

-- ----------------------------
-- Records of ws_user_profiles
-- ----------------------------
BEGIN;
INSERT INTO `ws_user_profiles` VALUES (1, '151003500201', '电子信息学院', '计算机1512', '孔元元', '2019-03-04 20:22:33', '2019-04-07 19:17:51', NULL);
COMMIT;

-- ----------------------------
-- Records of ws_users
-- ----------------------------
BEGIN;
INSERT INTO `ws_users` VALUES (1, '', '18516198291', '$2y$10$SkI.gIZ6tXCqXBCJ/7O9/OT/dps4WioQ7trx8WPzEyXHC4o.3dsPG', 'system@kyy1996.com', 0, 1, NULL, NULL, 'S7cVH6TXSSGOezv8hTuz0Emv5L1IBWe2leu9KF73Tn9nI4V9vP24LzZJqqeh', '127.0.0.1', '127.0.0.1', NULL, NULL, '2019-03-04 20:22:33', '2019-04-07 21:27:05', NULL);
COMMIT;

SET FOREIGN_KEY_CHECKS = 1;
