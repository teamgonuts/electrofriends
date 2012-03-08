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
        <script type="text/javascript" src="js/jquery.shuffle.js">//embedding youtube videos</script>
        <script type="text/javascript" src="coffee/master_mini.js"></script>
        
	<script>
		!window.jQuery && document.write('<script src="jquery-1.7.1min.js"><\/script>');
	</script>
	<script type="text/javascript" src="js/fancybox/jquery.mousewheel-3.0.4.pack.js"></script>
	<script type="text/javascript" src="js/fancybox/jquery.fancybox-1.3.4.pack.js"></script>
	<link rel="stylesheet" type="text/css" href="js/fancybox/jquery.fancybox-1.3.4.css" media="screen" />
	<script type="text/javascript">
		$(document).ready(function() {

			$(".lightbox-button").fancybox({
				'titlePosition'		: 'inside',
				'transitionIn'		: 'none',
				'transitionOut'		: 'none'
			});

		});
	</script>
        <script type="text/javascript">//Google Analytics

          var _gaq = _gaq || [];
          _gaq.push(['_setAccount', 'UA-29775135-1']);
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
		
			<img src="images/header/headerLogo.png" alt="t3k.no Logo" id="header-logo" />
		
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
					<a href="#lightbox" class="lightbox-button" id="upload"> <img src="images/header/upload.png"> Upload </a>
				</p>
					<div style="display: none;">
						<div id="lightbox" style="width:650px;height:420px; background-color:#000; color #fff; overflow:auto;">
                           <div class="hidden lightbox-div" id="contact" style="color:white;width:650px;height:420px; background-color:#000; color #fff; overflow:auto;">
								
							 <img src="images/lightbox/lightboxLogo-large.jpg" alt="T3K.no logo" />
							 
							 <div id="contact-results"></div>

								<div class="lightBoxLeft">
							        
                                    <h2>Need to get a hold of us?</h2>
                                    <p>Have a question, comment or concern? Fill out the form and will get back to you shortly.</p>
                                    
							    </div><!-- end of left -->
							    <div class="lightBoxRight">
                                                        
                                                                <form action="" method="post" id="contactForm">
                                                                        
                                                                        <label for="name">Name:</label>
                                                                        <input type="text" name="name" id="contact-name" placeholder="Your Full Name"/>
                                                                        
                                                                        <label for="email">Email:</label>
                                                                        <input type="text" name="email" id="contact-email" placeholder="Your Email Address" />
                                                                        
                                                                        <label for="comment">Comment:</label>
                                                                        <textarea id="contact-message" name="comments" placeholder="How can we help you?"></textarea>
                                                                        
                                                                        <h3 id="contact-submit">Submit</h3>
                                                                        <br>
                                                                        
                                                                </form>
                                 </div><!-- end of lightbox right -->
                                                                
                                                        </div><!-- end of contact -->
                                                        <div class="hidden lightbox-div" id="about" style="color:white; width:650px;height:420px; background-color:#000; color #fff; overflow:auto;">
                                                                <h2>Meet Our Team</h2>
                                                                
                                                                <h3>Calvin Hawkes</h3>
                                                                <img src="images/headshots/calvin.jpg" alt="Calvin Hawkes Picture" class="calvin" />
                                                                <p>Calvin Hawkes [@CalvinHawkes] is an entrepreneur from Los Gatos, CA and the developer of t3k.no. He 
                                                                   is currently a senior at New York University double majoring Computer Science
                                                                   & Economics. As a fan of EDM, Calvin noticed the need for 
                                                                   a centralized location to discover new, up-and-coming tracks. After 
                                                                   witnessing the success of the Electro Friends facebook group, 
                                                                   he set out to improve upon its functionality while maintaining its community-driven 
                                                                   principles. He hopes that by allowing anyone to share songs, t3k.no will stay up to date 
                                                                   with the freshest tracks, creating a database of incredible, free music.</p>
                                                                   
                                                                   <div class="clear"></div><!-- clear -->
                                                                
                                                                <h3>Joel Shaw</h3>
                                                                
                                                                <img src="images/headshots/joel.jpg" alt="Joel Shaw Picture" class="joel">
                                                                <p>Joel Shaw [@joelshaw5] is also a Bay Area native, from Cupertino, CA and the designer of t3k.no. He graduated 
                                                                from Cal Poly San Luis Obispo with a degree in Graphic Communications in March of 2011. 
                                                                Currently he is working as a graphic designer in the San Francisco Bay Area and San 
                                                                Luis Obispo, CA. 
                                                                </p>   
                                                                   
                                                        </div><!-- end of about -->

							<div class="hidden lightbox-div" id="upload_box">
							    
							    <div id="lightBoxLeft">
							    	<img src="images/lightbox/uploadLogo.png" alt="T3K.no logo" />
							        <div class="" id="upload-box-result">
                                                                    <h2>How it works</h2>
                                                                    <ul>
                                                                        <li>Share new songs you find</li>
                                                                        <li>Earn reputation by submitting good songs</li>
                                                                        <li>Avoid reposts </li>
                                                                        <li>Archive old songs (ie a classic)</li>
                                                                        <li>Songs deemed unworthy by the community are automatically removed</li>
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
							</div> <!--end up upload_box-->
						</div>
					</div>
			</div><!-- headerTop -->
			<br/>
			<br/>			
			<nav id="headerNav">
				<ul>
					<li><a class="lightbox-button" id="contact-link" href="#lightbox">Contact</a></li><!-- end of contact button -->
					<li><a href="http://t3kdev.tumblr.com/" target="_blank">Blog</a></li>
					<li><a id="about-link" class="lightbox-button" href="#lightbox">About</a></li>
					<li><a id="fresh-list" href="#">Fresh List</a></li>	
				</ul>
			</nav><!-- end of headerNav -->			
		</div><!-- end of twoColumnRight -->
	</div><!-- end of content -->	

