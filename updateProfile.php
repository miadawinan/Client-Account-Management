<?php @session_start(); ?>
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

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "updateForm")) {
  $updateSQL = sprintf("UPDATE Users SET firstName=%s, lastName=%s, contactNumber=%s, email=%s WHERE userID=%s",
                       GetSQLValueString($_POST['fristName'], "text"),
                       GetSQLValueString($_POST['lastName'], "text"),
                       GetSQLValueString($_POST['contactNumber'], "int"),
                       GetSQLValueString($_POST['email'], "text"),
                       GetSQLValueString($_POST['userID'], "int"));

  mysql_select_db($database_MAMP, $MAMP);
  $Result1 = mysql_query($updateSQL, $MAMP) or die(mysql_error());

  $updateGoTo = "accountClient.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $updateGoTo));
}

$colname_updateProfile = "-1";
if (isset($_SESSION['MM_Username'])) {
  $colname_updateProfile = $_SESSION['MM_Username'];
}
mysql_select_db($database_MAMP, $MAMP);
$query_updateProfile = sprintf("SELECT * FROM Users WHERE userName = %s", GetSQLValueString($colname_updateProfile, "text"));
$updateProfile = mysql_query($query_updateProfile, $MAMP) or die(mysql_error());
$row_updateProfile = mysql_fetch_assoc($updateProfile);
$totalRows_updateProfile = mysql_num_rows($updateProfile);
 
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
<title>UpdateProfile</title>
<script src="SpryAssets/SpryValidationTextField.js" type="text/javascript"></script>
</head>

<body>
<div id="holder">
<div id="header"></div>
<div id="navBar">
	<nav>
    	<ul>
        	<li><a href="account.php">My Profile</a>        	</li>
        	<li><a href="logout.php">Logout</a></li>
            <li></li>
        </ul>
    </nav>
    </div>
<div id="content">
	<div id="pageHeading">
	  <h1>Update Account</h1>
	</div>
    <div id="contentLeft">
      <h3>Account Links </h3>
      <p><a href="updateProfile.php">Update Profile</a></p>
      <p><a href="bankDetails.php">Bank Details</a></p>
      <p><a href="wallet.php">Wallet Transactions</a></p>
      <p><a href="changePassword.php">Change Password</a></p>
    </div>
    <div id="contentRight">
      <form action="<?php echo $editFormAction; ?>" id="updateForm" name="updateForm" method="POST">
        <table width="400" border="0" align="center">
          <tr>          </tr>
          <tr>          </tr>
        </table>
        <table width="579" height="223" border="0" align="center">
          <tr>
            <td colspan="2">User ID: <?php echo $row_updateProfile['userID']; ?> | Username: <?php echo $row_updateProfile['userName']; ?></td>
          </tr>
          <tr>
            <td width="139">First Name:</td>
            <td width="430"><span id="sprytextfield1">
              <label for="fristName"></label>
              <input name="fristName" type="text" id="fristName" value="<?php echo $row_updateProfile['firstName']; ?>" />
            <span class="textfieldRequiredMsg">A value is required.</span></span></td>
          </tr>
          <tr>
            <td>Last Name</td>
            <td><span id="sprytextfield2">
              <label for="lastName"></label>
              <input name="lastName" type="text" id="lastName" value="<?php echo $row_updateProfile['lastName']; ?>" />
            <span class="textfieldRequiredMsg">A value is required.</span></span></td>
          </tr>
          <tr>
            <td>Contact Number:</td>
            <td><span id="sprytextfield3">
              <label for="contactNumber"></label>
              <input name="contactNumber" type="text" id="contactNumber" value="<?php echo $row_updateProfile['contactNumber']; ?>" />
            <span class="textfieldRequiredMsg">A value is required.</span></span></td>
          </tr>
          <tr>
            <td>Email:</td>
            <td><span id="sprytextfield4">
              <label for="email"></label>
              <input name="email" type="text" id="email" value="<?php echo $row_updateProfile['email']; ?>" />
            <span class="textfieldRequiredMsg">A value is required.</span></span></td>
          </tr>
          <tr>
            <td>Service Address:</td>
            <td><?php echo $row_updateProfile['serviceAddress']; ?></td>
          </tr>
          <tr>
            <td><input name="userID" type="hidden" id="userID" value="<?php echo $row_updateProfile['userID']; ?>" /></td>
            <td><input type="submit" name="updateButton" id="updateButton" value="Update" /></td>
          </tr>
        </table>
        <p>&nbsp;</p>
        <input type="hidden" name="MM_update" value="updateForm" />
      </form>
    </div>
  </div>
<div id="footer"></div>
</div>
<script type="text/javascript">
var sprytextfield1 = new Spry.Widget.ValidationTextField("sprytextfield1");
var sprytextfield2 = new Spry.Widget.ValidationTextField("sprytextfield2");
var sprytextfield4 = new Spry.Widget.ValidationTextField("sprytextfield4");
var sprytextfield3 = new Spry.Widget.ValidationTextField("sprytextfield3");
</script>
</body>
</html>
<?php
mysql_free_result($updateProfile);
?>
