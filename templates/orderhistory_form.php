<style>
    .nobutton button
    {
        padding:0;
        font-weight: 100;
        border:0;
        background:transparent;
    }
    .table, th
    {
        text-align: center;
    }

</style>










<table class="table table-condensed  table-bordered">
    <thead>

    <tr   class="success" ><td colspan="9"  style="font-size:20px; text-align: center;">ORDER HISTORY (<?php echo(strtoupper($tabletitle)); ?>) &nbsp;
            <?php
            //	Display link to all history as long as your not already there
            if (isset($tabletitle))
            {
                if ($tabletitle !== "All")
                {
                    echo('<span class="input-group-btn" style="display:inline;">
    <form  method="post" action="orderhistory.php"><button type="submit" class="btn btn-success btn-xs" name="history" value="all">
        <span class="glyphicon glyphicon-plus-sign"></span> Show All
    </button></form>
</span>
                
                ');
                }
                else
                {
                    echo('<span class="input-group-btn" style="display:inline;">
    <form method="post" action="orders.php"><button type="submit" class="btn btn-success btn-xs" name="history" value="limit">
        <span class="glyphicon glyphicon-minus-sign"></span> Show Last 10
    </button></form>
</span>
                
                ');
                }
            }
            ?>
        </td></tr>
    <tr   class="active" >
        <th>Order #</th>
        <th>Date/Time (Y/M/D)</th>
        <th>Symbol</th>
        <th>Action</th>
    </tr>
    </thead>
    <tbody>
    <?php
    foreach ($history as $row)
    {  $row["symbol"] = htmlspecialchars(strtoupper($row["symbol"]));
        echo("<tr>");
        echo("<td>" . htmlspecialchars($row["ouid"]) . "</td>");
        echo("<td>" . htmlspecialchars(date('Y-m-d H:i:s',strtotime($row["date"]))) . "</td>");
        //echo("<td>" . htmlspecialchars(strtoupper($row["symbol"])) . "</td>");
        echo("<td><form method='post' action='information.php'><span class='nobutton'><button type='submit' name='symbol' value='" . $row['symbol'] . "'>" . $row['symbol'] . "</button></span></form></td>");

        if($row["transaction"]=='EXECUTED')
        {echo('<td><form method="post" action="trades.php"><span class="nobutton"><button type="submit" name="uid" value="' . htmlspecialchars($row["ouid"]) . '">EXECUTED</button></span></form></td>');}
        else //BID/ASK/CANCEL
        {echo("<td>" . htmlspecialchars($row["transaction"]) . "</td>");}
        echo("</tr>");
    }
    ?>
    </tbody>
</table>

















