<?php
include('config.php');
$id = $_GET['userid'];
$vid = $_SESSION['userid'];
if(!$id && !$vid)
{
	$url="index.php";
	header("Location:$url");
}

if(isset($_POST['comment']))
{
	try{
	$db = getdb();
		
	$sql = "INSERT into comments(userid,postid,txt) values(:az,:ac,:ax)";
	$query = $db->prepare($sql);
	$query->bindParam(':az',$vid);
	$query->bindParam(':ax',$_POST['commenttext']);
	$query->bindParam(':ac',$_POST['poid']);
	$query->execute();
}
catch(Exception $e)
{
	
}
}


// get data for this page to show
try
{
	$db =getdb();
	$smt = $db->prepare("SELECT * FROM user WHERE userid = :x");
	$smt->bindParam(":x",$vid);
	$smt->execute();
	$viewer = $smt->fetch(PDO::FETCH_BOTH);
	// now $data have all details of viewer
}
catch(PDOException $e)
{
	echo "We are sorry for trouble , please refresh page";
}try
{
	$db =getdb();
	$smt = $db->prepare("SELECT * FROM user WHERE userid = :x");
	$smt->bindParam(":x",$id);
	$smt->execute();
	$data = $smt->fetch(PDO::FETCH_BOTH);
	// now $data have all details of user
}
catch(PDOException $e)
{
	echo "We are sorry for trouble , please refresh page";
}
try
{
	$db =getdb();
	$smt = $db->prepare("SELECT * FROM user WHERE userid = :x");
	$smt->bindParam(":x",$id);
	$smt->execute();
	$data = $smt->fetch(PDO::FETCH_BOTH);
	// now $data have all details of user
}
catch(PDOException $e)
{
	echo "We are sorry for trouble , please refresh page";
}

try
{
	$db =getdb();
	$smt = $db->prepare("SELECT * FROM about WHERE userid = :x");
	$smt->bindParam(":x",$id);
	$smt->execute();
	$about = $smt->fetch(PDO::FETCH_BOTH);
	// now $about have all details of user-about
}
catch(PDOException $e)
{
	echo "We are sorry for trouble , please refresh page";
}


try
{
	$db =getdb();
	$smt = $db->prepare("SELECT count(userid2) FROM friends WHERE userid1 = :x");
	$smt->bindParam(":x",$id);
	$smt->execute();
	$followings = $smt->fetch(PDO::FETCH_BOTH);
	$smt = $db->prepare("SELECT count(userid1) FROM friends WHERE userid2 = :x");
	$smt->bindParam(":x",$id);
	$smt->execute();
	$followers = $smt->fetch(PDO::FETCH_BOTH);
	// now $about have all details of followers and follwings
}
catch(PDOException $e)
{
	echo "We are sorry for trouble , please refresh page";
}

try
{
	$db =getdb();
	$post = $db->prepare("SELECT * FROM post WHERE userid = :x order by posttime DESC");
	$post->bindParam(":x",$id);
	$post->execute();
	$countpost = $post->rowCount();	
	// no of post of the user;
	// $post have post data need to fetch 
	}
catch(PDOException $e)
{
	echo "We are sorry for trouble , please refresh page";
}
?>
<html>
<head>
	<title>Enlink</title>
	<meta name="viewport" content="width=device-width,initial-scale=1.0">
	<link rel="stylesheet" type="text/css" href="public/css/profilestyle.css">
	<link rel="javascript" type="text/javascript" href="public/js/script.js">
	<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

	<style type="text/css">
        .postpart1
        {
            width:98.63%;
            padding-right: 17px;
            height:100%;
            overflow-y: hidden;
            overflow-x: hidden;
            box-sizing: content-box;
        }
        .postpart2
        {
            width:100%;
            padding-right: 17px;
            height:100%;
            overflow-y: scroll;
            overflow-x: hidden;
            box-sizing: content-box;
        }
    </style>
</head>
<body>
	<div class="header" id="header">
		<img src="public/logo.png" class="" width="120px" height="40px">
		<div style="float: right;">
			<img src="public/<?php echo $viewer['profilepic']?>" width="40px" height="40px" style="border-radius: 18px;">
			<div class="usernametop">	
			<?php
				echo $viewer['username'];
			?>
			</div>
		</div>
	</div>

	<div class="banner">
	<ul class="menu" id="menu">
	<li class="tab active">Profile</li>
	<a href="feed.php"><li class="tab">News Feed</li></a>
	<a href=""><li class="tab">Friends</li></a>
	<a href="peoplesearch.php"><li class="tab">Search</li></a>
	<a href="Logout.php"><li class="tab">Log out</li></a>
	</ul>
	<img src="public/<?php echo $data['coverpic']?>" class="bannerimg">
	</div>


<div class="main_content" id="main" style="height: 600px">