</header>

<section id="body">
	<div class="content">
		<div id="songTable">
                    <h2 class="ellipsis" id="rankings-title">The Fresh List</h2>
                                    
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
			<iframe src="//www.facebook.com/plugins/likebox.php?href=http%3A%2F%2Fwww.facebook.com%2Fpages%2Ft3kno%2F146156005506087&amp;width=250&amp;height=290&amp;colorscheme=light&amp;show_faces=true&amp;border_color&amp;stream=false&amp;header=true&amp;appId=313109265403858" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:250px; height:290px;" allowTransparency="true"></iframe>		
			
			
		</div><!-- end of sidebar -->
<div class="hidden" id="max-queue">

    <div id="queue-logo-container">
        <img src="images/queue/queue.jpg" alt="T3K.no Logo"/>
    </div>
    <div id="queue-tip">drag & drop songs to change the order</div>
    <span class="queue-min">[close]</span>

    <div class="clear"></div><!-- end of clear -->
       
        <div class="queue" id="user-queue">
            <div class="queue-title">  
                <h3>User Queue</h3>
            </div><!-- end of queue-title -->
            <div class="shuffle">
            </div><!-- end of shuffle -->
            
            <div class="clear"></div><!-- end of clear -->
            
            <ol class="max-queue" id="userQ">
            </ol>
        </div>
        <div class="queue" id="generated-queue">
            <div class="queue-title">
                <h3>Generated Queue                   	
                </h3>
            </div>
			<div class="shuffle">
            </div><!-- end of shuffle --> 
            <div class="clear"></div><!-- end of clear -->                     
            <ol class="max-queue" id="genQ">
            </ol>
        </div>
    </div>
                    
	
	</div><!-- end of content -->

<div class="clear"></div><!-- end of clear -->

</section><!-- end of body -->

<section id="adBlock">
	<div class="content">
		<p id="adsPlease">Ads help support t3k.no's developlement. Help t3k.no stay fast, <span class="ads-button" id="show-ads">click here</span> to enable ads.</p>
                <div class="ads hidden">
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
		<a href="http://www.facebook.com/officialt3kno" target="_blank"><img src="images/footer/footerFacebook.png" alt="add us on facebook" class="socials"/></a>
		<a href="https://twitter.com/#!/t3kdev" target="_blank"><img src="images/footer/footerTwitter.png" alt="follow us on Twitter!" class="socials"/></a> 
	</div><!-- end of content -->
<div class="clear"></div>
</footer><!-- end of footer -->

<section id="bottomControls">
	
	<div class="content" id="bottom-contents">
		
		<div class="threeColumnLeft">
            <div id="queue-max">[Show Queue]</div>			
            <h3 style="padding-top:10px;">Up Next:</h3>
				<div class="clear"></div><!-- end of clear -->
				
				<ol class="elipsis" id="min-queue">
				</ol>
			
		</div>	<!-- end of threeColumnLeft -->
		
		<div class="threeColumnCenter">
			
			<div class="twoColumnLeft">
                <div id="ytplayer">
                    <p>You will need Flash 8 or better to view this content. Download it
                            <a href="http://get.adobe.com/flashplayer/">HERE</a>
                    </p>
                 </div>   
			</div><!-- end of twoColumnLeft -->
			
			<div class="twoColumnRight">                      
                            <div class="next-last-songs">
                                <span class="song-control previous-song">|<</span>  
                                <span class="song-control next-song">>|</span>
                            </div>
                            <br />
                            <p>Song Score</p>
                            <p class="score-container">
                                <span class="score" id="currentSongScore"></span> [<span id="currentSongUps"></span>/<span id="currentSongDowns"></span>] 
                            </p>
                            <div class="vote-buttons-container">
                                <p class="vote-button" id="player-down-vote"></p>
                                <p class="vote-button" id="player-up-vote"></p>
                            </div>
			</div><!-- end of twoColumnRight -->
			
		</div><!-- end of threeColumnCenter -->
		
		<div class="threeColumnRight">
		
		<h3>Current Song </h3>
			<p class="ellipsis"><span class="currentSongSubTitle">Title: </span> <span id="currentSongTitle"></span></p>
			<p class="ellipsis"><span class="currentSongSubTitle">Artist: </span><span id="currentSongArtist"></span></p> 
			<p><span class="currentSongSubTitle">Genre: </span><span id="currentSongGenre"></span></p>
			<p><span class="currentSongSubTitle">Uploaded By: </span><span id="currentSongUser"></span> </p>

			
		</div><!-- end of threeColumnRight -->
				
	</div><!-- end of content -->

</section><!-- end of bottomControls -->

</body>
</html>
