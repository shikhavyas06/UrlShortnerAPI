<?php /* conf.php ( config file ) */

// MySQL connection info
define('MYSQL_USER', 'root');
define('MYSQL_PASS', '');
define('MYSQL_DB', 'url');
define('MYSQL_HOST', 'localhost');

// MySQL tables
define('URL_TABLE', 'short_urls');

// allow urls that begin with these strings
$allowed_protocols = array('http:', 'https:');

?>
