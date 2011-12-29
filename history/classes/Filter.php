<?php
//Class for filtering content
abstract class Filter
{	
	//Generates sql query based on filter criteria
	//Ex. SELECT * WHERE genSQLQuery() 
	abstract protected function genSQL();
}
?>