<?php

##
# Included files.
##

include 'database.inc.php';

##
# Use extensions.
##

use Phalcon\Mvc\Micro;
use Phalcon\Http\Response;

##
# Helper functions.
##

# Credit where credit is due. This function was written by velcrow at this Stack Overflow link, with some modificiations:
# http://stackoverflow.com/questions/1755144/how-to-validate-domain-name-in-php
function is_valid_domain_name($domain_name)
{
	return (preg_match("/^([a-z\d](-*[a-z\d])*)(\.([a-z\d](-*[a-z\d])*))*$/i", $domain_name)	// Valid characters
		&& preg_match("/^.{1,253}$/", $domain_name)						// Valid length
		&& preg_match("/^[^\.]{1,63}(\.[^\.]{1,63})*$/", $domain_name));			// Valid label length
}

##
# Status checking functions.
##

function check_db($id)
{
	return !empty(exec("sudo puppet-query-nodes 'Site::Profiles::Db::Site[\"$id\"]'"));
}

function check_memcached($id)
{
	return !empty(exec("sudo puppet-query-nodes 'Site::Profiles::Memcached::Site[\"$id\"]'"));
}

function check_zebra($id)
{
	return !empty(exec("sudo puppet-query-nodes 'Site::Profiles::Zebra::Site[\"$id\"]'"));
}

function check_koha($id)
{
	return !empty(exec("sudo puppet-query-nodes 'Site::Profiles::Koha::Site[\"$id\"]'"));
}

function check_proxy($id)
{
	return !empty(exec("sudo puppet-query-nodes 'Site::Profiles::Proxy::Site[\"$id\"]'"));
}

##
# The REST API to collect site information from users.
##

$app = new Micro();

$app->post("/register", function() use($app)
{
	# Start building the response.
	$response = new Response();

	# Error message to set for the response.
	$respond = null;

	# Get the new site information from the JSON object.
	if (($object = $app->request->getJsonRawBody()))
	{
		$first_name = $object->{"first_name"};
		$surname = $object->{"surname"};
		$email = $object->{"email"};
		$password = $object->{"password"};
		$confirm_password = $object->{"confirm_password"};
		$opac_server_name = $object->{"opac_server_name"};
		$intra_server_name = $object->{"intra_server_name"};
	}
	else
	{
		$first_name = htmlspecialchars($_POST['firstname']);
		$surname = htmlspecialchars($_POST['surname']);
		$email = htmlspecialchars($_POST['email']);
		$password = htmlspecialchars($_POST['pword']);
		$confirm_password = htmlspecialchars($_POST['confirmpword']);
		$opac_server_name = htmlspecialchars($_POST['opacname']);
		$intra_server_name = htmlspecialchars($_POST['intraname']);
	}

	# Check validity of the parameters.
	if ($password !== $confirm_password && $respond == null)
		$respond = "Mismatching passwords";

	if (!filter_var($email, FILTER_VALIDATE_EMAIL) && $respond == null)
		$respond = "Invalid email address";

	if (!is_valid_domain_name($opac_server_name) && $respond == null)
		$respond = "Invalid OPAC server name";

	if (!is_valid_domain_name($intra_server_name) && $respond == null)
		$respond = "Invalid intranet server name";

	# If all of the parameters are valid, continue to send the request.
	if ($respond == null)
	{
		try
		{
			$mysqli = new mysqli(HOSTNAME, USERNAME, PASSWORD, DATABASE);

			##
			# Register the Koha site with the registration database.
			##
			if (!($statement = $mysqli->prepare("CALL add_koha_site(?, ?, ?, ?, ?, ?)")))
				throw new Exception("Unable to prepare SQL statement for adding the Koha site (" . $mysqli->errno . "): " . $mysqli->error);

			if (!$statement->bind_param("ssssss", $first_name, $surname, $email, $password, $opac_server_name, $intra_server_name))
				throw new Exception("Unable to bind parameters to the statement for adding the Koha site (" . $statement->errno . "): " . $statement->error);

			if (!$statement->execute())
				throw new Exception("Unable to execute the statement for getting the Koha site ID (" . $statement->errno . "): " . $statement->error);

			##
			# If we got to this point, yay! It worked!
			# Get the Koha site ID from the response.
			##

			$statement->bind_result($id);

			if (($results = $statement->fetch()))
			{
				# Only get the ID and send an "Accepted" reply if we got a result back.
				# 202 Accepted
				$response->setStatusCode(202, "Accepted");
				$response->setJsonContent(array("id" => $id));
			}
			else
				throw new Exception("Unable to get Koha site ID from statement response");
		} catch (Exception $e)
		{
			$response->setStatusCode(503, "Internal Server Error");
			$response->setJsonContent(array("message" => $e->getMessage()));
		}
	}
	# 400 Bad Request
	# The client sent invalid data.
	else
	{
		$response->setStatusCode(400, "Bad Request");
		$response->setJsonContent(array("message" => $respond));
	}

	return $response;
});

$app->get("/status/db/{id}", function($id)
{
	# Start building the response.
	$response = new Response();

	# Fill the response with the return value of the function that checks
	# the status of the component.
	$response->setStatusCode(200, "OK");
	$response->setJsonContent(array("ready" => check_db($id)));

	return $response;
});

$app->get("/status/memcached/{id}", function($id)
{
	# Start building the response.
	$response = new Response();

	# Fill the response with the return value of the function that checks
	# the status of the component.
	$response->setStatusCode(200, "OK");
	$response->setJsonContent(array("ready" => check_memcached($id)));

	return $response;
});

$app->get("/status/zebra/{id}", function($id)
{
	# Start building the response.
	$response = new Response();

	# Fill the response with the return value of the function that checks
	# the status of the component.
	$response->setStatusCode(200, "OK");
	$response->setJsonContent(array("ready" => check_zebra($id)));

	return $response;
});

$app->get("/status/koha/{id}", function($id)
{
	# Start building the response.
	$response = new Response();

	# Fill the response with the return value of the function that checks
	# the status of the component.
	$response->setStatusCode(200, "OK");
	$response->setJsonContent(array("ready" => check_koha($id)));

	return $response;
});

$app->get("/status/proxy/{id}", function($id)
{
	# Start building the response.
	$response = new Response();

	# Fill the response with the return value of the function that checks
	# the status of the component.
	$response->setStatusCode(200, "OK");
	$response->setJsonContent(array("ready" => check_proxy($id)));

	return $response;
});

$app->handle();

?>
