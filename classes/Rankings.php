<?php

class Rankings 
{
    private $filters;
    private $artist_set = false; //default
    
    //@params: $filty is the filter given to these rankings
    public function Rankings($filters)
    {
        $this->filters = $filters;
    }
    
    //@return: returns the html code for the rankings
    //Rankings are a table with 1 item per row, 1st item expanded, the rest minimized
    public function display()
    {
        $i = 1;
		$songsPerPage = 20; //default
		$upperLimit = $songsPerPage;
        echo '<input type="hidden" id="songs-per-page" value="' . $songsPerPage . '"\>';
        
        if(array_key_exists('artist' , $this->filters)) //if an artist was selected, sort by artist and date
        {
            $this->artist_set = true;
            $where = $this->filters['date']->genSQL() . ' AND ' . $this->filters['artist']->genSQL();
        }
        else //sort by genre and date
		    $where = $this->filters['date']->genSQL() . ' AND ' . $this->filters['genre']->genSQL();

        $topOf = $this->filters['date']->getDaysWord();
         //=================BEST OF TABLE=====================//
		echo '<table border="1" id="rankings-table">';

        //hardcoding DB
		if($topOf == 'New') //newest was selected, order by upload date
		{
			$qry = mysql_query("SELECT * FROM  `songs` 
                            WHERE $where
                            ORDER BY uploaded_on DESC
                            LIMIT 0 , $upperLimit
                            ");
		}
		else //order by score
		{
			$qry = mysql_query("SELECT * FROM  `songs` 
                            WHERE $where
                            ORDER BY score DESC
                            LIMIT 0 , $upperLimit
                            ");
		}					
            if (!$qry)
                die("FAIL: " . mysql_error());

        echo $this->genTitle();

        while($row = mysql_fetch_array($qry))
        {
            $song = new Song($row, $i);
			echo $song->showClasses();
			$i ++;

        }
        echo '</table>';
        //====================END TABLE===================//
        if ($i >= $songsPerPage)
            echo '<div id="showMoreDiv"><button id="showMoreSongs"> Show More </Button></div>';

    }

    //returns the title for the table given the rankings filters
    private function genTitle()
    {
        $ret = '<p class="rankings-title">'; //string to return
        if($this->filters['date']->getDaysWord() == 'New')
		{
              if($this->artist_set) //if an artist is selected
                  $ret .= 'The Freshest '. ucwords($this->filters['artist']->getName());
              else
                  $ret .= 'The Freshest '. $this->filters['genre']->getGenre();
		}
		else
		{
            if($this->artist_set)
                $ret .= 'The Top '. ucwords($this->filters['artist']->getName()) . ' of the ' . $this->filters['date']->getDaysWord();
            else
                $ret .= 'The Top '. $this->filters['genre']->getGenre() . ' of the ' . $this->filters['date']->getDaysWord();
		}
        $ret .= "</p>";
        return $ret;
    }
}
?>