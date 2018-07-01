<?php
include 'connect.php';
doDB();

if($_SESSION["user"] == null){
	header("Location: userLogin.html");
	exit;
}

//check for required fields from the form
if ( /*(!$_POST['topic_owner']) || */ (!$_POST['topic_title']) || (!$_POST['post_text'])) {
	header("Location: addtopic.html");
	exit;
}

//create safe values for input into the database
$clean_topic_owner = mysqli_real_escape_string($mysqli, $_SESSION["user"]);
$clean_topic_title = mysqli_real_escape_string($mysqli, $_POST['topic_title']);
$clean_post_text = mysqli_real_escape_string($mysqli, $_POST['post_text']);

//create and issue the first query
$add_topic_sql = "INSERT INTO rj_topics (Title, Created, Owner) VALUES ('".$clean_topic_title ."', now(), '".$clean_topic_owner."')";

$add_topic_res = mysqli_query($mysqli, $add_topic_sql) or die(mysqli_error($mysqli));

//get the id of the last query
$topic_id = mysqli_insert_id($mysqli);

//create and issue the second query
$add_post_sql = "INSERT INTO rj_posts (TopicId, PostText, Created, Owner) VALUES ('".$topic_id."', '".$clean_post_text."',  now(), '".$clean_topic_owner."')";

$add_post_res = mysqli_query($mysqli, $add_post_sql) or die(mysqli_error($mysqli));

//close connection to MySQL
mysqli_close($mysqli);

//create nice message for user
$display_block = "<p>The <strong>".$_POST["topic_title"]."</strong> topic has been created.</p>";
?>
<!DOCTYPE html>
<html>
<head>
<title>New Topic Added</title>
<link href="css/forms.css" rel="stylesheet" type="text/css" />
<style>
	body{
		text-align: center;
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
        <h1> New Topic Added </h1>
</header>
<nav>
	<a href = "discussionMenu.html"><button>Main Menu</button></a>
	<a href="showtopic.php"><button>Show Topics</button></a>
	<a href = "addtopic.html"><button>Add Topic</button></a>
</nav>
<?php echo $display_block; ?>

<a href = "logOut.php"><button id = "logOut">Log Out</button></a>
</body>
</html>
