<?php
include ("connection.php");
include ("classes/Song.php");
include ("classes/Rankings.php");
include ("classes/Filter.php");
include ("classes/DateFilter.php");
include ("classes/GenreFilter.php");

?>
<!DOCTYPE HTML>
<html>
<head>

<meta charset="UTF-8">

<!-- mainstyles -->
<link href="styles/main.css" rel="stylesheet" type="text/css">

<!-- fancybox -->
	<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.4/jquery.min.js"></script>
	<script type="text/javascript" src="coffee/test.js"></script>
        <script type="text/javascript" src="coffee/rankings.js"></script>
        
	<script>
		!window.jQuery && document.write('<script src="jquery-1.4.3.min.js"><\/script>');
	</script>
	<script type="text/javascript" src="js/fancybox/jquery.mousewheel-3.0.4.pack.js"></script>
	<script type="text/javascript" src="js/fancybox/jquery.fancybox-1.3.4.pack.js"></script>
	<link rel="stylesheet" type="text/css" href="js/fancybox/jquery.fancybox-1.3.4.css" media="screen" />
	<script type="text/javascript">
		$(document).ready(function() {

			$("#upload").fancybox({
				'titlePosition'		: 'inside',
				'transitionIn'		: 'none',
				'transitionOut'		: 'none'
			});

		});
	</script>


<!-- typekit -->
<script type="text/javascript" src="http://use.typekit.com/wlh5psa.js"></script>
<script type="text/javascript">try{Typekit.load();}catch(e){}</script>

</head>

<body>

<div id="headerTopLine">
</div><!-- end of headerTopLine -->

<header id="header">

	<div class="content">
		<div class="twoColumnLeft">
		
			<img src="images/header/headerLogo.png" alt="T3k.no Logo" />
		
		</div><!-- end of twoColumnLeft -->
		
		<div class="twoColumnRight">

			<div class="headerTop">
				<p class="signIn">
					<a href="#">Sign Up</a> // 
					<a href="#">Login</a> 
					<span class="divider">|</span> 
					<span class="socialSignIn">Sign in with:</span> 
				</p>
				
				<p class="upload">
					<a href="#lightbox" id="upload"> 
						<img src="images/header/upload.png"> Upload </a>
				</p>
					<div style="display: none;">
						<div id="lightbox" style="width:600px;height:400px; background-color:#000; color #fff; overflow:auto;">
							<div class="" id="upload_box">
							    
							    <div id="lightBoxLeft">
							    	<img src="images/lightbox/uploadLogo.png" alt="T3K.no logo" />
							    </div><!-- end of left -->
							    
							    <div id="lightBoxRight">
							    <div class="hidden" id="upload-box-result"></div>
						        	<form method="post"	action="">
								       	<label for="url">YouTube URL: </label> 
								       	<input id="upload_yturl" type="text" name="url"><br>
								        
								        <label for="title">Title: </label>
										<input id="upload_title" type="text" name="title"><br>
								        
								        <label for="artist">Artist: </label>
								        <input id="upload_artist" type="text" name="artist"><br>

								        <label for="genre">Genre: </label>
								        
								        <select id="upload_genre" name="genre">
								                <option value="DnB">Drum &amp; Bass</option>
								                <option value="Dubstep">Dubstep</option>
								                <option value="Electro">Electro</option>
								                <option value="Hardstyle">Hardstyle</option>
								                <option value="House">House</option>
								                <option value="Trance">Trance</option>
								               </select> <br>
								        
								        <label for="user">Uploaded By: </label>
								        <input id="upload_user" type="text" name="user" value=""> <br>
								        
								        <label for="oldie">Old Song: </label>
								        <input type="checkbox" value="oldie" name="oldie" id="oldie"><br>

								        <input type="submit" name="submit" class="submit" value="Upload Song" id="upload_song">

						        	</form>
						        </div><!-- end of right -->
						        
							</div>
						</div>
					</div>
			</div><!-- headerTop -->
			<br/>
			<br/>			
			<nav id="headerNav">
				<ul>
					<li><a href="#">Contact</a></li>					
					<li><a href="#">Blog</a></li>
					<li><a href="#">About</a></li>
				</ul>
			</nav><!-- end of headerNav -->			
		</div><!-- end of twoColumnRight -->
	</div><!-- end of content -->	

</header>

