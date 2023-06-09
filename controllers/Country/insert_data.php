<?php 
    include_once '../../app.php';
    use Models\Country;
    $_METHOD = $_SERVER["REQUEST_METHOD"];
    $_DATA = ($_METHOD=="POST" && count($_FILES)) ? array_merge($_POST,$_FILES): json_decode(file_get_contents("php://input"), true);
    $objCountry =new Country();
    
    echo json_encode($objCountry->saveData($_DATA)); 
 
?>