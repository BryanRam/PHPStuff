<?php
require_once "Math.php";
require_once "File.php";

$job = (!isset($_REQUEST["job"])) ? '' : trim($_REQUEST["job"]);
$evenNumbers = array();
$oddNumbers =array();
$fiboNumbers=array();
$file = array();


if ($job == "uploadFile") //when a file is uploaded
{
  	$file = new FileData($_FILES["aNumbers"]); //assign data from file to $file object
	//get even, odd and fibonacci numbers from file and store in respective arrays
    $evenNumbers = $file->GetEvenNumbers(); 
	$oddNumbers = $file->GetOddNumbers();
	$fiboNumbers = $file->GetFibonacciNumbers();
	
	if (!isset($_SESSION['file'])) //store file object as a session if it does not already exist
	{
		$_SESSION['file'] = $file;
	}
}

$job = (!isset($_GET["job"])) ? '' : trim($_GET["job"]);

if($job == "exportnumbers")
{
	
	//If a valid file was submitted with the submit button, then its details are saved in a session
	if (isset($_SESSION['file']))
	{
		$file = $_SESSION['file'];
		$file->ExportNumbers($_SESSION["filename"] . ".csv"); //call ExportNumbers function using the filename stored in a session and adding .csv as an extension
	}
	

}

if($job == "reset") //end current session by setting job to "reset"
{
	session_destroy();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Integer Inspector</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
    <script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>
</head>
<body>

<div class="container">
    <h1 class="text-primary">Integer Inspector</h1>
        <form role="form" action="fibonacci.php" enctype="multipart/form-data" method="POST">
            <input type="hidden" name="job" value="uploadFile" />
            <div class="form-group">
            <label for="file" class="text-info">Please Upload a file. <br />File must be a .txt or .dat, and contain integers separated by commas.</label>
                <input type="file" name="aNumbers" value="" id="file"/>
             <b>Even Numbers: </b><?php echo implode(",", $evenNumbers); ?> <br />
             <b>Odd Numbers: </b> <?php echo implode(",", $oddNumbers); ?><br />
             <b>Fibonacci Numbers: </b> <?php echo implode(",", $fiboNumbers); ?><br />
            </div>
            <button type="submit" class="btn btn-primary">Submit</button>&nbsp;
			<button 
                type="button" class="btn btn-info"
                onclick="document.location.href='fibonacci.php?job=exportnumbers'" >Export Numbers</button>
        </form>
</div>

</body>
</html>