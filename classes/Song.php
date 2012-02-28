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
    public $userScore;
    public $ups;
    public $downs;
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
        $this->ups = $row['ups'];
        $this->downs = $row['downs'];
        $this->plays = $row['plays'];
        //finding the userScore
        $qry = mysql_query('SELECT points FROM users WHERE user = "' . $this->user .'"');
        if (!$qry)
            die("FAIL: " . mysql_error());
        $row = mysql_fetch_array($qry); 
        $this->userScore = $row['points'];
        
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
                        <input type="hidden" id="userScore_'.$this->i.'" value="'.$this->userScore.'"/>
                        <input type="hidden" id="score_'.$this->i.'" value="'.$this->score.'"/>
                        <input type="hidden" id="user_'.$this->i.'" value="'.$this->user.'"/>
                        <input type="hidden" id="ups_'.$this->i.'" value="'.$this->ups .'"/>
                        <input type="hidden" id="downs_'.$this->i.'" value="'.$this->downs .'"/>
                        <input type="hidden" id="commentIterator_'.$this->i.'" value="4"/>
                    <td class="song-info-min song-info-min-clickable clickable"><span class="title">' . $this->title . '</span>
                    <span class="divider">//</span> <br />
                    '. $this->artist .'
                    </td>
                    <td class="song-genre">' . $this->genre . '</td>
                    <td class="song-score clickable">
                        <div class="center">
                            <span class="score-container">
                                <span class="score">'. $this->score .'</span> ['. $this->ups .'/'. $this->downs .'] 
                            </span>
                        </div>
                    </td>
                </tr>';
        //max row
        $html .= ' 
            <tr class="hidden song song-max" id="max_'. $this->i .'">
                <td class="song-index-clickable">
                        '.$this->i .'
                    </td>
                    <td class="song-info-max-clickable" colspan="2">
			<div class="left"> 
	                        <div class="song-buttons">
	                                <p class="song-button play-button">Play</p>
	                                <p class="song-button queue-button">Add to Queue</p>
	                        </div>
	                        
	                        <p id="title"><span class="more-info-heading title">Title</span>: '. $this->title .'</p>
	                        <p><span class="more-info-heading">Artist</span>: <span class="highlight search-filter">'. $this->artist .'</span></p>
	                        <p><span class="more-info-heading">Genre</span>: <span class="highlight filter genre-filter">'. $this->genre .'</span></p>
	                        <p><span class="more-info-heading">Uploaded By</span>: '. $this->user .' {<span class="user-score">' . $this->userScore . '</span>}</p>
	                        <p><span class="more-info-heading">Plays</span>: '. $this->plays .'</p>
	                        <p><span class="more-info-heading">Download</span>: 
                                    <a href="http://dirpy.com/studio/'. $this->ytcode . '" target="_blank"><img src="images/dirpy-logo-small.png" /></a>
                                    <a href="http://www.beatport.com/search?query=' . urlencode($this->title) . '" target="_blank"><img src="images/beatport_logo_small.png" /></a>
                                </p> 
                                ';
        /* TODO: IMPLEMENT
	                        <p><span class="more-info-heading">Share</span>: </p>
                                    <a type="amzn" category="music" search="' . $this->title . ' ' . $this->artist . '">Amazon</a> 
                                </p>
        */
                    	
        $html .= '
                    	</div><!-- end of left -->
                    	<div class="right">
	                    	<div class="comments">
				    <h3 class="comments">Comments</h3>
    				    <div class="comment-display">
                                    '. $this->showComments() .'
				    </div><!-- comment-display -->					
				    <!-- <h4 class="comments see-more-comments">See More</h4> -->
				</div><!-- end of comments -->	
				    
                <div id="comments-container">
                            <div class="comments-input">
                                <textarea class="comment-text" placeholder="Comments"></textarea><br>
                                <label for="comment-user" class="label">Username:</label>
                                <input type="text" class="comment-user" value="Anonymous">
                                <input type="submit" value="Submit" class="comments submit-comment">
                            </div>
                        </div>				    
				
                    	</div><!-- end of right -->
                    </td>
                    <td class="song-comments" colspan="2">
                                <p class="score-container">
                                    <span class="score">'. $this->score .'</span> ['. $this->ups .'/'. $this->downs .'] 
                                </p>
                                <div class="vote-buttons-container">
                                    <p class="vote-button" id="down-vote"></p>
                                    <p class="vote-button" id="up-vote"></p>
                                </div>                       
                            
                    </td>
                </tr>';

        return $html;
    }
    
	
	//generates html to display comments
	function showComments()
	{
            $qry = mysql_query("SELECT * FROM  `comments` 
                                WHERE '" . $this->ytcode ."' = `youtubecode`
                                ORDER BY upload_date DESC
                                LIMIT 0,5 ");//DEFAULT: Comments shown=5
            if (!$qry)
                die("FAIL: " . mysql_error());

            $html = '';
            while($row = mysql_fetch_array($qry))
            {
                    
                $com_user=$row['com_user'];
                $com_user = str_replace('\\','',$com_user);
                $comment_dis=$row['com_dis'];
                $comment_dis = str_replace('\\', '', $comment_dis);
                $date = new DateTime($row['upload_date']);

                $html .= '<p class="comment-p">
                            <span class="userName">' . $com_user . '</span>
                            <span class="divider">//</span>
                            <time datetime="' . $date->format('Y-m-d') .'">'.
                             $date->format('d/m/y g:i a')
                            .'</time> : '.  $comment_dis .'
                          </p>'; 
            }
    
            return $html;
	}
	

}
?>
