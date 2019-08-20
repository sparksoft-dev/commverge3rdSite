<?php
/**************************************************
 * session wsdl result codes
 **************************************************/
define("S_SESSION_FOUND", 200);
define("S_CONFIG_FILE_READ_ERROR", 600);
define("S_PRIMARY_SESSION_DB_CONNECT_ERROR", 400);
define("S_SECONDARY_SESSON_DB_CONNECT_ERROR", 400);
define("S_MYSQL_DB_CONNECT_ERROR", 400);
define("S_SESSION_DB_QUERY_ERROR", 400);
define("S_NO_SESSIONS_FOUND", 300);
define("S_DETOUR_CLIENT_ERROR", 400);
define("S_SESSION_DELETED", 200);
define("S_DELETE_SESSION_CLIENT_ERROR", 400);
define("S_REQUEST_DISCONNECT_ERROR", 400);
define("S_SESSION_NOT_DELETED_ERROR", 400);
define("S_PRIMARY_TBLMCONC_DB_CONNECT_ERROR", 400);
define("S_PRIMARY_TBLMCORE_DB_CONNECT_ERROR", 400);
define("S_TBLMCONC_DB_QUERY_ERROR", 400);
define("S_TBLMCORE_DB_QUERY_ERROR", 400);
define("S_SECONDARY_TBLMCONC_DB_CONNECT_ERROR", 400);
define("S_SECONDARY_TBLMCORE_DB_CONNECT_ERROR", 400);