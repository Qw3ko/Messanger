<?php  

function getUser($name, $conn){
   $sql = "SELECT * FROM users 
           WHERE name=?";
   $stmt = $conn->prepare($sql);
   $stmt->execute([$name]);

   if ($stmt->rowCount() === 1) {
   	 $user = $stmt->fetch();
   	 return $user;
   }else {
   	$user = [];
   	return $user;
   }
}