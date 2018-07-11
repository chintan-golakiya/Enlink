<?php
include('config.php');
$id = $_SESSION['userid'];
if(!$id)
{
	$url="index.php";
	header("Location:$url");
}
$fuser = $_GET['id'];
try{
$db = getdb();
$sql = "INSERT into friends(userid1,userid2) values ('$id','$fuser')";
$query = $db->prepare($sql);

$query->execute();

echo 'true';
}
catch(Exception $e)
{

}

?>