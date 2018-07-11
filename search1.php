<?php
session_start();
if($_GET['i']=="following")
{
$_SESSION['ds']=0;
}
else{
$_SESSION['ds']=1;
}
echo $_SESSION['ds'];
?>