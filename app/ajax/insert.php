<?php 

session_start();

if (isset($_SESSION['email'])) {

	if (isset($_POST['message']) &&
        isset($_POST['to_id'])) {
	
	include '../db.conn.php';

	$message = $_POST['message'];
	$to_id = $_POST['to_id'];
	$time = date("d.m.Y H:i");

	$from_id = $_SESSION['user_id'];

	$sql = "INSERT INTO 
	       chats (from_id, to_id, message, created_at) 
	       VALUES (?, ?, ?, ?)";
	$stmt = $conn->prepare($sql);
	$res  = $stmt->execute([$from_id, $to_id, $message, $time]);
    
    if ($res) {

       $sql2 = "SELECT * FROM conversations
               WHERE (user_1=? AND user_2=?)
               OR    (user_2=? AND user_1=?)";
       $stmt2 = $conn->prepare($sql2);
	   $stmt2->execute([$from_id, $to_id, $from_id, $to_id]);

		define('TIMEZONE', 'Russia/Moscow');
		date_default_timezone_set(TIMEZONE);

		if ($stmt2->rowCount() == 0 ) {
			$sql3 = "INSERT INTO 
			         conversations(user_1, user_2)
			         VALUES (?,?)";
			$stmt3 = $conn->prepare($sql3); 
			$stmt3->execute([$from_id, $to_id]);
		}
		?>

		<p class="msgln">
		    <?=$message?>
			<br>
		    <small class="date"><?=$time?></small>      	
		</p>

    <?php 
     }
  }
}else {
	header("Location: ../../index.php");
	exit;
}