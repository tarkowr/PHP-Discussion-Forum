<?php
include 'connect.php';
doDB();

if($_SESSION["user"] == null){
	header("Location: userLogin.html");
	exit;
}

//check to see if we're showing the form or adding the post
if (!$_POST) {
   // showing the form; check for required item in query string
   if (!isset($_GET['post_id'])) {
      header("Location: topiclist.php");
      exit;
   }

   //create safe values for use
   $safe_post_id = mysqli_real_escape_string($mysqli, $_GET['post_id']);

   //still have to verify topic and post
   $verify_sql = "SELECT t.TopicId, t.Title FROM rj_posts
                  AS p LEFT JOIN rj_topics AS t ON p.TopicId =
                  t.TopicId WHERE p.PostId = '".$safe_post_id."'";

   $verify_res = mysqli_query($mysqli, $verify_sql)
                 or die(mysqli_error($mysqli));

   if (mysqli_num_rows($verify_res) < 1) {
      //this post or topic does not exist
      header("Location: topiclist.php");
      exit;
   } else {
      //get the topic id and title
      while($topic_info = mysqli_fetch_array($verify_res)) {
         $topic_id = $topic_info['TopicId'];
         $topic_title = stripslashes($topic_info['Title']);
      }
?>
      <!DOCTYPE html>
      <html>
      <head>
      <title>Post Your Reply in <?php echo $topic_title; ?></title>
      <link href="css/forms.css" rel="stylesheet" type="text/css" />
      <style>
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
        <h1> Reply to <?php echo $topic_title; ?> </h1>
      </header>
      <nav>
            <a href = "discussionMenu.html"><button>Main Menu</button></a>
            <a href="showtopic.php"><button>Show Topics</button></a>
            <a href = "addtopic.html"><button>Add Topic</button></a>
      </nav>
      <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>" id = "replyForm">

      <p><label for="post_text">Post Text:</label><br/>
      <textarea id="post_text" name="post_text" rows="8" cols="40"
         required="required" autofocus></textarea></p>
      <input type="hidden" name="topic_id" value="<?php echo $topic_id; ?>">
      <button id = "addPost" class = "styledButton" type="submit" name="submit" value="submit">Add Post</button>
      </form>

      <a href = "logOut.php"><button id = "logOut">Log Out</button></a>
      </body>
      </html>
<?php
      //free result
      mysqli_free_result($verify_res);

      //close connection to MySQL
      mysqli_close($mysqli);
   }

} else if ($_POST) {
      //check for required items from form
      if ((!$_POST['topic_id']) || (!$_POST['post_text'])/* || (!$_POST['post_owner'])*/) {
          header("Location: topiclist.php");
          exit;
      }

      //create safe values for use
      $safe_topic_id = mysqli_real_escape_string($mysqli, $_POST['topic_id']);
      $safe_post_text = mysqli_real_escape_string($mysqli, $_POST['post_text']);
      $safe_post_owner = mysqli_real_escape_string($mysqli, $_SESSION["user"]);

      //add the post
      $add_post_sql = "INSERT INTO rj_posts (TopicId, PostText,
                       Created , Owner) VALUES
                       ('".$safe_topic_id."', '".$safe_post_text."',
                       now(),'".$safe_post_owner."')";
      $add_post_res = mysqli_query($mysqli, $add_post_sql)
                      or die(mysqli_error($mysqli));

      //close connection to MySQL
      mysqli_close($mysqli);

      //redirect user to topic
      header("Location: showtopic.php?topic_id=".$_POST['topic_id']);
      exit;
}
?>

