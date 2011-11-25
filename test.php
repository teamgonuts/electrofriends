<html>
    <head>
        <script type="text/javascript" src="swfobject.js"></script>
        <script language="javascript" src="marquee.js"></script>
        <script language="javascript">
            $(function() {
                titleScroll();
            });
            function titleScroll()
            {
                document.title = document.title.substring(1)+document.title.substring(0,1);
                setTimeout(titleScroll, 200);
            }
        </script>
        <marquee behavior="scroll" direction="left" scrollamount="2"
          height="75" width="150">
        <title>Bitches</title>
        </marquee>
    </head>
    <body>
    <marquee behavior="scroll" direction="left" scrollamount="2"
          height="75" width="150">
          <p>This is a test of a Smooth Marquee using jquery.</p>
     </marquee>
    <?php
        $test = "Above & Beyond";
        echo $test . "<br />";
        echo safeString($test);
    ?>
   </body>
 </html>

<?php

function safeString($in)
{
	$in = strip_tags($in);
	$in = mysql_real_escape_string($in);
	return $in;
}
?>