<?php
//        include 'settings.php';
//
//        $m = new MongoClient($logins[CURRENT]);
//        $db = substr($logins[CURRENT], strrpos($logins[CURRENT], '/') + 1);
$username = "FNVAnwar";
$password = "557abef8202588b4658b4579";
$db = "eforms";
$m = new MongoClient("mongodb://localhost", array("username" => $username, "password" => $password));

        $forms = $m->selectDB($db)->forms;
        
         $form = $forms->find();
         
         foreach ($form as $f){
             //echo "$id: ";
             echo json_encode($f[name])."<br>";
             
         }