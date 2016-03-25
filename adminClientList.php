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

$colname_user = "-1";
if (isset($_SESSION['MM_Username'])) {
  $colname_user = $_SESSION['MM_Username'];
}
mysql_select_db($database_MAMP, $MAMP);
$query_user = sprintf("SELECT * FROM Users WHERE userName = %s", GetSQLValueString($colname_user, "text"));
$user = mysql_query($query_user, $MAMP) or die(mysql_error());
$row_user = mysql_fetch_assoc($user);
$totalRows_user = mysql_num_rows($user);

$maxRows_qClient = 10;
$pageNum_qClient = 0;
if (isset($_GET['pageNum_qClient'])) {
  $pageNum_qClient = $_GET['pageNum_qClient'];
}
$startRow_qClient = $pageNum_qClient * $maxRows_qClient;

mysql_select_db($database_MAMP, $MAMP);
$query_qClient = "SELECT * FROM Clients ORDER BY FirstName ASC";
$query_limit_qClient = sprintf("%s LIMIT %d, %d", $query_qClient, $startRow_qClient, $maxRows_qClient);
$qClient = mysql_query($query_limit_qClient, $MAMP) or die(mysql_error());
$row_qClient = mysql_fetch_assoc($qClient);

if (isset($_GET['totalRows_qClient'])) {
  $totalRows_qClient = $_GET['totalRows_qClient'];
} else {
  $all_qClient = mysql_query($query_qClient);
  $totalRows_qClient = mysql_num_rows($all_qClient);
}
$totalPages_qClient = ceil($totalRows_qClient/$maxRows_qClient)-1;

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

$queryString_qClient = "";
if (!empty($_SERVER['QUERY_STRING'])) {
  $params = explode("&", $_SERVER['QUERY_STRING']);
  $newParams = array();
  foreach ($params as $param) {
    if (stristr($param, "pageNum_qClient") == false && 
        stristr($param, "totalRows_qClient") == false) {
      array_push($newParams, $param);
    }
  }
  if (count($newParams) != 0) {
    $queryString_qClient = "&" . htmlentities(implode("&", $newParams));
  }
}
$queryString_qClient = sprintf("&totalRows_qClient=%d%s", $totalRows_qClient, $queryString_qClient);
 
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
	  <h1>Client List</h1>
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
      <form id="clientListForm" name="clientListForm" method="post" action="">
        <table width="400" border="0" align="center">
          <tr>          </tr>
        <tr>          </tr>
      </table>
        <table width="761" height="90" border="0">
          <tr>
            <td height="65" colspan="5" align="right">Showing <?php echo ($startRow_qClient + 1) ?>to <?php echo min($startRow_qClient + $maxRows_qClient, $totalRows_qClient) ?>of <?php echo $totalRows_qClient ?></td>
          </tr>
          <tr class="table">
            <td width="142" height="58" bgcolor="#CCCCCC">NHI Number</td>
            <td width="144" bgcolor="#CCCCCC">First Name</td>
            <td width="140" bgcolor="#CCCCCC">Last Name</td>
            <td width="144" bgcolor="#CCCCCC">Service ID</td>
            <td width="169" bgcolor="#CCCCCC">Assigned Support Worker</td>
          </tr>
          <?php do { ?>
            <tr>
              <td height="30"><?php echo $row_qClient['NHINumber']; ?></td>
              <td height="30"><?php echo $row_qClient['FirstName']; ?></td>
              <td height="30"><?php echo $row_qClient['LastName']; ?></td>
              <td height="30"><?php echo $row_qClient['ServiceID']; ?></td>
              <td height="30"><?php echo $row_qClient['AssignedSW']; ?></td>
            </tr>
            <?php } while ($row_qClient = mysql_fetch_assoc($qClient)); ?>
          <tr>
            <td colspan="5" align="right" valign="bottom"><?php if ($pageNum_qClient > 0) { // Show if not first page ?>
                <a href="<?php printf("%s?pageNum_qClient=%d%s", $currentPage, max(0, $pageNum_qClient - 1), $queryString_qClient); ?>">Previous</a>
                <?php } // Show if not first page ?>
              <?php if ($pageNum_qClient < $totalPages_qClient) { // Show if not last page ?>
  <a href="<?php printf("%s?pageNum_qClient=%d%s", $currentPage, min($totalPages_qClient, $pageNum_qClient + 1), $queryString_qClient); ?>" class="table">Next </a>
  <?php } // Show if not last page ?>            </td>
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

mysql_free_result($qClient);
?>
