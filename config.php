<?php
	$conn = 'pgsql:host=localhost;dbname=teste';

	try {
		$db = new PDO($conn, 'postgres', 'root');
		$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	} catch(PDOException $e){
		exit($e->getMessage());
	}
