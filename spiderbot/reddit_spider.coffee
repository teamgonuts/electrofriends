$ ->
  console.log 'jQuery loaded'

  
  for link in $('.title') 
    debug = true;
    #if the link is a youtube link
    if $(link).attr('href') and $(link).attr('href').indexOf('youtube') >= 0
      if debug
        console.log 'title: ' + $(link).html()
        console.log '  link: ' + $(link).attr('href')

        video_url = $(link).attr('href')
        video_title = $(link).html()

        #guessing on song title
        title = ''
        artist = ''
        if video_title.indexOf(' - ') >= 0
          title = video_title.substring(video_title.indexOf(' - ') + 3, video_title.length).trim()
          artist = video_title.substring(0, video_title.indexOf(' - ')).trim()

        ytcode_index = $(link).attr('href').indexOf('v=')
        ytcode = $(link).attr('href').substring(ytcode_index+2, video_url.length)
        if debug then console.log ' ytcode: ' + ytcode

        $('#songs').append(' <p class="youtube-song" id="' + ytcode  + '">
                        <br />
                        <object width="560" height="315"><param name="movie" value="value="http://www.youtube.com/v/' + ytcode + '?version=3&amp;hl=en_US"></param><param name="allowfullscreen" value="true"></param><param name="allowscriptaccess" value="always"></param><embed src="http://www.youtube.com/v/' + ytcode + '?version=3&amp;hl=en_US" type="application/x-shockwave-flash" width="500" height="200" allowscriptaccess="always" allowfullscreen="true"></embed></object><br />
                                <b> ' + video_title + '</br>
                                <label for="url">YouTube URL: </label> 
                                <input id="upload_yturl" type="text" name="url" value="' + video_url +  '"><br>
                                
                                <label for="title">Title: </label>
                                <input id="upload_title" type="text" name="title" value="' + title + '"><br>
                                
                                <label for="artist">Artist: </label>
                                <input id="upload_artist" type="text" name="artist" value="' + artist + '"><br>

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
                                <input id="upload_user" type="text" name="user" value="narwhalbot"> <br>
                                
                                <label for="oldie" id="oldie">Archive: </label>
                                <input type="checkbox" value="oldie" name="oldie" id="upload_oldie"><br>

                                <input type="submit" name="submit" class="submit" value="Upload Song" id="upload_song">
                                </p><br />')

        $.post 'check_song.php',
          ytcode: ytcode,
          (data) ->
            if debug then console.log 'post done, data:' + data
            #if its not in the db
            if data != 'false'
              if debug then console.log 'removing ytcode:' + data
              $('#' + data).remove()

