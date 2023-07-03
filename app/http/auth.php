<?php
session_start();

if (
  isset($_POST['email']) &&
  isset($_POST['password'])
) {

  include '../db.conn.php';

  $password = $_POST['password'];
  $email = $_POST['email'];

  if (empty($email)) {

    $em = "Требуется электронная почта";

    header("Location: ../../index.php?error=$em");
  } else if (empty($password)) {

    $em = "Требуется пароль";

    header("Location: ../../index.php?error=$em");
  } else {
    $sql  = "SELECT * FROM 
               users WHERE email=?";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$email]);

    if ($stmt->rowCount() === 1) {

      $user = $stmt->fetch();

      if ($user['email'] === $email) {

        if (password_verify($password, $user['password'])) {

          $_SESSION['email'] = $user['email'];
          $_SESSION['name'] = $user['name'];
          $_SESSION['user_id'] = $user['user_id'];

          header("Location: ../../home.php");
        } else {

          $em = "Неправильная электронная почта или пароль";

          header("Location: ../../index.php?error=$em");
        }
      } else {

        $em = "Неправильная электронная почта или пароль";

        header("Location: ../../index.php?error=$em");
      }
    } else {
      
      $em = "Неправильная электронная почта или пароль";

      header("Location: ../../index.php?error=$em");
    }
  }
} else {
  header("Location: ../../index.php");
  exit;
}
