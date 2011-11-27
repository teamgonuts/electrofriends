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

    //returns the html for the song in 2 divs, open and close
    //jquery should set to hidden or not
    function showClasses()
    {
        //song info & minimized row
        $html = '
                <tr class="song min" id="min' .$this->i . '">
                    <td class="clickable" id="song-index-min_'.$this->i.'"><pre>' . $this->i . '</pre></td>
                        <input type="hidden" id="ytcode_'.$this->i.'" value="'.$this->ytcode.'"/>
                        <input type="hidden" id="title_'.$this->i.'" value="'.$this->title.'"/>
                        <input type="hidden" id="artist_'.$this->i.'" value="'.$this->artist.'"/>
                        <input type="hidden" id="genre_'.$this->i.'" value="'.$this->genre.'"/>
                        <input type="hidden" id="score_'.$this->i.'" value="'.$this->score.'"/>
                        <input type="hidden" id="ups_'.$this->i.'" value="'.$this->ups.'"/>
                        <input type="hidden" id="downs_'.$this->i.'" value="'.$this->downs.'"/>
                        <input type="hidden" id="user_'.$this->i.'" value="'.$this->user.'"/>
                        <input type="hidden" id="i_'.$this->i.'" value="'.$this->i.'"/>
                    <td class="clickable" id="song-info-min_'.$this->i.'">' . $this->title . " - " . $this->artist . '</td>
                    <td class="clickable" id="song-genre-min_'.$this->i.'">' . $this->genre . '</td>
                    <td class="clickable" id="song-score-min_'.$this->i.'">' . $this->score . "[" . $this->ups . "/" . $this->downs . "]" .'</td>
                </tr>';
        //max row
        $html .= '
                <tr class="song max" id="max'. $this->i .'">
                    <td class="clickable" id="song-index-max_'.$this->i.'">
                        '	. $this->i . '
                    </td>
                    <td class="clickable" id="song-info-max_'.$this->i.'">
                        <div class="song-buttons">
                            <center>
                                <span class="song-button play-button">Play</span>
                                <span class="song-button play-next-button">Play Next</span>
                                <span class="song-button queue-button">Add to Queue</span>
                            </center>
                        </div>
                        Title: <a class="link" id="title_link" href="#">'
                         . $this->title . '</a><br />
                        Artist: <a class="link" id="artist_link" href="#">' . $this->artist . '</a><br />
                        Genre: <a class="link" id="genre_link" href="#">' . $this->map($this->genre) .'</a><br />
                        Uploaded By: '.$this->user .'<br />
                        Download: <u>Amazon</u> <u>Apple</u> <br />
                    </td>
                    <td class="commentsTD" id="song-comments_'.$this->i.'">
                        '. $this->showComments() . '
                    </td>
                    <td class="votingTD" id="song-voting_'.$this->i.'">
                        '. $this->showVoting() . '
                    </td>
                </tr>';

        return $html;
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
		$where = "'". $ytcode ."' = youtubecode";
		
		$commentsShown = 7;
		$upperLimit = $commentsShown;
		$html = '<input type="hidden" id="whereCom" value="'.$where .'">
			  <input type="hidden" id="commentsShown" value="'.$commentsShown .'">
			  <input type="hidden" id="upperLimitCom" value="'.$upperLimit .'">';
		$html .= '<div class="comments-display">
		<ol id="update_'.$this->i.'" class="timeline">';
		
		//old comments
        $qry = mysql_query("SELECT * FROM  `comments` 
                            WHERE $where
							ORDER BY upload_date DESC
							LIMIT 0,$upperLimit
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
		$html .= '</ol>';
		if (mysql_num_rows($qry) == $upperLimit) //see if there are more comments to be displayed
		{
			$html .= '
			<center>
				<span class="showMoreComments" id="showMoreComments_'.$this->i .'" style="text-align:center; font-size:75%; font-weight:bold;"> 
					Show More 
				</span>
			</center>';
		}
		$html .= '
		</div>
		<div class="comments-input">
			<form action="#" method="post">
			<input type="hidden" id="ytcode_'.$this->i.'" value="'.$ytcode.'"/> 
			<textarea class="comments-text" id="comment_'.$this->i.'"></textarea>
			Username: <input type="text" id="cuser_'.$this->i.'" value="Anonymous"/>
			<input type="submit" class="comments-submit" id="submit_'.$this->i.'" value="Submit" />
			</form>
		</div>';
		
		return $html;
	}
	
	//returns the html to view the song for view.php
	function showView()
	{
		echo '<a href="https://twitter.com/share?url='. urlencode("http://t3kno.dewpixel.net/view.php?s=".$this->ytcode) .'&amp;text=This song rocks you gotta hear this!" 
			class="twitter-share-button" style="float:right;">Tweet</a> <br />' .
		    $this->title . ' by <a href="index.php?topof=new&artist=' . $this->artist . '">' . $this->artist . '</a><br />
			Genre: <a href="index.php?topof=new&genre=' . strtolower($this->genre) .'">' . $this->genre . '</a><br />
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
            <input type="hidden" id="targetSong" value="' . $this->i .'" />
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
            <center>
                <div class="nonclickable" id="ytp'. $this->i . '">
                    <p>You will need Flash 8 or better to view this content. Download it Here: http://get.adobe.com/flashplayer/</p>
                </div>
            </center>
            <script type="text/javascript">
                 var params = { allowScriptAccess: "always" };
                 swfobject.embedSWF("http://www.youtube.com/v/' . $this->ytcode . '&enablejsapi=1&playerapiid=ytp'
                    . $this->i . '", "ytp' . $this->i . '", "275", "90", "8", null, null, params);
//240 x 146
                function onYouTubePlayerReady(playerId) {
                  ytplayer = document.getElementById(playerId);
                  ytplayer.addEventListener("onStateChange", "playNext");
                  ytplayer.addEventListener("onError", "onPlayerError");
                  if(playerId != "ytp1") //if its not the first player, load on showing
                        ytplayer.playVideo();
                }
                //do I need to put this function for every open song???
                function playNext(newState)
                {
                   //alert("new state: " + newState);
                   //unstarted (-1), ended (0), playing (1), paused (2), buffering (3), video cued (5)
                   if(newState == 0) //song is done
                   {
                       //**************minimize myself*******************
                       var i = ' . $this->i .';
                       var dataString = getDataString(i);
                       minimizeSong(dataString, i);
                       //**************maximize next song****************
                       dataString = getDataString(i+1); //i + 1 is next song
                       maximizeSong(dataString, i+1);
                       //I will automatically start playing on load
                   }
                   else if(newState == 1) // if its playing, change the title
                   {
                       $("title").text("' . $this->title . ' by ' . $this->artist . '    - T3k.no");
                   }
                   else if(newState == 2) //song is paused, go back to original title
                   {
                       $("title").text("Paused - T3k.no");
                   }
                   else if(newState == 3) //song is buffering, change title
                   {
                       $("title").text("Loading '.$this->artist .' - T3k.no");
                   }
                }

                function onPlayerError(errorCode) {
                    if(errorCode == 100) //the video has been removed or turned to private.
                    {
                       //*********minimize myself**************
                       var i = ' . $this->i .';
                       var dataString = getDataString(i);
                       minimizeSong(dataString, i);
                       //**************maximize next song****************
                       dataString = getDataString(i+1); //i + 1 is next song
                       maximizeSong(dataString, i+1);
                       //I will automatically start playing on load
                    }
                }
                
            </script>
			<a href="https://twitter.com/share?url='.
             urlencode("http://t3kno.dewpixel.net/view.php?s=".$this->ytcode) .
             '&amp;text=This song rocks you gotta hear this!" class="twitter-share-button">Tweet</a>
			<br />
			Title: <a class="link" id="title_link" href="#">'
             . $this->title . '</a><br />
			Artist: <a class="link" id="artist_link" href="#">' . $this->artist . '</a><br />
			Genre: <a class="link" id="genre_link" href="#">' . $this->map($this->genre) .'</a><br />
			Uploaded By: '.$this->user .'<br />
			Download: <u>Amazon</u> <u>Apple</u> <br />
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