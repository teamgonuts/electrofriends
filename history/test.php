<html>
    <head>
        <!-- styles needed by jScrollPane -->
        <link type="text/css" href="plugins/jPane/jquery.jscrollpane.css" rel="stylesheet" media="all" />

        <!-- latest jQuery direct from google's CDN -->
        <script type="text/javascript" src="jquery.js"></script>

        <!-- the mousewheel plugin - optional to provide mousewheel support -->
        <script type="text/javascript" src="plugins/jPane/jquery.mousewheel.js"></script>

        <!-- the jScrollPane script -->
        <script type="text/javascript" src="plugins/jPane/jquery.jscrollpane.min.js"></script>

        <script type="text/javascript">

        $(function()
        {
            $('.scroll-pane').jScrollPane();
        });
        </script>

        <LINK href="plugins/jPane/test.css" rel="stylesheet" type="text/css">


    </head>
    <body>
    <div class="header">
        <p class="title">t3k.no</p>
    </div>
    <div class="filters">Bla bla</div>
    <div class="main-container">
    <table class="rankings-container">
    <?php
    for ($i = 0; $i < 100; $i++)
    {
        echo '<tr><td>'. $i . '</td></tr>';
    }
    ?>
    </table>
    </div>
    <div class="player-container">
            <p class="player-swf">swf</p>
    </div>
   </body>
 </html>

