<?php

##
# Helper functions.
##

function log_print($text)
{
	file_put_contents("/var/log/apache2/api.log", $text, FILE_APPEND | LOCK_EX);
}

##
# The REST API to collect site information from users.
##

##
# Update the instances.
##

function update($command)
{
	$date = date(DATE_COOKIE);
	$log = system($command, $ret);

	if ($ret != 0)
		log_print("Command: $command\nDate: $date\nReturn code: $ret\n\n$log\n\nEnd command.\n-------------\n\n");

	return $ret;
}

function update_db()
{
	return update("mco puppet runonce -C site::roles::db", $ret);
}

function update_cachedb()
{
	return update("mco puppet runonce -C site::roles::cachedb", $ret);
}

function update_koha()
{
	return update("mco puppet runonce -C site::roles::koha", $ret);
}

function update_proxy()
{
	return update("mco puppet runonce -C site::roles::proxy", $ret);
}

##
# Check if the Koha site components are available.
##


class Site
{
	private $firstname;
	private $surname;

	private $site_name;

	private $domain;

	private $email;
	private $pass;

	function __construct($args)
	{
		$this->firstname = $args[0];
		$this->surname = $args[1];
		$this->site_name = $args[2];
		$this->domain = $args[3];
		$this->email = $args[4];
		$this->pass = $args[5];
	}

	function export()
	{
		# scp the yaml file to the Puppet Master!
	}

	function check_db()
	{
		$ret = false;

		$host = ;
		$username = ;
		$passwd = ;

		$conn = mysqli_connect($host, $username, $passwd, $database);

		if ($conn)
		{
			mysqli_close($conn);
			$ret = true;
		}

		return $ret;
	}

	function check_zebra()
	{
		$ret = false;


		return $ret;
	}

	function check_memcached()
	{
		$memcached = new Memcached;

		$ret = $memcached->addServer();
		$memcached->resetServerList();
		return new ;
	}

	function check_koha()
	{
		$ret = false;

		return $ret;
	}

	function check_proxy()
	{
		$ret = false;

		return $ret;
	}
}

?>
