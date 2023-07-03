<?php 
  session_start();

  if (!isset($_SESSION['username'])) {
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Мессенджер - Регистрация</title>
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-+0n0xVW2eSR5OomGNYDnhzAbDsOXxcvSN1TPprVMTNDbiYZCxYbOOl7+AMvyTG2x" crossorigin="anonymous">
	<link rel="stylesheet" 
	      href="css/style.css">
</head>
<body class="d-flex
             justify-content-center
             bg-light
             bg-gradient
             align-items-center
             vh-100">
	 <div class="w-400 p-5 shadow rounded">
	 	<form method="post" 
	 	      action="app/http/signup.php"
	 	      enctype="multipart/form-data">
	 		<div class="d-flex
	 		            justify-content-center
	 		            align-items-center
	 		            flex-column">

	 		<h3 class="display-4 fs-1 
	 		           text-center">
	 			       Зарегестрироваться</h3>   
	 		</div>

	 		<?php if (isset($_GET['error'])) { ?>
	 		<div class="alert" role="alert">
			  <?php echo htmlspecialchars($_GET['error']);?>
			</div>
			<?php } 
              
              if (isset($_GET['name'])) {
              	$name = $_GET['name'];
              }else $name = '';
			?>

	 	  <div class="mb-3">
		    <label class="form-label">
		           Имя</label>
		    <input type="text"
		           name="name"
		           value="<?=$name?>" 
		           class="form-control">
		  </div>

		  <div class="mb-3">
		    <label class="form-label">
		           Электронная почта</label>
		    <input type="text" 
		           class="form-control"
		           value="<?=$username?>" 
		           name="email">
		  </div>


		  <div class="mb-3">
		    <label class="form-label">
		           Пароль</label>
		    <input type="password" 
		           class="form-control"
		           name="password">
		  </div>

		  <div class="mb-3">
		    <label class="form-label">
		           Изображение профиля</label>
		    <input type="file" 
		           class="form-control"
		           name="pp">
		  </div>
		  
		  <button type="submit" 
		          class="btn btn-primary">
		          Зарегестрироваться</button>
		  <a href="index.php">Войти</a>
		</form>
	 </div>
</body>
</html>
<?php
  }else{
  	header("Location: home.php");
   	exit;
  }
 ?>