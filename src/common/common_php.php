
<?php

include_once 'getparam.php';
include_once 'convert.php';

function redirect($link)
{
	$str  = '<meta http-equiv="Refresh" content="0; url=';
	$str .=	"'" . $link . "'";
	$str .= '" />';
	return $str;
}

function QIT($val)
{
	if (is_numeric($val))
		return $val;
	else
		return "'" . $val . "'";
}

function COR( $db, $id_n, $id_v, $sup_n, $sup_v, $res_n ) // Create Or Read
{
	global $emperator;

	$query = "SELECT * FROM " . $db . " WHERE";
	
	$n = count($id_n);
	for ($i=0; $i<$n; ++$i) {
		if ($i==0)
			$query .= " ";
		else
			$query .= " AND ";
		$query .= $id_n[$i] . "=" . QIT($id_v[$i]);
	}
	$res = mysqli_query($emperator, $query);
	if ($res) {
		$row = mysqli_fetch_array($res);
		if ($row) {
			return $row[$res_n];
		}
	}
	
	$nn = " (";
	$vv = " (";
	$n = count($id_n);
	$sep = "";
	for ($i=0; $i<$n; ++$i) {
		$nn .= $sep . $id_n[$i];
		$vv .= $sep . QIT($id_v[$i]);
		$sep = ",";
	}
	$n = count($sup_n);
	for ($i=0; $i<$n; ++$i) {
		$nn .= $sep . $sup_n[$i];
		$vv .= $sep . QIT($sup_v[$i]);
		$sep = ",";
	}
	$nn .= ")";
	$vv .= ")";
	
	$query = "INSERT INTO " . $db . $nn . " VALUES " . $vv;
	
	$res = mysqli_query($emperator, $query);
	if ($res) {
		return $emperator->insert_id;
	} else {
		return 'DB Error, insert --'.$query.'-- ' . "\n" . mysqli_error();
	}
}

function COU( $db, $id_n, $id_v, $sup_n, $sup_v, $key ) // Create Or Update
{
	global $emperator;

	$query = "SELECT * FROM " . $db . " WHERE";
	
	$n = count($id_n);
	for ($i=0; $i<$n; ++$i) {
		if ($i==0)
			$query .= " ";
		else
			$query .= " AND ";
		$query .= $id_n[$i] . "=" . QIT($id_v[$i]);
	}
	$res = mysqli_query($emperator, $query);
	if ($res) {
		$row = mysqli_fetch_array($res);
		if ($row) {
			
			$kval = $row[$key];
			
			$query = "UPDATE " . $db . " SET ";
			
			$sep = "";
			$n = count($sup_n);
			for ($i=0; $i<$n; ++$i) {
				$query .= $sep . $sup_n[$i] . "=" . QIT($sup_v[$i]);
				$sep = ",";
			}
			
			$query .= " WHERE " . $key . "=" . $kval;
			
			$res = mysqli_query($emperator, $query);
			
			return $res;
		}
	}
	
	$nn = " (";
	$vv = " (";
	$n = count($id_n);
	$sep = "";
	for ($i=0; $i<$n; ++$i) {
		$nn .= $sep . $id_n[$i];
		$vv .= $sep . QIT($id_v[$i]);
		$sep = ",";
	}
	$n = count($sup_n);
	for ($i=0; $i<$n; ++$i) {
		$nn .= $sep . $sup_n[$i];
		$vv .= $sep . QIT($sup_v[$i]);
		$sep = ",";
	}
	$nn .= ")";
	$vv .= ")";
	
	$query = "INSERT INTO " . $db . $nn . " VALUES " . $vv;
	
	$res = mysqli_query($emperator, $query);

	return $res;

}

function ROD( $db, $id_n, $id_v, $key, $def ) // Read Or Default
{
	global $emperator;

	$query = "SELECT * FROM " . $db . " WHERE";

	$n = count($id_n);
	for ($i=0; $i<$n; ++$i) {
		$query .= ($i==0) ? " " : " AND ";
		$query .= $id_n[$i] . "=" . QIT($id_v[$i]);
	}
	if ($res = mysqli_query($emperator, $query))
		if ($row = mysqli_fetch_array($res))
			return $row[$key];
	return $def;
}

function empgreen()
{
	return "#96BF0D";
}


?>
