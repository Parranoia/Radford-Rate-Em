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

    // Converts an integer to a grade
    function intToGrade($num)
    {
        if ($num != null)
        {
            $num = (int)$num;
            switch($num)
            {
                case 0:
                    $grade = "F";
                    break;
                case 1:
                    $grade = "D-";
                    break;
                case 2:
                    $grade = "D";
                    break;
                case 3:
                    $grade = "D+";
                    break;
                case 4:
                    $grade = "C-";
                    break;
                case 5:
                    $grade = "C";
                    break;
                case 6:
                    $grade = "C+";
                    break;
                case 7:
                    $grade = "B-";
                    break;
                case 8:
                    $grade = "B";
                    break;
                case 9:
                    $grade = "B+";
                    break;
                case 10:
                    $grade = "A-";
                    break;
                case 11:
                    $grade = "A";
                    break;
                case 12:
                    $grade = "A+";
                    break;
                default:
                    $grade = "N/A";
                    break;
                
            }   
        }
        else
            $grade = "N/A";
        
        return $grade;
    }

    header('Content-Type: text/html; charset=utf-8');

    session_start();