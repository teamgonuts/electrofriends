<?php

class ArtistFilter extends Filter {

    private $artist;

    public function ArtistFilter($art)
    {
       $this->artist = $art;
    }
    
    function genSQL()
    {
        return 'artist = \''. $this->artist . '\'';
    }

    function getName()
    {
        return $this->artist;
    }

}
?>