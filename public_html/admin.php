

<?php

require("../includes/config.php");
$id = $_SESSION["id"];
$title = "Dashboard";
$assets = query("SELECT symbol FROM assets"); // for processing, delete, and remove orderbook menu

if ($id != 1) { apologize("Unauthorized!"); exit();}
else
{






    if(isset($_POST['addasset'])) {
        $symbol = strtolower($_POST['addasset']);
        $depository = $_POST['depository'];
        $description = $_POST['description'];
        $asw = $_POST['asw'];
        $purity = $_POST['purity'];
        $country = $_POST['country'];
        $year = $_POST['year'];
        $quantity = $_POST['quantity'];
        $weight = $_POST['weight'];

        if (query("  INSERT INTO storage (symbol, depository, description, asw, purity, country, year, weight, quantity)
                      VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)",
                $symbol, $depository, $description, $asw, $purity, $country, $year, $weight, $quantity) === false)
        {apologize("Database Failure #P2.");} //update portfolio
    }


    if (isset($_POST["removeAsset"]))
    {
        $uid = $_POST["removeAsset"];
        if (!ctype_digit($uid)){apologize("Invalid asset #");}
        if (query("DELETE FROM `storage` WHERE uid = ?", $uid) === false) {apologize("Unable to cancel order!");}
    }


    if (isset($_POST["updateasset"]))
    {
        $uid =  $_POST['updateasset'];
        $symbol = strtolower($_POST['symbol']);
        $depository = $_POST['depository'];
        $description = $_POST['description'];
        $asw = $_POST['asw'];
        $purity = $_POST['purity'];
        $country = $_POST['country'];
        $year = $_POST['year'];
        $weight = $_POST['weight'];
        $quantity = $_POST['quantity'];

        if (!ctype_digit($uid)){apologize("Invalid asset #");}
        if (query("UPDATE `storage` SET symbol=?, depository=?, description=?, asw=?, purity=?, country=?, year=?, weight=?, quantity=? WHERE uid=?",
                $symbol, $depository, $description, $asw, $purity, $country, $year, $weight, $quantity, $uid) === false)
        {apologize("Database Failure #P3.");} //update portfolio

    }







    if(isset($_POST['process'])) {
        if ($_POST["process"] == 'ALL') {
            try {
                $processOrderbook = processOrderbook();
            } catch (Exception $e) {
                echo('Error: ' . $e->getMessage() . '<br>');
            }         //catch exception
        } else {
            try {
                $processOrderbook = processOrderbook($_POST["process"]);
            } catch (Exception $e) {
                echo('Error: ' . $e->getMessage() . '<br>');
            }         //catch exception
        }

        echo($processOrderbook . " orders processed.");
    }




    if(isset($_POST['transaction']))
    {
        //get variables
        if ( empty($_POST['quantity']) ||  empty($_POST['userid'])) { apologize("Please fill all required fields."); } //check to see if empty
        // if symbol or quantity empty
        $userid = sanatize('quantity', $_POST['userid']);
        $quantity = setPrice($_POST['quantity']);
        $transaction = strtoupper($_POST['transaction']);
        $symbol = $unittype;

        if($transaction=="WITHDRAW")
        {
            $totalq = query("SELECT units FROM accounts WHERE id = ?", $userid);
            @$total = (float)$totalq[0]["units"]; //convert array to value
            if ($quantity > $total)  //only allows user to deposit if they have less than
            { apologize("You only have " . number_format($total,2,".",",") . " to withdraw!"); }
            $quantity = ($quantity*-1);
        }
        /* IF TRANFER
        UID - TRANSFER #
        ID - USER TRANSFEREE
        OUID - 0
        DATE - CURRENT TIMESTAMP
        TRANSACTION - TRANSFER
        SYMBOL - MONEY TYPE (ie USD)
        COUNTERPARTY - TRANSFEREE
        QUANTITY -  0
        PRICE - 0
        TOTAL - AMOUNT
        */


        // transaction information
        query("SET AUTOCOMMIT=0");
        query("START TRANSACTION;"); //initiate a SQL transaction in case of error between transaction and commit
        // update cash after transaction for user          
        if (query("UPDATE accounts SET units = (units + ?) WHERE id = ?", $quantity, $userid) === false)
        {query("ROLLBACK"); query("SET AUTOCOMMIT=1"); apologize("Database Failure #P1.");} //update portfolio
        //update transaction history for user
        if (query("INSERT INTO history (id, transaction, symbol, quantity, price, counterparty, total) VALUES (?, ?, ?, ?, ?, ?, ?)", $userid, $transaction, $symbol, 0, 0, $adminid, $quantity) === false)
        {query("ROLLBACK"); query("SET AUTOCOMMIT=1"); apologize("Database Failure #P2.");} //update portfolio
        query("COMMIT;"); //If no errors, commit changes
        query("SET AUTOCOMMIT=1");
    }






    if(isset($_POST['reset'])) {
        $user = sanatize('quantity', $_POST['user']);
        $password = $_POST['reset'];
        $options = ['cost' => 12,];
        $password = password_hash($password, PASSWORD_BCRYPT, $options);
        query("UPDATE users SET password=?, fails=0 WHERE id=?", $password, $user);
    }










    if(isset($_POST['lock'])) {
        $user = sanatize('quantity', $_POST['lock']);
        query("UPDATE users SET active=0 WHERE id=?", $user);
    }







    if(isset($_POST['notice'])) {
        $user = sanatize('quantity', $_POST['user']);
        $notice = sanatize('address', $_POST['notice']);
        query("INSERT INTO notification (id, notice, status) VALUES (?, ?, ?)", $user, $notice, 1);
    }

















    if(isset($_POST['admin'])) {
        if ($_POST['admin'] == 'all') {clear_all();}
        if ($_POST['admin'] == 'test') {test();}
        if ($_POST['admin'] == 'orderbook') {clear_orderbook();}
        if ($_POST['admin'] == 'trades') {clear_trades();}
        if ($_POST['admin'] == 'populate') {populatetrades();}
        if ($_POST['admin'] == 'createstocks') {
            try {
                createStocks();
            } catch (Exception $e) {
                echo 'Message: ' . $e->getMessage();
            }
        }
        if ($_POST['admin'] == 'randomorders') {
            try {
                $randomOrders = randomOrders();
            } catch (Exception $e) {
                echo('Error: ' . $e->getMessage() . '<br>');
            }         //catch exception
        }
    } //if admin post







    //TOP MONEY USERS
    $searchusersquery = query("SELECT * FROM users, accounts WHERE users.id=accounts.id ORDER BY units DESC LIMIT 0,10 ;");
    foreach ($searchusersquery as $row)		// for each of user
    {
        $info["id"] = $row["id"];
        $info["email"] = $row["email"];
        $info["password"] = $row["password"];
        $info["phone"] = $row["phone"];
        $info["last_login"] = $row["last_login"];
        $info["registered"] = $row["registered"];
        $info["fails"] = $row["fails"];
        $info["ip"] = $row["ip"];
        $accounts = query("SELECT units, loan, rate FROM accounts WHERE id = ?", $info["id"]);
        $info["units"] = $accounts[0]["units"];
        $info["loan"] = $accounts[0]["loan"];
        $info["rate"] = $accounts[0]["rate"];
        $bidLocked =	query("SELECT SUM(total) AS total FROM orderbook WHERE (id=? AND side='b')", $info["id"]);	  // query user's portfolio
        $info["locked"] = $bidLocked[0]["total"]; //shares trading
        $topusers[] = $info;
    }


