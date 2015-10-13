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

function prepare_statement($response, $mysqli, $query)
{
	return $mysqli->prepare($query);
}

function bind_param($response, $statement, $param, $value)
{
	return $statement->bind_param($param, $value);
}

function execute_statement($response, $statement)
{
	return $statement->execute();
}

##
# Status checking functions.
##

function check_db($id)
{
	return !empty(system("puppet query nodes 'Site::Roles::Db::Site[\"$id\"]'"));
}

function check_cachedb($id)
{
	return !empty(system("puppet query nodes 'Site::Roles::Cachedb::Site[\"$id\"]'"));
}

function check_koha($id)
{
	return !empty(system("puppet query nodes 'Site::Roles::Koha::Site[\"$id\"]'"));
}

function check_proxy($id)
{
	return !empty(system("puppet query nodes 'Site::Roles::Prox::Site[\"$id\"]'"));
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
	if (!($object = $app->request->getJsonRawBody()))
		$respond = "Unable to decode JSON object";
	else
	{
		$first_name = $object->{"first_name"};
		$surname = $object->{"surname"};
		$email = $object->{"email"};
		$password = $object->{"password"};
		$opac_server_name = $object->{"opac_server_name"};
		$intra_server_name = $object->{"intra_server_name"};

		# Check validity of the parameters.
		if (!filter_var($email, FILTER_VALIDATE_EMAIL) && $respond == null)
			$respond = "Invalid email address";

		if (!is_valid_domain_name($opac_server_name) && $respond == null)
			$respond = "Invalid OPAC server name";

		if (!is_valid_domain_name($intra_server_name) && $respond == null)
			$respond = "Invalid intranet server name";
	}

	# If all of the parameters are valid, continue to send the request.
	if ($respond == null)
	{
		try
		{
			$mysqli = new mysqli(HOSTNAME, USERNAME, PASSWORD, DATABASE);

			##
			# Register the Koha site with the registration database.
			##
			if (!($statement = prepare_statement($response, $mysqli, "CALL add_koha_site(?)")))
				throw new Exception("Unable to prepare SQL statement for adding the Koha site");

			if (!bind_param($response, $statement, "first_name", $first_name))
				throw new Exception("Unable to bind the first name to the statement for adding the Koha site");

			if (!bind_param($response, $statement, "surname", $surname))
				throw new Exception("Unable to bind the surname to the statement for adding the Koha site");

			if (!bind_param($response, $statement, "email", $email))
				throw new Exception("Unable to bind the email address to the statement for adding the Koha site");

			if (!bind_param($response, $statement, "password", $password))
				throw new Exception("Unable to bind the password to the statement for adding the Koha site");

			if (!bind_param($response, $statement, "opac_server_name", $opac_server_name))
				throw new Exception("Unable to bind the OPAC server name to the statement for adding the Koha site");

			if (!bind_param($response, $statement, "intra_server_name", $intra_server_name))
				throw new Exception("Unable to bind the intranet server name to the statement for adding the Koha site");

			if (!execute_statement($response, $statement))
				throw new Exception("Unable to execute the statement for getting the Koha site ID");

			##
			# If we got to this point, yay! It worked!
			# Get the Koha site ID from the registration database.
			##
			if (!($statement = prepare_statement($response, $mysqli, "CALL get_koha_site_id(?)")))
				throw new Exception("Unable to prepare SQL statement for getting the Koha site ID");

			if (!bind_param($response, $statement, "opac_server_name", $opac_server_name))
				throw new Exception("Unable to bind the OPAC server name to the statement for getting the Koha site ID");

			if (!bind_param($response, $statement, "intra_server_name", $intra_server_name))
				throw new Exception("Unable to bind the intranet server name to the statement for getting the Koha site ID");

			if (!(execute_statement($response, $statement)))
				throw new Exception("Unable to execute the statement for getting the Koha site ID");

			$statement->bind_result($id);

			if (($results = $mysqli->fetch()))
			{
				# Only get the ID and send an "Accepted" reply if we got a result back.
				# 202 Accepted
				$response->setStatusCode(202, "Accepted");
				$response->setJsonContent(array("id" => $id));
			}
			else
				throw new Exception("Unable to get Koha site ID from OPAC/intra server name");
		} catch (Exception $e)
		{
			$response->setStatusCode(503, "Internal Server Error");
			$response->setJsonContent(array("message" => $e));
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

$app->handle();

?>
