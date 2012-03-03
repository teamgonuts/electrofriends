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
    
    public function test()
    {
        echo 'bitch!!!';
    }
    //@return: returns the html code for the rankings
    //Rankings are a table with 1 item per row, 1st item expanded, the rest minimized
    public function display()
    {
	$songsPerPage = 20; //default
	$upperLimit = $songsPerPage;
        echo '<input type="hidden" id="songs-per-page" value="' . $songsPerPage . '"\>';
        
	$where = $this->filters['date']->genSQL() . ' AND ' . $this->filters['genre']->genSQL();

        $topOf = $this->filters['date']->getDaysWord();
         //=================Rankings Table====================//
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
        //todo: generate new titles
        //echo $this->genTitle();

        $i = 1;
        while($row = mysql_fetch_array($qry))
        {
            $song = new Song($row, $i);
            echo $song->showClasses(); 
            $i ++;

        }
        //====================END TABLE===================//
        //todo: showMoreSongs buttons and logic
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
