<?php
include 'connect.php';
doDB();

    $safe_topic_id = mysqli_real_escape_string($mysqli, $_GET['topic_id']);

    $verify_sql = "SELECT TopicId, Likes FROM rj_topics
    WHERE TopicId = '".$safe_topic_id."'";

    $verify_res = mysqli_query($mysqli, $verify_sql) or die(mysqli_error($mysqli));

    //get the topic id and likes
    while($topic_info = mysqli_fetch_array($verify_res)) {
        $topic_id = $topic_info['TopicId'];
        $topic_likes = $topic_info['Likes'];
    }

    if($topic_likes >= 0){
        $topic_likes = $topic_likes + 1;
        $sql = "UPDATE rj_topics SET Likes = '".$topic_likes."' WHERE TopicId = '".$topic_id."'";
        $add_like_res = mysqli_query($mysqli, $sql) or die(mysqli_error($mysqli));
        header("Location: topiclist.php");
        exit;
    }
    else{
        $display_block = "Unable to Like Post";
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
    <title>Liked Post</title>
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