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

mysql_select_db($database_localhost, $localhost);
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
.homeMenu {	color: #036;
}
.user {text-decoration: none; color: #000;}
.user {	float: right;
}
.homeMenu1 {color: #036;
}
.homeMenu1 {font-weight: bold;
}
.homeMenu1 {color: #036;
}
.user1 {text-decoration: none; color: #000;}
.user2 {text-decoration: none; color: #000;}
.user2 {float: right;
}
</style>
<link href="SpryAssets/SpryMenuBarVertical.css" rel="stylesheet" type="text/css" />
<script src="SpryAssets/SpryMenuBar.js" type="text/javascript"></script>
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
    <td width="55" height="23" class="homeMenu1"><h3><a href="profile.php" class="homeMenu1"><strong>Home</strong></a></h3></td>
    <td width="85" class="homeMenu1"><h3><a href="registerUser.php" class="homeMenu1"><strong>Accounts</strong></a></h3></td>
    <td width="105" class="homeMenu1"><h3><a href="transactions.php" class="homeMenu1"><strong>Transactions</strong></a></h3></td>
    <td width="273">&nbsp;</td>
    <td width="260">&nbsp;</td>
  </tr>
  <tr>
    <td colspan="5"><table width="600" border="0">
      <tr>
        <td width="176" height="64" class="homeMenu1"><ul id="MenuBar1" class="MenuBarVertical">
          <li><a class="homeMenu" href="profile.php">Profile</a></li>
          <li><a href="updateProfileInfo.php" class="homeMenu">Update Profile</a></li>
        </ul></td>
        <td width="414" class="homeMenu1"><h2>Profile</h2></td>
      </tr>
</table></td>
</tr>
  <tr>
    <td colspan="5"><table width="600" border="0">
      <tr> </tr>
    </table></td>
  </tr>
</table>
<table width="801" border="0">
  <tr>
    <td width="182">&nbsp;</td>
    <td>UserID:</td>
    <td width="478"><?php echo $row_registerUser['UserID']; ?></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td width="127">Name:</td>
    <td><?php echo $row_registerUser['FirstName']; ?> <?php echo $row_registerUser['LastName']; ?></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>Contact Number:</td>
    <td><?php echo $row_registerUser['Phone']; ?></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>Email:</td>
    <td><?php echo $row_registerUser['Email']; ?></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>Service Address:</td>
    <td><?php echo $row_registerUser['ServiceAddress']; ?></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
</table>
<p>&nbsp;</p>
<form id="form1" name="form1" method="post" action="">
</form>
<p>&nbsp;</p>
<table width="800" border="0">
</table>
<table width="800" border="0">
  <tr> </tr>
</table>
<script type="text/javascript">
var MenuBar1 = new Spry.Widget.MenuBar("MenuBar1", {imgRight:"SpryAssets/SpryMenuBarRightHover.gif"});
</script>
</body>
</html>
<?php
mysql_free_result($registerUser);
?>
