<?php

//$mongoURI = "mongodb://FNVJoe:p455w0rd@192.168.33.4/admin";
$mongoURI = "mongodb://FNVAnwar:557abef8202588b4658b4579@localhost/admin";

$m = new MongoClient($mongoURI);

$db = $m->selectDB("eforms");

$gridfs = $db->getGridFS('images');

$args = array(
    "name"=>FILTER_SANITIZE_STRING
);


$filename = filter_input_array(INPUT_GET, $args, false);
$output = $gridfs->findOne($filename);

if($output)
{
    if(isset($output->file["mime type"]))
    {
        header("Content-type: ".$output->file["mime type"]);
        echo $output->getBytes();
    }
    else
    {
        echo "No mime type found";
    }
}
else
{
    if(!isset($filename["name"]))
    {
        echo "No file searched for";
    }
    else
    {
        echo $filename["name"]. " not found!";
    }
    
}




