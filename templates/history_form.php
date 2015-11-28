
<table class="table table-condensed  table-bordered" >
    <tr   class="success" ><td colspan="4"  style="font-size:20px; text-align: center;">HISTORY (<?php echo(strtoupper($tabletitle)); ?>) &nbsp;
            <?php
            //	Display link to all history as long as your not already there
            if (isset($title))
            {
                if ($title !== "All History")
                {
                    echo('

                        <span class="input-group-btn" style="display:inline;">
    <form method="post" action="history.php"><button type="submit" class="btn btn-success btn-xs" name="history" value="all">
        <span class="glyphicon glyphicon-plus-sign"></span> Show All
    </button></form>
</span>

	');
                }
                else
                {
                    echo('

                        <span class="input-group-btn" style="display:inline;">
    <form method="post" action="history.php"><button type="submit" class="btn btn-success btn-xs" name="history" value="limit">
        <span class="glyphicon glyphicon-minus-sign"></span> Last 10
    </button></form>
</span>

	');
                }
            }

            ?>


        </td></tr> <!--blank row breaker-->


    <tr   class="active" >

        <th>#</th>
        <th>Transaction</th>
        <th>Date/Time (Y/M/D)</th>
        <th>Amount</th>
    </tr>

    <?php
    foreach ($history as $row)
    {
    	$price = getPrice($row["price"]);
    	$total = getPrice($row["total"]);

        echo("<tr>");
        echo("<td>" . htmlspecialchars($row["uid"]) . "</td>");
        echo("<td>" . htmlspecialchars($row["transaction"]));
                if($row["transaction"]=='TRANFER'){echo(" (" . htmlspecialchars($row["counterparty"]) . ")");}

	echo("</td>");
        echo("<td>" . htmlspecialchars(date('Y-m-d H:i:s',strtotime($row["date"]))) . "</td>");
        //echo("<td>" . htmlspecialchars(strtoupper($row["symbol"])) . "</td>");
        //echo("<td>" . htmlspecialchars($row["quantity"]) . "</td>");
        //echo("<td>" . $unitsymbol . (number_format($price,$decimalplaces,".",",")) . "</td>");
        echo("<td>" . $unitsymbol . (number_format($total,$decimalplaces,".",",")) . "</td>");
        echo("</tr>");
    }
    if($history==null){echo('<td colspan="7">None</td>');}
    ?>




    <tr   class="active" >
        <th>#</th>
        <th>Type</th>
        <th>Date/Time (Y/M/D)</th>
        <th colspan>Description</th>
    </tr>
    <?php
    foreach ($error as $row)
    {
        echo("<tr>");
        echo("<td>" . htmlspecialchars($row["uid"]) . "</td>");
        echo("<td>" . htmlspecialchars(strtoupper($row["type"])) . "</td>");
        echo("<td>" . htmlspecialchars(date('Y-m-d H:i:s',strtotime($row["date"]))) . "</td>");
        echo("<td>" . htmlspecialchars($row["description"]) . "</td>");
        echo("</tr>");
    }
    if($error==null){echo('<td colspan="7">None</td>');}

    ?>

</table>

