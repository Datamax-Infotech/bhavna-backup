<?php
$b_debugmode = 1; // 0 || 1

function get_result_new(mysqli_stmt $Statement): mixed
{
    $RESULT = [];
    $PARAMS = [];
    $Statement->store_result();
    for ($i = 0; $i < $Statement->num_rows; $i++) {
        $Metadata = $Statement->result_metadata();
        $PARAMS = [];
		if (is_object($Metadata)) {
			while ($Field = $Metadata->fetch_field()) {
				if (is_object($Field) && property_exists($Field, 'name')) {
					$RESULT[$i][$Field->name] = null; // Initialize the array element
					$PARAMS[] = &$RESULT[$i][$Field->name];
				}
			}
		}
        call_user_func_array([$Statement, 'bind_result'], $PARAMS);
        $Statement->fetch();
    }
    return $RESULT;
}

/**
 * @param string $query
 * @param array<string>|null $param_type
 * @param array<mixed>|null $param_data
 * @return mixed
 */
function db_query(string $query, ?array $param_type = [], ?array $param_data = []): mixed
{

	$link = 'db_link';
	global ${$link};

	/*if (defined('STORE_DB_TRANSACTIONS') && (STORE_DB_TRANSACTIONS == 'true')) {
		error_log('QUERY ' . $query . "\n", 3, STORE_PAGE_PARSE_TIME_LOG);
	}*/
	if ($param_type != "" && is_array($param_type)) {
		$param_type = implode('', $param_type); // Join array elements with a string
		$a_params = array_merge([$param_type], $param_data); // Merge two arrays
	}
	$resultnew = ${$link}->prepare($query) or tep_db_error($query, mysqli_errno(${$link}), mysqli_error(${$link}));
	if ($param_type) {
		$resultnew->bind_param($param_type, ...$param_data);
	}


	if (!$resultnew->execute())
		echo "Execute failed: (" . $resultnew->errno . ") " . $resultnew->error;

	$result = get_result_new($resultnew);

	/*if (defined('STORE_DB_TRANSACTIONS') && (STORE_DB_TRANSACTIONS == 'true')) {
		$result_error = mysqli_error(${$link});
		//error_log('RESULT ' . $result . ' ' . $result_error . "\n", 3, STORE_PAGE_PARSE_TIME_LOG);
		error_log('RESULT '. $result_error . "\n", 3, STORE_PAGE_PARSE_TIME_LOG);
	}
	*/

	return $result;
}

/**
 * @param int[] $db_query
 * @return int
 */
function tep_db_num_rows(array $db_query): int
{
    return sizeof($db_query);
}

function tep_db_close(string $link = 'db_link'): bool
{
	global ${$link};

	//return mysql_close($$link);
	return mysqli_close(${$link});
}

function tep_db_error(string $query,int $errno, string $error): never
{
	die('<font color="#000000"><b>' . $errno . ' - ' . $error . '<br><br>' . $query . '<br><br><small><font color="#ff0000">[TEP STOP]</font></small><br><br></b></font>');
}

function tep_db_insert_id(): int|string
{
	$link = 'db_link';
	global ${$link};
	return mysqli_insert_id(${$link});
}

function FixString(string $strtofix): string
{ //THIS FUNCTION ESCAPES SPECIAL CHARACTERS FOR INSERTING INTO SQL
	$strtofix = addslashes($strtofix);
	$strtofix = str_replace("<", "&#60;", $strtofix);
	$strtofix = str_replace("'", "&#39;", $strtofix);
	$strtofix = preg_replace("/(\n)/", "<br>", $strtofix);
	return $strtofix;
}//end FixString

// // Encryption and Decryption
function encryptstr(string $encryptValue): string|false
{
	$ciphering = "AES-128-CTR";
	$iv_length = openssl_cipher_iv_length($ciphering);
	$options = 0;
	$cryption_iv = '1234567891011121';
	$cryption_key = "U1C!2B3l4@o5o#6p7";

	$encryption = openssl_encrypt(
		$encryptValue,
		$ciphering,
		$cryption_key,
		$options,
		$cryption_iv
	);

	return $encryption;
}

function decryptstr(string $decryptValue): string|false
{

	$ciphering = "AES-128-CTR";
	$iv_length = openssl_cipher_iv_length($ciphering);
	$options = 0;
	$cryption_iv = '1234567891011121';
	$cryption_key = "U1C!2B3l4@o5o#6p7";

	$decryption = openssl_decrypt(
		$decryptValue,
		$ciphering,
		$cryption_key,
		$options,
		$cryption_iv
	);

	return $decryption;
}
