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
    //Rankings are a table with 1 item per row, 1st item expanded, the rest minimized
    public function display()
    {
		echo '<center>';
        //=================BEST OF TABLE=====================//
        $i = 1;
		$songsPerPage = 25; //default
		$upperLimit = $songsPerPage;
        echo '<table border="1" class="rankings">';
		$where = $this->datefilter->genSQL() . ' AND ' . $this->genrefilter->genSQL();
        $topOf = $this->datefilter->getDays();
		echo '<input type="hidden" id="where" value="'.$where .'" />
			  <input type="hidden" id="topOf" value="'.$topOf .'" />
			  <input type="hidden" id="songsPerPage" value="'.$songsPerPage .'" />
			  <input type="hidden" id="upperLimit" value="'.$upperLimit .'" />';
		
        //echo $where;
        //                            LIMIT 0 , $upperLimit
        //hardcoding DB
		if($topOf == 'New') //newest was selected
		{
			$qry = mysql_query("SELECT * FROM  `songs` 
                            WHERE $where
                            ORDER BY uploaded_on DESC
                            LIMIT 0 , $upperLimit
                            ");
		}
		else
		{
			$qry = mysql_query("SELECT * FROM  `songs` 
                            WHERE $where
                            ORDER BY score DESC
                            LIMIT 0 , $upperLimit
                            ");
		}					
            if (!$qry)
                die("FAIL: " . mysql_error());
        
		if($this->datefilter->getDays() == 'New') 
		{
			echo'<center>
                    <b>The Freshest '. $this->genrefilter->getGenre() . '</b>
              </center>
            ';
		}
		else
		{

		echo '<center>
                    <b>The Top '. $this->genrefilter->getGenre() . ' of the ' . $this->datefilter->getDays() . '</b>
              </center>
            ';
		}
        while($row = mysql_fetch_array($qry))
        {
            $song = new Song($row, $i);
			echo '<tr class="song" id="'.$i.'">';
			if($i != 1) //show every other one minimized
			{
				$song->showMin();
			}
			else //show the first one maximized
			{
				$song->show();
			}
			echo '</tr>';
			$i ++;

        }
        //====================END TABLE===================//
        echo '</table>';
	    if ($i >= $songsPerPage)
            echo '<button class="showMore" style="margin:10;"> Show More </Button>';
		echo '</center>';

    }
}
?>