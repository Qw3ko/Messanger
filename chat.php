<?php
session_start();

if (isset($_SESSION['name'])) {
    include 'app/db.conn.php';

    include 'app/helpers/user.php';
    include 'app/helpers/chat.php';
    include 'app/helpers/opened.php';

    include 'app/helpers/timeAgo.php';

    if (!isset($_GET['user'])) {
        header("Location: home.php");
        exit;
    }

    $chatWith = getUser($_GET['user'], $conn);

    if (empty($chatWith)) {
        header("Location: home.php");
        exit;
    }

    $user = getUser($_SESSION['name'], $conn);

    $chats = getChats($_SESSION['user_id'], $chatWith['user_id'], $conn);

    opened($chatWith['user_id'], $conn, $chats);
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
            <div class="chat_container">
                <div class="chat">
                    <img src="uploads/<?= $chatWith['p_p'] ?>" class="profile_image">

                    <h3 class="">
                        <?= $chatWith['name'] ?> <br>
                        <div class="" title="online">
                            <?php
                            if (last_seen($chatWith['last_seen']) == "Active") {
                            ?>
                                <div class="online"></div>
                                <small class="">Онлайн</small>
                            <?php } else { ?>
                                <small class="">
                                    В прошлый раз был:
                                    <?= last_seen($chatWith['last_seen']) ?>
                                </small>
                            <?php } ?>
                        </div>
                    </h3>
                </div>

                <div id="chatBox">
                    <?php
                    if (!empty($chats)) {
                        foreach ($chats as $chat) {
                            if ($chat['from_id'] == $_SESSION['user_id']) { ?>
                                <p class="msgln">
                                    <?= $chat['message'] ?>
                                    <br>
                                    <small class="date">
                                        <?= $chat['created_at'] ?>
                                    </small>
                                </p>
                            <?php } else { ?>
                                <p class="msgln">
                                    <?= $chat['message'] ?>
                                    <br>
                                    <small class="date">
                                        <?= $chat['created_at'] ?>
                                    </small>
                                </p>
                        <?php }
                        }
                    } else { ?>
                        <div class="nomsg">
                            Сообщений нет
                        </div>
                    <?php } ?>
                </div>
                <div class="chat_block">
                    <input cols="3" id="message" placeholder="Наберите Ваше сообщение здесь" class="chat_input"></input>
                    <button id="sendBtn"><img src="img/send.svg" alt="Отправить"></button>
                </div>
            </div>

            <div class="user_container">
                <div class="profile_container">
                    <div class="info">
                        <img src="uploads/<?= $user['p_p'] ?>" class="profile_image">
                        <h3 class="fs-xs m-2"><?= $user['name'] ?></h3>
                    </div>
                    <form method="post" action="settings.php">
                        <button class="button" type="submit">Настройки</button>
                    </form>
                    <form method="post" action="logout.php">
                        <button class="button" type="submit">Выйти</button>
                    </form>
                    <br>
                </div>
            </div>
        </div>

        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

        <script>
            $(document).ready(function() {

                $("#sendBtn").on('click', function() {
                    message = $("#message").val();
                    if (message == "") return;
                    if ($(".nomsg").length > 0) $(".nomsg").remove();

                    $.post("app/ajax/insert.php", {
                            message: message,
                            to_id: <?= $chatWith['user_id'] ?>
                        },
                        function(data, status) {
                            $("#message").val("");
                            $("#chatBox").append(data);
                        });
                });

                $("input").keyup(function(e) {
                    if (e.which === 13) {
                        message = $("#message").val();
                        if (message == "") return;
                        if ($(".nomsg").length > 0) $(".nomsg").remove();

                        $.post("app/ajax/insert.php", {
                                message: message,
                                to_id: <?= $chatWith['user_id'] ?>
                            },
                            function(data, status) {
                                $("#message").val("");
                                $("#chatBox").append(data);
                            });
                    }
                });

                let lastSeenUpdate = function() {
                    $.get("app/ajax/update_last_seen.php");
                }
                lastSeenUpdate();

                setInterval(lastSeenUpdate, 10000);



                let fechData = function() {
                    $.post("app/ajax/getMessage.php", {
                            id_2: <?= $chatWith['user_id'] ?>
                        },
                        function(data, status) {
                            $("#chatBox").append(data);
                        });
                }

                fechData();

                setInterval(fechData, 500);

            });
        </script>

    </body>

    </html>
<?php
} else {
    header("Location: index.php");
    exit;
}
?>