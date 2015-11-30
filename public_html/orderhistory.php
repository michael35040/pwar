<?php
require("../includes/config.php");

$id =  $_SESSION["id"];
$title = "Orders";
$limit = "LIMIT 0, 10"; //active orders
$limit2 = "LIMIT 0, 10"; //order history
$tabletitle = "Last 10";
$option = '';
$option2 = '';

if ($_SERVER["REQUEST_METHOD"] == "POST")// if form is submitted
{

    if (isset($_POST["history"]))
    {
        $history = $_POST["history"];
        if ($history == "all") {$limit = ""; $tabletitle = "All";}
    }
    
}
/*
else
{

} //else !post , 
*/
$history = query("SELECT ouid, date, symbol, transaction, total FROM history WHERE (id = ? $option2) ORDER BY uid DESC $limit", $id);
render("orderhistory_form.php", ["title" => $title, "tabletitle" => $tabletitle, "history" => $history]);

?>

