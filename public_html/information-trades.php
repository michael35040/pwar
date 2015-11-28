<?php
require("../includes/config.php");
// if form was submitted
$id = $_SESSION["id"];

if ($_SERVER["REQUEST_METHOD"] == "POST")
{
    
    if(isset($_POST['symbol'])){ $symbol=$_POST['symbol'];} 
    else{apologize("Unknown symbol!"); exit();}
    
    $title = $symbol . " (Trades)";
    $trades =	query("SELECT * FROM trades WHERE (symbol = ? AND (type='limit' OR type='market')) ORDER BY date DESC", $symbol);
    $tradestotal =	query("SELECT AVG(price) AS avgprice, SUM(total) AS totaltotal, SUM(quantity) AS totalquantity, SUM(commission) AS totalcommission, COUNT(uid) AS totaltrades FROM trades WHERE (symbol = ?)", $symbol);
    
    render("information-trades_form.php",[
        "title" => $title,
        "symbol" => $symbol,
        "trades" => $trades,
        "tradestotal" => $tradestotal
        ]);
}
else{
 //apologize("No symbol selected!");
 redirect("information.php");
}
?>


