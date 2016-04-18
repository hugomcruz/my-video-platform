# ************************************************************
# Sequel Pro SQL dump
# Version 4096
#
# SQL DB DUMP with sample data
#
# 18.April.2016
# ************************************************************





# Dump of table category
# ------------------------------------------------------------

CREATE TABLE `category` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `sequence` int(11) NOT NULL DEFAULT '1',
  `name` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `language` varchar(3) COLLATE utf8_unicode_ci NOT NULL,
  `thumb_img` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `active` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

LOCK TABLES `category` WRITE;
/*!40000 ALTER TABLE `category` DISABLE KEYS */;

INSERT INTO `category` (`id`, `sequence`, `name`, `language`, `thumb_img`, `active`)
VALUES
	(1,1,'Category 1','','http://cdn.domain.com/images/cat1.png',1),
	(2,2,'Category 2','','http://cdn.domain.com/images/cat2.png',1);


/*!40000 ALTER TABLE `category` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table config
# ------------------------------------------------------------

CREATE TABLE `config` (
  `name` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `value` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



# Dump of table country_dc
# ------------------------------------------------------------

CREATE TABLE `country_dc` (
  `country_code` varchar(2) COLLATE utf8_unicode_ci NOT NULL,
  `dc_id` varchar(3) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`country_code`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

LOCK TABLES `country_dc` WRITE;
/*!40000 ALTER TABLE `country_dc` DISABLE KEYS */;

INSERT INTO `country_dc` (`country_code`, `dc_id`)
VALUES
	('SG','VN1'),
	('PT','US1'),
	('VN','VN1');

/*!40000 ALTER TABLE `country_dc` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table datacenter
# ------------------------------------------------------------

CREATE TABLE `datacenter` (
  `id` varchar(3) COLLATE utf8_unicode_ci NOT NULL,
  `name` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `base_url` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

LOCK TABLES `datacenter` WRITE;
/*!40000 ALTER TABLE `datacenter` DISABLE KEYS */;

INSERT INTO `datacenter` (`id`, `name`, `base_url`)
VALUES
	('VN1','Vietnam 1','http://vn.domain.com/bzvideos/'),
	('US1','US Server','http://pt.domain.com/bzvideos/'),
	('VN2','Vietnam 2','http://vn2.domain.com/bzvideos/');

/*!40000 ALTER TABLE `datacenter` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table device
# ------------------------------------------------------------

CREATE TABLE `device` (
  `type` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `base_path` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`type`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



# Dump of table feed
# ------------------------------------------------------------

CREATE TABLE `feed` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `subcat_id` int(11) NOT NULL,
  `title` varchar(100) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `stream_format` varchar(3) COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT 'mp4, hls',
  `stream_quality` varchar(2) COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT 'SD,HD',
  `stream_bitrate` int(11) NOT NULL,
  `synopsys` longtext COLLATE utf8_unicode_ci NOT NULL,
  `runtime` int(11) NOT NULL,
  `sequence` int(11) NOT NULL,
  `active` int(11) NOT NULL DEFAULT '0',
  `file_path` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `file_name` varchar(100) COLLATE utf8_unicode_ci NOT NULL COMMENT 'No slashes.',
  `thumb_img` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

LOCK TABLES `feed` WRITE;
/*!40000 ALTER TABLE `feed` DISABLE KEYS */;

INSERT INTO `feed` (`id`, `subcat_id`, `title`, `stream_format`, `stream_quality`, `stream_bitrate`, `synopsys`, `runtime`, `sequence`, `active`, `file_path`, `file_name`, `thumb_img`)
VALUES
	(1,1,'Video 1','mp4','HD',2500,'',0,4,1,'/video1','file1.mp4','http://cdn.domain.com/images/bzvideo/video.png'),
	(2,2,'Video 2','mp4','HD',2000,'',0,1,1,'/video2','file2.mp4','http://cdn.domain.com/images/bzvideo/video.png');

/*!40000 ALTER TABLE `feed` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table permissions
# ------------------------------------------------------------

CREATE TABLE `permissions` (
  `userid` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `cat_id` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `sequence_number` int(4) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



# Dump of table roku_reg
# ------------------------------------------------------------

CREATE TABLE `roku_reg` (
  `device_id` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `gen_code` varchar(5) COLLATE utf8_unicode_ci NOT NULL,
  `token` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created` datetime NOT NULL,
  `updated` datetime DEFAULT NULL,
  `active` tinyint(4) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



# Dump of table sub_category
# ------------------------------------------------------------

CREATE TABLE `sub_category` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cat_id` int(11) NOT NULL,
  `sequence` int(11) NOT NULL DEFAULT '1',
  `name` varchar(50) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `active` tinyint(1) NOT NULL DEFAULT '0',
  `notes` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

LOCK TABLES `sub_category` WRITE;
/*!40000 ALTER TABLE `sub_category` DISABLE KEYS */;

INSERT INTO `sub_category` (`id`, `cat_id`, `sequence`, `name`, `active`, `notes`)
VALUES
	(1,1,1,'Subcat 1',1,'Note 1'),
	(2,2,2,'Subcat 2',1,'Note 2');


/*!40000 ALTER TABLE `sub_category` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table token
# ------------------------------------------------------------

CREATE TABLE `token` (
  `token` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `userid` int(11) NOT NULL,
  `created` datetime NOT NULL,
  `validity` datetime NOT NULL,
  PRIMARY KEY (`token`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


# Dump of table user
# ------------------------------------------------------------

CREATE TABLE `user` (
  `userid` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `active` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`userid`),
  UNIQUE KEY `userid` (`userid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

LOCK TABLES `user` WRITE;
/*!40000 ALTER TABLE `user` DISABLE KEYS */;

INSERT INTO `user` (`userid`, `username`, `password`, `email`, `active`)
VALUES
	(1,'user1','user123','user1@gmail.com',1),
	(2,'user2','user123','user2@gmail.com',1);


/*!40000 ALTER TABLE `user` ENABLE KEYS */;
UNLOCK TABLES;