//apologize(var_dump(get_defined_vars())); //dump all variables anywhere (displays in header)
    require("../templates/header.php");
    ?>
    <style>
        #middle
        {
            background-color:transparent;
            border:0;
        }
    </style>




    <form action="admin.php"  class="symbolForm" method="post"   >
        <fieldset>
            <table class="table table-condensed table-striped table-bordered" id="admin" style="border-collapse:collapse;text-align:center;vertical-align:middle;">
                <tr><th colspan=2>TESTING</th></tr>
                <tr><td><input type="radio" name="admin" value="all"></td>          <td>Clear All</td></tr>
                <tr><td><input type="radio" name="admin" value="test"></td>          <td>New Environment</td></tr>
                <tr><td><input type="radio" name="admin" value="orderbook"></td>    <td>Clear Orderbook</td></tr>
                <tr><td><input type="radio" name="admin" value="trades"></td>       <td>Clear Trades</td></tr>
                <tr><td><input type="radio" name="admin" value="createstocks"></td> <td>Create Stocks</td></tr>
                <tr><td><input type="radio" name="admin" value="randomorders"></td> <td>Random Orders</td></tr>
                <tr><td><input type="radio" name="admin" value="populate"></td>      <td>Populate</td></tr>
                <tr><td colspan='2'>
                        <button type="submit" class="btn btn-info"><b> SUBMIT </b></button></span>
                    </td></tr>
            </table>

        </fieldset>
    </form>























    <!--font color='#FF0000'></font-->
    <table class="table table-condensed table-striped table-bordered" id="topusers" style="border-collapse:collapse;text-align:center;vertical-align:middle;">
        <!-- HEADER ROW -->
        <thead>
        <tr>
            <td colspan="12" class="success"><strong>TOP USERS</strong></td>
        </tr>
        </thead>
        <tbody>
        <tr class="active">
            <td>ID</td>
            <td>EMAIL</td>
            <!--th>Password</th-->
            <td>PHONE</td>
            <td>LAST LOGIN</td>
            <td>REGISTERED</td>
            <td>FAILS</td>
            <td>IP</td>
            <td><?php echo($unittype) //set in finance.php ?></td>
            <td>LOCKED</td>
            <td>PASSWORD</td>
        </tr>

        <?php
        foreach ($topusers as $row)
        {
            echo("<tr>");
            echo("<td>" . number_format($row["id"],0,".",",") . "</td>");
            echo("<td>" . htmlspecialchars($row["email"]) . "</td>");
            //  echo("<td>" . htmlspecialchars($row["password"]) . "</td>");
            echo("<td>" . htmlspecialchars($row["phone"]) . "</td>");
            echo("<td>" . (date('Y-m-d H:i:s', strtotime($row["last_login"])) . "</td>"));
            echo("<td>" . (date('Y-m-d H:i:s', strtotime($row["registered"])) . "</td>"));
            echo("<td>" . htmlspecialchars($row["fails"]) . "</td>");
            echo("<td>" . htmlspecialchars($row["ip"]) . "</td>");
            echo("<td>" . number_format(getPrice($row["units"]),2,".",",") . "</td>");
            echo("<td>" . number_format(getPrice($row["locked"]),2,".",",") . "</td>");
            echo("<td>" . htmlspecialchars($row["password"]) . "</td>");
            echo("</tr>");
        }
        ?>
        </tbody>
    </table>








    <table class="table table-condensed table-striped table-bordered" id="notice" style="border-collapse:collapse;text-align:center;vertical-align:middle;">
        <thead>
        <tr>
            <td class="info"><strong>NOTIFICATION</strong></td>
        </tr>
        </thead>
        <tbody>
        <tr>
            <td>
                <form action="admin.php"  class="noticeForm" method="post">
                    <input type="text" name="user" placeholder="User"  size="4">
                    <input type="text" name="notice" placeholder="Notice"  size="70">
                    <button type="submit" class="btn btn-info"><b> SUBMIT </b></button>
                </form>
            </td>
        </tr>
        </tbody>
    </table>












    <table class="table table-condensed table-striped table-bordered" id="password" style="border-collapse:collapse;text-align:center;vertical-align:middle;">
        <thead>
        <tr>
            <td class="danger"><strong>RESET PASSWORD</strong></td>
        </tr>
        </thead>
        <tbody>
        <tr>
            <td>
                <form action="admin.php" method="post">
                    <input type="text" name="user" placeholder="User"  size="4">
                    <input type="text" name="reset" placeholder="Password"  size="35">
                    <button type="submit" class="btn btn-danger"><b> RESET </b></button>
                </form>
            </td>
        </tr>
        </tbody>
    </table>




















    <table class="table table-condensed table-striped table-bordered" id="lock" style="border-collapse:collapse;text-align:center;vertical-align:middle;">
        <thead>
        <tr>
            <td class="warning"><strong>LOCK</strong></td>
        </tr>
        </thead>
        <tbody>
        <tr>
            <td>
                <form action="admin.php" method="post">
                    <input type="text" name="lock" placeholder="User"  size="4">
                    <button type="submit" class="btn btn-warning"><b> LOCK </b></button>
                </form>
            </td>
        </tr>
        </tbody>
    </table>













    <table class="table table-condensed table-striped table-bordered" id="process" style="border-collapse:collapse;text-align:center;vertical-align:middle;">
        <thead>
        <tr>
            <td class="success"><strong>PROCESS ORDERBOOK</strong></td>
        </tr>
        </thead>
        <tbody>
        <tr>
            <td>
                <form action="admin.php"  class="processForm" method="post">

                    <select name="process"  class="form-control" >
                        <?php
                        if (empty($assets)) {
                            echo("<option value=''>No Assets</option>");
                        } else {
                            //echo ('    <option class="select-dash" disabled="disabled">-All Assets-</option>');
                            echo ('    <option value="ALL">-All Assets-</option>');
                            foreach ($assets as $asset) {
                                $symbol = $asset["symbol"];
                                echo("<option value='" . $symbol . "'>  " . $symbol . "</option>");
                            }
                        }
                        ?>
                    </select>
                    <button type="submit" class="btn btn-success"><b> PROCESS </b></button></span>
                </form></td>
        </tr>
        </tbody>
    </table>












    <table class="table table-condensed table-striped table-bordered" id="notice" style="border-collapse:collapse;text-align:center;vertical-align:middle;">
        <thead>
        <tr>
            <td class="success" colspan="3"><strong>TRANSACTION</strong></td>
        </tr>
        </thead>
        <tbody>
        <tr>
            <td>
                <script type="text/javascript">
                    function FillUnits(f)
                    { if(f.copyunits.checked == true) {f.quantity.value = <?php echo($units); ?>;}}
                </script>
                <form action="admin.php" method="post" class="formtable">
                    <fieldset>
                        <input class="input-small" name="userid" placeholder="User ID" type="number" min="0" max="any" required /><br />
                        <input class="input-medium" type="number" name="quantity" placeholder="Amount/Quantity" step="0.01" min="0" max="any" required /><br>
                        <button type="submit"
                                class="btn btn-warning btn-xs"
                                name="transaction"
                                value="withdraw">WITHDRAW
                        </button>
                        <button type="submit"
                                class="btn btn-success btn-xs"
                                name="transaction"
                                value="deposit">DEPOSIT
                        </button>

                        <br /><input type="checkbox" name="copyunits" onclick="FillUnits(this.form)"> All <?php echo($unittype);?>
                    </fieldset>
                </form>
            </td>
        </tr>
        </tbody>
    </table>










    <table class="table table-condensed table-striped table-bordered" id="activity" style="border-collapse:collapse;text-align:center;vertical-align:middle;">
        <tr>
            <td colspan="13" class="success"><strong>ITEMS</strong></td>
        </tr>
        <tr class="active">
            <td></td>
            <td></td>
            <td>UID</td>
            <td>Depository</td>
            <td>Symbol</td>
            <td>Description</td>
            <td>Purity</td>
            <td>Country</td>
            <td>Year</td>
            <td>ASW</td>
            <td># of Items</td>
            <td>Total Weight (ozt)</td>

        </tr>

        <?php
        //$depository = query("SELECT depository, symbol, SUM(asw*quantity) AS weight FROM storage GROUP BY depository, symbol");
        $storage = query("SELECT * FROM storage WHERE 1"); // query database for user

        /*
         ,SUM(asw*quantity) AS totalweight
        if (empty($total[0]["totalweight"])) {$total[0]["totalweight"] = 0;}
        $totalweight[$symbol] = $total[0]["totalweight"];
         */



        foreach ($storage as $row)
        {
            ?>
            <tr>

                <td>
                    <form method="post" action="admin.php"><button type="submit" class="btn btn-danger btn-xs" name="removeAsset" value="<?php echo($row["uid"]); ?>"><span class="glyphicon glyphicon-remove-circle"></span></button>
                    </form>
                </td>

                <form method="post" action="admin.php">
                    <td><button type="submit" class="btn btn-warning btn-xs" ><span class="glyphicon glyphicon-refresh"></span></button></td>
                    <td>   <input type="text" name="updateasset" placeholder="UID" value="<?php echo(number_format($row["uid"], 0, '.', ',')); //echo(number_format((31.1034768*$row["weight"]), 2, '.', ',')); ?>"></td>
                    <td>   <input type="text" name="depository" placeholder="Depository" value="<?php echo(htmlspecialchars($row["depository"])); ?>"></td>
                    <td><input type="text" name="symbol" placeholder="Symbol"  size="4" value="<?php echo(htmlspecialchars(strtoupper($row["symbol"]))); ?>"></td>
                    <td><input type="text" name="description" placeholder="Description" value="<?php echo(($row["description"])); ?>"></td>
                    <td><input type="text" name="asw" placeholder="ASW"  size="4" value="<?php echo(number_format($row["asw"], 2, '.', ',')); ?>"></td>
                    <td><input type="text" name="purity" placeholder="Purity"  size="4" value="<?php echo(number_format($row["purity"], 2, '.', ',')); ?>"></td>
                    <td><input type="text" name="country" placeholder="Country"  size="4" value="<?php echo(htmlspecialchars($row["country"])); ?>"></td>
                    <td><input type="text" name="year" placeholder="Year"  size="4" value="<?php echo(number_format($row["year"], 0, '.', '')); ?>"></td>
                    <td><input type="text" name="quantity" placeholder="Quantity"  size="4" value="<?php echo(number_format(($row["quantity"]), 0, '.', ',')); ?>"></td>
                    <td><input type="text" name="weight" placeholder="ozt Weight"  size="4" value="<?php echo(number_format($row["weight"], 4, '.', ',')); ?>">
                    </td>
                </form>
            </tr>
        <?php } ?>
        <tr>
            <form action="admin.php"  class="noticeForm" method="post">
                <td><button type="submit" class="btn btn-info"><b> + </b></button></td>
                <td></td>
                <td></td>
                <td><input type="text" name="depository" placeholder="Depository"></td>
                <td><input type="text" name="addasset" placeholder="Symbol"  size="4"></td>
                <td><input type="text" name="description" placeholder="Description"></td>
                <td><input type="text" name="asw" placeholder="ASW"  size="4"></td>
                <td><input type="text" name="purity" placeholder="Purity"  size="4"></td>
                <td><input type="text" name="country" placeholder="Country"  size="4"></td>
                <td><input type="text" name="year" placeholder="Year"  size="4"></td>
                <td><input type="text" name="quantity" placeholder="Quantity"  size="4"></td>
                <td><input type="text" name="weight" placeholder="ozt Weight"  size="4"></td>
            </form>

        </tr>

    </table>












    <?php
    require("../templates/footer.php");

} //if adminid
?>
