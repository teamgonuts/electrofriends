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
        echo '<table border="1" align="center">';

            $where = $this->datefilter->genSQL() . ' AND ' . $this->genrefilter->genSQL();
            //echo $where;
            
            //hardcoding DB
            $qry = mysql_query("SELECT * FROM  `songs` 
                                WHERE $where
                                ");
                if (!$qry)
                    die("FAIL: " . mysql_error());
            
            while($row = mysql_fetch_array($qry))
            {
                $song = new Song($row);
                $song->show();
            }
            echo '</table>';
    }
}
?>