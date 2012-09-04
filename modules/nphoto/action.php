<?php

/**
 * @Project NUKEVIET 3.0
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2010 VINADES.,JSC. All rights reserved
 * @Createdate 2-10-2010 20:59
 */

if( ! defined( 'NV_IS_FILE_MODULES' ) ) die( 'Stop!!!' );

$sql_drop_module = array();

$result = $db->sql_query( "SHOW TABLE STATUS LIKE '" . $db_config['prefix'] . "\_" . $lang . "\_" . $module_data . "\_%'" );
$num_table = intval( $db->sql_numrows( $result ) );
if( $num_table > 0 )
{
	$result = $db->sql_query( "SELECT `catid` FROM `" . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_category` ORDER BY `order` ASC" );
	while( list( $catid_i ) = $db->sql_fetchrow( $result ) )
	{
		$sql_drop_module[] = "DROP TABLE IF EXISTS `" . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_" . $catid_i . "`";
	}
	$db->sql_freeresult();
}

$sql_drop_module[] = "DROP TABLE IF EXISTS `" . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "`;";
$sql_drop_module[] = "DROP TABLE IF EXISTS `" . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_photos`;";
$sql_drop_module[] = "DROP TABLE IF EXISTS `" . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_0`;";
$sql_drop_module[] = "DROP TABLE IF EXISTS `" . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_album`;";
$sql_drop_module[] = "DROP TABLE IF EXISTS `" . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_category`;";
$sql_drop_module[] = "DROP TABLE IF EXISTS `" . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_setting`;";
$sql_drop_module[] = "DROP TABLE IF EXISTS `" . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_admins`;";
$sql_drop_module[] = "DROP TABLE IF EXISTS `" . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_comment_photos`";
$sql_drop_module[] = "DROP TABLE IF EXISTS `" . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_comment_album`";

$sql_create_module = $sql_drop_module;

$sql_create_module[] = "CREATE TABLE `" . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_photos` (
  `pid` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `filename` varchar(255) NOT NULL,
  `filetype` varchar(255) NOT NULL DEFAULT '',
  `filepath` varchar(255) NOT NULL,
  `thumbpath` varchar(255),
  `catid` mediumint(8),
  `listcatid` varchar(255),
  `albumid` mediumint(8),
  `userid` mediumint(8) NOT NULL DEFAULT '1',
  `title` varchar(255) NOT NULL,
  `alias` varchar(255) NOT NULL,
  `alt` varchar(255),
  `meta_title` varchar(255),
  `meta_keywords` mediumtext,
  `meta_description` varchar(255),
  `tags_cloud` mediumtext,
  `bodytext` mediumtext,
  `weight` smallint(4) unsigned NOT NULL DEFAULT '0',
  `viewed` mediumint(8) NOT NULL DEFAULT '0',
  `cmcount` mediumint(8) NOT NULL DEFAULT '0',
  `like` mediumint(8) NOT NULL DEFAULT '0',
  `dislike` mediumint(8) NOT NULL DEFAULT '0',
  `user_like` mediumtext,
  `user_dislike` mediumtext,
  `img_size` varchar(255),
  `allowed_comm` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `allowed_rating` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `add_time` int(11) NOT NULL DEFAULT '0',
  `edit_time` int(11) NOT NULL DEFAULT '0',
  `who_view` int(11) NOT NULL DEFAULT '0',
  `groups_view` varchar(255),
  `status` tinyint(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`pid`),
  UNIQUE KEY `alias` (`alias`)
) ENGINE=MyISAM";

$sql_create_module[] = "CREATE TABLE `" . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_0` (
  `pid` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `filename` varchar(255) NOT NULL,
  `filetype` varchar(255) NOT NULL DEFAULT '',
  `filepath` varchar(255) NOT NULL,
  `thumbpath` varchar(255),
  `catid` mediumint(8),
  `listcatid` varchar(255),
  `albumid` mediumint(8),
  `userid` mediumint(8) NOT NULL DEFAULT '1',
  `title` varchar(255) NOT NULL,
  `alias` varchar(255) NOT NULL,
  `alt` varchar(255),
  `meta_title` varchar(255),
  `meta_keywords` mediumtext,
  `meta_description` varchar(255),
  `tags_cloud` mediumtext,
  `bodytext` mediumtext,
  `weight` smallint(4) unsigned NOT NULL DEFAULT '0',
  `viewed` mediumint(8) NOT NULL DEFAULT '0',
  `cmcount` mediumint(8) NOT NULL DEFAULT '0',
  `like` mediumint(8) NOT NULL DEFAULT '0',
  `dislike` mediumint(8) NOT NULL DEFAULT '0',
  `user_like` mediumtext,
  `user_dislike` mediumtext,
  `img_size` varchar(255),
  `allowed_comm` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `allowed_rating` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `add_time` int(11) NOT NULL DEFAULT '0',
  `edit_time` int(11) NOT NULL DEFAULT '0',
  `who_view` int(11) NOT NULL DEFAULT '0',
  `groups_view` varchar(255),
  `status` tinyint(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`pid`),
  UNIQUE KEY `alias` (`alias`)
) ENGINE=MyISAM";

