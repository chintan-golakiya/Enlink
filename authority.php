<?php
	
	
	class authClass
	{
		
		public function authLogin($fuserid,$fpass){
			try{
				$db=getdb();
				$stmt = $db->prepare("SELECT userid FROM user WHERE username=:userid AND password=:pass");
				$stmt->bindParam(':userid',$fuserid);
				$stmt->bindParam(':pass',$fpass);
				$stmt->execute();
				$count = $stmt->rowCount();
				$r = $stmt->fetch();
				if($count){
					return $r['userid'];
				}
				else
					return 0;
				$db=null;

			}
			catch(PDOException $e){
				echo "connection failed: ".$e->getMessage();
			}
		}

		public function authDetail($fuserid){
			
			try{
				$db=getdb();
				$stmt = $db->prepare("SELECT * FROM user WHERE username=:userid");
				$stmt->bindParam(':userid',$fuserid);
				
				$stmt->execute();
				$data = $stmt->fetch(PDO::FETCH_BOTH);
				$db=null;
				return $data;
			}
			catch(PDOException $e){
				echo 'connection failed : '.$e->getMessage();
			}
		}
	}
?>