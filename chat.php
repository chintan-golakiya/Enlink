<?php
include('config.php');
$id = $_SESSION['userid'];

if(!$id)
{
	$url="index.php";
	header("Location:$url");
}

?>

<!DOCTYPE html>
<html>
<head>
	<title>Enlink</title>

	<script type="text/javascript">

	</script>
</head>
<body>
	<input type="text" name="user" value="1" id="usr">
	<input type="text" name="message" id="msg" required>
	<input type="button" name="send" value="send" onclick="sendmsg();">
<div id="chats"></div>
<script type="text/javascript">
	lastupdatetime = '2018-06-25 09:00:00';
	getmessage();
	function sendmsg()
	{
		var sendajax = new XMLHttpRequest();
		var id = document.getElementById('usr').value;
		var message = document.getElementById('msg').value;
		var pst = "userid="+id+"&message="+message;
		sendajax.open('GET','messagesend.php?'+pst,true);

		sendajax.onreadystatechange = function(){
			if(sendajax.readyState==4 && sendajax.status ==200)
			{
				document.getElementById('r').innerHTML =sendajax.responseText;
			}
		}

		sendajax.send();
	}
				
	function getmessage()
	{
		console.log("works");
		var getajax = new XMLHttpRequest();
		var id = document.getElementById('usr').value;
		getajax.open('GET','getmessage.php?user='+id+'&time='+lastupdatetime,true);
console.log(lastupdatetime);
		getajax.onreadystatechange = function()
		{
			if(getajax.readyState==4 && getajax.status==200)
			{
				
				var xmlDoc = getajax.responseXML;
				var x = xmlDoc.getElementsByTagName("message");
				console.log(x.length);
				for(i=0;i<x.length;i++)
				{
				document.getElementById('chats').innerHTML=document.getElementById('chats').innerHTML+x[i].getElementsByTagName("type")[0].childNodes[0].nodeValue+" "+x[i].getElementsByTagName("text")[0].childNodes[0].nodeValue+"<br>";
				}
				var currentdate = new Date();
				lastupdatetime = currentdate.getFullYear() + "-"
                + (currentdate.getMonth()+1)  + "-" 
                + currentdate.getDate() + " "  
                + currentdate.getHours() + ":"  
                + currentdate.getMinutes() + ":" 
                + currentdate.getSeconds();
				console.log(lastupdatetime);
			}
		}

		getajax.send();
	}
	setInterval(getmessage , 2000);
</script>

<div id="r"></div>
</body>
</html>