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

$MM_restrictGoTo = "staffAccess.php";
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
  $MM_dupKeyRedirect="adminRegisterClient.php";
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

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "addClientForm")) {
  $insertSQL = sprintf("INSERT INTO Users (userName, firstName, lastName, contactNumber, serviceID, email, password, `role`) VALUES (%s, %s, %s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['username'], "text"),
                       GetSQLValueString($_POST['firstName'], "text"),
                       GetSQLValueString($_POST['lastName'], "text"),
                       GetSQLValueString($_POST['contactNumber'], "int"),
                       GetSQLValueString($_POST['serviceMenu'], "int"),
                       GetSQLValueString($_POST['email'], "text"),
                       GetSQLValueString($_POST['password'], "text"),
                       GetSQLValueString($_POST['clientRole'], "text"));

  mysql_select_db($database_MAMP, $MAMP);
  $Result1 = mysql_query($insertSQL, $MAMP) or die(mysql_error());

  $insertGoTo = "adminClientRegSuccess.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    $insertGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $insertGoTo));
}

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "addClientForm")) {
  $insertSQL = sprintf("INSERT INTO Clients (Username, NHINumber, FirstName, LastName, Gender, AssignedSW, ServiceID, ServiceArea, PhoneNumber, Email, EmergencyContactName, EmergencyContactAdd, EmergencyContactNo, FinAgreement) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['username'], "text"),
                       GetSQLValueString($_POST['nhiNumber'], "text"),
                       GetSQLValueString($_POST['firstName'], "text"),
                       GetSQLValueString($_POST['lastName'], "text"),
                       GetSQLValueString($_POST['genderOption'], "text"),
                       GetSQLValueString($_POST['assignSW'], "text"),
                       GetSQLValueString($_POST['serviceMenu'], "int"),
                       GetSQLValueString($_POST['areaMenu'], "text"),
                       GetSQLValueString($_POST['contactNumber'], "int"),
                       GetSQLValueString($_POST['email'], "text"),
                       GetSQLValueString($_POST['emergencyContactName'], "text"),
                       GetSQLValueString($_POST['emgncyContactAdd'], "text"),
                       GetSQLValueString($_POST['emergencyContactNumber'], "int"),
                       GetSQLValueString($_POST['finAgreement'], "text"));

  mysql_select_db($database_MAMP, $MAMP);
  $Result1 = mysql_query($insertSQL, $MAMP) or die(mysql_error());

  $insertGoTo = "adminClientRegSuccess.php";
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

mysql_select_db($database_MAMP, $MAMP);
$query_registerClient = "SELECT * FROM Clients";
$registerClient = mysql_query($query_registerClient, $MAMP) or die(mysql_error());
$row_registerClient = mysql_fetch_assoc($registerClient);
$totalRows_registerClient = mysql_num_rows($registerClient);
 
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
</style>
<link href="SpryAssets/SpryValidationConfirm.css" rel="stylesheet" type="text/css" />
<link href="SpryAssets/SpryValidationPassword.css" rel="stylesheet" type="text/css" />
<link href="SpryAssets/SpryValidationTextField.css" rel="stylesheet" type="text/css" />
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Register Client</title>
<script src="SpryAssets/SpryValidationConfirm.js" type="text/javascript"></script>
<script src="SpryAssets/SpryValidationPassword.js" type="text/javascript"></script>
<script src="SpryAssets/SpryValidationTextField.js" type="text/javascript"></script>
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
	  <h1>Register New Client</h1>
	</div>
    <div id="contentLeft">
      <h3>Links      </h3>
      <p><a href="adminClientList.php">Client List</a></p>
      <p><a href="adminRegisterClient.php">Register Client</a>      </p>
      <p><a href="adminUpdateClient.php">Update Client</a></p>
      <p><a href="adminDeleteClient.php">Delete Client</a></p>
      <p>&nbsp;</p>
      <p>&nbsp;</p>
    </div>
    <div id="contentRight">
      <form action="<?php echo $editFormAction; ?>" method="POST" enctype="multipart/form-data" name="addClientForm" id="addClientForm">
        <table width="400" border="0" align="center">
          <tr>          </tr>
        <tr>          </tr>
      </table>
        <div id="contentRight2">
          <table width="600" height="429" border="0" align="center">
            <tr>
              <td width="213" height="30">Role:</td>
              <td width="377" height="30"><label for="clientRole"></label>
                <select name="clientRole" id="clientRole">
                  <option value="Client">Client</option>
              </select></td>
            </tr>
            <tr>
              <td height="30">First Name:</td>
              <td height="30"><span id="sprytextfield1">
                <label for="firstName"></label>
                <input type="text" name="firstName" id="firstName" />
                <span class="textfieldRequiredMsg">A value is required.</span></span></td>
</tr>
            <tr>
              <td height="30">Last Name:</td>
              <td height="30"><span id="sprytextfield2">
                <label for="lastName"></label>
                <input type="text" name="lastName" id="lastName" />
                <span class="textfieldRequiredMsg">A value is required.</span></span></td>
</tr>
            <tr>
              <td height="30">NHI Number:</td>
              <td height="30"><span id="sprytextfield3">
                <label for="nhiNumber"></label>
                <input type="text" name="nhiNumber" id="nhiNumber" />
                <span class="textfieldRequiredMsg">A value is required.</span></span></td>
</tr>
            <tr>
              <td height="30">Gender:</td>
              <td height="30"><label for="genderOption"></label>
                <select name="genderOption" id="genderOption">
                  <option value="Male">Male</option>
                  <option value="Female">Female</option>
              </select></td>
            </tr>
            <tr>
              <td height="30">Contact Number:</td>
              <td height="30"><span id="sprytextfield4">
                <label for="contactNumber"></label>
                <input type="text" name="contactNumber" id="contactNumber" />
                <span class="textfieldRequiredMsg">A value is required.</span></span></td>
</tr>
            <tr>
              <td height="30">Assign Support Worker:</td>
              <td height="30"><label for="assignSW"></label>
                <select name="assignSW" id="assignSW">
                  <option value="Support1">Support1</option>
                  <option value="Support2">Support2</option>
              </select></td>
            </tr>
            <tr>
              <td height="30">ServiceID:</td>
              <td height="30"><select name="serviceMenu" size="1" id="serviceMenu">
                <option value="1111">1111</option>
                <option value="1234">1234</option>
                <option value="0987">0987</option>
              </select></td>
            </tr>
            <tr>
              <td height="30">service Area:</td>
              <td height="30"><label for="areaMenu"></label>
                <select name="areaMenu" size="1" id="areaMenu">
                  <option value="Area1">Area1</option>
                  <option value="Area2">Area2</option>
                  <option value="Area3">Area3</option>
              </select></td>
            </tr>
            <tr>
              <td height="30">Email:</td>
              <td height="30"><span id="sprytextfield6">
              <label for="email"></label>
              <input type="text" name="email" id="email" />
              <span class="textfieldRequiredMsg">A value is required.</span><span class="textfieldInvalidFormatMsg">Invalid format.</span></span></td>
</tr>
            <tr>
              <td height="30">Emergency Contact Name:</td>
              <td height="30"><span id="sprytextfield8">
                <label for="emergencyContactName"></label>
                <input type="text" name="emergencyContactName" id="emergencyContactName" />
              <span class="textfieldRequiredMsg">A value is required.</span></span></td>
            </tr>
            <tr>
              <td height="30">Emergency Contact Number:</td>
              <td height="30"><span id="sprytextfield9">
                <label for="emergencyContactNumber"></label>
                <input type="text" name="emergencyContactNumber" id="emergencyContactNumber" />
              <span class="textfieldRequiredMsg">A value is required.</span></span></td>
            </tr>
            <tr>
              <td height="30">Emergency Contact Address:</td>
              <td height="30"><span id="sprytextfield11">
                <label for="emgncyContactAdd"></label>
                <input type="text" name="emgncyContactAdd" id="emgncyContactAdd" />
              <span class="textfieldRequiredMsg">A value is required.</span></span></td>
            </tr>
            <tr>
              <td height="30">Financial Agreement:</td>
              <td height="30"><label for="finAgreement"></label>
              <input type="file" name="finAgreement" id="finAgreement" /></td>
            </tr>
            <tr>
              <td height="30">Username:</td>
              <td height="30"><span id="sprytextfield7">
                <label for="username"></label>
                <input type="text" name="username" id="username" />
                <span class="textfieldRequiredMsg">A value is required.</span></span></td>
</tr>
            <tr>
              <td height="30">Password:</td>
              <td height="30"><span id="sprypassword1">
                <label for="password"></label>
                <input type="password" name="password" id="password" />
                <span class="passwordRequiredMsg">A value is required.</span></span></td>
</tr>
            <tr>
              <td height="30">Confirm Password:</td>
              <td height="30"><span id="spryconfirm1">
                <label for="confirmPassword"></label>
                <input type="password" name="confirmPassword" id="confirmPassword" />
                <span class="confirmRequiredMsg">A value is required.</span><span class="confirmInvalidMsg">The values don't match.</span></span></td>
</tr>
            <tr>
              <td height="30">&nbsp;</td>
              <td height="30">&nbsp;</td>
            </tr>
            <tr>
              <td height="30" colspan="2" align="center"><input type="submit" name="registerButton" id="registerButton" value="Register" /></td>
            </tr>
          </table>
          <input type="hidden" name="MM_insert" value="registerUserForm" />
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
        <p>&nbsp;</p>
        <input type="hidden" name="MM_insert" value="addClientForm" />
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
var sprytextfield4 = new Spry.Widget.ValidationTextField("sprytextfield4");
var sprytextfield3 = new Spry.Widget.ValidationTextField("sprytextfield3");
var sprytextfield1 = new Spry.Widget.ValidationTextField("sprytextfield1");
var sprytextfield2 = new Spry.Widget.ValidationTextField("sprytextfield2");
var sprytextfield8 = new Spry.Widget.ValidationTextField("sprytextfield8");
var sprytextfield9 = new Spry.Widget.ValidationTextField("sprytextfield9");
var sprytextfield11 = new Spry.Widget.ValidationTextField("sprytextfield11");
</script>
</body>
</html>
<?php
mysql_free_result($users);

mysql_free_result($registerClient);

//file properties
$file = $_FILES['finAgreement']['tmp_name'];

if (!isset($file))
	echo "Please select Financial Agreement form";
	else
	{
		$image = addslashes(file_getcontents($_FILES['finAagreement']['tmp_name']));
		$image_name = $_FILES['finAgreement']['name'];
		$image_size = getimagesize($_FILES['finAagreement']['tmp_name']);
	
		if ($image_size==FALSE)
			echo "Please upload a png file,";
			else
			{
				}
			
	}
		
?>
