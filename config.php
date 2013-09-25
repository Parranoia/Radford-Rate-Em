<?php    
    error_reporting(1);
    include_once('include/chromephp.php');

    $username = "root";
    $password = "";
    $host = "127.0.0.1";
    $dbname = "radford_rate_em";

    $options = array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8');

    try
    {
        $db = new PDO("mysql:host={$host};dbname={$dbname};charset=utf8", $username, $password, $options);
    } 
    catch (PDOException $e)
    {
        die("Failed to connect to the database: " . $e->getMEssage());
    }

    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

    header('Content-Type: text/html; charset=utf-8');

    session_start();