<?php

function check_db($site_name)
{
	$ret = false;

	$host = "db.kohaaas.catalyst.net.nz";
	$username = "kohaaas.catalyst.net.nz";
	$passwd = "kohaaas.catalyst.net.nz";

	$conn = mysqli_connect($host, $username, $passwd, $database);

	if ($ret)
	{
		mysqli_close($conn);
		$ret = true;
	}

	return $ret;
}

function check_cachedb($site_name)
{
	$ret = false;

	return $ret;
}

function check_koha($site_name)
{
	$ret = false;

	return $ret;
}

?>
