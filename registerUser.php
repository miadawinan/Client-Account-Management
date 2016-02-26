<?php require_once('Connections/localhost.php'); ?>
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

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "registerUserForm")) {
  $insertSQL = sprintf("INSERT INTO Users (FirstName, LastName, NHInumber, ServiceAddress, Phone, Email, Password, `Role`) VALUES (%s, %s, %s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['firstName'], "text"),
                       GetSQLValueString($_POST['lastName'], "text"),
                       GetSQLValueString($_POST['nhiNumber'], "text"),
                       GetSQLValueString($_POST['serviceAddress'], "text"),
                       GetSQLValueString($_POST['phone'], "int"),
                       GetSQLValueString($_POST['email'], "text"),
                       GetSQLValueString($_POST['password'], "text"),
                       GetSQLValueString($_POST['roleMenu'], "text"));

  mysql_select_db($database_localhost, $localhost);
  $Result1 = mysql_query($insertSQL, $localhost) or die(mysql_error());
}

mysql_select_db($database_localhost, $localhost);
$query_registerUser = "SELECT * FROM Users";
$registerUser = mysql_query($query_registerUser, $localhost) or die(mysql_error());
$row_registerUser = mysql_fetch_assoc($registerUser);
$totalRows_registerUser = mysql_num_rows($registerUser);
$query_registerUser = "SELECT * FROM Users";
$registerUser = mysql_query($query_registerUser, $localhost) or die(mysql_error());
$row_registerUser = mysql_fetch_assoc($registerUser);
$totalRows_registerUser = mysql_num_rows($registerUser);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Untitled Document</title>
<style type="text/css">
.accountBar {	background-color: #15B2FF;
}
.accountBar {	background-color: #15B2FF;
}
.homeMenu {color: #036;
}
.homeMenu {	font-weight: bold;
}
.homeMenu1 {color: #036;
}
.user1 {text-decoration: none; color: #000;}
.user2 {text-decoration: none; color: #000;}
.user2 {float: right;
}
</style>
<link href="SpryAssets/SpryMenuBarVertical.css" rel="stylesheet" type="text/css" />
<link href="SpryAssets/SpryValidationTextField.css" rel="stylesheet" type="text/css" />
<script src="SpryAssets/SpryMenuBar.js" type="text/javascript"></script>
<script src="SpryAssets/SpryValidationTextField.js" type="text/javascript"></script>
</head>

<body>
<table width="800" border="0">
  <tr>
    <td colspan="4"><img src="NZCarelogo.png" alt="NZcare logo" width="95" height="125" /></td>
    <td><span class="user1"> </span><span class="user2"><?php echo $row_registerUser['Role']; ?> <?php echo $row_registerUser['FirstName']; ?> | <a href="logout.php">Logout</a></span><a href="logout.php"></a></td>
  </tr>
  <tr>
    <td colspan="5" class="accountBar">&nbsp;</td>
  </tr>
  <tr>
    <td width="55" height="23" class="homeMenu"><h3><a href="home.php" class="homeMenu"><strong>Home</strong></a></h3></td>
    <td width="85" class="homeMenu"><h3><a href="accountAdminPage.php" class="homeMenu"><strong>Accounts</strong></a></h3></td>
    <td width="105" class="homeMenu"><h3><a href="transactions.php" class="homeMenu"><strong>Transactions</strong></a></h3></td>
    <td width="273">&nbsp;</td>
    <td width="260">&nbsp;</td>
  </tr>
  <tr>
    <td colspan="5"><table width="600" border="0">
        <tr>
          <td width="176" height="64" class="homeMenu1"><ul id="MenuBar1" class="MenuBarVertical">
            <li><a class="homeMenu1" href="registerUser.php">Register User</a></li>
            <li><a href="manageAccounts.php" class="homeMenu1">Manage User Accounts</a></li>
          </ul></td>
          <td width="414" class="homeMenu1"><h2>Register User</h2></td>
        </tr>
    </table></td>
  </tr>
</table>
</p>
<form id="registerUserForm" name="registerUserForm" method="POST" action="<?php echo $editFormAction; ?>">
  <table width="802" border="0">
    <tr>
      <td width="180">&nbsp;</td>
      <td>Role:</td>
      <td width="490"><label for="roleMenu"></label>
        <select name="roleMenu" id="roleMenu">
          <option value="Client" selected="selected">Client</option>
          <option value="Support Worker">Support Worker</option>
          <option value="Branch Administrator">Branch Administrator</option>
          <option value="Area Manager">Area Manager</option>
          <option value="Service Manager">Service Manager</option>
          <option value="Financial Manager">Financial Manager</option>
          <option value="Auditor">Auditor</option>
          <option value="Administrator">Administrator</option>
      </select></td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>First Name:</td>
      <td><span id="sprytextfield1">
        <label for="firstName"></label>
        <input type="text" name="firstName" id="firstName" />
      <span class="textfieldRequiredMsg">A value is required.</span></span></td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td width="118">Last Name:</td>
      <td><span id="sprytextfield2">
        <label for="lastName"></label>
        <input type="text" name="lastName" id="lastName" />
      <span class="textfieldRequiredMsg">A value is required.</span></span></td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>NHI Number:</td>
      <td><span id="sprytextfield3">
        <label for="nhiNumber"></label>
        <input type="text" name="nhiNumber" id="nhiNumber" />
      <span class="textfieldRequiredMsg">A value is required.</span></span></td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>Phone Number:</td>
      <td><span id="sprytextfield4">
        <label for="phone"></label>
        <input type="text" name="phone" id="phone" />
      <span class="textfieldRequiredMsg">A value is required.</span></span></td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>Service Address:</td>
      <td><span id="sprytextfield5">
        <label for="serviceAddress"></label>
        <input type="text" name="serviceAddress" id="serviceAddress" />
      <span class="textfieldRequiredMsg">A value is required.</span></span></td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>Email:</td>
      <td><span id="sprytextfield6">
        <label for="email"></label>
        <input type="text" name="email" id="email" />
      <span class="textfieldRequiredMsg">A value is required.</span></span></td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>Username:</td>
      <td><span id="sprytextfield7">
        <label for="email"></label>
        <input type="text" name="email2" id="email" />
      <span class="textfieldRequiredMsg">A value is required.</span></span></td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>Password:</td>
      <td><span id="sprytextfield8">
        <label for="password"></label>
        <input type="text" name="password" id="password" />
      <span class="textfieldRequiredMsg">A value is required.</span></span></td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td><input type="submit" name="Register" id="Register" value="Register" /></td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
  </table>
  <input type="hidden" name="MM_insert" value="registerUserForm" />
</form>
<script type="text/javascript">
var MenuBar1 = new Spry.Widget.MenuBar("MenuBar1", {imgRight:"SpryAssets/SpryMenuBarRightHover.gif"});
var sprytextfield1 = new Spry.Widget.ValidationTextField("sprytextfield1");
var sprytextfield2 = new Spry.Widget.ValidationTextField("sprytextfield2");
var sprytextfield3 = new Spry.Widget.ValidationTextField("sprytextfield3");
var sprytextfield4 = new Spry.Widget.ValidationTextField("sprytextfield4");
var sprytextfield5 = new Spry.Widget.ValidationTextField("sprytextfield5");
var sprytextfield6 = new Spry.Widget.ValidationTextField("sprytextfield6");
var sprytextfield7 = new Spry.Widget.ValidationTextField("sprytextfield7");
var sprytextfield8 = new Spry.Widget.ValidationTextField("sprytextfield8");
</script>
</body>
</html>
<?php
mysql_free_result($registerUser);
?>
