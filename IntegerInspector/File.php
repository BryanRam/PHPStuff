<?php
require_once "Math.php";
session_start();

class FileData
{

    public $data = "";

	

    public function FileData($FILE)
    {
        $tmpName = $FILE["tmp_name"]; //store temporary file name
        $arrName = explode(".", $FILE["name"]); //store the filename as an array by parsing out the "."
        $fileExt = strtolower(array_pop($arrName)); //get the file extension from $arrName and store
        $fileSize = $FILE["size"]; //store the size of the file
        $fileError = $FILE["error"]; //store any errors generated from submitting
		
		//store the filename in a session for exporting to csv
		if (!isset($_SESSION["filename"]))
		{
			$_SESSION["filename"] = $arrName[0];
		}
		
		
		//If file size is 0 or greater than 2MB
        if ($fileSize == 0 || $fileSize > 2048) {
            throw new Exception("There was a problem with upload"); //throw Exception
        } 
		elseif ($fileError != "") {
            throw new Exception("File was not uploaded sucessfully");
        }
		if ($fileExt == "txt" || $fileExt == "dat")
		{
			//File extension must be .txt or .dat
		}
		else //throw exception if extension is not .txt or .dat
		{
			throw new Exception("Invalid File. Only .txt or .dat files are accepted!");
		}
		
		//extract file contents and store in this instance of $data
        $this->data = file_get_contents($tmpName);
		
		

    }


    public function ExportNumbers($filename)
    {
		//declare array to store even, odd and fibonacci numbers
		$allnumbers = array();
		$allnumbers = $this->arrangeNumbers($allnumbers); //arrange the even, odd and fibonacci numbers into rows and store in $allnumbers
	
		$this->unsetFile(); //clear the file and filename sessions to prevent re-exporting data without submitting a file first
		$this->unsetSessionNumbers(); //clear the even, odd and fibonacci number sessions
	
		
        // send headers for download
        header("Content-Type: text/csv");
		header("Content-Disposition: attachment; filename=$filename");
		// Disable caching
		header("Cache-Control: no-cache, no-store, must-revalidate"); // HTTP 1.1
		header("Pragma: no-cache"); // HTTP 1.0
		header("Expires: 0"); // Proxies
		
		
		//declare headings array for the number headings
		$headings = array('0' => "Even Numbers", '1' => "Odd Numbers", '2' => "Fibonacci Numbers"
						);
		
		$output = fopen("php://output", "w"); //open php output buffer for writing 
		fputcsv($output, $headings); //output contents of headings array as a csv line format
		fclose($output);

		$this->outputCSV($allnumbers); //call outputCSV function to output contents of $allnumbers as csv line formats
		
		exit; //terminate script to prevent any more output reaching the .csv file
		
		
		
    }
	
	/*function to output given data as a csv line format,
	given that $data is an array
	*/	
	public function outputCSV($data) 
	{
		$output = fopen("php://output", "w");
		foreach ($data as $row) {
			fputcsv($output, $row); 
		}
		fclose($output);
	}
	
	/*Function to arrange the even, odd and fibonacci numbers into rows of 3,
	such that each row = even number, odd number, fibonacci number, in that order.
	*/
	public function arrangeNumbers(array $numbers)
	{
		//if the getEvenNumbers, getOddNumbers, and getFiboNumbers functions were called, data would be stored in their sessions
		if(isset($_SESSION["evenNumbers"]) && isset($_SESSION["oddNumbers"]) && isset($_SESSION["fiboNumbers"]))
		{
			//determine the largest array among even numbers, odd numbers and fibonacci numbers
			$largest = max(count($_SESSION["evenNumbers"]), count($_SESSION["oddNumbers"]), count($_SESSION["fiboNumbers"]));
			
			//use a for loop to assign rows of even, odd, and fibonacci numbers in $numbers
			for($i = 0; $i<$largest; $i++)
			{
					if(!isset($_SESSION["evenNumbers"][$i])) //if you run out of even numbers before the end is reached
					{
						$numbers[$i][0] = ""; //put a blank spot in that number's place
					}
					else
					$numbers[$i][0] = $_SESSION["evenNumbers"][$i]; //otherwise store the even number in the first column
					
					if(!isset($_SESSION["oddNumbers"][$i])) //do the same for the rest
					{
						$numbers[$i][1] = "";
					}
					else
					$numbers[$i][1] = $_SESSION["oddNumbers"][$i]; //store the odd number in the second column
					
					if(!isset($_SESSION["fiboNumbers"][$i]))
					{
						$numbers[$i][2] = "";
					}
					else			
					$numbers[$i][2] = $_SESSION["fiboNumbers"][$i]; //store the fibonacci number in the third column
			
			}
		}
		
		else
		{
			throw new Exception("No data was submitted!"); //throw an exception if no data was submitted
		}
	
		return $numbers;
	}
	
	/*unsetFile() and unsetSessionNumbers are functions to clear the session variables
	for the file object, filename, even, odd and fibonacci numbers, once they exist
	*/
	public function unsetFile()
	{ 
		if(isset($_SESSION["file"]))
		{
			unset($_SESSION["file"]);
		}
		
		if(isset($_SESSION["filename"]))
		{
			unset($_SESSION["filename"]);
		}
	
	}
	
	public function unsetSessionNumbers()
	{
		if(isset($_SESSION["evenNumbers"]))
		{
			unset($_SESSION["evenNumbers"]);
		
		}
		
		if(isset($_SESSION["oddNumbers"]))
		{
			unset($_SESSION["oddNumbers"]);
		}
		
		if(isset($_SESSION["fiboNumbers"]))
		{
			unset($_SESSION["fiboNumbers"]);
		} 
	
	}


    public function GetData()
    {
        return $this->data;
    }

    public function GetEvenNumbers()
    {
        $numbers = explode(",", $this->data); //add $this->data as array elements to numbers by parsing out commas
        $evenNumbers = array();


        foreach ($numbers as $number) {
			if(is_numeric($number)) //check if $number is actually a number before putting it in a category
			{
				if (Math::isEven($number)) {
				   $evenNumbers[] = $number;
			   }
			}
        }
		
		//store the even numbers array as a session
		$_SESSION["evenNumbers"] = $evenNumbers;
		
        return $evenNumbers;
    }
	
	 public function GetOddNumbers()
    {
        $numbers = explode(",", $this->data);
        $oddNumbers = array();

		
			foreach ($numbers as $number) {
				if(is_numeric($number))
				{
					if (Math::isOdd($number)) {
					   $oddNumbers[] = $number;
				   }
				}   
			}
			
		
		//store the odd numbers array as a session
		$_SESSION["oddNumbers"] = $oddNumbers;
		
        return $oddNumbers;
    }
	
	 public function GetFibonacciNumbers()
    {
        $numbers = explode(",", $this->data);
        $fiboNumbers = array();

		
			foreach ($numbers as $number) {
				if(is_numeric($number))
				{
					if (Math::isFibonacci($number)) {
					   $fiboNumbers[] = $number;
				   }
				}   
			}
			
		//store the fibonacci numbers array as a session
		$_SESSION["fiboNumbers"] = $fiboNumbers;
		
        return $fiboNumbers;
    }

}