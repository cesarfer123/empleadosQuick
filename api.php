<?php

// echo "here";

function query($query){
    $res=null;
    
    if(!$con=mysqli_connect('localhost','root','','employees')){
        die("imposible de conectar");
    }
    $result=mysqli_query($con,$query);
    if(!is_bool($result)){
        if(mysqli_num_rows($result)>0){

            while($row=mysqli_fetch_assoc($result)){
                $res[]=$row;
            }
        }
    }

    return $res;
}

if(count($_POST)){
    $info=[];
    $info["data_type"]=$_POST["data_type"];
    if($_POST["data_type"]=="read"){
        $query=         "select * from users order by id desc";
        $result=        query($query);
        $info["data"]=  $result;
    }else if($_POST["data_type"]=="save"){

        $image="";
        // check for a image
        if(!empty($_FILES["image"]["name"])){
            $allowed=["image/jpeg","image/png"];
            if(in_array($_FILES["image"]["type"], $allowed)){
                $folder="uploads/";
                if(!file_exists($folder)){
                    mkdir($folder,0777,true);
                }
                $path=$folder . time() . $_FILES["image"]["name"];
                move_uploaded_file($_FILES["name"]["tmp_name"],$path);
            }
            $image=$path;
        }

        $name=addslashes($_POST["name"]);
        $age=addslashes($_POST["age"]);
        $image=$image;
        $email=addslashes($_POST["email"]);
        $city=addslashes($_POST["city"]);
        $query=         "insert into users (nombre,edad,imagen,email,ciudad) values ('$name','$age','$image','$email','$city')";
        $result=        query($query);
        $info["data"]=  "Datos guardados!";
    }

    echo json_encode($info);
}