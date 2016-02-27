<?php require_once('Connections/MAMP.php'); ?>
<?php @session_start();?>
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

$MM_restrictGoTo = "login.php";
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

if ((isset($_GET['userID'])) && ($_GET['userID'] != "") && (isset($_POST['deleteUserHiddenID2']))) {
  $deleteSQL = sprintf("DELETE FROM Users WHERE userID=%s",
                       GetSQLValueString($_GET['userID'], "int"));

  mysql_select_db($database_MAMP, $MAMP);
  $Result1 = mysql_query($deleteSQL, $MAMP) or die(mysql_error());

  $deleteGoTo = "adminManageUser.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $deleteGoTo .= (strpos($deleteGoTo, '?')) ? "&" : "?";
    $deleteGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $deleteGoTo));
}

$maxRows_manageUsers = 10;
$pageNum_manageUsers = 0;
if (isset($_GET['pageNum_manageUsers'])) {
  $pageNum_manageUsers = $_GET['pageNum_manageUsers'];
}
$startRow_manageUsers = $pageNum_manageUsers * $maxRows_manageUsers;

mysql_select_db($database_MAMP, $MAMP);
$query_manageUsers = "SELECT * FROM Users ORDER BY regDate DESC";
$query_limit_manageUsers = sprintf("%s LIMIT %d, %d", $query_manageUsers, $startRow_manageUsers, $maxRows_manageUsers);
$manageUsers = mysql_query($query_limit_manageUsers, $MAMP) or die(mysql_error());
$row_manageUsers = mysql_fetch_assoc($manageUsers);

if (isset($_GET['totalRows_manageUsers'])) {
  $totalRows_manageUsers = $_GET['totalRows_manageUsers'];
} else {
  $all_manageUsers = mysql_query($query_manageUsers);
  $totalRows_manageUsers = mysql_num_rows($all_manageUsers);
}
$totalPages_manageUsers = ceil($totalRows_manageUsers/$maxRows_manageUsers)-1;

$queryString_manageUsers = "";
if (!empty($_SERVER['QUERY_STRING'])) {
  $params = explode("&", $_SERVER['QUERY_STRING']);
  $newParams = array();
  foreach ($params as $param) {
    if (stristr($param, "pageNum_manageUsers") == false && 
        stristr($param, "totalRows_manageUsers") == false) {
      array_push($newParams, $param);
    }
  }
  if (count($newParams) != 0) {
    $queryString_manageUsers = "&" . htmlentities(implode("&", $newParams));
  }
}
$queryString_manageUsers = sprintf("&totalRows_manageUsers=%d%s", $totalRows_manageUsers, $queryString_manageUsers);
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
        	<li><a href="login.php">Login</a>        	</li>
        	<li><a href="register.php">Register</a></li>
            <li><a href="forgotPassword.php">Forgot Password</a></li>
        </ul>
    </nav>
    </div>
<div id="content">
	<div id="pageHeading">
	  <h1>Admin Control Panel</h1>
	</div>
    <div id="contentLeft">
      <p>Account Links</p>
      <p><a href="logout.php">Logout</a></p>
      <p><a href="adminManageUser.php">Manage Users</a></p>
    </div>
    <div id="contentRight">
      <table width="750" border="0" align="center">
        <tr>
          <td align="right" valign="top"><p>Showing&nbsp;<?php echo ($startRow_manageUsers + 1) ?></p>
to <?php echo min($startRow_manageUsers + $maxRows_manageUsers, $totalRows_manageUsers) ?>of <?php echo $totalRows_manageUsers ?></td>
        </tr>
        <tr>
          <td><?php if ($totalRows_manageUsers > 0) { // Show if recordset not empty ?>
              <?php do { ?>
                <table width="545" border="0" align="center">
                  <tr>
                    <td width="539"><?php echo $row_manageUsers['firstName']; ?> <?php echo $row_manageUsers['lastName']; ?> |<?php echo $row_manageUsers['email']; ?></td>
                  </tr>
                  <tr>
                    <td><form id="deleteUserForm" name="deleteUserForm" method="post" action="">
                      <input name="deleteUserHiddenID2" type="hidden" id="deleteUserHiddenID2" value="<?php echo $row_manageUsers['userID']; ?>" />
                      <input type="submit" name="deleteUser2" id="deleteUser2" value="Delete User" />
                    </form></td>
                  </tr>
                  <tr>
                    <td height="38">&nbsp;</td>
                  </tr>
                </table>
                <?php } while ($row_manageUsers = mysql_fetch_assoc($manageUsers)); ?>
              <?php } // Show if recordset not empty ?></td>
        </tr>
        <tr>
          <td align="right" valign="top"><?php if ($pageNum_manageUsers < $totalPages_manageUsers) { // Show if not last page ?>
              <a href="<?php printf("%s?pageNum_manageUsers=%d%s", $currentPage, min($totalPages_manageUsers, $pageNum_manageUsers + 1), $queryString_manageUsers); ?>">Next</a>
              <?php } // Show if not last page ?>
|
<?php if ($pageNum_manageUsers > 0) { // Show if not first page ?>
  <a href="<?php printf("%s?pageNum_manageUsers=%d%s", $currentPage, max(0, $pageNum_manageUsers - 1), $queryString_manageUsers); ?>">Previous</a>
  <?php } // Show if not first page ?>          </td>
        </tr>
      </table>
    </div>
  </div>
<div id="footer"></div>
</div>
</body>
</html>
<?php
mysql_free_result($manageUsers);
?>
