<?php

/* This script asks the user to choose a file to upload (the file chosen should be an image file
 * Aftr the file is chosen the user clicks on the submit button and the file is uploaded (in chunks) 
 * into the designated mongoDB collection. 
 */
    
    $fileUploaded = isset($_FILES['file']['tmp_name']) ? true : false;
    $file = $_FILES['file'];
    //$mongoURI = "mongodb://FNVI:7e6fe9681d7de834c1b43cb6f9c3b5ff@web.plenary-group.com/FNVI";
    //$mongoURI = "mongodb://anwar:56015238733391a07c773fc6@cloud2.plenary-group.com:27019/anwar";
    $mongoURI = "mongodb://stem:564e2acc13cbf82b4fb2ed3b@cloud2.plenary-group.com,cloud2.plenary-group.com+:27018,web.plenary-group.com:27017/stem?replicaSet=rsUpgrade";
    
    $m = new MongoClient($mongoURI);
    
    $db = $m->selectDB("stem");
    
    $gridfs = $db->getGridFS('testimages');
       
?>

<html>
    <head>
        <meta charset="UTF-8">
        <title></title>
    </head>
    <body>
        <?php if(!$fileUploaded){ ?>
        <form method="post" enctype="multipart/form-data">
            <input type="file" name="file">
            <br>
            <input type="submit">
        </form>
        <?php } else {
            
            echo json_encode($file);
            
            $metadata = array(
                "mime type"=>$file["type"],
                "name"=>$file["name"],
                "filename"=>$file["name"]
            );
            
            $gridfs->storeFile($file["tmp_name"], $metadata);
            
        } ?>
    </body>
</html>

