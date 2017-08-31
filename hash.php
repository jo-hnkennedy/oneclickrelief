<?php

	header('Content-Type: application/json');
	echo "{'hash':'", hash_hmac("sha256", $_GET["string"], "KGt1oGFOJ2XW7azNd0wnga0CB4IkmiVSn6FTTUvF"), "'}";
?>
