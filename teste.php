<?php
		/*$conn = ssh2_connect("10.51.8.71");
		ssh2_auth_password($conn, 'wmsadm', 'ry01200');
		$stream = ssh2_exec($conn, "pwd");
		$errorStream = ssh2_fetch_stream($stream, SSH2_STREAM_STDERR);
		stream_set_blocking($errorStream, true);
		stream_set_blocking($stream, true);
		echo "output: ". stream_get_contents($stream,-1,-1);
		ssh2_disconnect($conn);

		$socket = fsockopen("10.0.156.21", 80);
		echo $socket*/
		$pass1 = hash("sha1", "Ripley.2020");
		$pass2 = hash("sha1", "Ripley.2020");
		echo $pass1;
		echo "<br>";
		echo $pass2;

?>
