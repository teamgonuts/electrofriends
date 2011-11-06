<?php

class Rankings 
{
    private $datefilter;
    private $genrefilter;
    
    //@params: $filty is the filter given to these rankings
    public function Rankings($df, $gf)
    {
        $this->datefilter = $df;
        $this->genrefilter = $gf;
    }
    
    //@return: returns the html code for the rankings
    //Rankings are a table with 1 item per row
    public function display()
    {
        echo'<table border="0" align="center"><tr><td  valign="top">';
        //=================BEST OF TABLE=====================//
        $i = 1;
        echo '<table border="1" style="padding-top:0px; margin-top:0px;">';

        $where = $this->datefilter->genSQL() . ' AND ' . $this->genrefilter->genSQL();
        //echo $where;
        
        //hardcoding DB
        $qry = mysql_query("SELECT * FROM  `songs` 
                            WHERE $where
                            ORDER BY score DESC
                            LIMIT 0 , 30
                            ");
            if (!$qry)
                die("FAIL: " . mysql_error());
        echo '<center>
                    <b>The Best '. $this->genrefilter->getGenre() . ' of the ' . $this->datefilter->getDays() . '</b>
              </center>
            <br />';
        while($row = mysql_fetch_array($qry))
        {
            echo '<tr>';
            $song = new Song($row);
            echo '<td><pre>  '.$i.'  </pre></td>';
            $i ++;
            $song->show();
            echo '</tr>';
        }
        echo '</table>';
        //====================END TABLE===================//
        echo '</td><td valign="top">';
        
        //================NEWEST TABLE=================//
        $i = 1;
        echo '<table border="1" align="center">';
        $where = $this->genrefilter->genSQL();
        
        //hardcoding DB
        $qry = mysql_query("SELECT * FROM  `songs` 
                            WHERE $where
                            ORDER BY uploaded_on DESC
                            LIMIT 0 , 30
                            ");
            if (!$qry)
                die("FAIL: " . mysql_error());
        echo '<center>
                    <b>Freshest ' . $this->genrefilter->getGenre() . '</b>
              </center>
              <br />';
        while($row = mysql_fetch_array($qry))
        {
            echo '<tr>';
            $song = new Song($row);
            echo '<td><pre>  '.$i.'  </pre></td>';
            $i ++;
            $song->show();
            echo '</tr>';
        }
        echo '</table>';
        //====================END TABLE===================//

        echo '</td></tr></table>';
    }
}
?>