<?php
include ("RankableItem.php");

class Song extends RankableItem
{
    //Make public or private?
    public $title;
    public $artist;
    public $genre;
    public $ytcode;
    public $upload_date;
    public $id;
    public $user;
    public $ups;
    public $downs;
    public $score;
    
    public function Song($row)
    {
        $this->id = $row['id'];
        $this->upload_date = $row['uploaded_on'];
        parent::RankableItem($this->id, $this->upload_date);
        
        $this->title = $row['title'];
        $this->artist = $row['artist'];
        $this->genre = $row['genre'];
        $this->ytcode = $row['youtubecode'];
        $this->user = $row['user'];
        $this->ups = $row['ups'];
        $this->downs = $row['downs'];
        $this->score = $row['score'];
    }
	
	//constructor can be passed the youtube url
	public function Song_URL($url)
	{
		 $instance = new self();
	}
    
    function map($in)
    {
        if($in == 'DnB')
            return 'Drum & Bass';
        else
            return $in;
    }
    
    //Generates HTML to display all info and embedded youtube vid
    //Code generated to go <table>HERE</table>
    function show()
    {
        echo "<tr><td>" . 
        "URL: " . $this->ytcode . "<br />" .
        "Title: " . $this->title . "<br />" .
        "Artist: " . $this->artist . "<br />" .
        "Genre: " . $this->map($this->genre) . "<br />" .
        "Uploaded By: " . $this->user . "<br />" .
        "Ups: " . $this->ups . "<br />" .
        "Downs: " . $this->downs . "<br />" .
        "Rank: " . $this->score . "<br />" .
        "</td></tr>";
    }
    

    //Generates HTML to display all basic info of song
    //Code generated to go <table>HERE</table> 
    function showMin()
    {
        echo "<tr>".
        "<td>" . $this->title . " - " . $this->artist . "</td>" .
        "<td>Score - " . $this->score . "</td>" .
        "</tr>";
    }
    
    function uploadDate(){
        return parent::$uploadDate;
    }
    
    function rankNum(){
        return 69;
    }
    
    //======================GETTER METHODS=======================/

}
?>