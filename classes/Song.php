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
        $this->title = str_replace('\\','',$row['title']);
        $this->artist = str_replace('\\','',$row['artist']);
        $this->genre = $row['genre'];
        $this->ytcode = str_replace('\\','',$row['youtubecode']);
        $this->user = str_replace('\\','',$row['user']);
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
    
	//@param: $i = how many comments to show
	function showIComments($i)
	{
		$ytcode = $this->ytcode;
		$qry = mysql_query("SELECT * FROM  `comments` 
                            WHERE '$ytcode' = youtubecode
							ORDER BY upload_date DESC
							LIMIT 0,$i
                            ");
		if (!$qry)
                die("FAIL: " . mysql_error());
		
		$html = '
		<center>
			<div>
				<form action="#" method="post">
				<input type="hidden" id="ytcode_'.$this->i.'" value="'.$ytcode.'"/> 
				<textarea id="comment_'.$this->i.'"></textarea><br />
				Username: <input type="text" id="cuser_'.$this->i.'" value="Anonymous"/>
				<input type="submit" class="submit" id="submit_'.$this->i.'" value=" Submit Comment " />
				</form>
			</div>
		</center>';
		$html .= '<ol id="update_'.$this->i.'" class="timeline">';
		while($row = mysql_fetch_array($qry))
		{
			$com_user=$row['com_user'];
			$com_user = str_replace('\\','',$com_user);
			
			$comment_dis=$row['com_dis'];
			$comment_dis = str_replace('\\', '', $comment_dis);
			$date_t = $row['upload_date'];
			$date = new DateTime($date_t);
			$html .= '<li style="display: list-item;" class="box"><span class="com_name"> '.$com_user.'</span>:
			<span class="com_text"> ' . $comment_dis . '</span>
			<span class="com_date"> ' . $date->format('M. j, Y G:i:s') . '</span></li>';
		}
		$html .= '</ol>';
		echo $html;
	}
	
	//generates html to display comments
	function showComments()
	{
		$ytcode = $this->ytcode;
		
		$html = '<ol id="update_'.$this->i.'" class="timeline">';
		
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
			
			$com_user=$row['com_user'];
			$com_user = str_replace('\\','',$com_user);
			
			$comment_dis=$row['com_dis'];
			$comment_dis = str_replace('\\', '', $comment_dis);
			$date_t = $row['upload_date'];
			$date = new DateTime($date_t);
			$html .= '<li style="display: list-item;" class="box"><span class="com_name"> '.$com_user.'</span>:
			<span class="com_text"> ' . $comment_dis . '</span>
			<span class="com_date"> ' . $date->format('M. j, Y G:i:s') . '</span></li>';
		}
		
		//comment area
		$html .= '</ol>
		<div id="flash"></div>
		<div>
			<form action="#" method="post">
			<input type="hidden" id="ytcode_'.$this->i.'" value="'.$ytcode.'"/> 
			<textarea id="comment_'.$this->i.'"></textarea><br />
			Username: <input type="text" id="cuser_'.$this->i.'" value="Anonymous"/>
			<input type="submit" class="submit" id="submit_'.$this->i.'" value=" Submit Comment " />
			</form>
		</div>';
		
		return $html;
	}
	
	//returns the html to view the song for view.php
	function showView()
	{
		echo
		    $this->title . ' by ' . $this->artist . '<br />
			Genre: ' . $this->map($this->genre) .'<br />
			Uploaded By: '.$this->user .'<br />
			Download: <u>Amazon</u> <u>Apple</u> <br />
			';

	}
	//returns the voting functionality
	function showVoting()
	{
		//check the users ip to see if he's voted
		$ytcode = $this->ytcode;
		$disabled = '';
		if (isset($_SERVER['HTTP_X_FORWARD_FOR'])) 
			$ip = $_SERVER['HTTP_X_FORWARD_FOR'];
		else 
			$ip = $_SERVER['REMOTE_ADDR'];
			
		$qry = mysql_query("SELECT * FROM  `ipcheck` 
                            WHERE '$ytcode' = ytcode AND '$ip' = ip");
							
		if(mysql_num_rows($qry) > 0) //he voted already
			$disabled = 'disabled="true"';

		return '
		<input type="hidden" id="score_'.$this->i.'" value="'.$this->score.'"/> 
		<input type="hidden" id="ups_'.$this->i.'" value="'.$this->ups.'"/> 
		<input type="hidden" id="downs_'.$this->i.'" value="'.$this->downs.'"/> 
		<center>
			<form action="#" method="post">
				<input type="submit" '.$disabled.' class="upvote" id="'.$this->i.'" value=" + " style="width:30px;" />
			</form>
			' . $this->score . "[" . $this->ups . "/" . $this->downs . "]" .'<br />
			<form action="#" method="post">
				<input type="submit" '.$disabled.' class="downvote" id="'.$this->i.'" value=" - " style="width:30px;" />
			</form>
		</center>
		';
		/*OLD VOTING
		return '
			<center>
				<form action="index.php" method="post" style="text-align:center; margin-top:0px; margin-bottom:0px; display: inline; ">
						<input type="hidden" name="vote" value=1>
						<input type="hidden" name="ytcode" value='. $this->ytcode .'>
						<input type="submit" class="upvote" id="'.$this->ytcode.'" name="upvote" value="+" style="width:30px; font-size:20px text-align:center;" /> 
				</form> <br />
				' . $this->score . "[" . $this->ups . "/" . $this->downs . "]" .'<br />
				<form action="index.php" method="post" style="text-align:center; margin-top:0px; margin-bottom:0px; display: inline;">
						<input type="hidden" name="vote" value=-1>
						<input type="hidden" name="ytcode" value="'. $this->ytcode .'">
						<input type="submit" class="downvote" id="'.$this->ytcode.'" name="downvote" value="-" style="width:30px; font-size:20px text-align:center;" /> 
				</form>
			</center>
			';
		*/
	}
	
    //Generates HTML to display all info and embedded youtube vid
    //Code generated to go <table>HERE</table> 
    function show()
    {
	//TO FIGURE OUT HOW TO VOTE ONLY ONCE: http://stackoverflow.com/questions/7056827/cookie-only-vote-once
	//http://paperkilledrock.com/2010/05/html5-localstorage-part-one/
	//Local Storage not supported before html5...so need alternate
    
	/* CODE TO SEARCH FILESTUBE
	<a href="http://www.filestube.com/search.html?q='.
												urlencode($this->title).'+'.urlencode($this->artist).'&select=All" 
												style="color:red;"  target="_blank">Pirate</a>*/
		echo '
		<td class="clickable" id="td1_'.$this->i.'">
			<input type="hidden" id="status_'.$this->i.'" value="max">
			<input type="hidden" id="ytcode_'.$this->i.'" value="'.$this->ytcode.'"/>
			<input type="hidden" id="title_'.$this->i.'" value="'.$this->title.'"/> 
			<input type="hidden" id="artist_'.$this->i.'" value="'.$this->artist.'"/> 
			<input type="hidden" id="genre_'.$this->i.'" value="'.$this->genre.'"/> 
			<input type="hidden" id="user_'.$this->i.'" value="'.$this->user.'"/> 
			<input type="hidden" id="i_'.$this->i.'" value="'.$this->i.'"/> 
			<input type="hidden" id="id_'.$this->i.'" value="'.$this->id.'"/> 
			<input type="hidden" id="upload_date_min'.$this->i.'" value="'.$this->upload_date.'"/> 
			'	. $this->i . '
		</td>
        <td class="clickable" id="td2_'.$this->i.'">
            <iframe title="YouTube video player" class="youtube-player" type="text/html" 
            width="240" height="146" src="http://www.youtube.com/embed/'. $this->ytcode .'"
            frameborder="0" allowFullScreen></iframe>
			<br />
			Title: ' . $this->title . '<br />
			Artist: ' . $this->artist . '<br />
			Genre: ' . $this->map($this->genre) .'<br />
			Uploaded By: '.$this->user .'<br />
			Download: <u>Amazon</u> <u>Apple</u> <br />
			<center>
				<button class="share" style="width:100px;"> Share </button>
				<span class="shareURL" id="shareURL_' .$this->i .'"></span>
			</center>
        </td>
        <td class="commentsTD" id="td3_'.$this->i.'">
			'. $this->showComments() . '
		</td>
		<td class="votingTD" id="td4_'.$this->i.'">
			'. $this->showVoting() . '
		</td>';
    }
	
	
	//@param: $i is index in rankings
    //Generates HTML to display all basic info of song
    //Code generated to go <table><tr>HERE</tr></table> 
    function showMin()
    {
        echo '<td class="clickable" id="td1_'.$this->i.'"><pre>' . $this->i . "</pre></td>" .
			'<input type="hidden" id="status_'.$this->i.'" value="min">'.
			'<input type="hidden" id="ytcode_'.$this->i.'" value="'.$this->ytcode.'"/>
			<input type="hidden" id="title_'.$this->i.'" value="'.$this->title.'"/> 
			<input type="hidden" id="artist_'.$this->i.'" value="'.$this->artist.'"/> 
			<input type="hidden" id="genre_'.$this->i.'" value="'.$this->genre.'"/> 
			<input type="hidden" id="score_'.$this->i.'" value="'.$this->score.'"/> 
			<input type="hidden" id="ups_'.$this->i.'" value="'.$this->ups.'"/> 
			<input type="hidden" id="downs_'.$this->i.'" value="'.$this->downs.'"/> 
			<input type="hidden" id="user_'.$this->i.'" value="'.$this->user.'"/> 
			<input type="hidden" id="i_'.$this->i.'" value="'.$this->i.'"/> 
			<input type="hidden" id="id_'.$this->i.'" value="'.$this->id.'"/> 
			<input type="hidden" id="upload_date_min'.$this->i.'" value="'.$this->upload_date.'"/> '.
        '<td class="clickable" id="td2_'.$this->i.'">' . $this->title . " - " . $this->artist . "</td>" .
		'<td class="clickable" id="td3_'.$this->i.'">' . $this->genre . "</td>" .
        '<td class="clickable" id="td4_'.$this->i.'">' . $this->score . "[" . $this->ups . "/" . $this->downs . "]" ."</td>";
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