$sql_create_module[] = "CREATE TABLE `" . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_album` (
  `albid` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `adminids` varchar(255) NOT NULL,
  `image` varchar(255),
  `thumbpath` varchar(255),
  `userid` mediumint(8) NOT NULL DEFAULT 1,
  `imgfolder` varchar(255),
  `title` varchar(255) NOT NULL,
  `alias` varchar(255) NOT NULL,
  `meta_title` varchar(255),
  `meta_keywords` mediumtext,
  `meta_description` varchar(255),
  `tags_cloud` mediumtext,
  `bodytext` mediumtext,
  `weight` smallint(4) unsigned NOT NULL DEFAULT '0',
  `viewed` mediumint(8) NOT NULL DEFAULT '0',
  `cmcount` mediumint(8) NOT NULL DEFAULT '0',
  `like` mediumint(8) NOT NULL DEFAULT '0',
  `dislike` mediumint(8) NOT NULL DEFAULT '0',
  `user_like` mediumtext NOT NULL,
  `user_dislike` mediumtext NOT NULL,
  `allowed_comm` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `allowed_rating` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `add_time` int(11) NOT NULL DEFAULT '0',
  `edit_time` int(11) NOT NULL DEFAULT '0',
  `who_view` int(11) NOT NULL DEFAULT '0',
  `groups_view` varchar(255),
  `showtype` int(11) NOT NULL DEFAULT '0',
  `inhome` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`albid`),
  UNIQUE KEY `alias` (`alias`)
) ENGINE=MyISAM";

$sql_create_module[] = "CREATE TABLE `" . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_category` (
  `catid` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `parentid` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `adminids` varchar(255) NOT NULL DEFAULT '',
  `subcatids` varchar(255),
  `numsubcat` int(11) NOT NULL DEFAULT '0',
  `weight` smallint(4) unsigned NOT NULL DEFAULT '0',
  `order` mediumint(8) NOT NULL DEFAULT '0',
  `lev` smallint(4) NOT NULL DEFAULT '0',
  `imgfolder` varchar(255),
  `image` varchar(255),
  `thumbpath` varchar(255),
  `title` varchar(255) NOT NULL DEFAULT '',
  `alias` varchar(255) NOT NULL DEFAULT '',
  `meta_title` varchar(255),
  `meta_keywords` mediumtext,
  `meta_description` varchar(255),
  `tags_cloud` mediumtext,
  `bodytext` mediumtext,
  `viewed` mediumint(8) NOT NULL DEFAULT '0',
  `like` mediumint(8) NOT NULL DEFAULT '0',
  `dislike` mediumint(8) NOT NULL DEFAULT '0',
  `allowed_comm` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `allowed_rating` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `add_time` int(11) NOT NULL DEFAULT '0',
  `edit_time` int(11) NOT NULL DEFAULT '0',
  `who_view` int(11) NOT NULL DEFAULT '0',
  `groups_view` varchar(255),
  `showtype` int(11) NOT NULL DEFAULT '0',
  `inhome` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`catid`),
  UNIQUE KEY `alias` (`alias`)
) ENGINE=MyISAM";

