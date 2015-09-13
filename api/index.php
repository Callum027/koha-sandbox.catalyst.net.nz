<?php

##
# Use extensions.
##

# PECL extensions: yaml, ssh2, phalcon
use Phalcon\Mvc\Micro;

##
# Global static configuration variables.
##

const REG_QUEUE_FLUSH_FREQUENCY = 60;

const LOCAL_SITES_DIR = "/tmp/koha-sites";
const LOCAL_SITES_DIR_CREATE_MODE = 0600;

const PUPPET_MASTER = "puppet.kohaaas.catalyst.net.nz";
const PUPPET_MASTER_KOHA_SITES_DIR = "/vol/koha-sites";
const PUPPET_MASTER_KOHA_SITES_CREATE_MODE = 0440;

##
# Classes.
##

class Site
{
	private $first_name;
	private $surname;

	private $site_name;

	private $base_domain;

	private $email;
	private $password;

	function __construct($args)
	{
		$this->first_name = $args[0];
		$this->surname = $args[1];
		$this->site_name = $args[2];
		$this->base_domain = $args[3];
		$this->email = $args[4];
		$this->password = $args[5];
	}

	function export()
	{
		$data = array
		(
			"first_name"	=> $this->first_name,
			"surname"	=> $this->surname,
			"site_name"	=> $this->site_name,
			"base_domain"	=> $this->base_domain,
			"email"		=> $this->email,
			"password"	=> $this->password,
		);

		$local_file = LOCAL_SITES_DIR . "/{$this->site_name}.yaml";
		$remote_file = PUPPET_MASTER_KOHA_SITES_DIR . "/{$this->site_name}.yaml";

		# Create the temporary directory (if it doesn't already exist), and set the correct permissions on it.
		mkdir(LOCAL_SITES_DIR, LOCAL_SITES_DIR_CREATE_MODE);

		# Create the yaml file with the site's information.
		yaml_emit_file($local_file, $data);

		# Open the SSH connection to the Puppet Master.
		$session = ssh2_connect(PUPPET_MASTER);
		ssh2_scp_send($session, $local_file, $remote_file, PUPPET_MASTER_KOHA_SITES_CREATE_MODE);

		# Delete the local copy of the yaml file.
		unlink($local_file);
	}

	function check_db()
	{
		return !empty(system("puppet query nodes 'Koha::Db::Site[\"$site_name\"]'"));
	}

	function check_zebra()
	{
		return !empty(system("puppet query nodes 'Koha::Zebra::Site[\"$site_name\"]'"));
	}

	function check_memcached()
	{
		return !empty(system("puppet query nodes 'Koha::Memcache::Site[\"$site_name\"]'"));
	}

	function check_koha()
	{
		return !empty(system("puppet query nodes 'Koha::Koha::Site[\"$site_name\"]'"));
	}

	function check_proxy()
	{
		return !empty(system("puppet query nodes 'Koha::Proxy::Site[\"$site_name\"]'"));
	}
}

##
# Helper functions.
##

function log_print($text)
{
	file_put_contents("/var/log/apache2/api.log", $text, FILE_APPEND | LOCK_EX);
}

function update($command)
{
	$date = date(DATE_COOKIE);
	$log = system($command, $ret);

	if ($ret != 0)
		log_print("##\n# Command: $command\n# Date: $date\n# Return code: $ret\n##\n\n$log\n\n##\n# End command.\n##\n\n-------------\n\n");

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

function update_all()
{
	update_db();
	update_cachedb();
	update_koha();
	update_proxy();
}

function update_schedule()
{
	
}

##
# The REST API to collect site information from users.
##

# Queue of site registration requests.
$reg_queue = new SplQueue();

##
# Update the instances.
##

$app = new Micro();

$app->post("/api/register", function ()
{
	# Get the new site information from the JSON object.
	
	# If the parameters are correct.
	{
		# Create a site object with the registered site's information, and export
		# it to the Puppet Master.
		$site = new Site()
		$site.export();

		# Schedule an update of the instances.
		update_schedule();

		# Start building the response.
		$response = new Response();

		# 202 Accepted
		if ($ret == 0)
		{
			$response->setStatusCode(202, "Accepted");
			$response->setJsonContent("session" => $session);
		}
	}
	# 400 Bad Request
	# 
	else
	{
	}
});

##
# Start-up process. Set up the application 
$app->handle();



?>