<div class="sidebar">
	<div class="mainprofilepic" id="mainprofilepic">

	<img src="public/<?php echo $data['profilepic'];?>" width="100%" height="100%">

	</div>
	<hr class="lineafterpic" id="line">
	<div style="font-family: helvetica; color:#cb6318;text-align: center;font-size: 1.1em">
		<?php echo $data['firstname']." ".$data['lastname']?>
	</div>

	<hr class="lineafterpic" style="margin-top:5px">

	<div class="followdetails">
		<div>Followers <span style="float: right"><?php echo $followers[0]; ?></span></div>
		<div>Followings <span style="float: right"><?php echo $followings[0]; ?></span></div>		
		<div>Posts <span style="float: right"><?php echo $countpost; ?></span></div>

	</div>
	
	<div class="aboutdetails">
	<div><i class="fa fa-birthday-cake" style="margin-right: 10px" aria-hidden="true"></i>
	<?php echo $about['birthdate']?></div>
	<div><i class="fa fa-home" style="margin-right: 10px" aria-hidden="true"></i>
 	<?php echo $about['city']?>, <?php echo $about['state']?>.
	</div>
	<div><i class="fa fa-briefcase" style="margin-right: 10px" aria-hidden="true"></i>
	<?php echo $about['profession'];?></div>
	<div><i class="fa fa-envelope" style="margin-right: 10px" aria-hidden="true"></i>
	<?php echo $about['email']?></div>
	</div>

	
</div>

<div class="postpart"  style="overflow:hidden;width: 60%">
<div class="postpart1" id="postscroll">
<?php



while($countpost>0)
{ 
$postdetail = $post->fetch();

try
{
	$db =getdb();
	$smt = $db->prepare("SELECT * FROM user WHERE userid = :x");
	$smt->bindParam(":x",$postdetail['userid']);
	$smt->execute();
	$postusername = $smt->fetch(PDO::FETCH_BOTH);

	$db =getdb();
	$smt = $db->prepare("SELECT * FROM likes WHERE postid = :x");
	$smt->bindParam(":x",$postdetail['postid']);
	$smt->execute();
	$nooflike = $smt->rowCount();

	$db =getdb();
	$cmt = $db->prepare("SELECT username,profilepic,txt,commenttime from user natural join comments WHERE postid = :x order by commenttime DESC");
	$cmt->bindParam(":x",$postdetail['postid']);
	$cmt->execute();
	$noofcomment = $cmt->rowCount();

	$db = getdb();
	$smt = $db->prepare("SELECT * from likes WHERE postid=:lp and userid=:lu");
	$smt->bindParam(':lp',$postdetail['postid']);
	$smt->bindParam(':lu',$vid);
	$smt->execute();
	$ifliked = $smt->rowCount(); 
}
catch(PDOException $e)
{
	echo "We are sorry for trouble , please refresh page 1";
}
?>
	<div class="postdiv" >
		<div class="postheader">
			<img src="public/<?php echo $postusername['profilepic']?>" width="40px" height="40px" style="border-radius: 18px">
			<div class="postuserdetail">	
			<?php
				echo $postusername['username'];
			?><br>
			<span style="font-size: 0.8em"><?php echo $postdetail['posttime']?></span>
			</div>
		</div>
		<div class="postcontent">
			<p> <?php echo $postdetail['txt'];?></p>
			<?php if($postdetail['postpic']) {?>
			<img src="public/<?php echo $postdetail['postpic']; ?>">
			<?php }?>
		</div>
		<hr style="width: 100%;color: green;border-top:1px solid green">
		<ul class="postinsights">
			<li><i class="fa fa-thumbs-o-up" onclick="like(this);" id="like<?php echo $postdetail['postid'];?>"></i><span id="text<?php echo $postdetail['postid'];?>"><?php echo $nooflike; ?></span></li>
			<li><i class="fa fa-comments-o" id="commenti<?php echo $postdetail['postid'];?>" onclick="opencomments(this);"></i><?php echo $noofcomment;?></li>
		</ul>
		<div class="postcomments" id="comment<?php echo $postdetail['postid'];?>">
		<?php while($noofcomment){
			$commentrow = $cmt->fetch();
		?>
		<div class="cmt" style="margin:20px 0px">
		<img src="public/<?php echo $commentrow['profilepic']?>" width="40px" height="40px" style="border-radius: 18px;">
		<div class="postuserdetail">	
			<?php
				echo $commentrow['username'];
			?><br>
			<span style="font-size: 0.8em"><?php echo $commentrow['txt']?></span>
			</div>
		</div>

		<?php
		$noofcomment--; 
		}?>
		</div>
		<div class="newcomment">
			<img src="public/<?php echo $viewer['profilepic']?>" width="40px" height="40px" style="border-radius: 18px">
			<form method="post" action="Profile.php">
				<input type="number" name="poid" style="display: none;" value="<?php echo $postdetail['postid'];?>">
				<input type="text" name="commenttext" placeholder="Post a comment...." required>
				<input type="submit" name="comment" value="comment" style="color: white;
				background-color: green;">
			</form>
		</div>
	</div>

<?php

if($ifliked>0)
		{
			?>
			<script type="text/javascript">
				document.getElementById('like<?php echo $postdetail['postid'];?>').classList.remove('fa-thumbs-o-up');
				document.getElementById('like<?php echo $postdetail['postid'];?>').classList.add('fa-thumbs-up');
			</script>

			<?php
		}
	$countpost--;
}?>

