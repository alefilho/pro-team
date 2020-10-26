<?php
if ($_SERVER['HTTP_HOST'] == 'localhost'):
  define('BASE', 'http://localhost/pro-team');
  define('BASE_MEMBER', 'https://localhost/pro-team-members');
  define('HOST', 'localhost');
  define('USER', 'root');
  define('PASS', '');
  define('DBSA', 'proteam');
else:
  define('BASE', '');
  define('BASE_MEMBER', '');
  define('HOST', '');
  define('USER', '');
  define('PASS', '');
  define('DBSA', '');
endif;

define('MAIL_USER','');
define('MAIL_PASS','');
define('MAIL_PORT','');
define('MAIL_HOST','');
define("MAIL_FROM",'');
?>
