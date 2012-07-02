-- phpMyAdmin SQL Dump
-- version 3.4.9
-- http://www.phpmyadmin.net
--
-- 主机: localhost
-- 生成日期: 2012 年 07 月 02 日 04:50
-- 服务器版本: 5.1.50
-- PHP 版本: 5.2.14

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- 数据库: `zxtd_sql`
--

-- --------------------------------------------------------

--
-- 表的结构 `zx_group`
--

CREATE TABLE IF NOT EXISTS `zx_group` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(20) NOT NULL COMMENT '分组名',
  `lader` int(11) NOT NULL COMMENT '组长名',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=7 ;

-- --------------------------------------------------------

--
-- 表的结构 `zx_library_book`
--

CREATE TABLE IF NOT EXISTS `zx_library_book` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL COMMENT '书名',
  `libNum` int(11) NOT NULL COMMENT '校图书馆编号，否则为0',
  `ISBN` varchar(20) NOT NULL COMMENT 'ISBN编号',
  `press` varchar(50) NOT NULL COMMENT '出版社',
  `editor` varchar(100) NOT NULL COMMENT '编辑，多个英文逗号隔开',
  `content` text NOT NULL COMMENT '内容简介',
  `publishTime` varchar(6) NOT NULL COMMENT '出版时间如200806',
  `pricing` float NOT NULL COMMENT '定价',
  `category` int(11) NOT NULL COMMENT '对应的分组ID',
  `stuName` int(11) NOT NULL COMMENT '属于谁，为0为公有',
  `nowBorrow` int(11) NOT NULL COMMENT '当前借阅，为0为在管',
  `beginTime` date NOT NULL COMMENT '图书从图书馆借出时间',
  `borrowTime` date NOT NULL COMMENT '图书借出时间',
  `ebook` int(11) NOT NULL DEFAULT '0' COMMENT '对应的电子书ID',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=19 ;

-- --------------------------------------------------------

--
-- 表的结构 `zx_library_category`
--

CREATE TABLE IF NOT EXISTS `zx_library_category` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `name` varchar(20) NOT NULL COMMENT '分类名',
  `description` varchar(200) NOT NULL,
  `parent` int(11) NOT NULL DEFAULT '0' COMMENT '上级分类',
  `count` int(11) NOT NULL COMMENT '统计',
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=17 ;

-- --------------------------------------------------------

--
-- 表的结构 `zx_library_ebook`
--

CREATE TABLE IF NOT EXISTS `zx_library_ebook` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL COMMENT '名字',
  `type` varchar(4) NOT NULL COMMENT '文件类型',
  `path` varchar(256) NOT NULL COMMENT '文件路径或地址',
  `group` int(11) NOT NULL COMMENT '分组',
  `count` int(11) NOT NULL COMMENT '下载次数',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `zx_library_history`
--

CREATE TABLE IF NOT EXISTS `zx_library_history` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user` int(11) NOT NULL,
  `time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `content` varchar(500) NOT NULL,
  `type` varchar(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=11 ;

-- --------------------------------------------------------

--
-- 表的结构 `zx_options`
--

CREATE TABLE IF NOT EXISTS `zx_options` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL COMMENT '名字',
  `value` varchar(256) NOT NULL COMMENT '值',
  `lading` varchar(3) NOT NULL DEFAULT 'no' COMMENT '自动加载选项到全局变量',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=7 ;

-- --------------------------------------------------------

--
-- 表的结构 `zx_user`
--

CREATE TABLE IF NOT EXISTS `zx_user` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `user` varchar(20) NOT NULL COMMENT '用户名',
  `name` varchar(20) NOT NULL COMMENT '真实姓名',
  `major` varchar(20) NOT NULL COMMENT '专业',
  `class` int(1) NOT NULL COMMENT '班级',
  `grade` int(4) NOT NULL COMMENT '年级',
  `group` int(11) NOT NULL DEFAULT '0' COMMENT '分组',
  `tel` varchar(15) NOT NULL COMMENT '电话',
  `qq` varchar(15) NOT NULL COMMENT 'QQ',
  `email` varchar(50) NOT NULL COMMENT '邮箱',
  `password` varchar(128) NOT NULL COMMENT '密码',
  `cookie` varchar(32) NOT NULL COMMENT '浏览器Cookie',
  `cookieTime` int(11) NOT NULL COMMENT '浏览器cookie生成时间',
  `LoginInfo` text NOT NULL COMMENT '登陆信息',
  `active` int(11) NOT NULL COMMENT '是否激活账号',
  `power` int(1) NOT NULL DEFAULT '0' COMMENT '用户权限，默认为0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`),
  UNIQUE KEY `user` (`user`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=16 ;

--
-- 转存表中的数据 `zx_user`
--

INSERT INTO `zx_user` (`id`, `user`, `name`, `major`, `class`, `grade`, `group`, `tel`, `qq`, `email`, `password`, `cookie`, `cookieTime`, `LoginInfo`, `active`, `power`) VALUES
(1, 'loveyu', 'loveyu', '', 0, 0, 0, '', '', 'admin@loveyu.info', '433536acee41bfdc1d941434d78b0489027a1cc87ef486c8587ceae74077a4ecb7f8a5477f9b092499c93e656db70e1972c118904274cb2b6fefe50cf5e25c8f', '', 0, '', 1, 1);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
