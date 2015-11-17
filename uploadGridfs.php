<!DOCTYPE html>

<?php
    
    $fileUploaded = isset($_FILES['file']['tmp_name']) ? true : false;
    $file = $_FILES['file'];
    //$mongoURI = "mongodb://FNVJoe:p455w0rd@192.168.33.4/admin";
    $mongoURI = "mongodb://FNVAnwar:557abef8202588b4658b4579@localhost/admin";
    
    $m = new MongoClient($mongoURI);
    
    $db = $m->selectDB("eforms");
    
    $gridfs = $db->getGridFS('images');
    
    
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

