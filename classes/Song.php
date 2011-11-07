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
	public $i; //index of song on rankings
    
    public function Song($row, $i)
    {
        $this->id = $row['id'];
        $this->upload_date = $row['uploaded_on'];
        parent::RankableItem($this->id, $this->upload_date);
        
		$this->i = $i;
        $this->title = $row['title'];
        $this->artist = $row['artist'];
        $this->genre = $row['genre'];
        $this->ytcode = $row['youtubecode'];
        $this->user = $row['user'];
        $this->ups = $row['ups'];
        $this->downs = $row['downs'];
        $this->score = $row['score'];
    }
	
	
	
	//constructor can be passed all the info
	public static function Song_Info($title, $artist, $genre, $ytcode, $user, $score, $ups, $downs, $id, $upload_date, $i)
	{		
		 $info = array("title" => $title,
					   "artist" => $artist,
					   "genre" => $genre,
					   "youtubecode" => $ytcode,
					   "user" => $user,
					   "score" => $score,
					   "ups" => $ups,
					   "downs" => $downs,
					   "id" => $id,
					   "uploaded_on" => $upload_date,
					   );
		 $instance = new self($info, $i);
		 /*
		 $instance->title = $title;
		 $instance->artist = $artist;
		 $instance->genre = $genre;
		 $instance->ytcode = $ytcode;
		 $instance->user = $user;
		 $instance->score = $score;
		 $instance->ups = $ups;
		 $instance->downs = $downs;
		 */
		 return $instance;
	}
    
    function map($in)
    {
		$in = ucfirst($in);
        if($in == 'DnB')
            return 'Drum & Bass';
        else
            return $in;
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
    function show()
    {
	//TO FIGURE OUT HOW TO VOTE ONLY ONCE: http://stackoverflow.com/questions/7056827/cookie-only-vote-once
	//http://paperkilledrock.com/2010/05/html5-localstorage-part-one/
	//Local Storage not supported before html5...so need alternate
        echo '
		<input type="hidden" id="title_max" value="'.$this->title.'"/> 
		<input type="hidden" id="artist_max" value="'.$this->artist.'"/> 
		<input type="hidden" id="genre_max" value="'.$this->genre.'"/> 
		<input type="hidden" id="score_max" value="'.$this->score.'"/> 
		<input type="hidden" id="ups_max" value="'.$this->ups.'"/> 
		<input type="hidden" id="downs_max" value="'.$this->downs.'"/> 
		<input type="hidden" id="i_max" value="'.$this->downs.'"/> 
		<tr>
		<td>
		'	. $this->i . '
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
	
	//same as show() except without <tr></tr> tags
	function showGuts()
    {
        echo '
		<td>
		'	. $this->i . '
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
		';
    }
	

	
	
	//@param: $i is index in rankings
    //Generates HTML to display all basic info of song
    //Code generated to go <table><tr>HERE</tr></table> 
    function showMin()
    {
	
	/*
		<input type="hidden" id="ytcode_min" value="'.$this->ytcode.'"/>
		<input type="hidden" id="title_min" value="'.$this->title.'"/> 
		<input type="hidden" id="artist_min" value="'.$this->artist.'"/> 
		<input type="hidden" id="genre_min" value="'.$this->genre.'"/> 
		<input type="hidden" id="score_min" value="'.$this->score.'"/> 
		<input type="hidden" id="ups_min" value="'.$this->ups.'"/> 
		<input type="hidden" id="downs_min" value="'.$this->downs.'"/> 
		<input type="hidden" id="user_min" value="'.$this->user.'"/> 
		<input type="hidden" id="i_min" value="'.$this->i.'"/> 
		<input type="hidden" id="id_min" value="'.$this->id.'"/> 
		<input type="hidden" id="upload_date_min" value="'.$this->upload_date.'"/> 
		*/
        echo '
		<input type="hidden" id="ytcode_min'.$this->i.'" value="'.$this->ytcode.'"/>
		<input type="hidden" id="title_min'.$this->i.'" value="'.$this->title.'"/> 
		<input type="hidden" id="artist_min'.$this->i.'" value="'.$this->artist.'"/> 
		<input type="hidden" id="genre_min'.$this->i.'" value="'.$this->genre.'"/> 
		<input type="hidden" id="score_min'.$this->i.'" value="'.$this->score.'"/> 
		<input type="hidden" id="ups_min'.$this->i.'" value="'.$this->ups.'"/> 
		<input type="hidden" id="downs_min'.$this->i.'" value="'.$this->downs.'"/> 
		<input type="hidden" id="user_min'.$this->i.'" value="'.$this->user.'"/> 
		<input type="hidden" id="i_min'.$this->i.'" value="'.$this->i.'"/> 
		<input type="hidden" id="id_min'.$this->i.'" value="'.$this->id.'"/> 
		<input type="hidden" id="upload_date_min'.$this->i.'" value="'.$this->upload_date.'"/> 
		
		
		
		<div id="song_load"></div>
		<tr class="song_min" id="song_min'.$this->i.'">'.
		"<td><pre>" . $this->i . "</pre></td>" .
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