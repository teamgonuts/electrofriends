<?php
abstract class RankableItem
{
	private $uploadDate;   //upload date
	private $id;      //unique id
	private $rankNumber; //quatifiable rank number used to rank
	private $rankCriteria; // = new RankingCriteria();
	
    public function RankableItem($id_n, $UDate)
    {
        $this->id = $id_n;
        $this->uploadDate = $UDate;
    }
    
	//Get Methods
	abstract protected function show(); //returns html to display
	abstract protected function rankNum(); 
	abstract protected function uploadDate();
}

?>