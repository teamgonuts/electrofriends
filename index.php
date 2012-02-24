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
<title>t3k.no</title>
<meta charset="UTF-8">
<meta name="description" content="t3k.no is a user-driven electronic music community. " />
<meta name="keywords" content="t3k.no,t3kno,play music online, streaming music, listen to techno online, create playlist, freshest tracks, fresh list, free online music, free music, electronic music, dubstep, house, trance, electro, hardstyle, dnb, drum & bass" />


<!-- flavicon -->
<link rel="shortcut icon" href="images/favicon.ico" />


<!-- mainstyles -->
<link href="styles/main.css" rel="stylesheet" type="text/css">

<!-- fancybox -->
        <script src="//ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js" type="text/javascript"></script>
        <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.9/jquery-ui.min.js">//for drag & drop</script>
        <script type="text/javascript" src="js/swfobject.js">//embedding youtube videos</script>
        <script type="text/javascript" src="coffee/master.js"></script>
        
        
	<script>
		!window.jQuery && document.write('<script src="jquery-1.7.1min.js"><\/script>');
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
        <script type="text/javascript"> //Google Analytics
          var _gaq = _gaq || [];
          _gaq.push(['_setAccount', 'UA-27461232-1']);
          _gaq.push(['_trackPageview']);

          (function() {
            var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
            ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
            var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
          })();
        </script>
        <!--Amazon Affiliate -->
        <!-- <SCRIPT charset="utf-8" type="text/javascript" src="http://ws.amazon.com/widgets/q?ServiceVersion=20070822&MarketPlace=US&ID=V20070822/US/t075c-20/8005/79bdde03-08b0-45a0-986c-0440e49253bf"> </SCRIPT> <NOSCRIPT><A HREF="http://ws.amazon.com/widgets/q?ServiceVersion=20070822&MarketPlace=US&ID=V20070822%2FUS%2Ft075c-20%2F8005%2F79bdde03-08b0-45a0-986c-0440e49253bf&Operation=NoScript">Amazon.com Widgets</A></NOSCRIPT> -->


<!-- typekit -->
<script type="text/javascript" src="http://use.typekit.com/wlh5psa.js"></script>
<script type="text/javascript">try{Typekit.load();}catch(e){}</script>

</head>

<body>
<div id="fb-root"></div>
<div id="headerTopLine">
</div><!-- end of headerTopLine -->

<header id="header">

	<div class="content">
		<div class="twoColumnLeft">
		
			<a href="index.php"><img src="images/header/headerLogo.png" alt="t3k.no Logo" /></a>
		
		</div><!-- end of twoColumnLeft -->
		
		<div class="twoColumnRight">

			<div class="headerTop">

<!--  				
				<p class="signIn">
					<a href="#">Sign Up</a> // 
					<a href="#">Login</a> 
					<span class="divider">|</span> 
					<span class="socialSignIn">Sign in with:</span> 
                                        <span>Coming Soon</span>
				</p>
-->				
				<p class="upload">
					<a href="#lightbox" id="upload"> 
						<img src="images/header/upload.png"> Upload </a>
				</p>
					<div style="display: none;">
						<div id="lightbox" style="width:650px;height:420px; background-color:#000; color #fff; overflow:auto;">
							<div class="" id="upload_box">
							    
							    <div id="lightBoxLeft">
							    	<img src="images/lightbox/uploadLogo.png" alt="T3K.no logo" />
							        <div class="" id="upload-box-result">
                                                                    <h2>How it works</h2>
                                                                    <ul>
                                                                        <li>Share new songs you find</li>
                                                                        <li>Earn reputation by submitting good songs</li>
                                                                        <li>Avoid reposts </li>
                                                                        <li>Archive old songs (ie a classic)</li>
                                                                        <li>Songs deemed unworthy by the community are removed</li>
                                                                    </ul>
                                                                </div>
							    </div><!-- end of left -->
							    <div id="lightBoxRight">
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
                                                                
                                                                <label for="oldie" id="oldie">Archive: </label>
                                                                <input type="checkbox" value="oldie" name="oldie" id="upload_oldie"><br>
                                                                <label for="upload_comment">Comment: </label>
                                                                <input type="text" id="upload_comment"></textarea><br>

                                                                <input type="submit" name="submit" class="submit" value="Upload Song" id="upload_song">
						        </div><!-- end of right -->
						        
							</div>
						</div>
					</div>
			</div><!-- headerTop -->
			<br/>
			<br/>			
			<nav id="headerNav">
				<ul>
					<li><a href="#">Contact</a></li><!-- end of contact button -->
					<li><a href="http://t3kdev.tumblr.com/" target="_blank">Blog</a></li>
					<li><a href="#">About</a></li>
					<li><a id="fresh-list" href="#">Fresh List</a></li>	
				</ul>
			</nav><!-- end of headerNav -->			
		</div><!-- end of twoColumnRight -->
	</div><!-- end of content -->	

</header>

