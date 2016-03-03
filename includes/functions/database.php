<?php
/*
  $Id: database.php,v 1.21 2003/06/09 21:21:59 hpdl Exp $

 E-Commerce Solutions

  Copyright (c) 2005 www.flash-template-design.com

  Released under the GNU General Public License
*/

  function tep_db_connect($server = "localhost", $username = 'root', $password = '', $database = 'pffmy_main', $link = 'db_link') {
    global $$link;

    if (USE_PCONNECT == 'true') {
      $$link = mysql_pconnect($server, $username, $password);
    } else {
      $$link = mysql_connect($server, $username, $password);
    }

    if ($$link) mysql_select_db($database);

    if (version_compare(mysql_get_server_info(), '4.1.0', '>=')) {
        mysql_query("SET NAMES utf8");
        }

    return $$link;
  }
  function tep_db_close($link = 'db_link') {
    global $$link;

    return mysql_close($$link);
  }

  function tep_error($query, $errno, $error) { 
    die('<font color="#000000"><b>' . $errno . ' - ' . $error . '<br><br>' . $query . '<br><br><small><font color="#ff0000">[TEP STOP]</font></small><br><br></b></font>');
  }

  function tep_query($query, $link = 'db_link') {
    global $$link;

    if (defined('STORE_TRANSACTIONS') && (STORE_TRANSACTIONS == 'true')) {
      error_log('QUERY ' . $query . "\n", 3, STORE_PAGE_PARSE_TIME_LOG);
    }

    $result = mysql_query($query, $$link) or tep_error($query, mysql_errno(), mysql_error());

    if (defined('STORE_TRANSACTIONS') && (STORE_TRANSACTIONS == 'true')) {
       $result_error = mysql_error();
       error_log('RESULT ' . $result . ' ' . $result_error . "\n", 3, STORE_PAGE_PARSE_TIME_LOG);
    }

    return $result;
  }

  function tep_perform($table, $data, $action = 'insert', $parameters = '', $link = 'db_link') {
    reset($data);
    if ($action == 'insert') {
      $query = 'insert into ' . $table . ' (';
      while (list($columns, ) = each($data)) {
        $query .= $columns . ', ';
      }
      $query = substr($query, 0, -2) . ') values (';
      reset($data);
      while (list(, $value) = each($data)) {
        switch ((string)$value) {
          case 'NOW()':
            $query .= 'NOW(), ';
            break;
          case 'null':
            $query .= 'null, ';
            break;
          default:
            $query .= '\'' . tep_input($value) . '\', ';
            break;
        }
      }
      $query = substr($query, 0, -2) . ')';
    } elseif ($action == 'update') {
      $query = 'update ' . $table . ' set ';
      while (list($columns, $value) = each($data)) {
        switch ((string)$value) {
          case 'NOW()':
            $query .= $columns . ' = NOW(), ';
            break;
          case 'null':
            $query .= $columns .= ' = null, ';
            break;
          default:
            $query .= $columns . ' = \'' . tep_input($value) . '\', ';
            break;
        }
      }
      $query = substr($query, 0, -2) . ' where ' . $parameters;

     // echo $query;
    }

    return tep_query($query, $link);
  }

  function tep_fetch_object($db_query) {
    return mysql_fetch_object($db_query);
  }

  function tep_fetch_array($db_query) {
    return mysql_fetch_array($db_query, MYSQL_ASSOC);
  }

  function tep_result($db_query, $row=0) {
    return mysql_result($db_query, $row);
  }

  function tep_num_rows($db_query) {
    return mysql_num_rows($db_query);
  }

  function tep_data_seek($db_query, $row_number) {
    return mysql_data_seek($db_query, $row_number);
  }

  function tep_insert_id() {
    return mysql_insert_id();
  }

  function tep_free_result($db_query) {
    return mysql_free_result($db_query);
  }

  function tep_fetch_fields($db_query) {
    return mysql_fetch_field($db_query);
  }

  function tep_output($string) {
    //return htmlspecialchars($string);
       return str_replace(array("&amp;"), array("&"), htmlspecialchars($string, ENT_QUOTES, "UTF-8")); //change to this as the chinese charater unable to read
  }

  

   function tep_input($string) {
    return mysql_real_escape_string($string);
  }

  /*function tep_prepare_input($string) {
    if (is_string($string)) {
      return trim(tep_sanitize_string(stripslashes($string)));
    } elseif (is_array($string)) {
      reset($string);
      while (list($key, $value) = each($string)) {
        $string[$key] = tep_prepare_input($value);
      }
      return $string;
    } else {
      return $string;
    }
  }
  */
   function tep_prepare_input($string) {
    if (is_string($string)) {
      return trim(stripslashes($string));
    } elseif (is_array($string)) {
      reset($string);
      while (list($key, $value) = each($string)) {
        $string[$key] = tep_prepare_input($value);
      }
      return $string;
    } else {
      return $string;
    }
  }

  function tep_sql_date($date)
{
	return date('Y-m-d',strtotime($date));
}

    function get_date(){

    }

?>
