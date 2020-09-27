<?php
	error_reporting(0);
	require_once("database.php");
	if ($_POST['action'] != 'logout') {
		$mail = $_POST["email"];
		$password = hash('sha512', $_POST["password"]);
		if ($_POST['action'] == 'registration') {
			if ($stmt = $mysqli->prepare("SELECT `id` FROM `users` WHERE `mail` = ?")) {
				$stmt->bind_param("s", $mail);
				$stmt->execute();
				$stmt->bind_result($result);
				$stmt->fetch();
				if (strlen($result) > 0) {
					echo "0";
				} else {
					$sign = $mysqli->prepare("INSERT INTO `users` (`mail`, `password`, `name`) VALUES (? , ?, ?)");
					$sign->bind_param("sss", $mail, $password, $_POST["name"]);
					$sign->execute();
					login(mysqli_insert_id($mysqli), $_POST["name"]);
				}
				$stmt->close();
			}
		} else if ($_POST['action'] == 'login') {
			if ($stmt = $mysqli->prepare("SELECT `id`,`name` FROM `users` WHERE `mail` = ? AND `password` = ?")) {
				$stmt->bind_param("ss", $mail, $password);
				$stmt->execute();
				$result = $stmt->get_result();
				$stmt->fetch();
				while ($row = $result->fetch_assoc()) {
					if (strlen($row['id']) > 0) {
						login($row['id'], $row['name']);
					} else {
						echo "0";
					}
				}
				$stmt->close();
			}
		}
	} else {
		session_start();
		unset($_SESSION['user_id']);
	}
	
	function login($user_id, $user_name){
		session_start();
		$_SESSION['user_id'] = $user_id;
		$_SESSION['user_name'] = $user_name;
		echo "1";
	}
?>
