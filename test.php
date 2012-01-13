<html>
<head>

<meta charset="UTF-8">

<!-- mainstyles -->
<link href="styles/main.css" rel="stylesheet" type="text/css">

<!-- fancybox -->
    <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.4/jquery.min.js"></script>
    <script type="text/javascript" src="coffee/test.js"></script>
    <script type="text/javascript" src="coffee/rankings.js"></script>
    
    <script>
            !window.jQuery && document.write('<script src="jquery-1.4.3.min.js"><\/script>');
    </script>
    <script type="text/javascript" src="js/fancybox/jquery.mousewheel-3.0.4.pack.js"></script>
    <script type="text/javascript" src="js/fancybox/jquery.fancybox-1.3.4.pack.js"></script>
    <link rel="stylesheet" type="text/css" href="js/fancybox/jquery.fancybox-1.3.4.css" media="screen" />
    <script type="text/javascript">
            $(document).ready(function() {

                    $("#upload").fancybox({
                            'titlePosition'		: 'inside',
                            'transitionIn'		: 'none',
                            'transitionOut'		: 'none'
                    });

            });
    </script>


<!-- typekit -->
<script type="text/javascript" src="http://use.typekit.com/wlh5psa.js"></script>
<script type="text/javascript">try{Typekit.load();}catch(e){}</script>

</head>

<body>
    <table id="rankings-table" cellspacing="0"><tbody>
<?php

include ("connection.php");
include ("classes/Song.php");
$qry = mysql_query("SELECT * FROM  `songs` 
                    ORDER BY uploaded_on DESC
                    LIMIT 0 , 30
                   ");
if (!$qry)
    die("FAIL: " . mysql_error());

$i = 1;
while($row = mysql_fetch_array($qry))
{
    $song = new Song($row, $i);
    echo $song->showClasses();
    $i ++;
    

}

?>
</tbody></table>
    <p>Hiya</p>
</body>
</html>
