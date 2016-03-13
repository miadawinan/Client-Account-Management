<?php @session_start(); ?>
<?php require_once('Connections/MAMP.php'); ?>
<?php
if (!isset($_SESSION)) {
  session_start();
}
$MM_authorizedUsers = "Financial Manager,Area Manager,Service Manager,Branch Administrator";
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
<link href="SpryAssets/SpryValidationConfirm.css" rel="stylesheet" type="text/css" />
<link href="SpryAssets/SpryValidationPassword.css" rel="stylesheet" type="text/css" />
<link href="SpryAssets/SpryValidationTextField.css" rel="stylesheet" type="text/css" />
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Register Employee</title>
<script src="SpryAssets/SpryValidationConfirm.js" type="text/javascript"></script>
<script src="SpryAssets/SpryValidationPassword.js" type="text/javascript"></script>
<script src="SpryAssets/SpryValidationTextField.js" type="text/javascript"></script>
</head>

<body>
<div id="holder">
<div id="header"></div>
<div id="navBar">
	<nav>
    	<ul>
        	<li><a href="account.php">My Profile</a>        	</li>
        	<li><a href="empClientPage.php">Clients</a></li>
            <li><a href="adminAuditPage.php">Audit</a></li>
            <li><a href="empEmployeePage.php">Employees</a></li>
            <li></li>
            <li></li>
            <li><a href="logout.php">Logout</a></li>
        </ul>
    </nav>
  </div>
<div id="content">
	<div id="pageHeading">
	  <h1>Register Employees</h1>
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
    <div id="contentRight">
      <form action="<?php echo $editFormAction; ?>" method="POST" enctype="multipart/form-data" name="addEmployeeForm" id="addEmployeeForm">
        <table width="400" border="0" align="center">
          <tr>          </tr>
        <tr>          </tr>
      </table>
        <div id="contentRight2">
          <table width="600" border="0" align="center">
            <tr> </tr>
            <tr> </tr>
            <tr> </tr>
            <tr> </tr>
            <tr> </tr>
            <tr> </tr>
            <tr> </tr>
            <tr> </tr>
            <tr> </tr>
          </table>
        </div>
        <table width="600" border="0" align="center">
          <tr>
            <td width="144">Role:</td>
            <td width="446"><label for="roleMenu2"></label>
              <select name="roleMenu" size="1" id="roleMenu2">
<option value="Support Worker" selected="selected">Support Worker</option>
                <option value="Support Worker">Branch Administrator</option>
                <option value="Area Manager">Area Manager</option>
                <option value="Service Manager">Service Manager</option>
                <option value="Financial Manager">Financial Manager</option>
                <option value="Auditor">Auditor</option>
                <option value="Administrator">Administrator</option>
              </select></td>
          </tr>
          <tr>
            <td>EmployeeID:</td>
            <td><span id="sprytextfield3">
              <label for="employeeID"></label>
              <input type="text" name="employeeID" id="employeeID" />
            <span class="textfieldRequiredMsg">A value is required.</span></span></td>
          </tr>
          <tr>
            <td>First Name:</td>
            <td><span id="sprytextfield1">
              <label for="firstName"></label>
              <input type="text" name="firstName" id="firstName" />
              <span class="textfieldRequiredMsg">A value is required.</span></span></td>
</tr>
          <tr>
            <td>Last Name:</td>
            <td><span id="sprytextfield2">
              <label for="lastName"></label>
              <input type="text" name="lastName" id="lastName" />
              <span class="textfieldRequiredMsg">A value is required.</span></span></td>
</tr>
          <tr>
            <td>Service:</td>
            <td><select name="serviceMenu" size="1" id="serviceMenu">
              <option value="1111">1111</option>
              <option value="1234">1234</option>
              <option value="0987">0987</option>
            </select></td>
</tr>
          <tr>
            <td>Contact Number:</td>
            <td><span id="sprytextfield4">
            <label for="contactNumber"></label>
            <input type="text" name="contactNumber" id="contactNumber" />
            <span class="textfieldRequiredMsg">A value is required.</span><span class="textfieldInvalidFormatMsg">Invalid format.</span></span></td>
</tr>
          <tr>
            <td>Email:</td>
            <td><span id="sprytextfield6">
            <label for="email"></label>
            <input type="text" name="email" id="email" />
            <span class="textfieldRequiredMsg">A value is required.</span><span class="textfieldInvalidFormatMsg">Invalid format.</span></span></td>
</tr>
          <tr>
            <td>Username:</td>
            <td><span id="sprytextfield7">
              <label for="username"></label>
              <input type="text" name="username" id="username" />
              <span class="textfieldRequiredMsg">A value is required.</span></span></td>
</tr>
          <tr>
            <td>Password:</td>
            <td><span id="sprypassword1">
              <label for="password"></label>
              <input type="password" name="password" id="password" />
              <span class="passwordRequiredMsg">A value is required.</span></span></td>
</tr>
          <tr>
            <td>Confirm Password:</td>
            <td><span id="spryconfirm1">
              <label for="confirmPassword"></label>
              <input type="password" name="confirmPassword" id="confirmPassword" />
              <span class="confirmRequiredMsg">A value is required.</span><span class="confirmInvalidMsg">The values don't match.</span></span></td>
</tr>
          <tr>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
          </tr>
          <tr>
            <td colspan="2" align="center"><input type="submit" name="registerButton" id="registerButton" value="Register" /></td>
          </tr>
        </table>
        <p>&nbsp;</p>
        <input type="hidden" name="MM_insert" value="addEmployeeForm" />
      </form>
    </div>
  </div>
<div id="footer"></div>
</div>
<script type="text/javascript">
var spryconfirm1 = new Spry.Widget.ValidationConfirm("spryconfirm1", "password");
var sprypassword1 = new Spry.Widget.ValidationPassword("sprypassword1");
var sprytextfield7 = new Spry.Widget.ValidationTextField("sprytextfield7");
var sprytextfield6 = new Spry.Widget.ValidationTextField("sprytextfield6", "email");
var sprytextfield4 = new Spry.Widget.ValidationTextField("sprytextfield4", "phone_number", {format:"phone_custom", hint:"000-0000000", pattern:"000-0000000"});
var sprytextfield2 = new Spry.Widget.ValidationTextField("sprytextfield2");
var sprytextfield1 = new Spry.Widget.ValidationTextField("sprytextfield1");
var sprytextfield3 = new Spry.Widget.ValidationTextField("sprytextfield3");
</script>
</body>
</html>
<?php
mysql_free_result($users);

mysql_free_result($registerEmp);
?>
