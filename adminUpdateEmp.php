<?php @session_start(); ?>
<?php require_once('Connections/MAMP.php'); ?>
<?php require_once('Connections/MAMP.php'); ?>
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

$currentPage = $_SERVER["PHP_SELF"];

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

$maxRows_qEmp = 10;
$pageNum_qEmp = 0;
if (isset($_GET['pageNum_qEmp'])) {
  $pageNum_qEmp = $_GET['pageNum_qEmp'];
}
$startRow_qEmp = $pageNum_qEmp * $maxRows_qEmp;

mysql_select_db($database_MAMP, $MAMP);
$query_qEmp = "SELECT * FROM Employees ORDER BY FirstName ASC";
$query_limit_qEmp = sprintf("%s LIMIT %d, %d", $query_qEmp, $startRow_qEmp, $maxRows_qEmp);
$qEmp = mysql_query($query_limit_qEmp, $MAMP) or die(mysql_error());
$row_qEmp = mysql_fetch_assoc($qEmp);

if (isset($_GET['totalRows_qEmp'])) {
  $totalRows_qEmp = $_GET['totalRows_qEmp'];
} else {
  $all_qEmp = mysql_query($query_qEmp);
  $totalRows_qEmp = mysql_num_rows($all_qEmp);
}
$totalPages_qEmp = ceil($totalRows_qEmp/$maxRows_qEmp)-1;

$queryString_qEmp = "";
if (!empty($_SERVER['QUERY_STRING'])) {
  $params = explode("&", $_SERVER['QUERY_STRING']);
  $newParams = array();
  foreach ($params as $param) {
    if (stristr($param, "pageNum_qEmp") == false && 
        stristr($param, "totalRows_qEmp") == false) {
      array_push($newParams, $param);
    }
  }
  if (count($newParams) != 0) {
    $queryString_qEmp = "&" . htmlentities(implode("&", $newParams));
  }
}
$queryString_qEmp = sprintf("&totalRows_qEmp=%d%s", $totalRows_qEmp, $queryString_qEmp);
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
.table {
	height: 10px;
	border: 1px solid #000;
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
	border-top-color: #000;
	border-right-color: #000;
	border-bottom-color: #000;
	border-left-color: #000;
}
table .table {
	width: 700px;
}
.table1 {	border-top-width: thin;
	border-right-width: thin;
	border-bottom-width: thin;
	border-left-width: thin;
	height: 10px;
}
</style>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Manage Employees</title>
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
	  <h1>All Employees</h1>
	  <div id="contentRight">
	    <form id="updateEmpForm" name="updateEmpForm" method="post" action="">
	      <table width="400" border="0" align="center">
	        <tr> </tr>
	        <tr> </tr>
          </table>
	      <table width="772" height="100" border="0">
	        <tr>
	          <td height="22" colspan="5" align="right">Showing <?php echo ($startRow_qEmp + 1) ?> to <?php echo min($startRow_qEmp + $maxRows_qEmp, $totalRows_qEmp) ?> of <?php echo $totalRows_qEmp ?></td>
            </tr>
	        <tr class="table1">
	          <td width="114" height="24" bgcolor="#CCCCCC">Employee ID</td>
	          <td width="157" bgcolor="#CCCCCC">First Name</td>
	          <td width="142" bgcolor="#CCCCCC">Last Name</td>
	          <td width="136" bgcolor="#CCCCCC">Role</td>
	          <td width="173" bgcolor="#CCCCCC">Service ID</td>
            </tr>
	        <?php do { ?>
	          <tr>
	            <td><?php echo $row_qEmp['EmployeeID']; ?></td>
	            <td><?php echo $row_qEmp['FirstName']; ?></td>
	            <td><?php echo $row_qEmp['LastName']; ?></td>
	            <td><?php echo $row_qEmp['Role']; ?></td>
	            <td><?php echo $row_qEmp['ServiceID']; ?></td>
              </tr>
	          <?php } while ($row_qEmp = mysql_fetch_assoc($qEmp)); ?>
            <tr>
              <td height="22" colspan="5" align="right"><a href="<?php printf("%s?pageNum_qEmp=%d%s", $currentPage, max(0, $pageNum_qEmp - 1), $queryString_qEmp); ?>">Previous</a> | <a href="<?php printf("%s?pageNum_qEmp=%d%s", $currentPage, min($totalPages_qEmp, $pageNum_qEmp + 1), $queryString_qEmp); ?>">Next </a></td>
            </tr>
          </table>
	      <p>&nbsp;</p>
        </form>
      </div>
	</div>
    <div id="contentLeft">
      <h3>Links </h3>
      <p><a href="adminUpdateEmp.php">All Employees</a></p>
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
mysql_free_result($user);

mysql_free_result($qEmp);
?>
