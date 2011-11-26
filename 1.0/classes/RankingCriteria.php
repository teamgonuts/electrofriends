<?php
abstract class RankingCriteria
{
	//No matter what kind of criteria is used to rank, this function updates a quantifiable number than can be used to determine rank.
	abstract protected function updateRank();
	
	abstract protected function genHTML();
}
?>