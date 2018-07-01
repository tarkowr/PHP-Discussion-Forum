<?php
include 'connect.php';
doDB();

if($_SESSION["user"] == null){
	header("Location: userLogin.html");
	exit;
}

if($_GET){
    $safe_topic_id = mysqli_real_escape_string($mysqli, $_GET['topic_id']);
    $_SESSION["topic_id"] = $safe_topic_id;
}

if(!$_POST){
    
    //get record from topics table
    $get_topic_sql = "SELECT * FROM rj_topics WHERE TopicId = $safe_topic_id;";
    $get_topic_res = mysqli_query($mysqli, $get_topic_sql) or die(mysqli_error($mysqli));
    
    // get record from topic posting table
    //$get_post_sql = "SELECT * FROM rj_posts WHERE TopicId = $safe_topic_id;";
    //$get_post_res = mysqli_query($mysqli, $get_post_sql) or die(mysqli_error($mysqli));

    $display_block = "";
    
    if (mysqli_num_rows($get_topic_res) < 1) {
        //no records
        $display_block .= "<p><em>There was an error retrieving your topic!</em></p>";
    } else {
        //topic record exists, so display topic and post information for editing
        $rec = mysqli_fetch_array($get_topic_res);
        $display_id = stripslashes($rec['TopicId']);
        $display_title = stripslashes($rec['Title']);
        $display_block .= "<form method='post' id = 'addForm' action='".$_SERVER['PHP_SELF']."'>";
        $display_block .="<p><label>Topic Title:</label> <br> <input type='text' id='topic_title' name='topic_title' value='".$display_title."'></p>";
        //$postRec = mysqli_fetch_array($get_post_res);
        //$display_post = stripslashes($postRec['PostText']);
        //$display_block .="<p><label>Post Text:</label> <br> <textarea rows='8' cols='40' style='vertical-align:text-top;' id='post_text' name='post_text'>".$display_post."</textarea></p>";
        $display_block .= "<button type='submit' class = 'styledButton' id='change' name='change' value='change'>Change Topic</button></p>";
        $display_block .="</form>";
    }
    //free result
    //mysqli_free_result($get_post_res);
    mysqli_free_result($get_topic_res);
}
else{
    $clean_topic_title = mysqli_real_escape_string($mysqli, $_POST['topic_title']);
	//$clean_post_text = mysqli_real_escape_string($mysqli, $_POST['post_text']);

	//create and issue the forum_topic update
	$update_topic_sql = "UPDATE rj_topics SET Title = '".$clean_topic_title ."' WHERE TopicId =".$_SESSION["topic_id"];
	$update_topic_res = mysqli_query($mysqli, $update_topic_sql) or die(mysqli_error($mysqli));

	//create and issue the forum_post update
	//$update_post_sql = "UPDATE rj_posts SET PostText='" .$clean_post_text."' WHERE TopicId= ".$_SESSION["topic_id"];
	//$update_post_res = mysqli_query($mysqli, $update_post_sql) or die(mysqli_error($mysqli));

	//close connection to MySQL
	mysqli_close($mysqli);

	//create nice message for user
    $display_block ="<p style = 'font-size: 20px; margin-top: 15px;'> Your Topic has been modified. </p>";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="css/forms.css">

    <title>Edit Topic</title>
</head>
<body>
<header id = "top">
    <h1> Edit Topic </h1>
</header>
<nav>
	<a href = "discussionMenu.html"><button>Main Menu</button></a>
	<a href="showtopic.php"><button>Show Topics</button></a>
	<a href = "addtopic.html"><button>Add Topic</button></a>
</nav>
<section style = "text-align: center;">
    <?php echo $display_block ?>
</section>
</body>
</html>