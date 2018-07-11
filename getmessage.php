<?php
include('config.php');
$id = $_SESSION['userid'];
if(!$id)
{
	$url = "index.php";
	header("Location:$url");
}

if(isset($_GET['user']))
{
	try
	{
		$db = getdb();
		$query = $db->prepare("SELECT message,userid FRom chat where (userid=:u or userid=:i) and (senderid=:i or senderid=:u) and msgtime > :t order by msgtime");
		$query->bindParam(':i',$id);
		$query->bindParam(':u',$_GET['user']);
		$query->bindParam(':t',$_GET['time']);
		$query->execute();
		$count = 20;
		$xml = new SimpleXMLElement("<xml/>");
		while($count-->0 && $row= $query->fetch())
		{
			if($row['userid']==$id)
			{
				$msg = $xml->addChild('message');
				$msg->addChild('type',"receive");
				$msg->addChild('text',$row['message']);
			}
			else
			{
				$msg = $xml->addChild('message');
				$msg->addChild('type',"send");
				$msg->addChild('text',$row['message']);
			}
		}
		Header('Content-type: text/xml');
		print($xml->asXML()); 
	}
	catch(Exception $e)
	{
		echo $e->getMessage();
	}
}
?>