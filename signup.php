<?php
include('config.php');
include('authority.php');

$err='';$url='';$er='';
if(!empty($_POST['sign']))
{
$suser=trim($_POST['use']);
	$spass=trim($_POST['pass']);
	$firstname=trim($_POST['firstname']);
	$lastname=trim($_POST['lastname']);
    
if(strlen($suser)>1&&strlen($spass)>1&&strlen($firstname)>1&&strlen($lastname)>1)
{
    $db=getdb();
    $stmt = $db->prepare("SELECT username FROM user WHERE username=:userid");
				$stmt->bindParam(':userid',$suser);
				$stmt->execute();
				$count = $stmt->rowCount();
				$db=null;
				if($count)
				{
                  $err="This username already exists....";
				}
				 else
				 {
				 	$db=getdb();

               $stm = $db->query("INSERT INTO user(username,password,firstname,lastname) VALUES ('".$_POST['use']."','".$_POST['pass']."','".$_POST['firstname']."','".$_POST['lastname']."')");
  

   if($stm)
   {
    $url="index.php";
    header("Location:$url");
                  
    $db=null;
}}}
else
{
	$er="Enter valid details..";
}
                
 }
 
?>
<html>
<head>
	<title>Enlink</title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" type="text/css" href="public/css/style.css">
	<link rel="javascript" type="text/script" href="public/js/script.js">
</head>
<body class="log-sign">
	<img src="public/logo.png" class="logo">
	<div class="tab"></div>
	<div class="main">
		<fieldset >
				<form method="post" action="" name="authlogin">
					<h2 class="formtitle">Create a new Account...</h2>
					<div style="color: white"><?php echo $er.$err;?></div>
					<br>
					<input type="text" name="firstname" placeholder="firstname" required>
					<br>
					<input type="text" name="lastname" placeholder="lastname" required>
					<br>
					<input type="text" name="use" placeholder="Username" required>
					<br>
					<input type="password" name="pass" placeholder="Password" required><br>
						<input class="button" type="submit" name="sign" value="Create account" style="color: white">
					<h3 style="text-align: center;">
  <a href="index.php" style="text-decoration: none;color:black;" >Already Member? Login </a></h3>
				</form>
				</fieldset>
	</div>
</body>
</html>
