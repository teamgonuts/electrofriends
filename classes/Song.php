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
        echo '<tr>
        <td>
            <iframe title="YouTube video player" class="youtube-player" type="text/html" 
            width="320" height="195" src="http://www.youtube.com/embed/'. $this->ytcode .'"
            frameborder="0" allowFullScreen></iframe> <br />
            Title: ' . $this->title . "<br />" .
            "Artist: " . $this->artist . "<br />" .
            "Genre: " . $this->map($this->genre) . "<br />" .
            "Uploaded By: " . $this->user . "<br />" .
            "Ups: " . $this->ups . "<br />" .
            "Downs: " . $this->downs . "<br />" .
            "Rank: " . $this->score . "<br />" .
        '</td>
        <td>
        <a align="center" class="button" style="margin-top:0px; margin-bottom:0px;"> 
                    <form action="index.php" method="post" style="text-align:center">
                        <input type="hidden" name="vote" value=1>
                        <input type="submit" name="upvote" value="+" style="width:30px; font-size:20px text-align:center;" /> 
                    </form>
        </a>
        <a align="center" class="button" style="margin-top:0px; margin-bottom:0px;"> 
                    <form action="index.php" method="post" style="text-align:center">
                        <input type="hidden" name="vote" value=-1>
                        <input type="submit" name="downvote" value="-" style="width:30px; font-size:20px text-align:center;" /> 
                    </form>
        </a>
        </td>
        </tr>';
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