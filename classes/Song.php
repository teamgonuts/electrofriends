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
		$in = ucfirst($in);
        if($in == 'DnB')
            return 'Drum & Bass';
        else
            return $in;
    }
    
    //Generates HTML to display all info and embedded youtube vid
    //Code generated to go <table><tr>HERE</tr></table> 
    function showLong()
    {
        echo '<tr>
        <td width="320">
            <iframe title="YouTube video player" class="youtube-player" type="text/html" 
            width="320" height="195" src="http://www.youtube.com/embed/'. $this->ytcode .'"
            frameborder="0" allowFullScreen></iframe> <br />
            <b>Title:</b> ' . $this->title . "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>Genre:</b> " . $this->map($this->genre) ."<br />" .
            "<b>Artist:</b> " . $this->artist ."&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>Uploaded By: </b>".$this->user ."<br />" .
            "<b>Score:</b> " . $this->score . " [". $this->ups ."/". $this->downs ."]<br />" .
        '</td>
        <td>
        <a align="center" class="button" style="margin-top:0px; margin-bottom:0px;"> 
                    <form action="index.php" method="post" style="text-align:center">
                        <input type="hidden" name="vote" value=1>
                        <input type="hidden" name="ytcode" value='. $this->ytcode .'>
                        <input type="submit" name="upvote" value="+" style="width:30px; font-size:20px text-align:center;" /> 
                    </form>
        </a>
        <a align="center" class="button" style="margin-top:0px; margin-bottom:0px;"> 
                    <form action="index.php" method="post" style="text-align:center">
                        <input type="hidden" name="vote" value=-1>
                        <input type="hidden" name="ytcode" value="'. $this->ytcode .'">
                        <input type="submit" name="downvote" value="-" style="width:30px; font-size:20px text-align:center;" /> 
                    </form>
        </a>
        </td>
        </tr>';
    }
    
	//generates html to display comments
	function showComments()
	{
		$ytcode = $this->ytcode;
		
		$html = '<ol id="update" class="timeline">';
		
		//old comments
        $qry = mysql_query("SELECT * FROM  `comments` 
                            WHERE '$ytcode' = youtubecode
							ORDER BY upload_date DESC
							LIMIT 0,7
                            ");
		if (!$qry)
                die("FAIL: " . mysql_error());
		while($row = mysql_fetch_array($qry))
		{
			
			$name=$row['com_user'];
			$comment_dis=$row['com_dis'];
			$date_t = $row['upload_date'];
			$date = new DateTime($date_t);
			$html .= '<li style="display: list-item;" class="box"><span class="com_name"> '.$name.'</span>:
			<span class="com_text"> ' . $comment_dis . '</span>
			<span class="com_date"> ' . $date->format('M. j, Y G:i:s') . '</span></li>';
		}
		
		//comment area
		$html .= '</ol>
		<div id="flash"></div>
		<div>
			<form action="#" method="post">
			<input type="hidden" id="ytcode" value="'.$ytcode.'"/> 
			<textarea id="comment"></textarea><br />
			Username: <input type="text" id="name" value="Anonymous"/>
			<input type="submit" class="submit" id="submit" value=" Submit Comment " />
			</form>
		</div>';
		
		return $html;
	}
	
	//@param: $i is index in rankings
    //Generates HTML to display all info and embedded youtube vid
    //Code generated to go <table>HERE</table> 
    function show($i)
    {
	//TO FIGURE OUT HOW TO VOTE ONLY ONCE: http://stackoverflow.com/questions/7056827/cookie-only-vote-once
	//http://paperkilledrock.com/2010/05/html5-localstorage-part-one/
	//Local Storage not supported before html5...so need alternate
        echo '<tr>
		<td>
		'	. $i . '
		</td>
        <td>
            <iframe title="YouTube video player" class="youtube-player" type="text/html" 
            width="240" height="146" src="http://www.youtube.com/embed/'. $this->ytcode .'"
            frameborder="0" allowFullScreen></iframe>
			<br />
			Title: ' . $this->title . '<br />
			Artist: ' . $this->artist . '<br />
			Genre: ' . $this->map($this->genre) .'<br />
			Uploaded By: '.$this->user .'<br />
			Download: <u>Amazon</u> <u>Apple</u>
        </td>
        <td class="commentTD">
		'. $this->showComments() . '
		</td>
		<td><center>
			<form action="index.php" method="post" style="text-align:center; margin-top:0px; margin-bottom:0px; display: inline; ">
						<input type="hidden" name="vote" value=1>
						<input type="hidden" name="ytcode" value='. $this->ytcode .'>
						<input type="submit" name="upvote" value="+" style="width:30px; font-size:20px text-align:center;" /> 
			</form> <br />
			' . $this->score . "[" . $this->ups . "/" . $this->downs . "]" .'<br />
			<form action="index.php" method="post" style="text-align:center; margin-top:0px; margin-bottom:0px; display: inline;">
                    <input type="hidden" name="vote" value=-1>
                    <input type="hidden" name="ytcode" value="'. $this->ytcode .'">
                    <input type="submit" name="downvote" value="-" style="width:30px; font-size:20px text-align:center;" /> 
			</form>
			</center></td>
		</tr>';
    }
	

	
	
	//@param: $i is index in rankings
    //Generates HTML to display all basic info of song
    //Code generated to go <table><tr>HERE</tr></table> 
    function showMin($i)
    {
        echo "<tr>".
		"<td><pre>" . $i . "</pre></td>" .
        "<td>" . $this->title . " - " . $this->artist . "</td>" .
		"<td>" . $this->genre . "</td>" .
        "<td>" . $this->score . "[" . $this->ups . "/" . $this->downs . "]" ."</td>" .
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