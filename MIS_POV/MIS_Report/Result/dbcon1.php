<?php 

define("HOSTNAME", "localhost");
define("USERNAME", "root");
define("PASSWORD", "your_password");
define("DATABASE", "mis_report");

$connection  = mysqli_connect(HOSTNAME,USERNAME,PASSWORD,DATABASE);

if(!$connection){
	die("Connection Failed");
}

?>