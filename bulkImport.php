<?php
//        include '../AJAX/settings.php';
//
//        $m = new MongoClient($logins[CURRENT]);
//        $db = substr($logins[CURRENT], strrpos($logins[CURRENT], '/') + 1);
        include "../../private/FNV.php";
        $m = new MongoClient(FNVCollection::getMongoURI());
        $db = DATABASE;

        $forms = $m->selectDB($db)->testforms;
        $tasks = $m->selectDB($db)->testtasks;

        $defaults = array("Pass", "N/A", "Fail");
        $default = array();
        foreach ($defaults as $val) {
            $default["radiogroup"][] = array("input" => array("value" => $val, "name" => "result", "type" => "radio"));
        }

      
        $form = array();        
        $section = array();
        $activity = array();
        
        $form["task count"] = 0;
        $formCount = 0;
        
        $result = array();
        

         if ($_FILES["file"]) {
            $handle = fopen($_FILES["file"]["tmp_name"], "r");
            $headers = fgetcsv($handle);
            
            $lastrept = '';
            $lastservices = '';
            $services = array();
            
            $serviceNames = array();
            
            $allServices = array
                          (
                           "A" => "Analysis",
                           "B" => "Burner",
                           "C" => "Conductivity",
                           "D" => "Density",
                           "E" => "Voltage",
                           "F" => "Flow",
                           "H" => "Hand",
                           "I" => "Current",
                           "J" => "Power",
                           "K" => "Time",
                           "L" => "Level",
                           "M" => "Moisture",
                           "O" => "Fire and Gas",
                           "P" => "Pressure",
                           "Q" => "Quantity",
                           "R" => "Radioactivity",
                           "S" => "Speed",
                           "T" => "Temperature",
                           "V" => "Vibration",
                           "W" => "Weight",
                           "X" => "Emergency Shutdown",
                           "Y" => "Valve",
                           "Z" => "Position"
                           );
            
          while ($row = fgetcsv($handle)) {

           //-------------------Activity-----------------------   
            if ($row[2] != $activity["description"] && $activity["description"] != "") {  //if activity changes             
                $form["task count"]++;
                $activity["_id"] = new MongoId();
                
                $services = explode(',', $lastservices);
                
                foreach ($services as $service){
                    if (!empty($service)){
                    $serviceNames[] = $allServices[$service];
                    }
                } 
                
                //echo json_encode($serviceNames)."<br>";
                //$section["tasks"][] = array("task_id"=>$activity["_id"], "repeating"=>$row[12] === "TRUE" ? true : false);
                $section["tasks"][] = array("task_id" => $activity["_id"], "services" => $serviceNames, "repeating" => $lastrept === "TRUE" ? true : false, "minutes" => $lastmins, "multiplier" => $lastmult);
                
                $serviceNames = array();
                $checkEmpty = $forms->findOne(array('name'=>$form["name"]));
                
                if (!empty($checkEmpty)) {
                    $result[] = array("activity" => "not inserted");
                }
                else {
                       try {
                           $result[] = $tasks->insert($activity, array("w" => 1));
                           //echo json_encode($result)."<br>";
                           } catch(MongoCursorException $e)
                           {
                               $error = array("failed" => 0, "err" => "unable to insert", "errmsg" => "form already exists");
                               $result[] = $error;

                           }    
                    
                    //$tasks->insert($activity);
                    
                }
                
                $activity = array();
            }
            $activity["description"] = $row[2];
      
            //---------------Section------------------------
            
            if (($row[11] != $section["heading"] && $section["heading"] != "") || ($row[0] != $form["name"] && $form["name"] != "")) { // if section changes or form name changes
              //echo $row[11]."<br>";
              
              $form["sections"][] = $section; //add sections to form
              //echo json_encode($form)."<br>";
              $section = array(); //empty section
              
            }
            $section["heading"] = $row[11];
            
            //-------------------Form---------------------------------
            if ($row[0] != $form["name"] && $form["name"] != ""){ //if form changes

                $formCount++;
                
           try {
           $result[] = $forms->insert($form, array("w" => 1));
           //echo json_encode($result)."<br>";
           } catch(MongoCursorException $e)
           {
               $error = array("failed" => 0, "err" => "unable to insert", "errmsg" => "form already exists");
               $result[] = $error;
               //echo json_encode($result)."<br>";
           }    
                $form = array();
                $form["task count"] = 0;
                
            }
             $form["name"] = $row[0];
             $form["title"] = $row[1];
             $form["discipline"] = $row[6];
             $form["type"] = $row[7];
             $form["revision"] = intval($row[8]);
             $form["signatures required"] = $row[9];
             $form["source"] = $row[10];
             $form["edited"] = array("by" => "somebody", "timestamp" => new MongoDate());
             $form["record test equipment"] = $row[13] === "TRUE"? true : false;
              
             
             //$form["services"] =  $row[14];

            $activity["inputs"][] = getInput($row[3], $row[4], $row[5]);  //add input to activity
           // $activity["minutes"] = intval($row[15]);
           // $activity["multiplier"] = intval($row[16]);
         
            $lastrept = $row[12];
            $lastservices = $row[14];
            $lastmins = intval($row[15]);
            $lastmult = intval($row[16]);

        } // while loop
        
           $form["task count"]++;
           $activity["_id"] = new MongoId();
           $services = explode(',', $lastservices);
                
                foreach ($services as $service){
                    if ($service == "A") {
                        $serviceNames[] = str_replace("A", "Analysis", $service);
                    }
                    if ($service == "B") {
                        $serviceNames[] =  str_replace("B", "Burner", $service);
                    }
                    if ($service == "C") {
                        $serviceNames[] = str_replace("C", "Conductivity", $service);
                    }
                    if ($service == "D") {
                        $serviceNames[] = str_replace("D", "Density", $service);
                    }
                     if ($service == "E") {
                        $serviceNames[] = str_replace("E", "Voltage", $service);
                    }
                     if ($service == "F") {
                        $serviceNames[] = str_replace("F", "Flow", $service);
                    }
                     if ($service == "H") {
                        $serviceNames[] = str_replace("H", "Hand", $service);
                    }
                     if ($service == "I") {
                        $serviceNames[] = str_replace("I", "Current", $service);
                    }
                     if ($service == "J") {
                        $serviceNames[] = str_replace("J", "Power", $service);
                    }
                     if ($service == "K") {
                        $serviceNames[] = str_replace("K", "Time", $service);
                    }
                     if ($service == "L") {
                        $serviceNames[] = str_replace("L", "Level", $service);
                    }
                     if ($service == "M") {
                        $serviceNames[] = str_replace("M", "Moisture", $service);
                    }
                     if ($service == "O") {
                        $serviceNames[] = str_replace("O", "Fire and Gas", $service);
                    }
                     if ($service == "P") {
                        $serviceNames[] = str_replace("P", "Pressure", $service);
                    }
                     if ($service == "Q") {
                        $serviceNames[] = str_replace("Q", "Quantity", $service);
                    }
                     if ($service == "R") {
                        $serviceNames[] = str_replace("R", "Radioactivity", $service);
                    }
                     if ($service == "S") {
                        $serviceNames[] = str_replace("S", "Speed", $service);
                    }
                     if ($service == "T") {
                        $serviceNames[] = str_replace("T", "Temperature", $service);
                    }
                     if ($service == "V") {
                        $serviceNames[] = str_replace("V", "Vibration", $service);
                    }
                     if ($service == "W") {
                        $serviceNames[] = str_replace("W", "Weight", $service);
                    }
                     if ($service == "X") {
                        $serviceNames[] = str_replace("X", "Emergency Shutdown", $service);
                    }
                     if ($service == "Y") {
                        $serviceNames[] = str_replace("Y", "Valves", $service);
                    }
                     if ($service == "Z") {
                        $serviceNames[] = str_replace("Z", "Position", $service);
                    }
                }
                
           //echo json_encode($serviceNames)."<br>";
           //$section["tasks"][] = array("task_id"=>$activity["_id"], "repeating"=>$row[12] === "TRUE" ? true : false); 

//           $section["tasks"][] = array("task_id" => $activity["_id"], "s" => $serviceNames, "repeating" => $lastrept === "TRUE" ? true : false, "minutes" => $lastmins);
           $section["tasks"][] = array("task_id" => $activity["_id"], "services" => $serviceNames, "repeating" => $lastrept === "TRUE" ? true : false, "minutes" => $lastmins, "multiplier" => $lastmult);
           
           $serviceNames = array();
           $form["sections"][] = $section; //add sections to form
           
           //echo json_encode($form)."<br>";
           $formCount++;
           try {
           $result[] = $forms->insert($form, array("w" => 1));
           //echo json_encode($result)."<br>";
           } catch(MongoCursorException $e)
           {
               $error = array("failed" => 0, "err" => "unable to insert", "errmsg" => "form already exists");
               $result[] = $error;
               
           }         
          $result[] = array("form count: " => $formCount);
          
          //echo json_encode($activity)."<br>";
          
          //$checkEmpty = $forms->findOne(array('name'=>$form["name"]));
                
                if (!empty($checkEmpty)) {
                    //echo "Form already exists"."<br>";
                    $result[] = array("activity" => "not inserted");
                }
                else {
                    
                     try {
                           $result[] = $tasks->insert($activity, array("w" => 1));
                           //echo json_encode($result)."<br>";
                           } catch(MongoCursorException $e)
                           {
                               $error = array("failed" => 0, "err" => "unable to insert", "errmsg" => "form already exists");
                               $result[] = $error;

                           }         
                    
                    //$tasks->insert($activity);
                    
                }       
       
       fclose($handle);
       //echo json_encode($result)."<br>";
       //echo json_encode(array("file"=>$_FILES['file']['name'], "output"=>$output));
      } //end if
      
        function getInput($type, $name, $unit) {
            global $default;
            //$inputs = array();

            if ($type) {
                $input = array();
                if ($type) {
                    $input["type"] = $type;
                }
                if ($name) {
                    $input["name"] = mb_convert_encoding($name, 'ISO-8859-1', 'UTF-8');
                }
                if ($unit) {
                    $input["unit"] = mb_convert_encoding($unit, 'ISO-8859-1', 'UTF-8');
                }
                return array("input" => $input);
            } else {
                return $default;
            }
        } 
        ?>
