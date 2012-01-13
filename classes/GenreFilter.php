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
        if($this->genre == 'all')
            return "genre=genre";
        else
            return "genre = '" . $this->genre . "'";
    }
    
    function getGenre()
    {
        $in = $this->genre;
        if($in== 'all')
            $in = 'tracks';
        if($in == 'dnb')
            $in = 'DnB';
    
    return ucfirst($in);
    }
}
?>
