<?php

include ("Filter.php");

class DateFilter extends Filter
{
	//@param daysOld: how many days can be passed to be included in filter
	//Ex. If daysOld = 7, everything that is less than a week old is included
	private $interval;

	public function DateFilter($daysOld)
	{
		$this->interval = new DateInterval('P'.$daysOld.'D');
	}

	//@Return: Returns a DateTime that is the earliest possible date to be included in the filter
	function createLimitDate()
	{
		$now = new DateTime();
		return $now->sub($this->interval);
	}
	
	//generates SQL code for checking date
	//Ex. WHERE limitDate > created... if > means before
	function genSQL()
	{
		$limitDate = $this->createLimitDate();
       
		return "'" . $limitDate->format('Y-m-d') . "' < uploaded_on";
	}
}
?>