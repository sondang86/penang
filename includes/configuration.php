<?php
//VARIABLES
define('SITENAME', 'Penang Future Foundation');
define('DIR_WS_IMAGES', 'includes/images/');
define('DIR_WS_INCLUDES', 'includes/');
define('DIR_WS_FUNCTIONS', DIR_WS_INCLUDES . 'functions/');
define('DIR_WS_MODULES', DIR_WS_INCLUDES . 'modules/');
define('DIR_WS_PAGES',DIR_WS_INCLUDES . 'pages/');

//GENERAL
define('CURRENCY','RM');
define('FORMAT_DATE','d/m/Y');
define('FORMAT_DATETIME','d/m/Y H:i');
define('DEC_PLACES',2);
define('ROWSPERPAGE',30);

// define MySQL database connection
define('DB_SERVER', 'localhost'); // eg, localhost - should not be empty for productive servers
define('USE_PCONNECT', 'false'); // use persistent connections?
define('STORE_SESSIONS', 'mysql');// leave empty '' for default handler or set to 'mysql'

if($_SERVER['HTTP_HOST']!="localhost"){
    define('DB_SERVER_USERNAME', 'pffmy_main');
    define('DB_SERVER_PASSWORD', 'YNGd4jKl');
    define('DB_DATABASE', 'pffmy_main');
    define('HTTP_SERVER', 'http://form.penangfuturefoundation.my/');
    define('ROOT_PATH', $_SERVER['DOCUMENT_ROOT']);
} else{
    define('DB_SERVER_USERNAME', 'root');
    define('DB_SERVER_PASSWORD', '');
    define('DB_DATABASE', 'pff_main');
    define('HTTP_SERVER', 'http://localhost/penangfuture/');
    define('HTTP_DOMAIN', 'http://localhost/penangfuture/');
    define('ROOT_PATH', $_SERVER['DOCUMENT_ROOT']."/seekerpages");
}

    error_reporting(E_ERROR);
    session_start();
    
    define('MEMBER', 'member');
    define('PERSONAL', 'personal_details');
    define('ACADEMIC', 'academic');
    define('COURSE', 'course_details');
    define('FINANCIAL', 'financial_details');
    define('AUDITS', 'system_audits');
    define('TEMP', 'temp');
    
    tep_db_connect();
    
    mysql_query("SET time_zone = '+8:00'");
    date_default_timezone_set("Asia/Kuala_Lumpur");

?>