</div>
</div>
<div class="sidebar" style="overflow: hidden;">
<div class="postpart2" id="sidescroll">
    <div class="followsuggestion">Suggestion to follow</div>
    <?php

$r=$_SESSION['userid'];
try{    
$db=getdb();
$stts=$db->prepare("SELECT * FROM user WHERE userid!='$r' and userid not in (SELECT userid2 FROM friends WHERE userid1='$r')");
$stts->execute();


}
catch(Exception $e)
{
    echo $e;
}

while($row=$stts->fetch(PDO::FETCH_BOTH))
{

    ?>

    <div class="postheader" style="margin:15px 10px">
            <img src="public/<?php echo $row['profilepic']?>" width="40px" height="40px" style="border-radius: 18px">
            <div class="postuserdetail" style="color: green;font-size: 1em;">    
            <?php
                echo $row['username'];
            ?><br>
            <div class="followbtn" id="follow<?php echo $row['userid']?>" onclick="follow(this);">Follow</div>
            </div>
            <hr style="border-top:1px solid lightgreen;">
        </div>
<?php
}


?>
</div>
</div>
</div>




<script type="text/javascript">

 	window.onscroll = function()
 	{
		if(window.pageYOffset>=186)
    	{
        	document.getElementById('menu').classList.remove('menu');
        	document.getElementById('menu').classList.add('menustick');
        	document.getElementById('postscroll').classList.add('postpart2');
	        document.getElementById('postscroll').classList.remove('postpart1');
	        document.getElementById('mainprofilepic').classList.remove('mainprofilepic');
	        document.getElementById('mainprofilepic').classList.add('mainstickprofilepic');
	        document.getElementById('line').style.margin="0px";
	    }
	    else
	    {
	        document.getElementById('menu').classList.add('menu');
	        document.getElementById('menu').classList.remove('menustick');
	        document.getElementById('postscroll').classList.add('postpart1');
	        document.getElementById('postscroll').classList.remove('postpart2');
	        document.getElementById('mainprofilepic').classList.add('mainprofilepic');
	        document.getElementById('mainprofilepic').classList.remove('mainstickprofilepic');
	        document.getElementById('line').style.margin="60px 0px 10px 0px";
	        document.getElementById('postscroll').scrollTo(0,0);
	    }
	}


	function opencomments(element)
	{
		var temp = element.id;
		var temp2 = temp.substring(temp.indexOf('i')+1);
		if(element.classList.contains('fa-comments-o'))
		{
			document.getElementById('comment'+temp2).style.display='block';
			element.classList.remove('fa-comments-o');
			element.classList.add('fa-comments');
		}
		else
		{
			document.getElementById('comment'+temp2).style.display='none';
			element.classList.remove('fa-comments');
			element.classList.add('fa-comments-o');
		}
	}

	
	function like(element)
	{
		var cur = element.id;
		if(element.classList.contains('fa-thumbs-o-up'))
		{
			var xhr = new XMLHttpRequest();
			var pid = cur.substring(cur.indexOf('e')+1);
			var $url = 'like.php?i='+pid;
			xhr.open('GET',$url,true);

			xhr.onreadystatechange = function(){
				if(xhr.readyState==4 && xhr.status==200)
				{
					element.classList.remove('fa-thumbs-o-up');
					element.classList.add('fa-thumbs-up');
					document.getElementById('text'+pid).innerHTML=xhr.responseText;
				}
			}
			xhr.send();
		}
	}

	function follow(element)
    {

        var cur = element.id;
        if(element.innerHTML=='Follow')
        {
            console.log('running');
            var xfr = new XMLHttpRequest();
            var fid = cur.substring(cur.indexOf('w')+1);
            var $url = 'follow.php?id='+fid;

            xfr.open('GET',$url,true);

            xfr.onreadystatechange = function()
            {
                if(xfr.readyState==4 && xfr.status==200)
                {
                    element.innerHTML = 'Following';
                    element.style.backgroundColor = '#cb6318';
                    element.style.color='white';
                }
            }
            xfr.send();
        }
    }
</script>

</body>
</html>

