<?php
function createTables() {
$tables[] = <<<TABLE
CREATE TABLE `mytrend_data_database` (
  `mysql_id` int(11) DEFAULT NULL,
  `database` varchar(100) DEFAULT NULL,
  `date` date DEFAULT NULL,
  `data_length` bigint(20) DEFAULT NULL,
  `index_length` bigint(20) DEFAULT NULL,
  KEY `database` (`database`,`date`)
) ENGINE=MyISAM
TABLE;

$tables[] = <<<TABLE
CREATE TABLE `mytrend_data_instance` (
  `mysql_id` int(11) DEFAULT NULL,
  `date` date DEFAULT NULL,
  `data_length` bigint(20) DEFAULT NULL,
  `index_length` bigint(20) DEFAULT NULL,
  KEY `date` (`date`)
) ENGINE=MyISAM
TABLE;

$tables[] = <<<TABLE
CREATE TABLE `mytrend_data_table` (
  `mysql_id` int(11) DEFAULT NULL,
  `database` varchar(100) DEFAULT NULL,
  `table` varchar(100) DEFAULT NULL,
  `date` date DEFAULT NULL,
  `rows` bigint(20) DEFAULT NULL,
  `data_length` bigint(20) DEFAULT NULL,
  `index_length` bigint(20) DEFAULT NULL,
  KEY `database` (`database`,`table`,`date`)
) ENGINE=MyISAM
TABLE;

$tables[] = <<<TABLE
CREATE TABLE `mytrend_ignore_database` (
  `mysql_id` int(11) DEFAULT NULL,
  `database` varchar(100) DEFAULT NULL
) ENGINE=MyISAM
TABLE;

$tables[] = <<<TABLE
CREATE TABLE `mytrend_mysql_instances` (
  `mysql_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) DEFAULT NULL,
  `host` varchar(50) DEFAULT NULL,
  `username` varchar(50) DEFAULT NULL,
  `password` varchar(50) DEFAULT NULL,
  `port` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`mysql_id`),
  UNIQUE KEY `host` (`host`,`port`)
) ENGINE=MyISAM
TABLE;

$tables[] = <<<TABLE
CREATE TABLE `mytrend_selected_variables` (
  `name` varchar(50) NOT NULL,
  PRIMARY KEY (`name`)
) ENGINE=MyISAM 
TABLE;

$tables[] = <<<TABLE
INSERT INTO `mytrend_selected_variables`(`name`) VALUES('Com_delete'),('Com_insert'),('Com_select'),('Com_update'),('Connections'),('Questions'),('Slow_queries')
TABLE;

$tables[] = <<<TABLE
CREATE TABLE `mytrend_status_log` (
  `mysql_id` int(11) NOT NULL DEFAULT '0',
  `date` date NOT NULL DEFAULT '0000-00-00',
  `name` varchar(50) NOT NULL DEFAULT '',
  `value` bigint(20) DEFAULT NULL,
  PRIMARY KEY (`mysql_id`,`date`,`name`)
) ENGINE=MyISAM
TABLE;

$tables[] = <<<TABLE
CREATE TABLE `mytrend_status_variables` (
  `mysql_id` int(11) NOT NULL DEFAULT '0',
  `date` date NOT NULL DEFAULT '0000-00-00',
  `name` varchar(50) NOT NULL DEFAULT '',
  `value` bigint(20) DEFAULT NULL,
  PRIMARY KEY (`mysql_id`,`date`,`name`)
) ENGINE=MyISAM
TABLE;

$tables[] = <<<TABLE
CREATE TABLE `mytrend_users` (
  `role` varchar(10) DEFAULT NULL,
  `password` varchar(50) DEFAULT NULL
) ENGINE=MyISAM
TABLE;

$tables[] = <<<TABLE
CREATE TABLE `mytrend_server_configs` (
  `mysql_id` int(11) default NULL,
  `date` date default NULL,
  `config_data` text,
  KEY `mysql_id` (`mysql_id`,`date`)
) ENGINE=MyISAM
TABLE;

$tables[] = <<<TABLE
CREATE TABLE `mytrend_change_log` (
  `mysql_id` int(11) default NULL,
  `date` date default NULL,
  `log` text,
  KEY `mysql_id` (`mysql_id`),
  KEY `date` (`date`)
) ENGINE=MyISAM
TABLE;

return $tables;
}

function createSettingsFile($host,$port,$username,$password,$database,$INSTALLED='') {
$file = <<<FILE
<?php
\$databaseSettings = array (
    'host'      =>"$host",
    'database'  =>"$database",
    'username'  =>"$username",
    'password'  =>"$password",
    'port'      =>"$port"
);

\$INSTALLED = "$INSTALLED";
?>
FILE;

return $file;
}
?>
