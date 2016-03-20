<?php @session_start(); ?>
<?php require_once('Connections/MAMP.php'); ?>
<?php
if (!isset($_SESSION)) {
  session_start();
}
$MM_authorizedUsers = "Administrator";
$MM_donotCheckaccess = "false";

// *** Restrict Access To Page: Grant or deny access to this page
function isAuthorized($strUsers, $strGroups, $UserName, $UserGroup) { 
  // For security, start by assuming the visitor is NOT authorized. 
  $isValid = False; 

  // When a visitor has logged into this site, the Session variable MM_Username set equal to their username. 
  // Therefore, we know that a user is NOT logged in if that Session variable is blank. 
  if (!empty($UserName)) { 
    // Besides being logged in, you may restrict access to only certain users based on an ID established when they login. 
    // Parse the strings into arrays. 
    $arrUsers = Explode(",", $strUsers); 
    $arrGroups = Explode(",", $strGroups); 
    if (in_array($UserName, $arrUsers)) { 
      $isValid = true; 
    } 
    // Or, you may restrict access to only certain users based on their username. 
    if (in_array($UserGroup, $arrGroups)) { 
      $isValid = true; 
    } 
    if (($strUsers == "") && false) { 
      $isValid = true; 
    } 
  } 
  return $isValid; 
}

$MM_restrictGoTo = "account.php";
if (!((isset($_SESSION['MM_Username'])) && (isAuthorized("",$MM_authorizedUsers, $_SESSION['MM_Username'], $_SESSION['MM_UserGroup'])))) {   
  $MM_qsChar = "?";
  $MM_referrer = $_SERVER['PHP_SELF'];
  if (strpos($MM_restrictGoTo, "?")) $MM_qsChar = "&";
  if (isset($_SERVER['QUERY_STRING']) && strlen($_SERVER['QUERY_STRING']) > 0) 
  $MM_referrer .= "?" . $_SERVER['QUERY_STRING'];
  $MM_restrictGoTo = $MM_restrictGoTo. $MM_qsChar . "accesscheck=" . urlencode($MM_referrer);
  header("Location: ". $MM_restrictGoTo); 
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

// *** Redirect if username exists
$MM_flag="MM_insert";
if (isset($_POST[$MM_flag])) {
  $MM_dupKeyRedirect="adminAddEmployee.php";
  $loginUsername = $_POST['username'];
  $LoginRS__query = sprintf("SELECT userName FROM Users WHERE userName=%s", GetSQLValueString($loginUsername, "text"));
  mysql_select_db($database_MAMP, $MAMP);
  $LoginRS=mysql_query($LoginRS__query, $MAMP) or die(mysql_error());
  $loginFoundUser = mysql_num_rows($LoginRS);

  //if there is a row in the database, the username was found - can not add the requested username
  if($loginFoundUser){
    $MM_qsChar = "?";
    //append the username to the redirect page
    if (substr_count($MM_dupKeyRedirect,"?") >=1) $MM_qsChar = "&";
    $MM_dupKeyRedirect = $MM_dupKeyRedirect . $MM_qsChar ."requsername=".$loginUsername;
    header ("Location: $MM_dupKeyRedirect");
    exit;
  }
}

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "addEmployeeForm")) {
  $insertSQL = sprintf("INSERT INTO Users (firstName, lastName, contactNumber, serviceID, email, userName, password, `role`) VALUES (%s, %s, %s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['firstName'], "text"),
                       GetSQLValueString($_POST['lastName'], "text"),
                       GetSQLValueString($_POST['contactNumber'], "int"),
                       GetSQLValueString($_POST['serviceMenu'], "int"),
                       GetSQLValueString($_POST['email'], "text"),
                       GetSQLValueString($_POST['username'], "text"),
                       GetSQLValueString($_POST['password'], "text"),
                       GetSQLValueString($_POST['roleMenu'], "text"));

  mysql_select_db($database_MAMP, $MAMP);
  $Result1 = mysql_query($insertSQL, $MAMP) or die(mysql_error());

  $insertGoTo = "adminAddEmployee.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    $insertGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $insertGoTo));
}


if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "addEmployeeForm")) {
  $insertSQL = sprintf("INSERT INTO Employees (EmployeeID, `Role`, FirstName, LastName, Email, ServiceID, Username, Password) VALUES (%s, %s, %s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['employeeID'], "int"),
                       GetSQLValueString($_POST['roleMenu'], "text"),
                       GetSQLValueString($_POST['firstName'], "text"),
                       GetSQLValueString($_POST['lastName'], "text"),
                       GetSQLValueString($_POST['email'], "text"),
                       GetSQLValueString($_POST['serviceMenu'], "int"),
                       GetSQLValueString($_POST['username'], "text"),
                       GetSQLValueString($_POST['password'], "text"));

  mysql_select_db($database_MAMP, $MAMP);
  $Result1 = mysql_query($insertSQL, $MAMP) or die(mysql_error());

  $insertGoTo = "adminAddEmployee.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    $insertGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $insertGoTo));
}

$colname_users = "-1";
if (isset($_SESSION['MM_Username'])) {
  $colname_users = $_SESSION['MM_Username'];
}
mysql_select_db($database_MAMP, $MAMP);
$query_users = sprintf("SELECT * FROM Users WHERE userName = %s", GetSQLValueString($colname_users, "text"));
$users = mysql_query($query_users, $MAMP) or die(mysql_error());
$row_users = mysql_fetch_assoc($users);
$totalRows_users = mysql_num_rows($users);

$colname_registerEmp = "-1";
if (isset($_SESSION['MM_Username'])) {
  $colname_registerEmp = $_SESSION['MM_Username'];
}
mysql_select_db($database_MAMP, $MAMP);
$query_registerEmp = sprintf("SELECT * FROM Employees WHERE Username = %s", GetSQLValueString($colname_registerEmp, "text"));
$registerEmp = mysql_query($query_registerEmp, $MAMP) or die(mysql_error());
$row_registerEmp = mysql_fetch_assoc($registerEmp);
$totalRows_registerEmp = mysql_num_rows($registerEmp);
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
<title>Registration Failed</title>
</head>

<body>
<div id="holder">
<div id="header">
  <table width="200" border="0" align="right">
    <tr>
      <td>Role: <?php echo $row_users['role']; ?></td>
    </tr>
  </table>
</div>
<div id="navBar">
	<nav>
    	<ul>
        	<li><a href="account.php">My Profile</a>        	</li>
        	<li><a href="adminClientPage.php">Clients</a></li>
            <li><a href="adminServiceAreaPage.php">Service Areas</a></li>
            <li><a href="adminAuditPage.php">Audit</a></li>
            <li><a href="adminEmployeePage.php">Employees</a></li>
            <li></li>
            <li><a href="logout.php">Logout</a></li>
        </ul>
    </nav>
  </div>
<div id="content">
	<div id="pageHeading">
	  <h1>Employee Registration Failed!</h1>
	</div>
    <div id="contentLeft">
      <h3>Links </h3>
      <p><a href="#">Financial Manager(s)</a></p>
      <p><a href="#">Area Manager(s)</a></p>
      <p><a href="#">Service Manager(s)</a></p>
      <p><a href="#">Branch Administrator(s)</a></p>
      <p><a href="#">Support Workers(s)</a></p>
      <p><a href="#">Register Employee</a></p>
      <p>&nbsp;</p>
    </div>
</div>
<div id="footer"></div>
</div>
</body>
</html>
<?php
mysql_free_result($users);

mysql_free_result($registerEmp);
?>
