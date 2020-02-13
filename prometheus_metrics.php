<?php
	header('Content-Type: text/plain; version=0.0.4; charset=utf-8');
	$max_age = 30;
	$expires = gmdate("D, d M Y H:i:s", time() + $max_age) . " GMT";
	header("Expires: $expires");
	header("Pragma: cache");
	header("Cache-Control: max-age=$max_age");
?>

<?php
	require __DIR__ . '/src/MinecraftQuery.php';
	require __DIR__ . '/src/MinecraftQueryException.php';

	use xPaw\MinecraftQuery;
	use xPaw\MinecraftQueryException;

	$Query = new MinecraftQuery();

	try
	{
		$Query->Connect('localhost', 25565);

		// get all players from whitelist
		$whitelist_file = '/home/minecraftuser/tekkit/white-list.txt';
		$whitelist = file($whitelist_file);

		$player_count = 0;
		$online_users = [];
		foreach ($Query->GetPlayers() as $online_user) {
			$online_users[] = strtolower($online_user);
			$player_count++;
		}

		print("mc_players_online_total $player_count\n");
		foreach($whitelist as $username) {
			$user_status = 0;
			$formatted_user = strtolower(rtrim($username));
			if (in_array($formatted_user, $online_users)) {
				$user_status = 1;
			}

			print("mc_players_online{username=\"$formatted_user\"} $user_status\n");
		}
	}
	catch( MinecraftQueryException $e )
	{
		echo $e->getMessage( );
	}
?>

