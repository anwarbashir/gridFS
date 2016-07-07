<?php
/*
* This script retrieves an image that has been stored in the database 
* Usage: viewGridFS.php?name=name of file in database
*/

//$mongoURI = "mongodb://FNVJoe:p455w0rd@192.168.33.4/admin";
//$mongoURI = "mongodb://anwar:56015238733391a07c773fc6@cloud2.plenary-group.com:27019/anwar";

//$mongoURI = "mongodb://FNVAnwar:P%40r%40N01A@cloud2.plenary-group.com,cloud2.plenary-group.com+:27018,web.plenary-group.com:27017/stem";
//$mongoURI = "mongodb://stem:564e2acc13cbf82b4fb2ed3b@cloud2.plenary-group.com,cloud2.plenary-group.com+:27018,web.plenary-group.com:27017/stem?replicaSet=rsUpgrade";

//$mongoURI  = "mongodb://anwar:56015238733391a07c773fc6@cloud2.plenary-group.com:27019/anwar";

$mongoURI = "mongodb://stem:564e2acc13cbf82b4fb2ed3b@cloud2.plenary-group.com,cloud2.plenary-group.com+:27018,web.plenary-group.com:27017/stem?replicaSet=rsUpgrade";

$m = new MongoClient($mongoURI);

$db = $m->selectDB("stem");

$gridfs = $db->getGridFS('testimages');

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




