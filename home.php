<?php
session_start();

if (isset($_SESSION['name'])) {
    # database connection file
    include 'app/db.conn.php';

    include 'app/helpers/user.php';
    include 'app/helpers/conversations.php';
    include 'app/helpers/timeAgo.php';
    include 'app/helpers/last_chat.php';

    # Getting User data data
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
                <input type="text" placeholder="Поиск..." id="searchText">
                <button class="" id="searchBtn">Поиск</button>
                <ul id="chatList">
                    Список пользователей:
                    <?php if (!empty($conversations)) { ?>
                        <?php

                        foreach ($conversations as $conversation) { ?>
                            <li>
                                <a href="chat.php?user=<?= $conversation['name'] ?>" class="users_list">
                                    <div class="user">
                                        <img src="uploads/<?= $conversation['p_p'] ?>" class="profile_image">
                                        <h3 class="">
                                            <?= $conversation['name'] ?><br>
                                            <small>
                                                <?php
                                                echo "Последнее сообщение: "; echo lastChat($_SESSION['user_id'], $conversation['user_id'], $conn);
                                                ?>
                                            </small>
                                        </h3>
                                    </div>
                                    <?php if (last_seen($conversation['last_seen']) == "Active") { ?>
                                        <div title="online">
                                            <div class="online"></div>
                                        </div>
                                    <?php } ?>
                                </a>
                            </li>
                        <?php } ?>
                    <?php } else { ?>
                        <div class="alert-info">
                            Пользователей нет
                        </div>
                    <?php } ?>
                </ul>
            </div>
            <div class="chat_container">
                <div id="chatbox_home">
                    <div>
                        Сообщений нет
                    </div>
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
                        <h3><?= $user['name'] ?></h3>
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

                $("#searchText").on("input", function() {
                    var searchText = $(this).val();
                    if (searchText == "") return;
                    $.post('app/ajax/search.php', {
                            key: searchText
                        },
                        function(data, status) {
                            $("#chatList").html(data);
                        });
                });

                $("#searchBtn").on("click", function() {
                    var searchText = $("#searchText").val();
                    if (searchText == "") return;
                    $.post('app/ajax/search.php', {
                            key: searchText
                        },
                        function(data, status) {
                            $("#chatList").html(data);
                        });
                });

                let lastSeenUpdate = function() {
                    $.get("app/ajax/update_last_seen.php");
                }
                lastSeenUpdate();

                setInterval(lastSeenUpdate, 10000);

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