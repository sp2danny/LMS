
<?php

include_once "common.php";
include_once "connect.php";

class Token {
	public $token_id;
	public $pid;
	public $flavour;
	public $uuid;
}

function guidv4($data = null) {
    // Generate 16 bytes (128 bits) of random data or use the data passed into the function.
    $data = $data ?? random_bytes(16);
    assert(strlen($data) == 16);

    // Set version to 0100
    $data[6] = chr(ord($data[6]) & 0x0f | 0x40);
    // Set bits 6-7 to 10
    $data[8] = chr(ord($data[8]) & 0x3f | 0x80);

    // Output the 36 character UUID.
    return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
}


function Login($username, $password, $flavour)
{
	global $emperator;
	
	$query = "SELECT * FROM pers WHERE pnr = '" . $username . "'";

	$result = mysqli_query($emperator, $query);
	if (!$result) return false;
	$row = mysqli_fetch_array($res);
	if (!$row) return false;
	if ($row['pwd'] != $password) return false;
	
	$pid = $row['pers_id'];
	
	$query = "DELETE FROM token WHERE pid = '$pid'";
	$result = mysqli_query($emperator, $query);
	
	$uuid = guidv4();

	$query  = "INSERT INTO token (expires, pid, flavour, uuid) ";
	$query .= " VALUES ( NOW() + INTERVAL 1 DAY, '$pid', '$flavour', '$uuid' )";
	$result = mysqli_query($emperator, $query);
	if (!result) return false;
	$last_id = mysqli_insert_id($emperator);

	$tok = new Token;
	$tok->token_id = $last_id;
	$tok->pid = $pid;
	$tok->flavour = $flavour;
	$tok->uuid = $uuid;

	return $tok;
}

function Verify($token)
{
	global $emperator;
	
	$tid = $token->token_id;
	
	$query = "SELECT * FROM token token_id = '$tid'";

	$result = mysqli_query($emperator, $query);
	if (!$result) return false;
	
	$row = mysqli_fetch_array($res);
	if (!$row) return false;
	
	if ($row['pid'] != $token->pid) return false;
	if ($row['flavour'] != $token->flavour) return false;

	$exp = $row['expires'];
	return $exp;	
}

?>