<section id="body">
	<div class="content">
		<div id="songTable">
                    <h2 id="rankings-title">The Fresh List</h2>
                                    
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
                                <input type="text" name="search" id="search-input" placeholder="Search">
				<input id="search-button" type="submit" value="Go">  
				<div class="clear"></div>
			</div>

			<div id="genre">
			
				<h2>Genre</h2>
				
					<ul>
						<li><a class="filter genre-filter" id="filter-all" title="all">All</a></li>
						<li><a class="filter genre-filter" id="filter-dnb" >DnB</a></li>
						<li><a class="filter genre-filter" id="filter-dubstep" >Dubstep</a></li>
						<li><a class="filter genre-filter" id="filter-electro" >Electro</a></li>
						<li><a class="filter genre-filter" id="filter-hardstyle">Hardstyle</a></li>
						<li><a class="filter genre-filter" id="filter-house" >House</a></li>
						<li><a class="filter genre-filter" id="filter-trance">Trance</a></li>
					</ul>
			
			</div><!-- end of genre -->
			
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
			<br/>
			<iframe src="//www.facebook.com/plugins/likebox.php?href=http%3A%2F%2Fwww.facebook.com%2Fpages%2Ft3kno%2F146156005506087&amp;width=300&amp;height=290&amp;colorscheme=light&amp;show_faces=true&amp;border_color=%23cccccc&amp;stream=false&amp;header=true&amp;appId=313109265403858" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:300px; height:290px;" allowTransparency="true"></iframe>			
			
			
		</div><!-- end of sidebar -->
<div class="hidden" id="max-queue">

<img src="queue.jpg" alt="T3K.no Logo"/>

    <span class="queue-min">[close]</span>

    <div class="clear"></div><!-- end of clear -->
       
        <div id="user-queue">
            <div class="queue-title">  
                <h3>User Queue</h3>
            </div><!-- end of queue-title -->
            <ol class="max-queue" id="userQ">
            </ol>
        </div>
        <div id="generated-queue">
            <div class="queue-title">
                <h3>Generated Queue</h3>
            </div>
            <ol class="max-queue" id="genQ">
            </ol>
        </div>
    </div>
                    
	
	</div><!-- end of content -->

<div class="clear"></div><!-- end of clear -->

</section><!-- end of body -->

<section id="adBlock">
	<div class="content">
		<p id="adsPlease">Ads help support t3k.no's development. If it <i>really</i> bothers you, <span class="ads-button" id="hide-ads">click here</span> to hide the ads.</p>
                <div class="ads">
                    <div class="adBlockLeft">
                        <script type="text/javascript"><!--
                        google_ad_client = "ca-pub-6274607241853425";
                        /* Footer ad */
                        google_ad_slot = "6791996487";
                        google_ad_width = 300;
                        google_ad_height = 250;
                        //-->
                        </script>
                        <script type="text/javascript"
                        src="http://pagead2.googlesyndication.com/pagead/show_ads.js">
                        </script>
                    </div><!-- end of adBlockLeft -->
                    <div class="adBlockCenter">
                        <script type="text/javascript"><!--
                        google_ad_client = "ca-pub-6274607241853425";
                        /* footer ad 2 */
                        google_ad_slot = "9153020020";
                        google_ad_width = 300;
                        google_ad_height = 250;
                        //-->
                        </script>
                        <script type="text/javascript"
                        src="http://pagead2.googlesyndication.com/pagead/show_ads.js">
                        </script>
                    </div><!-- end of adBlockCenter -->
                    <div class="adBlockRight">
                        <script type="text/javascript"><!--
                        google_ad_client = "ca-pub-6274607241853425";
                        /* footer ad 3 */
                        google_ad_slot = "9800245746";
                        google_ad_width = 300;
                        google_ad_height = 250;
                        //-->
                        </script>
                        <script type="text/javascript"
                        src="http://pagead2.googlesyndication.com/pagead/show_ads.js">
                        </script>
                    </div><!-- end of adBlockRight -->
                </div><!--end of ads-->
		
	</div><!-- end of content -->
</section><!-- end of addBlock -->

<footer id="footer">
	
	<div class="content">
		
		<img src="images/footer/footerLogo.png" alt="T3K.no Logo" />
		<p>&copy; 2011 t3k.no | All Rights Reserved.</p>
		<a href="http://www.facebook.com/pages/t3kno/146156005506087" target="_blank"><img src="images/footer/footerFacebook.png" alt="add us on facebook" class="socials"/></a>
		<a href="https://twitter.com/#!/t3kdev" target="_blank"><img src="images/footer/footerTwitter.png" alt="follow us on Twitter!" class="socials"/></a> 
	</div><!-- end of content -->
<div class="clear"></div>
</footer><!-- end of footer -->

<section id="bottomControls">
	
	<div class="content" id="bottom-contents">
		
		<div class="threeColumnLeft">
			
            <h3>Up Next:<span id="queue-max">[Show Queue]</span></h3>
				
				<ol id="min-queue">
				</ol>
			
		</div>	<!-- end of threeColumnLeft -->
		
		<div class="threeColumnCenter">
			
			<div class="twoColumnLeft">
                        <span id="ytplayer">
                            <p>You will need Flash 8 or better to view this content. Download it
                                    <a href="http://get.adobe.com/flashplayer/">HERE</a>
                            </p>
                        </span>
			</div><!-- end of twoColumnLeft -->
			
			<div class="twoColumnRight">

			</div><!-- end of twoColumnRight -->
			
		</div><!-- end of threeColumnCenter -->
		
		<div class="threeColumnRight">
		
		<h3>Current Song <span class="song-control previous-song">Last</span>  <span class="song-control next-song">Next</span></h3>
			<p><span class="currentSongSubTitle">Title: </span> <span id="currentSongTitle"></span></p>
			<p><span class="currentSongSubTitle">Artist: </span><span id="currentSongArtist"></span></p> 
			<p><span class="currentSongSubTitle">Genre: </span><span id="currentSongGenre"></span></p>
			<p><span class="currentSongSubTitle">Uploaded By: </span><span id="currentSongUser"></span> </p>

			
		</div><!-- end of threeColumnRight -->
				
	</div><!-- end of content -->

</section><!-- end of bottomControls -->

</body>
</html>
