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

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "updateForm")) {
  $updateSQL = sprintf("UPDATE Clients SET FirstName=%s, LastName=%s, Gender=%s, AssignedSW=%s, ServiceID=%s, ServiceArea=%s, PhoneNumber=%s, Email=%s, EmergencyContactName=%s, EmergencyContactAdd=%s, EmergencyContactNo=%s WHERE NHINumber=%s",
                       GetSQLValueString($_POST['firstName'], "text"),
                       GetSQLValueString($_POST['lastName'], "text"),
                       GetSQLValueString($_POST['genderOption'], "text"),
                       GetSQLValueString($_POST['SWMenu'], "text"),
                       GetSQLValueString($_POST['serviceMenu'], "int"),
                       GetSQLValueString($_POST['areaMenu'], "text"),
                       GetSQLValueString($_POST['contactNumber'], "int"),
                       GetSQLValueString($_POST['email'], "text"),
                       GetSQLValueString($_POST['emergencyContactName'], "text"),
                       GetSQLValueString($_POST['emgncyContactAdd'], "text"),
                       GetSQLValueString($_POST['emergencyContactNumber'], "int"),
                       GetSQLValueString($_POST['clientID'], "text"));

  mysql_select_db($database_MAMP, $MAMP);
  $Result1 = mysql_query($updateSQL, $MAMP) or die(mysql_error());

  $updateGoTo = "adminClientUpdateDetails.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $updateGoTo));
}

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "updateForm")) {
  $updateSQL = sprintf("UPDATE Users SET firstName=%s, lastName=%s, contactNumber=%s, serviceID=%s, email=%s WHERE userName=%s",
                       GetSQLValueString($_POST['firstName'], "text"),
                       GetSQLValueString($_POST['lastName'], "text"),
                       GetSQLValueString($_POST['contactNumber'], "int"),
                       GetSQLValueString($_POST['serviceMenu'], "int"),
                       GetSQLValueString($_POST['email'], "text"),
                       GetSQLValueString($_POST['clientID'], "text"));

  mysql_select_db($database_MAMP, $MAMP);
  $Result1 = mysql_query($updateSQL, $MAMP) or die(mysql_error());

  $updateGoTo = "adminClientUpdateDetailList.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $updateGoTo));
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

$colname_qClientDetails = "-1";
if (isset($_GET['NHINumber'])) {
  $colname_qClientDetails = $_GET['NHINumber'];
}
mysql_select_db($database_MAMP, $MAMP);
$query_qClientDetails = sprintf("SELECT * FROM Clients WHERE NHINumber = %s", GetSQLValueString($colname_qClientDetails, "text"));
$qClientDetails = mysql_query($query_qClientDetails, $MAMP) or die(mysql_error());
$row_qClientDetails = mysql_fetch_assoc($qClientDetails);
$totalRows_qClientDetails = mysql_num_rows($qClientDetails);

$colname_qUser = "-1";
if (isset($_GET['userName'])) {
  $colname_qUser = $_GET['userName'];
}
mysql_select_db($database_MAMP, $MAMP);
$query_qUser = sprintf("SELECT * FROM Users WHERE userName = %s", GetSQLValueString($colname_qUser, "text"));
$qUser = mysql_query($query_qUser, $MAMP) or die(mysql_error());
$row_qUser = mysql_fetch_assoc($qUser);
$totalRows_qUser = mysql_num_rows($qUser);

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
<link href="SpryAssets/SpryValidationTextField.css" rel="stylesheet" type="text/css" />
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Update Client Details</title>
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
	  <h1>Update Client Details</h1>
	</div>
    <div id="contentLeft">
      <h3>Links      </h3>
      <p><a href="adminClientList.php">Client List</a></p>
      <p><a href="adminRegisterClient.php">Register Client</a>      </p>
      <p><a href="adminClientUpdate.php">Update Client</a></p>
      <p><a href="adminDeleteClient.php">Delete Client</a></p>
      <p>&nbsp;</p>
      <p>&nbsp;</p>
    </div>
    <div id="contentRight">
      <form action="<?php echo $editFormAction; ?>" method="POST" enctype="multipart/form-data" name="updateForm" id="updateForm">
        <table width="400" border="0" align="center">
          <tr>          </tr>
        <tr>          </tr>
      </table>
        <div id="contentRight2">
          <table width="600" height="429" border="0" align="center">
            <tr>
              <td width="213" height="30">First Name:</td>
              <td width="377" height="30"><span id="sprytextfield1">
                <label for="firstName"></label>
                <input name="firstName" type="text" id="firstName" value="<?php echo $row_qClientDetails['FirstName']; ?>" />
                <span class="textfieldRequiredMsg">A value is required.</span></span></td>
</tr>
            <tr>
              <td height="30">Last Name:</td>
              <td height="30"><span id="sprytextfield2">
                <label for="lastName"></label>
                <input name="lastName" type="text" id="lastName" value="<?php echo $row_qClientDetails['LastName']; ?>" />
                <span class="textfieldRequiredMsg">A value is required.</span></span></td>
</tr>
            <tr>
              <td height="30">NHI Number:</td>
              <td height="30"><?php echo $row_qClientDetails['NHINumber']; ?></td>
</tr>
            <tr>
              <td height="30">Gender:</td>
              <td height="30"><label for="genderOption"></label>
                <select name="genderOption" id="genderOption">
                  <option value="Male" <?php if (!(strcmp("Male", $row_qClientDetails['Gender']))) {echo "selected=\"selected\"";} ?>>Male</option>
                  <option value="Female" <?php if (!(strcmp("Female", $row_qClientDetails['Gender']))) {echo "selected=\"selected\"";} ?>>Female</option>
                  <?php
do {  
?>
                  <option value="<?php echo $row_qClientDetails['Gender']?>"<?php if (!(strcmp($row_qClientDetails['Gender'], $row_qClientDetails['Gender']))) {echo "selected=\"selected\"";} ?>><?php echo $row_qClientDetails['Gender']?></option>
<?php
} while ($row_qClientDetails = mysql_fetch_assoc($qClientDetails));
  $rows = mysql_num_rows($qClientDetails);
  if($rows > 0) {
      mysql_data_seek($qClientDetails, 0);
	  $row_qClientDetails = mysql_fetch_assoc($qClientDetails);
  }
?>
                </select></td>
            </tr>
            <tr>
              <td height="30">Contact Number:</td>
              <td height="30"><span id="sprytextfield4">
                <label for="contactNumber"></label>
                <input name="contactNumber" type="text" id="contactNumber" value="<?php echo $row_qClientDetails['PhoneNumber']; ?>" />
                <span class="textfieldRequiredMsg">A value is required.</span></span></td>
</tr>
            <tr>
              <td height="30">Assign Support Worker:</td>
              <td height="30"><label for="SWMenu"></label>
                <select name="SWMenu" id="SWMenu">
                  <option value="Support1" <?php if (!(strcmp("Support1", $row_qClientDetails['AssignedSW']))) {echo "selected=\"selected\"";} ?>>Support1</option>
                  <option value="Support2" <?php if (!(strcmp("Support2", $row_qClientDetails['AssignedSW']))) {echo "selected=\"selected\"";} ?>>Support2</option>
                  <?php
do {  
?>
                  <option value="<?php echo $row_qClientDetails['AssignedSW']?>"<?php if (!(strcmp($row_qClientDetails['AssignedSW'], $row_qClientDetails['AssignedSW']))) {echo "selected=\"selected\"";} ?>><?php echo $row_qClientDetails['AssignedSW']?></option>
                  <?php
} while ($row_qClientDetails = mysql_fetch_assoc($qClientDetails));
  $rows = mysql_num_rows($qClientDetails);
  if($rows > 0) {
      mysql_data_seek($qClientDetails, 0);
	  $row_qClientDetails = mysql_fetch_assoc($qClientDetails);
  }
?>
                </select></td>
            </tr>
            <tr>
              <td height="30">ServiceID:</td>
              <td height="30"><select name="serviceMenu" size="1" id="serviceMenu" title="<?php echo $row_qClientDetails['ServiceID']; ?>">
                <option value="1111" <?php if (!(strcmp(1111, $row_qClientDetails['ServiceID']))) {echo "selected=\"selected\"";} ?>>1111</option>
                <option value="1234" <?php if (!(strcmp(1234, $row_qClientDetails['ServiceID']))) {echo "selected=\"selected\"";} ?>>1234</option>
                <option value="0987" <?php if (!(strcmp(0987, $row_qClientDetails['ServiceID']))) {echo "selected=\"selected\"";} ?>>0987</option>
                <?php
do {  
?>
                <option value="<?php echo $row_qClientDetails['ServiceID']?>"<?php if (!(strcmp($row_qClientDetails['ServiceID'], $row_qClientDetails['ServiceID']))) {echo "selected=\"selected\"";} ?>><?php echo $row_qClientDetails['ServiceID']?></option>
<?php
} while ($row_qClientDetails = mysql_fetch_assoc($qClientDetails));
  $rows = mysql_num_rows($qClientDetails);
  if($rows > 0) {
      mysql_data_seek($qClientDetails, 0);
	  $row_qClientDetails = mysql_fetch_assoc($qClientDetails);
  }
?>
              </select></td>
            </tr>
            <tr>
              <td height="30">Service Area:</td>
              <td height="30"><label for="areaMenu"></label>
                <select name="areaMenu" id="areaMenu">
                  <option value="Area1" <?php if (!(strcmp("Area1", $row_qClientDetails['ServiceArea']))) {echo "selected=\"selected\"";} ?>>Area1</option>
                  <option value="Area2" <?php if (!(strcmp("Area2", $row_qClientDetails['ServiceArea']))) {echo "selected=\"selected\"";} ?>>Area2</option>
                  <option value="Area3" <?php if (!(strcmp("Area3", $row_qClientDetails['ServiceArea']))) {echo "selected=\"selected\"";} ?>>Area3</option>
                  <?php
do {  
?>
                  <option value="<?php echo $row_qClientDetails['ServiceArea']?>"<?php if (!(strcmp($row_qClientDetails['ServiceArea'], $row_qClientDetails['ServiceArea']))) {echo "selected=\"selected\"";} ?>><?php echo $row_qClientDetails['ServiceArea']?></option>
                  <?php
} while ($row_qClientDetails = mysql_fetch_assoc($qClientDetails));
  $rows = mysql_num_rows($qClientDetails);
  if($rows > 0) {
      mysql_data_seek($qClientDetails, 0);
	  $row_qClientDetails = mysql_fetch_assoc($qClientDetails);
  }
?>
                </select></td>
            </tr>
            <tr>
              <td height="30">Email:</td>
              <td height="30"><span id="sprytextfield6">
              <label for="email"></label>
              <input name="email" type="text" id="email" value="<?php echo $row_qClientDetails['Email']; ?>" />
              <span class="textfieldRequiredMsg">A value is required.</span><span class="textfieldInvalidFormatMsg">Invalid format.</span></span></td>
</tr>
            <tr>
              <td height="30">Emergency Contact Name:</td>
              <td height="30"><span id="sprytextfield8">
                <label for="emergencyContactName"></label>
                <input name="emergencyContactName" type="text" id="emergencyContactName" value="<?php echo $row_qClientDetails['EmergencyContactName']; ?>" />
              <span class="textfieldRequiredMsg">A value is required.</span></span></td>
            </tr>
            <tr>
              <td height="30">Emergency Contact Number:</td>
              <td height="30"><span id="sprytextfield9">
                <label for="emergencyContactNumber"></label>
                <input name="emergencyContactNumber" type="text" id="emergencyContactNumber" value="<?php echo $row_qClientDetails['EmergencyContactNo']; ?>" />
              <span class="textfieldRequiredMsg">A value is required.</span></span></td>
            </tr>
            <tr>
              <td height="30">Emergency Contact Address:</td>
              <td height="30"><span id="sprytextfield11">
                <label for="emgncyContactAdd"></label>
                <input name="emgncyContactAdd" type="text" id="emgncyContactAdd" value="<?php echo $row_qClientDetails['EmergencyContactAdd']; ?>" />
              <span class="textfieldRequiredMsg">A value is required.</span></span></td>
            </tr>
            <tr>
              <td height="30"><input name="clientID" type="hidden" id="clientID" value="<?php echo $row_qClientDetails['NHINumber']; ?>" /></td>
              <td height="30">&nbsp;</td>
            </tr>
            <tr>
              <td height="30" colspan="2" align="center"><input type="submit" name="updateButton" id="updateButton" value="Update" /></td>
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
        <input type="hidden" name="MM_update" value="updateForm" />
      </form>
    </div>
  </div>
<div id="footer"></div>
</div>
<script type="text/javascript">
var sprytextfield6 = new Spry.Widget.ValidationTextField("sprytextfield6", "email");
var sprytextfield4 = new Spry.Widget.ValidationTextField("sprytextfield4");
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

mysql_free_result($qClientDetails);

mysql_free_result($qUser);

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
