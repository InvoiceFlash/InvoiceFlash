<?php
$mysqli = new mysqli('127.0.0.1', 'root', '', 'invoiceflash', 3306);

if ($mysqli->connect_errno) {
	exit('Connect failed: ' . $mysqli->connect_error);
}

$result = $mysqli->query("SELECT user_group_id, permission FROM user_group WHERE user_group_id = 1");
$row = $result->fetch_assoc();

$permission = unserialize($row['permission']);

foreach (array('access', 'modify') as $key) {
	if (!in_array('purchase/purchase_order', $permission[$key])) {
		$permission[$key][] = 'purchase/purchase_order';
	}
}

$serialized = $mysqli->real_escape_string(serialize($permission));

$mysqli->query("UPDATE user_group SET permission = '" . $serialized . "' WHERE user_group_id = 1");

echo "Updated. Affected rows: " . $mysqli->affected_rows . "\n";

$mysqli->close();
