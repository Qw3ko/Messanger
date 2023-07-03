<?php

session_start();

if (isset($_SESSION['name'])) {
	if (isset($_POST['key'])) {
		include '../db.conn.php';

		$key = "%{$_POST['key']}%";

		$sql = "SELECT * FROM users
	           WHERE name
	           LIKE ? OR name LIKE ?";
		$stmt = $conn->prepare($sql);
		$stmt->execute([$key, $key]);

		if ($stmt->rowCount() > 0) {
			$users = $stmt->fetchAll();

			foreach ($users as $user) {
				if ($user['user_id'] == $_SESSION['user_id']) continue;
?>
				<li class="">
					<a href="chat.php?user=<?= $user['name'] ?>" class="user_list">
						<div class="user">

							<img src="uploads/<?= $user['p_p'] ?>" class="profile_image">

							<h3 class="">
								<?= $user['name'] ?>
							</h3>
						</div>
					</a>
				</li>
			<?php }
		} else { ?>
			<div class="">
				<i class=""></i>
				Пользователь "<?= htmlspecialchars($_POST['key']) ?>"
				не найден.
			</div>
<?php }
	}
} else {
	header("Location: ../../index.php");
	exit;
}
