<?php
//song to be made smashed into JSON and sent through the interwebs
class Song
{
  public $title;
  public $artist;
  public $ytcode;
  public $score;
  public $genre;
  public $user;

  public function Song($title, $artist, $ytcode, $score, $genre, $user){
    $this->title = $title;
    $this->artist = $artist;
    $this->ytcode = $ytcode;
    $this->score = $score;
    $this->genre = $genre;
    $this->user = $user;
  }
}
?>