<section id="body">
	<div class="content">
		<div id="songTable">
                    <h2>The Fresh List</h2>
                                    
                    <table id="rankings-table" cellspacing="0"><tbody>
                        <?php 
                        //Defaults
                        $filters = array("date" => new DateFilter('new'), "genre" => new GenreFilter('all'));
                        $rankings = new Rankings($filters);
                        $rankings->display();

                        ?>
                    </tbody></table>
                    
                    <div id="showMoreSongs"><h3>Show More</h3></div>
	
		</div><!-- end of songTable -->
		
		<div id="sidebar">
			
			<div id="search">
			
				<h2>Search</h2>
				
				<form method="post" action="#">                    
                        <input  type="text" name="search" id="search" placeholder="Seach">
                    		
				        <input type="submit" value="Go">  
                        
				</form>
				
				<div class="clear"></div>
			</div>
			
			<div id="topList">
				
				<h2>Top of The</h2>
				
				<ul>
					<li><a class="filter time-filter" id="filter-day" href="#">Day</a></li>
					<li><a class="filter time-filter" id="filter-week" href="#">Week</a></li>
					<li><a class="filter time-filter" id="filter-month" href="#">Month</a></li>
					<li><a class="filter time-filter" id="filter-year" href="#">Year</a></li>
                                
					<li><a class="filter time-filter" id="filter-century" href="#">Century</a></li>
				</ul>

			</div><!-- end of topList -->
			
			<div id="genre">
			
				<h2>Genre</h2>
				
					<ul>
						<li><a class="filter genre-filter" id="filter-all" title="all" href="#">All</a></li>
						<li><a class="filter genre-filter" id="filter-dnb" href="#">DnB</a></li>
						<li><a class="filter genre-filter" id="filter-dubstep" href="#">Dubstep</a></li>
						<li><a class="filter genre-filter" id="filter-electro" href="#">Electro</a></li>
						<li><a class="filter genre-filter" id="filter-hardstyle" href="#">Hardstyle</a></li>
						<li><a class="filter genre-filter" id="filter-house" href="#">House</a></li>
						<li><a class="filter genre-filter" id="filter-trance" href="#">Trance</a></li>
					</ul>
			
			</div><!-- end of genre -->
			
		</div><!-- end of sidebar -->
	
	</div><!-- end of content -->

<div class="clear"></div><!-- end of clear -->

</section><!-- end of body -->

<section id="adBlock">
	<div class="content">
		
		<div class="adBlockLeft">
			<img src="images/ad/adBlock.png" />
		</div><!-- end of adBlockLeft -->
		
		<div class="adBlockCenter">
			<img src="images/ad/adBlock.png" />
		</div><!-- end of adBlockCenter -->
		
		<div class="adBlockRight">
			<img src="images/ad/adBlock.png" />		
		</div><!-- end of adBlockRight -->
		
	</div><!-- end of content -->
</section><!-- end of addBlock -->

<footer id="footer">
	
	<div class="content">
		
		<img src="images/footer/footerLogo.png" alt="T3K.no Logo" />
		<p>&copy; 2011 T3K.no | All Rights Reserved.</p>
		<img src="images/footer/footerFacebook.png" alt="add us on facebook" class="socials"/>
		<img src="images/footer/footerTwitter.png" alt="follow us on Twitter!" class="socials"/>
		
	</div><!-- end of content -->

</footer><!-- end of footer -->

<section id="bottomControls">
	
	<div class="content">
		
		<div class="threeColumnLeft">
			
			<h3>Current Song</h3>
			
			<p><span class="currentSongSubTitle">Title:</span> Svenska (Original Mix)</p>
			<p><span class="currentSongSubTitle">Artist:</span> Matisse & Sadko</p>
			<p><span class="currentSongSubTitle">Genre:</span> House </p>
			<p><span class="currentSongSubTitle">Uploaded By:</span> AF </p>

			
		</div>	<!-- end of threeColumnLeft -->
		
		<div class="threeColumnCenter">
			
			<div class="twoColumnLeft">
				<img src="images/ad/musicPlayer.png" />
			</div><!-- end of twoColumnLeft -->
			
			<div class="twoColumnRight">
                    <div class="vote-button" id="up">
                    	<img src="images/bottomContainer/voteUp.png" alt="Give this song a vote up" />
                    </div>

                    <div class="vote-button" id="down">
                    	<img src="images/bottomContainer/voteDown.png" alt="Give this song a down vote" />
                    </div>
                                        
                    <div id="song-score">
                    	1[1/0] 
                    </div>                    

				
			</div><!-- end of twoColumnRight -->
			
		</div><!-- end of threeColumnCenter -->
		
		<div class="threeColumnRight">
			<h3>Queue (Expand)</h3>
				<ol>
					<li><span class="text">Anticipate</span> // <span class="text">Skream ft. Sam Frank</span></li>
					<li><span class="text">Work Hard, Play Hard</span> // <span class="text">Tiesto ft. Kay</span></li>
					<li><span class="text">Pacifica (Chasing Shadows Remix)</span> // <span class="text">Spor</span></li>
				</ol>
		</div><!-- end of threeColumnRight -->
				
	</div><!-- end of content -->

</section><!-- end of bottomControls -->

</body>
</html>
