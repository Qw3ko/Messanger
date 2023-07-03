<?php  

if(isset($_POST['email']) &&
   isset($_POST['password']) &&
   isset($_POST['name'])){

   include '../db.conn.php';
   
   $name = $_POST['name'];
   $password = $_POST['password'];
   $email = $_POST['email'];

   $data = 'name='.$name;

   if (empty($name)) {

   	  $em = "Требуется имя пользователя";

   	  header("Location: ../../signup.php?error=$em");
   	  exit;
   }else if(empty($email)){

   	  $em = "Требуется никнейм";

   	  header("Location: ../../signup.php?error=$em&$data");
   	  exit;
   }else if(empty($password)){

   	  $em = "Требуется пароль";

   	  header("Location: ../../signup.php?error=$em&$data");
   	  exit;
   }else {

   	  $sql = "SELECT name
   	          FROM users
   	          WHERE name=?";
      $stmt = $conn->prepare($sql);
      $stmt->execute([$name]);

      if($stmt->rowCount() > 0){
      	$em = "Имя ($name) уже занято";
      	header("Location: ../../signup.php?error=$em&$data");
   	    exit;
      }else {

      	if (isset($_FILES['pp'])) {

      		$img_name  = $_FILES['pp']['name'];
      		$tmp_name  = $_FILES['pp']['tmp_name'];
      		$error  = $_FILES['pp']['error'];

      		if($error === 0){
               
      		   $img_ex = pathinfo($img_name, PATHINFO_EXTENSION);

				$img_ex_lc = strtolower($img_ex);

				$allowed_exs = array("jpg", "jpeg", "png");

				if (in_array($img_ex_lc, $allowed_exs)) {

					$new_img_name = $name. '.'.$img_ex_lc;

					$img_upload_path = '../../uploads/'.$new_img_name;

                    move_uploaded_file($tmp_name, $img_upload_path);
				}else {
					$em = "Вы не можете загрузить файл этого типа";
			      	header("Location: ../../signup.php?error=$em&$data");
			   	    exit;
				}

      		}
      	}

      	$password = password_hash($password, PASSWORD_DEFAULT);

      	if (isset($new_img_name)) {

            $sql = "INSERT INTO users
                    (name, email, password, p_p)
                    VALUES (?,?,?,?)";
            $stmt = $conn->prepare($sql);
            $stmt->execute([$name, $email, $password, $new_img_name]);
      	}else {

            $sql = "INSERT INTO users
                    (name, email, password)
                    VALUES (?,?,?)";
            $stmt = $conn->prepare($sql);
            $stmt->execute([$name, $email, $password]);
      	}

      	$sm = "Аккаунт успешно создан";

      	header("Location: ../../index.php?success=$sm");
     	exit;
      }

   }
}else {
	header("Location: ../../signup.php");
   	exit;
}