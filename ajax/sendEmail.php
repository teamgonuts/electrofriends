<?php

/* All form fields are automatically passed to the PHP script through the array $HTTP_POST_VARS. */
$email = $_POST['email'];
$name = $_POST['name'];
$message = $_POST['message'] . '  [' . $email . ']';
$subject = "User feedback from: " . $name . '[' . $email . ']';
$to = "officialt3kno@gmail.com";

//checking to see if they've sumbitted in the last 30seconds
if(isset($_COOKIE['contact'])){
  echo '<h4>Please wait 30 seconds before submitting again</h4>';
}
else
{

    /* PHP form validation: the script checks that the Email field contains a valid email address and the Subject field isn't empty. preg_match performs a regular expression match. It's a very powerful PHP function to validate form fields and other strings - see PHP manual for details. */
    if (!preg_match("/\w+([-+.]\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*/", $email)) {
      echo "<h4>Invalid email address</h4>";
    } elseif ($name == "") {
      echo "<h4>Please enter a name</h4>";
    }
    else if ($_POST['message'] == ""){
        echo "<h4>Please enter a message</h4>";
    }
    /* Sends the mail and outputs the "Thank you" string if the mail is successfully sent, or the error string otherwise. */
    elseif (mail($to, $subject, $message)) {
        //setting cookie
        $inThirtySeconds = 30 + time(); 
        setcookie("contact", "wait", $inThirtySeconds);
        echo "<h4>E-mail successfully sent</h4>";
    } else {
        echo "<h4>Can't send email: Unknown Error</h4>";
    }
}
?>
