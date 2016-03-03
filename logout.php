<?php @session_start(); ?>
<?php require_once('Connections/MAMP.php'); ?>
<?php
// *** Logout the current user.
$logoutGoTo = "index.php";
if (!isset($_SESSION)) {
  session_start();
}
$_SESSION['MM_Username'] = NULL;
$_SESSION['MM_UserGroup'] = NULL;
unset($_SESSION['MM_Username']);
unset($_SESSION['MM_UserGroup']);
if ($logoutGoTo != "") {header("Location: $logoutGoTo");
exit;
}
?>
<?php
if (!function_exists("GetSQLValueString")) {
function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") 
{
  if (PHP_VERSION < 6) {
    $theValue = get_magic_quotes_gpc() ? stripslashes($theValue) : $theValue;
  }

  $theValue = function_exists("mysql_real_escape_string") ? mysql_real_escape_string($theValue) : mysql_escape_string($theValue);

  switch ($theType) {
    case "text":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;    
    case "long":
    case "int":
      $theValue = ($theValue != "") ? intval($theValue) : "NULL";
      break;
    case "double":
      $theValue = ($theValue != "") ? doubleval($theValue) : "NULL";
      break;
    case "date":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;
    case "defined":
      $theValue = ($theValue != "") ? $theDefinedValue : $theNotDefinedValue;
      break;
  }
  return $theValue;
}
}

$colname_logout = "-1";
if (isset($_GET['MM_Username'])) {
  $colname_logout = $_GET['MM_Username'];
}
mysql_select_db($database_MAMP, $MAMP);
$query_logout = sprintf("SELECT * FROM Users WHERE userName = %s", GetSQLValueString($colname_logout, "text"));
$logout = mysql_query($query_logout, $MAMP) or die(mysql_error());
$row_logout = mysql_fetch_assoc($logout);
$totalRows_logout = mysql_num_rows($logout);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link href="CSS/layout.css" rel="stylesheet" type="text/css" />
<link href="CSS/menu.css" rel="stylesheet" type="text/css" />
<style type="text/css">
h1 {
	font-weight: lighter;
}
</style>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Untitled Document</title>
</head>

<body>
<div id="holder">
<div id="header"></div>
<div id="navBar">
	<nav>
    	<ul>
        	<li><a href="#">Login</a>        	</li>
        	<li><a href="#">Register</a></li>
            <li><a href="#">Forgot Password</a></li>
        </ul>
    </nav>
    </div>
<div id="content">
	<div id="pageHeading">
	  <h1>You Have Logged Out!</h1>
	</div>
    <div id="contentLeft"></div>
    <div id="contentRight"></div>
  </div>
<div id="footer"></div>
</div>
</body>
</html>
<?php
mysql_free_result($logout);
?>
