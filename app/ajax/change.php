<?php

session_start();

include '../db.conn.php';

$name = $_SESSION['name'];

$user_id = $_SESSION['user_id'];

$data = 'name=' . $name;

if (isset($_SESSION['email'])) {

    if (isset($_POST['nameChg'])) {

        $sql = "SELECT name 
   	          FROM users
   	          WHERE name=?";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$_POST['nameChg']]);
        $res = $stmt->fetch();

        if ($res["name"] === $_POST['nameChg']) {
            $em = "Имя " . $_POST['nameChg'] . " уже занято";
            header("Location: ../../settings.php?error=$em&$data");
            exit;
        } else {

            $nameChg = $_POST['nameChg'];
            $user_id = $_SESSION['user_id'];

            $sql = "UPDATE users SET name=? WHERE user_id=?";
            $stmt = $conn->prepare($sql);
            $res = $stmt->execute([$nameChg, $user_id]);

            $_SESSION['name'] = $nameChg;

            header("Location: ../../settings.php");
        }
    }

    if (isset($_FILES['pp'])) {

        $sql = "SELECT p_p FROM users WHERE user_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$user_id]);
        $file_name = $stmt->fetch();

        echo ($file_name["p_p"]);

        unlink("../../uploads/" . $file_name["p_p"]);

        $img_name  = $_FILES['pp']['name'];
        $tmp_name  = $_FILES['pp']['tmp_name'];
        $error  = $_FILES['pp']['error'];

        if ($error === 0) {

            $img_ex = pathinfo($img_name, PATHINFO_EXTENSION);

            $img_ex_lc = strtolower($img_ex);

            $allowed_exs = array("jpg", "jpeg", "png");

            if (in_array($img_ex_lc, $allowed_exs)) {

                $new_img_name = $name . '.' . $img_ex_lc;

                $img_upload_path = '../../uploads/' . $new_img_name;

                move_uploaded_file($tmp_name, $img_upload_path);

                $sql2 = "UPDATE users SET p_p = ? WHERE user_id = ?";
                $stmt2 = $conn->prepare($sql2);
                $stmt2->execute([$new_img_name, $user_id]);

                header("Location: ../../settings.php");
            } else {
                $em = "Вы не можете загрузить файл этого типа";
                header("Location: ../../signup.php?error=$em&$data");
                exit;
            }
        }
    }
} else {
    header("Location: ../../index.php");
    exit;
}