$sql_create_module[] = "CREATE TABLE `" . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_comment_photos` (
  `cid` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `level` varchar(255) NOT NULL,
  `cmcount` int(11) unsigned NOT NULL DEFAULT '0',
  `id` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `content` mediumtext NOT NULL,
  `like` mediumint(8) NOT NULL DEFAULT '0',
  `dislike` mediumint(8) NOT NULL DEFAULT '0',
  `user_like` mediumtext NOT NULL,
  `user_dislike` mediumtext NOT NULL,
  `post_time` int(11) unsigned NOT NULL DEFAULT '0',
  `userid` int(11) NOT NULL DEFAULT '0',  
  `post_name` varchar(100) NOT NULL,
  `post_email` varchar(100) NOT NULL,
  `post_ip` varchar(15) NOT NULL,
  `status` tinyint(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`cid`),
  KEY `post_time` (`post_time`),
  KEY `id` (`id`)
) ENGINE=MyISAM";

$sql_create_module[] = "CREATE TABLE `" . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_comment_album` (
  `cid` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `level` varchar(255) NOT NULL,
  `cmcount` int(11) unsigned NOT NULL DEFAULT '0',
  `id` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `content` mediumtext NOT NULL,
  `like` mediumint(8) NOT NULL DEFAULT '0',
  `dislike` mediumint(8) NOT NULL DEFAULT '0',
  `user_like` mediumtext NOT NULL,
  `user_dislike` mediumtext NOT NULL,
  `post_time` int(11) unsigned NOT NULL DEFAULT '0',
  `userid` int(11) NOT NULL DEFAULT '0',  
  `post_name` varchar(100) NOT NULL,
  `post_email` varchar(100) NOT NULL,
  `post_ip` varchar(15) NOT NULL,
  `status` tinyint(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`cid`),
  KEY `post_time` (`post_time`),
  KEY `id` (`id`)
) ENGINE=MyISAM";

$sql_create_module[] = "CREATE TABLE `" . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_admins` (
  `userid` int(11) NOT NULL default '0',
  `listcatid` varchar(255) NOT NULL DEFAULT '0',
  `listalbid` varchar(255) NOT NULL DEFAULT '0',
  `admin` tinyint(4) NOT NULL default '0',
  `add_cat` tinyint(4) NOT NULL default '0',
  `add_album` tinyint(4) NOT NULL default '0',
  `del_cat` tinyint(4) NOT NULL default '0',
  `del_album` tinyint(4) NOT NULL default '0',
  `addphoto` tinyint(4) NOT NULL default '0',
  UNIQUE KEY `userid` (`userid`)
) ENGINE=MyISAM";
	
$sql_create_module[] = "CREATE TABLE `" . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_setting` (
  `name` varchar(30) NOT NULL,
  `value` varchar(255) NOT NULL,
  UNIQUE KEY `config_name` (`name`)
)ENGINE=MyISAM";

$sql_create_module[] = "INSERT INTO `" . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_setting` VALUES
('maxfilenums', '10'),
('maxfilesize', '2097152'),
('upload_filetype', 'jpg,png,gif,bmp'),
('comment_album', '1'),
('comment_photos', '1'),
('hide_real_imgurl', '0'),
('upload_img_maxwidht', '1500'),
('upload_img_maxheight', '1500'),
('thumb_maxwidht', '80'),
('thumb_maxheight', '80'),
('member_post', '0'),
('home_cat_numphotos', '10'),
('view_cat_numphotos', '20'),
('view_album_numphotos', '20'),
('view_numalbums', '20'),
('home_category', ''),
('home_album', '')";
	
$sql_create_module[] = "INSERT INTO `" . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_admins` (`userid`, `listcatid`, `listalbid`, `admin`, `add_cat`, `add_album`, `del_cat`, `del_album`, `addphoto`) VALUES (1, '', '', 1, 1, 1, 1, 1, 1)";

?>