<?php
	class Staff
	{
		public $empID;
		public $firstName;
		public $lastName;
		
		function Staff($ID, $fName, $lName)
		{
			$this->empID = $ID;
			$this->firstName = $fName;
			$this->lastName = $lName;
		}
		
		function getEmpID()
		{ return $this->empID; }
		
		function getFirstName()
		{ return $this->firstName; }
		
		function getLastName()
		{ return $this->lastName; }
	}
?>