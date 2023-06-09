<?php 
    require_once 'vendor/autoload.php';
    use App\Database;
    use Models\Country;
    $db = new Database();
    $conn = $db->getConnection('mysql');
    Country::setConn($conn);
?>