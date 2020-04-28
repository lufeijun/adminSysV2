-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/
--
-- 主机： 127.0.0.1:3306
-- 生成日期： 2020-04-28 15:54:18
-- 服务器版本： 8.0.18
-- PHP 版本： 7.3.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- 数据库： `laravel`
--

-- --------------------------------------------------------

--
-- 表的结构 `abilities`
--

CREATE TABLE `abilities` (
  `id` int(11) UNSIGNED NOT NULL,
  `role_id` int(11) DEFAULT NULL,
  `ability` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `type` tinyint(1) DEFAULT NULL COMMENT '权限种类，1、菜单权限。2、功能权限',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- 转存表中的数据 `abilities`
--

INSERT INTO `abilities` (`id`, `role_id`, `ability`, `type`, `created_at`, `updated_at`) VALUES
(10, 4, 'one级,third级,功能一,', 2, '2020-04-22 01:17:16', '2020-04-22 01:17:16'),
(12, 4, '权限,角色管理,角色信息,', 1, '2020-04-22 01:19:06', '2020-04-22 01:19:06'),
(14, 4, '权限,角色管理,角色列表,', 1, '2020-04-22 01:39:44', '2020-04-22 01:39:44'),
(17, 4, 'one级,two级,功能一,', 2, '2020-04-22 01:45:35', '2020-04-22 01:45:35'),
(18, 1, '一级,二级,功能一,', 2, '2020-04-22 10:19:54', '2020-04-22 10:19:54'),
(19, 1, '一级,二级,功能二,', 2, '2020-04-22 10:19:54', '2020-04-22 10:19:54'),
(20, 1, '一级,二级,功能三,', 2, '2020-04-22 10:19:54', '2020-04-22 10:19:54'),
(21, 1, 'one级,two级,功能一,', 2, '2020-04-22 10:19:54', '2020-04-22 10:19:54'),
(22, 1, 'one级,two级,功能二,', 2, '2020-04-22 10:19:54', '2020-04-22 10:19:54'),
(23, 1, 'one级,two级,功能三,', 2, '2020-04-22 10:19:54', '2020-04-22 10:19:54'),
(24, 1, 'one级,two级,功能四,', 2, '2020-04-22 10:19:54', '2020-04-22 10:19:54'),
(25, 1, 'one级,third级,功能一,', 2, '2020-04-22 10:19:54', '2020-04-22 10:19:54'),
(26, 1, 'one级,third级,功能二,', 2, '2020-04-22 10:19:54', '2020-04-22 10:19:54'),
(27, 1, 'one级,third级,功能三,', 2, '2020-04-22 10:19:54', '2020-04-22 10:19:54'),
(28, 1, 'one级,third级,功能四,', 2, '2020-04-22 10:19:54', '2020-04-22 10:19:54'),
(29, 1, '权限,成员管理,成员列表,', 1, '2020-04-22 10:19:54', '2020-04-22 10:19:54'),
(30, 1, '权限,成员管理,成员信息,', 1, '2020-04-22 10:19:54', '2020-04-22 10:19:54'),
(31, 1, '权限,角色管理,角色列表,', 1, '2020-04-22 10:19:54', '2020-04-22 10:19:54'),
(32, 1, '权限,角色管理,角色信息,', 1, '2020-04-22 10:19:54', '2020-04-22 10:19:54'),
(33, 1, '权限,角色管理,成员信息,', 1, '2020-04-22 10:19:54', '2020-04-22 10:19:54');

-- --------------------------------------------------------

--
-- 表的结构 `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- 转存表中的数据 `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2014_10_12_000000_create_users_table', 1),
(2, '2014_10_12_100000_create_password_resets_table', 1);

-- --------------------------------------------------------

--
-- 表的结构 `password_resets`
--

CREATE TABLE `password_resets` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- 表的结构 `roles`
--

CREATE TABLE `roles` (
  `id` int(11) UNSIGNED NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci ROW_FORMAT=COMPACT;

--
-- 转存表中的数据 `roles`
--

INSERT INTO `roles` (`id`, `name`, `created_at`, `updated_at`) VALUES
(1, '管理员', NULL, NULL),
(2, '采购员', NULL, NULL),
(3, '市场部', NULL, '2020-04-21 20:10:23'),
(4, '行政中心', '2020-04-21 20:10:59', '2020-04-21 20:10:59');

-- --------------------------------------------------------

--
-- 表的结构 `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `enable` tinyint(4) NOT NULL DEFAULT '0',
  `role` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `wechat` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `image` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- 转存表中的数据 `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `remember_token`, `enable`, `role`, `phone`, `address`, `wechat`, `image`, `created_at`, `updated_at`) VALUES
(1, 'admin', 'admin@ooxx.com', NULL, '$2y$10$29gdTw5bkCrk/oU/taCCHOtm2uSz0duomEdxrgtVQdJ3YrHR0kNTK', NULL, 1, ',1,', '18524125541', '地址', '无', '', '2019-04-15 02:20:22', '2020-04-28 04:10:52'),
(2, '名字', 'aaa@ooxx.com', NULL, '$2y$10$29gdTw5bkCrk/oU/taCCHOtm2uSz0duomEdxrgtVQdJ3YrHR0kNTK', NULL, 1, ',1,2,3,', NULL, '无', '12', '', '2019-04-15 02:37:00', '2020-04-27 07:47:00'),
(3, '测试项目', 'bbb@ooxx.com', NULL, '$2y$10$n4eJHYCppsRuPFekSRdLl.0S8DxKdVtJpdn7XQe99etwBGb67Aqmm', NULL, 1, ',3,4,', '18522223335', '测试地址', '', '', '2020-04-21 01:22:13', '2020-04-22 10:03:27'),
(4, '测试', 'test@ooxx.com', NULL, '$2y$10$wtwMa.u0.u4K1XPKdfZ4TuTyylUKSeY67hNRuKY7ExPPfoRjOZLSC', NULL, 1, ',1,2,', '18452154412', '撒大声地', NULL, '', '2020-04-27 08:40:24', '2020-04-27 09:01:44');

--
-- 转储表的索引
--

--
-- 表的索引 `abilities`
--
ALTER TABLE `abilities`
  ADD PRIMARY KEY (`id`);

--
-- 表的索引 `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- 表的索引 `password_resets`
--
ALTER TABLE `password_resets`
  ADD KEY `password_resets_email_index` (`email`);

--
-- 表的索引 `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`);

--
-- 表的索引 `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- 在导出的表使用AUTO_INCREMENT
--

--
-- 使用表AUTO_INCREMENT `abilities`
--
ALTER TABLE `abilities`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- 使用表AUTO_INCREMENT `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- 使用表AUTO_INCREMENT `roles`
--
ALTER TABLE `roles`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- 使用表AUTO_INCREMENT `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
