<?php 
@session_start();
$_SESSION['emailPassword'] = $_POST['email'];
?>
<?php require_once('Connections/MAMP.php'); ?>
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

$colname_emailPassword = "-1";
if (isset($_SESSION['emailPassword'])) {
  $colname_emailPassword = $_SESSION['emailPassword'];
}
mysql_select_db($database_MAMP, $MAMP);
$query_emailPassword = sprintf("SELECT * FROM Users WHERE email = %s", GetSQLValueString($colname_emailPassword, "text"));
$emailPassword = mysql_query($query_emailPassword, $MAMP) or die(mysql_error());
$row_emailPassword = mysql_fetch_assoc($emailPassword);
$totalRows_emailPassword = mysql_num_rows($emailPassword);

$colname_emailPassword = "-1";
if (isset($_SESSION['emailPassword'])) {
  $colname_emailPassword = $_SESSION['emailPassword'];
}
mysql_select_db($database_MAMP, $MAMP);
$query_emailPassword = sprintf("SELECT * FROM Users WHERE email = %s", GetSQLValueString($colname_emailPassword, "text"));
$emailPassword = mysql_query($query_emailPassword, $MAMP) or die(mysql_error());
$row_emailPassword = mysql_fetch_assoc($emailPassword);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Untitled Document</title>
</head>

<body>
</body>
</html>
<?php
mysql_free_result($emailPassword);
?>
<?php 
if($totalRows_emailPassword > 0) {
$from="noreply@nzcare.com";
$email=$_SESSION['emailPassword'];
$subject="You Domain - Email Password";
$message="Here is your Password:".$row_emailPassword['password'];

mail($email, $subject, $message, "From:".$from);
}
	if($totalRows_emailPassword > 0) {
			echo "Please check your email, you have been sent your Password";
	} else {
		echo "Fail - Please try again";
	}
?>