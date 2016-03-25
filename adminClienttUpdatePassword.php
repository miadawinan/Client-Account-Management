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

$colname_qUserDetails = "-1";
if (isset($_GET['userName'])) {
  $colname_qUserDetails = $_GET['userName'];
}
mysql_select_db($database_MAMP, $MAMP);
$query_qUserDetails = sprintf("SELECT * FROM Users WHERE userName = %s ORDER BY firstName ASC", GetSQLValueString($colname_qUserDetails, "text"));
$qUserDetails = mysql_query($query_qUserDetails, $MAMP) or die(mysql_error());
$row_qUserDetails = mysql_fetch_assoc($qUserDetails);
$totalRows_qUserDetails = mysql_num_rows($qUserDetails);

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
	border: 1px none #000;
}
table {
	margin: 0px;
	padding: 0px;
	height: auto;
	border: 1px none #000;
}
</style>
<link href="SpryAssets/SpryValidationTextField.css" rel="stylesheet" type="text/css" />
<link href="SpryAssets/SpryValidationConfirm.css" rel="stylesheet" type="text/css" />
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Update Password</title>
<script src="SpryAssets/SpryValidationTextField.js" type="text/javascript"></script>
<script src="SpryAssets/SpryValidationConfirm.js" type="text/javascript"></script>
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
	  <h1>Update Password</h1>
	</div>
    <div id="contentLeft">
      <h3>Links      </h3>
      <p><a href="#">Search Client</a></p>
      <p><a href="adminRegisterClient.php">Register New Client</a></p>
      <p><a href="adminClientUpdate.php">Update Client</a></p>
      <p><a href="adminDeleteClient.php">Delete Client</a></p>
      <p>&nbsp;</p>
      <p>&nbsp;</p>
    </div>
    <div id="contentRight">
      <form id="changePassForm" name="changePassForm" method="post" action="">
        <table width="400" border="0" align="center">
          <tr>          </tr>
        <tr>          </tr>
      </table>
        <table width="600" border="0" cellpadding="2">
          <tr>
            <td width="162">First Name:</td>
            <td width="424"><?php echo $row_qUserDetails['firstName']; ?></td>
          </tr>
          <tr>
            <td>Last Name:</td>
            <td><?php echo $row_qUserDetails['lastName']; ?></td>
          </tr>
          <tr>
            <td>Username:</td>
            <td><?php echo $row_qUserDetails['userName']; ?></td>
          </tr>
          <tr>
            <td>Current Password:</td>
            <td><?php echo $row_qUserDetails['password']; ?></td>
          </tr>
          <tr>
            <td>New Password:</td>
            <td><span id="sprytextfield1">
              <label for="newPassword"></label>
            <span class="textfieldRequiredMsg">A value is required.</span></span><span id="sprytextfield2">
            <label for="newPassword2"></label>
            <input name="newPassword" type="password" id="newPassword2" value="<?php echo $row_qUserDetails['password']; ?>" />
            <span class="textfieldRequiredMsg">A value is required.</span></span></td>
          </tr>
          <tr>
            <td>Confirm Password:</td>
            <td><span id="spryconfirm1">
              <label for="confirmNewPass"></label>
            <span class="confirmRequiredMsg">A value is required.</span><span class="confirmInvalidMsg">The values don't match.</span></span><span id="spryconfirm2">
            <label for="confirmPass"></label>
            <input name="confirmPass" type="password" id="confirmPass" value="<?php echo $row_qUserDetails['password']; ?>" />
            <span class="confirmRequiredMsg">A value is required.</span><span class="confirmInvalidMsg">The values don't match.</span></span></td>
          </tr>
          <tr>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
          </tr>
          <tr>
            <td>&nbsp;</td>
            <td><input type="submit" name="updatePassButton" id="updatePassButton" value="Update Password" /></td>
          </tr>
        </table>
        <p>&nbsp;</p>
      </form>
    </div>
  </div>
<div id="footer"></div>
</div>
<script type="text/javascript">
var sprytextfield1 = new Spry.Widget.ValidationTextField("sprytextfield1");
var spryconfirm1 = new Spry.Widget.ValidationConfirm("spryconfirm1", "newPassword");
var sprytextfield2 = new Spry.Widget.ValidationTextField("sprytextfield2");
var spryconfirm2 = new Spry.Widget.ValidationConfirm("spryconfirm2", "newPassword2", {validateOn:["blur"]});
</script>
</body>
</html>
<?php
mysql_free_result($user);

mysql_free_result($qUserDetails);
?>
