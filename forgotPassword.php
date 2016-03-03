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
        	<li><a href="index.php">Login</a>        	</li>
        	<li>Forgot Password</li>
            <li></li>
        </ul>
    </nav>
    </div>
<div id="content">
	<div id="pageHeading">
	  <h1>Email Password</h1>
	</div>
	<div id="contentRight">
      <form id="emailPasswordForm" name="emailPasswordForm" method="post" action="emailPasswordScript.php">
        <p>
          <label for="email"></label>
          Email: 
          <input type="text" name="email" id="email" />
        </p>
        <p>
          <input type="submit" name="emailPasswordButton" id="emailPasswordButton" value="Email Password" />
        </p>
      </form>
    </div>
  </div>
<div id="footer"></div>
</div>
</body>
</html>
