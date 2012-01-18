<?php

class Song
{
    //Make public or private?
    public $title;
    public $artist;
    public $genre;
    public $ytcode;
    public $upload_date;
    public $id;
    public $user;
    public $score;
    public $i; //index of song on rankings
    public $plays; //how many plays the song has
    
    public function Song($row, $i)
    {
        $this->id = $row['id'];
        $this->upload_date = $row['uploaded_on'];
        $this->i = $i;
        $this->title = str_replace('\\','',$row['title']);
        $this->artist = str_replace('\\','',$row['artist']);
        $this->genre = $row['genre'];
        $this->ytcode = str_replace('\\','',$row['youtubecode']);
        $this->user = str_replace('\\','',$row['user']);
        $this->score = $row['score'];
        $this->plays = $row['plays'];
    }
	

    //returns the html for the song in 2 divs, open and close
    //jquery should set to hidden or not
    function showClasses()
    {
        //song info & minimized row
        $html = '<tr class="song song-min" id="min_' .$this->i . '">
                    <td class="song-index clickable"><p>' . $this->i . '</p></td>
                        <input type="hidden" id="ytcode_'.$this->i.'" value="'.$this->ytcode.'"/>
                        <input type="hidden" id="title_'.$this->i.'" value="'.$this->title.'"/>
                        <input type="hidden" id="artist_'.$this->i.'" value="'.$this->artist.'"/>
                        <input type="hidden" id="genre_'.$this->i.'" value="'.$this->genre.'"/>
                        <input type="hidden" id="score_'.$this->i.'" value="'.$this->score.'"/>
                        <input type="hidden" id="user_'.$this->i.'" value="'.$this->user.'"/>
                        <input type="hidden" id="i_'.$this->i.'" value="'.$this->i.'"/>
                    <td class="song-info-min clickable"><span class="title">' . $this->title . '</span>
                    <span class="divider">//</span> <br />
                    '. $this->artist .'
                    </td>
                    <td class="song-genre">' . $this->genre . '</td>
                    <td class="song-score clickable">
                        <div class="center">' . $this->score . '</div>
                    </td>
                </tr>';
        //max row
        $html .= ' <tr class="song song-max hidden" id="max_'. $this->i .'">
                    <td class="song-index-clickable">
                        '.$this->i .'
                    </td>
                    <td class="song-info-max-clickable">
			<div class="left"> 
	                        <div class="song-buttons">
	                            <center>
	                                <span class="song-button play-button">Play</span>
	                                <span class="song-button play-next-button">Play Next</span>
	                                <span class="song-button queue-button">Add to Queue</span>
	                            </center>
	                        </div>
	                        
	                        <p><span class="more-info-heading">Title</span>: '. $this->title .'</p>
	                        <p><span class="more-info-heading">Artist</span>: '. $this->artist .'</p>
	                        <p><span class="more-info-heading">Genre</span>: <span class="filter genre-filter">'. $this->genre .'</span></p>
	                        <p><span class="more-info-heading">Uploaded By</span>: '. $this->user .'</p>
	                        <p><span class="more-info-heading">Plays</span>: '. $this->plays .'</p>
	                        <p><span class="more-info-heading">Share</span>: </p>
	                        <p><span class="more-info-heading">Buy Now</span>: Buy Now</p>
                    	
                    	</div><!-- end of left -->
                    	<div class="right">
	                    	<div class="comments"><!-- todo: showComments function? -->
				    <h3 class="comments">Comments</h3>
    				    <div class="comment-display">
                                        <p>
                                            <span class="userName">UserName</span> 
                                            <span class="divider">//</span> 
                                            <time datetime="2011-5-11">12/5/11 12:27 pm</time> : 
                                            Sample coment. Sed vel leo mi. Praesent suscipit turpis et sem 
                                            eleifend lobortis.
                                        </p>
                                        <p>
                                            <span class="userName">UserName</span> 
                                            <span class="divider">//</span> 
                                            <time datetime="2011-5-11">12/5/11 12:27 pm</time> : 
                                            Sample coment. Sed vel leo mi. Praesent suscipit turpis et sem 
                                            eleifend lobortis.
                                        </p>
                                        <p>
                                            <span class="userName">UserName</span> 
                                            <span class="divider">//</span> 
                                            <time datetime="2011-5-11">12/5/11 12:27 pm</time> : 
                                            Sample coment. Sed vel leo mi. Praesent suscipit turpis et sem 
                                            eleifend lobortis.
                                        </p>
				    </div><!-- comment-display -->					
				    <h4 class="comments">See All Comments</h4>
				</div><!-- end of comments -->	
                    	</div><!-- end of right -->
                    </td>
                    <td class="song-comments" colspan="2">
                        <span id="comments-container">
                            <div class="comments-input">
                                <textarea class="comments-text" id="comment-text_15" placeholder="Comments"></textarea><br>
                                <label for="comment-user_15" class="label">Username:</label>
                                <input type="text" id="comment-user_15" value="Anonymous">
                                <input type="submit" value="submit" class="submit-comment" id="submit-comment_15">
                            </div>
                        </span>
                        <center> <br />0<br /> </center>
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
		
		$commentsShown = 4;
		$upperLimit = $commentsShown;
		$html = '<input type="hidden" id="whereCom" value="'.$where .'">
			  <input type="hidden" id="commentsShown" value="'.$commentsShown .'">
			  <input type="hidden" id="upperLimitCom" value="'.$upperLimit .'">';
		$html .= '<div class="comments-display">
		<ol id="update_'.$this->i .'" class="timeline">';
		
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
		$html .= '</ol>';

         //see if there are more comments to be displayed
		if (mysql_num_rows($qry) == $upperLimit)
		{
			$html .= '
			<center>
				<span class="showMoreComments" id="showMoreComments_'.$this->i .'">
					Show More 
				</span>
			</center>';
		}
		$html .= '
		</div>
		<div class="comments-input">
			<textarea class="comments-text" id="comment-text_'.$this->i.'"></textarea><br />
			Username: <input type="text" id="comment-user_'.$this->i.'" value="Anonymous"/>
			<button class="submit-comment" id="submit-comment_'.$this->i .'">Submit</button>
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
	function showScore()
	{
		return '
		<input type="hidden" id="score_'.$this->i.'" value="'.$this->score.'"/> 
		<input type="hidden" id="ups_'.$this->i.'" value="'.$this->ups.'"/> 
		<input type="hidden" id="downs_'.$this->i.'" value="'.$this->downs.'"/> 
		<center> '
               . $this->score . "[" . $this->ups . "/" . $this->downs . "]" . '<br />
		</center>
		';
	}

    
    //======================GETTER METHODS=======================/

}
?>
