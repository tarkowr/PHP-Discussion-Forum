<?php
include 'connect.php';
doDB();

if($_SESSION["user"] == null){
	header("Location: userLogin.html");
	exit;
}
//gather the topics
$get_topics_sql = "SELECT TopicId, Title, DATE_FORMAT(Created,  '%b %e %Y at %r') as fmt_topic_create_time, Owner, Likes, Hot FROM rj_topics ORDER BY Created DESC";
$get_topics_res = mysqli_query($mysqli, $get_topics_sql) or die(mysqli_error($mysqli));

$display_read = "<a href = 'ReadTopicList.php'><button id = 'read' class = 'styledButton'>Read Saved Topics</button></a>";
$display_save = "";
if (mysqli_num_rows($get_topics_res) >= 1) {
	$display_save = "<a href = 'SaveTopicList.php'><button id = 'save' class = 'styledButton'>Save Topics</button></a>";
}

if (mysqli_num_rows($get_topics_res) < 1) {
	//there are no topics, so say so
	$display_block = "<p><em>No topics exist.</em></p>";
} else {
	//create the display string
    $display_block = <<<END_OF_TEXT
    <table style="border: 1px solid black; border-collapse: collapse;">
    <tr>
    <th>Topic Title</th>
	<th class = 'num_posts_col'>Posts</th>
	<th class = 'num_posts_col'>Likes</th>
	<th class = 'num_posts_col'>Edit/Delete</th>
    </tr>
END_OF_TEXT;

	while ($topic_info = mysqli_fetch_array($get_topics_res)) {
		$topic_id = $topic_info['TopicId'];
		$topic_title = stripslashes($topic_info['Title']);
		$topic_create_time = $topic_info['fmt_topic_create_time'];
		$topic_owner = stripslashes($topic_info['Owner']);
		$topic_likes = $topic_info['Likes'];
		$topic_hot = $topic_info['Hot'];

		//get number of posts
		$get_num_posts_sql = "SELECT COUNT(PostId) AS post_count FROM rj_posts WHERE TopicId = '".$topic_id."'";
		$get_num_posts_res = mysqli_query($mysqli, $get_num_posts_sql) or die(mysqli_error($mysqli));

		while ($posts_info = mysqli_fetch_array($get_num_posts_res)) {
			$num_posts = $posts_info['post_count'];
		}

		if($topic_likes >= 10){
			$topic_hot = 1;
		}
		else{
			$topic_hot = 0;
		}

		if($topic_hot == 1){
			//add to display
			$display_block .= <<<END_OF_TEXT
			<tr>
			<td><a href="showtopic.php?topic_id=$topic_id"><strong>$topic_title</strong></a> <i class = "fa fa-check-circle" id = "check"></i><br/>
			Created on <label> $topic_create_time </label> <br> <i id = "userLogo" class = "fa fa-user"></i> <u> $topic_owner </u></td>
			<td class="num_posts_col">$num_posts</td><td class="num_posts_col" style = 'min-width: 60px;'><a id = "like" href = "handleLikes.php?topic_id=$topic_id"><i class = "fa fa-heart-o"></i></a> $topic_likes</td>
END_OF_TEXT;
			if($topic_owner == stripslashes($_SESSION["user"])){
				$display_block .= '<td class="num_posts_col">  <a id = "edit" href = "handleEditTopic.php?topic_id='.$topic_id.'"><i class = "fa fa-edit"></i></a> <a id = "delete" href = "handleDeleteTopic.php?topic_id='.$topic_id.'"><i class = "fa fa-trash"></i></a></td>';				
			}
			else{
				$display_block .= '<td class = "num_posts_col">Not <br> Available</td>';
			}

			$display_block .= "</tr>";
		}
		else{
			//add to display
			$display_block .= <<<END_OF_TEXT
			<tr>
			<td><a href="showtopic.php?topic_id=$topic_id"><strong>$topic_title</strong></a><br/>
			Created on <label> $topic_create_time </label> <br> <i id = "userLogo" class = "fa fa-user"></i> <u> $topic_owner </u></td>
			<td class="num_posts_col">$num_posts</td><td class="num_posts_col" style = 'min-width: 60px;'><a id = "like" href = "handleLikes.php?topic_id=$topic_id"><i class = "fa fa-heart-o"></i></a> $topic_likes</td>
END_OF_TEXT;
			if($topic_owner == stripslashes($_SESSION["user"])){
				$display_block .= '<td class="num_posts_col">  <a id = "edit" href = "handleEditTopic.php?topic_id='.$topic_id.'"><i class = "fa fa-edit"></i></a> <a id = "delete" href = "handleDeleteTopic.php?topic_id='.$topic_id.'"><i class = "fa fa-trash"></i></a></td>';				
			}
			else{
				$display_block .= '<td class = "num_posts_col">Not <br> Available</td>';
			}

			$display_block .= "</tr>";
		}
		
	}
	//free results
	mysqli_free_result($get_topics_res);
	mysqli_free_result($get_num_posts_res);

	//close connection to MySQL
	mysqli_close($mysqli);

	//close up the table
	$display_block .= "</table>";
}
?>
<!DOCTYPE html>
<html>
<head>
<title>Topics in My Forum</title>
<link rel="stylesheet" href="css/topics.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<style>
	table {
		border: 1px solid black;
		border-collapse: collapse;
	}
	th {
		border: 1px solid black;
		padding: 6px;
		font-weight: bold;
		background-color: #4286f4;
		color: white;
	}
	td {
		border: 1px solid black;
		padding: 6px;
	}
	.num_posts_col { text-align: center; }
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
        <h1> March Madness Topics </h1>
</header>
<nav>
	<a href = "discussionMenu.html"><button>Main Menu</button></a>
	<a href="showtopic.php"><button>Show Topics</button></a>
	<a href = "addtopic.html"><button>Add Topic</button></a>
</nav>
<main>
	<section id = "filler"> </section>
	<section id = "topics">
		<?php echo $display_block; 
			echo $display_save;
			echo $display_read;
		?>
	</section>
	<section id = "filler"> </section>
</main>

<a href = "logOut.php"><button id = "logOut">Log Out</button></a>
</body>
</html>
