<?php

class GenreFilter extends Filter
{
    private $genre;
    
	function GenreFilter($g)
    {
        $this->genre = $g;
    }
	
	
	//generates SQL code for checking date
	//Ex. WHERE limitDate > created... if > means before
	function genSQL()
	{   
        global $genre;
        if($genre == 'all')
            return "genre=genre";
        else
            return "genre = '" . $this->genre . "'";
	}
}
?>