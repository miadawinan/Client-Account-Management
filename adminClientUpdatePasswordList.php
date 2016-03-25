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

$colname_user = "-1";
if (isset($_SESSION['MM_Username'])) {
  $colname_user = $_SESSION['MM_Username'];
}
mysql_select_db($database_MAMP, $MAMP);
$query_user = sprintf("SELECT * FROM Users WHERE userName = %s", GetSQLValueString($colname_user, "text"));
$user = mysql_query($query_user, $MAMP) or die(mysql_error());
$row_user = mysql_fetch_assoc($user);
$totalRows_user = mysql_num_rows($user);

$maxRows_qUserClient = 10;
$pageNum_qUserClient = 0;
if (isset($_GET['pageNum_qUserClient'])) {
  $pageNum_qUserClient = $_GET['pageNum_qUserClient'];
}
$startRow_qUserClient = $pageNum_qUserClient * $maxRows_qUserClient;

mysql_select_db($database_MAMP, $MAMP);
$query_qUserClient = "SELECT * FROM Users WHERE `role` = 'Client' ORDER BY firstName ASC";
$query_limit_qUserClient = sprintf("%s LIMIT %d, %d", $query_qUserClient, $startRow_qUserClient, $maxRows_qUserClient);
$qUserClient = mysql_query($query_limit_qUserClient, $MAMP) or die(mysql_error());
$row_qUserClient = mysql_fetch_assoc($qUserClient);

if (isset($_GET['totalRows_qUserClient'])) {
  $totalRows_qUserClient = $_GET['totalRows_qUserClient'];
} else {
  $all_qUserClient = mysql_query($query_qUserClient);
  $totalRows_qUserClient = mysql_num_rows($all_qUserClient);
}
$totalPages_qUserClient = ceil($totalRows_qUserClient/$maxRows_qUserClient)-1;

$queryString_qUsers = "";
if (!empty($_SERVER['QUERY_STRING'])) {
  $params = explode("&", $_SERVER['QUERY_STRING']);
  $newParams = array();
  foreach ($params as $param) {
    if (stristr($param, "pageNum_qUsers") == false && 
        stristr($param, "totalRows_qUsers") == false) {
      array_push($newParams, $param);
    }
  }
  if (count($newParams) != 0) {
    $queryString_qUsers = "&" . htmlentities(implode("&", $newParams));
  }
}
$queryString_qUsers = sprintf("&totalRows_qUsers=%d%s", $totalRows_qUsers, $queryString_qUsers);

@session_start(); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link href="CSS/layout.css" rel="stylesheet" type="text/css" />
<link href="CSS/menu.css" rel="stylesheet" type="text/css" />
<style type="text/css">
h1 {
	font-weight: lighter;
}
table tr td {
	border-top-style: solid;
	border-right-style: solid;
	border-bottom-style: solid;
	border-left-style: solid;
}
table tr td {
	border-top-style: double;
	border-right-style: double;
	border-bottom-style: double;
	border-left-style: double;
}
table tr td {
	border-top-style: none;
	border-right-style: none;
	border-bottom-style: none;
	border-left-style: none;
	border-top-color: #333;
	border-right-color: #333;
	border-bottom-color: #333;
	border-left-color: #333;
}
.table {
	border-top-width: thin;
	border-right-width: thin;
	border-bottom-width: thin;
	border-left-width: thin;
	height: 10px;
}
table tr td {
}
table tr td {
	border-top-width: 1px;
	border-right-width: 1px;
	border-bottom-width: 1px;
	border-left-width: 1px;
	border-top-style: solid;
	border-right-style: none;
	border-bottom-style: solid;
	border-left-style: none;
	border-top-color: #999;
	border-right-color: #999;
	border-bottom-color: #999;
	border-left-color: #999;
}
a {
}
table tr td {
	margin: 0px;
	padding: 0px;
	height: 16px;
	border-top-width: 1px;
	border-right-width: 1px;
	border-bottom-width: 1px;
	border-left-width: 1px;
	border-top-style: solid;
	border-right-style: none;
	border-bottom-style: solid;
	border-left-style: none;
	border-top-color: #000;
	border-right-color: #000;
	border-bottom-color: #000;
	border-left-color: #000;
}
table {
	margin: 0px;
	padding: 0px;
	height: auto;
	border: 1px none #000;
}
</style>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Clients</title>
</head>

<body>
<div id="holder">
<div id="header">
  <table width="200" border="0" align="right">
    <tr>
      <td>Role: <?php echo $row_user['role']; ?></td>
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
	  <h3>NZCare Client List</h3>
	  <p>Please click on a client to update its Password. To update client's details please use the update client details form. </p>
	</div>
    <div id="contentLeft">
      <h3>Links      </h3>
      <p><a href="adminClientList.php">Client List</a></p>
      <p><a href="adminRegisterClient.php">Register New Client</a></p>
      <p><a href="adminClientUpdate.php">Update Client</a></p>
      <p><a href="adminDeleteClient.php">Delete Client</a></p>
      <p>&nbsp;</p>
      <p>&nbsp;</p>
    </div>
    <div id="contentRight">
      <form id="updateForm" name="updateForm" method="post" action="">
        <table width="400" border="0" align="center">
          <tr>          </tr>
        <tr>          </tr>
      </table>
        <table width="761" height="90" border="0">
          <tr>
            <td height="65" colspan="5" align="right">&nbsp;</td>
          </tr>
          <tr class="table">
            <td width="142" height="58" bgcolor="#CCCCCC">Username</td>
            <td width="144" bgcolor="#CCCCCC">First Name</td>
            <td width="140" bgcolor="#CCCCCC">Last Name</td>
            <td width="144" bgcolor="#CCCCCC">Service ID</td>
            <td width="169" bgcolor="#CCCCCC">Passwrord</td>
          </tr>
          <?php do { ?>
            <tr>
              <td height="30"><a href="adminClienttUpdatePassword.php?userName=<?php echo $row_qUserClient['userName']; ?>"><?php echo $row_qUserClient['userName']; ?></a></td>
              <td height="30"><?php echo $row_qUserClient['firstName']; ?></td>
              <td height="30"><?php echo $row_qUserClient['lastName']; ?></td>
              <td height="30"><?php echo $row_qUserClient['serviceID']; ?></td>
              <td height="30"><?php echo $row_qUserClient['password']; ?></td>
            </tr>
            <?php } while ($row_qUserClient = mysql_fetch_assoc($qUserClient)); ?>
<tr>
            <td colspan="5" align="right" valign="bottom">Previous Next </td>
          </tr>
        </table>
        <p>&nbsp;</p>
      </form>
    </div>
  </div>
<div id="footer"></div>
</div>
</body>
</html>
<?php
mysql_free_result($user);

mysql_free_result($qUserClient);
?>
