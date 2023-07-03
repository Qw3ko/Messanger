<?php
session_start();

if (isset($_SESSION['name'])) {
    
    include 'app/db.conn.php';

    include 'app/helpers/user.php';
    include 'app/helpers/conversations.php';
    include 'app/helpers/timeAgo.php';
    include 'app/helpers/last_chat.php';

    $user = getUser($_SESSION['name'], $conn);

    $conversations = getConversation($user['user_id'], $conn);

?>
    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Мессенджер - Чат</title>
        <link rel="stylesheet" href="styles/styles.css">
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Roboto&display=swap" rel="stylesheet">
    </head>

    <body>
        <div class="container_chat">
            <div class="users_container">
                <a href="home.php"><img class="back_btn" src="img/arrow-left.svg" alt="Назад"></a>
            </div>
            <div class="settings_container">
                <div class="settings_chg">
                    <form action="app/ajax/change.php" method="post">
                        Новое имя пользователя: <input name="nameChg">
                        <button type="submit">Изменить</button>
                    </form>
                    <?php if (isset($_GET['error'])) { ?>
                        <div class="alert" role="alert">
                            <?php echo htmlspecialchars($_GET['error']); ?>
                        </div>
                    <?php }

                    if (isset($_GET['name'])) {
                        $name = $_GET['name'];
                    } else $name = '';

                    if (isset($_GET['email'])) {
                        $email = $_GET['email'];
                    } else $email = '';
                    ?>
                </div>
                <div class="settings_chg">
                    <form action="app/ajax/change.php" method="post" enctype="multipart/form-data">
                        Изменить аватар: <input type="file" name="pp">
                        <button type="submit">Изменить</button>
                    </form>
                </div>
            </div>
            <div class="user_container">
                <div class="profile_container">
                    <div class="info">
                        <img src="uploads/<?= $user['p_p'] ?>" class="profile_image">
                        <h3><?= $user['name'] ?></h3>
                    </div>
                    <form method="post" action="logout.php">
                        <button class="button" type="submit">Выйти</button>
                    </form>
                    <br>
                </div>
            </div>
        </div>

        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

    </body>

    </html>
<?php
} else {
    header("Location: index.php");
    exit;
}
?>