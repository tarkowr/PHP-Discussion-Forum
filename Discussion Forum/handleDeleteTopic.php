<?php
include 'connect.php';
doDB();

    $safe_topic_id = mysqli_real_escape_string($mysqli, $_GET['topic_id']);

    $sql = "SELECT Owner FROM rj_topics WHERE TopicId = '".$safe_topic_id."'";
    $verify_owner = mysqli_query($mysqli, $sql) or die(mysqli_error($mysqli));

    while ($topic_info = mysqli_fetch_array($verify_owner)) {
        $topic_owner = stripslashes($topic_info['Owner']);
    }

    if($topic_owner == stripslashes($_SESSION["user"])){

        $verify_sql = "DELETE FROM rj_topics
        WHERE TopicId = '".$safe_topic_id."'";
    
        $verify_res = mysqli_query($mysqli, $verify_sql) or die(mysqli_error($mysqli));
    
        if($verify_res){
    
            $delete = "DELETE FROM rj_posts
            WHERE PostId = '".$safe_topic_id."'";
            
            $verify_delete = mysqli_query($mysqli, $delete) or die(mysqli_error($mysqli));
    
            if($verify_delete){
                header("Location: topiclist.php");
                exit;
            }
            else{
                $display_block = "Unable to Delete the Post";
            }
        }
        else{
            $display_block = "Unable to Delete the Topic";
        }
    }
    else{
        header("Location: topiclist.php");
        exit;
    }


    mysqli_close($mysqli);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel = "stylesheet" href = "css/menu.css">
    <title>Delete Topic</title>
    <style>
        p{
            text-align: center;
            color: red;
        }
        a{
            text-align: center;
            color: black;
            text-decoration: underline;
        }
        a:hover{
            text-decoration: none;
        }
        #logOut{
            background-color: #4286f4;
            color: white;
            position: fixed;
            top: 15px;
            right: 15px;
            padding: 5px 20px;
            border: 1px solid white;
            border-radius: 20px;
        }
        #logOut:hover{
            background-color: white;
            color: #4286f4;
        }
    </style>
</head>
<body>
<header id = "top">
        <h1> March Madness Forum Menu </h1>
    </header>
    <section id = "slogan">
        <p> - For people who love basketball - </p>
    </section>
    <p><?php echo $display_block?></p>
    <p><a href = 'topiclist.php' style = 'color: black;'>Return to March Madness Topics</a></p>

    <a href = "logOut.php"><button id = "logOut">Log Out</button></a>
</body>
</html>