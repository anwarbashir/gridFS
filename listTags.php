<?php
    $mongoURI = "mongodb://stem:564e2acc13cbf82b4fb2ed3b@cloud2.plenary-group.com,cloud2.plenary-group.com+:27018,web.plenary-group.com:27017/stem?replicaSet=rsUpgrade";
    
    $m = new MongoClient($mongoURI);
    
    $db = $m->selectDB("stem");
    
    $collection = new MongoCollection($db, 'tags');
    
    $cursor = $collection->find();
    
    $cursor->sort(array('name' => 1));
    
    echo "Total Number of Tags: ".$cursor->count().'<br>';
    
    foreach ($cursor as $doc) {
    echo json_encode($doc['name']).'<br>';
     
    echo json_encode($doc['files']['photo 1']).'<br>';
    
//    $files = $doc['files'];
//    
//        foreach ($files as $attached){
//            echo json_encode($attached).'<br>';
//        }
    
     }
    
    