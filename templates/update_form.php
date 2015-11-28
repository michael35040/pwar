
<style>

    .changeForm {
        text-shadow: 2px 2px 5px #fff;
        display: inline-block;
        text-align: center;
        width:25%;
        padding: 0px;
        position:relative;
        margin-left: auto;
        margin-right: auto;
    }
</style>

<?php
$fname = $userinfo[0]["fname"];
$lname = $userinfo[0]["lname"];
$birth = $userinfo[0]["birth"];
$email = $userinfo[0]["email"];
$address = $userinfo[0]["address"];
$city = $userinfo[0]["city"];
$region = $userinfo[0]["region"];
$zip = $userinfo[0]["zip"];
$phone = $userinfo[0]["phone"];
$question = $userinfo[0]["question"];
$answer = $userinfo[0]["answer"];
?>

<div class="container">
    <form id="update" action="update.php" method="post">
        <fieldset>

            <div class="input-group">
                <span class="input-group-addon"><span class="glyphicon glyphicon-user"></span></span>
                <input class="form-control" name="fname" placeholder="First Name" type="text" value="<?php echo(htmlspecialchars($fname)); ?>"  maxlength="60" required/>
            </div>

            <div class="input-group">
                <span class="input-group-addon"><span class="glyphicon glyphicon-user"></span></span>
                <input class="form-control" name="lname" placeholder="Last Name" type="text" value="<?php echo(htmlspecialchars($lname)); ?>" maxlength="60" required/>
            </div>

            <div class="input-group">
                <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                <input class="form-control" name="birth" placeholder="Birthdate" type="date" value="<?php echo(htmlspecialchars($birth)); ?>"  required/>
            </div>

            <div class="input-group">
                <span class="input-group-addon"><span class="glyphicon glyphicon-envelope"></span></span>
                <input class="form-control" name="email" placeholder="Email" type="email" maxlength="60" value="<?php echo(htmlspecialchars($email)); ?>" required/>
            </div>

            <div class="input-group">
                <span class="input-group-addon"><span class="glyphicon glyphicon-home"></span></span>
                <input class="form-control" name="address" placeholder="Street Address" type="text" value="<?php echo(htmlspecialchars($address)); ?>"  maxlength="60" required/>
            </div>

            <div class="input-group">
                <span class="input-group-addon"><span class="glyphicon glyphicon-home"></span></span>
                <input class="form-control" name="city" placeholder="City" type="text" value="<?php echo(htmlspecialchars($city)); ?>"  maxlength="60" />
            </div>

            <div class="input-group">
                <span class="input-group-addon"><span class="glyphicon glyphicon-globe"></span></span>
                <select class="form-control" name="region"><option value="<?php echo(htmlspecialchars($region)); ?>"><?php echo(htmlspecialchars($region)); ?></option>
                    <option value="AL"> Alabama</option>
                    <option value="AK"> Alaska</option>
                    <option value="AZ"> Arizona</option>
                    <option value="AR"> Arkansas</option>
                    <option value="CA"> California</option>
                    <option value="CO"> Colorado</option>
                    <option value="CT"> Connecticut</option>
                    <option value="DC"> District of Columbia</option>
                    <option value="DE"> Delaware</option>
                    <option value="FL"> Florida</option>
                    <option value="GA"> Georgia</option>
                    <option value="HI"> Hawaii</option>
                    <option value="ID"> Idaho</option>
                    <option value="IL"> Illinois</option>
                    <option value="IN"> Indiana</option>
                    <option value="IA"> Iowa</option>
                    <option value="KS"> Kansas</option>
                    <option value="KY"> Kentucky</option>
                    <option value="LA"> Louisiana</option>
                    <option value="ME"> Maine</option>
                    <option value="MD"> Maryland</option>
                    <option value="MA"> Massachusetts</option>
                    <option value="MI"> Michigan</option>
                    <option value="MN"> Minnesota</option>
                    <option value="MS"> Mississippi</option>
                    <option value="MO"> Missouri</option>
                    <option value="MT"> Montana</option>
                    <option value="NE"> Nebraska</option>
                    <option value="NV"> Nevada</option>
                    <option value="NH"> New Hampshire</option>
                    <option value="NJ"> New Jersey</option>
                    <option value="NM"> New Mexico</option>
                    <option value="NY"> New York</option>
                    <option value="NC"> North Carolina</option>
                    <option value="ND"> North Dakota</option>
                    <option value="OH"> Ohio</option>
                    <option value="OK"> Oklahoma</option>
                    <option value="OR"> Oregon</option>
                    <option value="PA"> Pennsylvania</option>
                    <option value="RI"> Rhode Island</option>
                    <option value="SC"> South Carolina</option>
                    <option value="SD"> South Dakota</option>
                    <option value="TN"> Tennessee</option>
                    <option value="TX"> Texas</option>
                    <option value="UT"> Utah</option>
                    <option value="VT"> Vermont</option>
                    <option value="VA"> Virginia</option>
                    <option value="WA"> Washington</option>
                    <option value="WV"> West Virginia</option>
                    <option value="WI"> Wisconsin</option>
                    <option value="WY"> Wyoming</option>
                    <option value="AS"> American Samoa</option>
                    <option value="FM"> Federated States of Micronesia</option>
                    <option value="MH"> Marshall Islands</option>
                    <option value="MP"> Northern Mariana Islands</option>
                    <option value="PW"> Palau</option>
                    <option value="PR"> Puerto Rico</option>
                    <option value="VI"> Virgin Islands</option>
                    <option value="GU"> Guam</option>
                    <option value="AB"> Alberta</option>
                    <option value="BC"> British Columbia</option>
                    <option value="MB"> Manitoba</option>
                    <option value="NB"> New Brunswick</option>
                    <option value="NL"> Newfoundland and Labrador</option>
                    <option value="NS"> Nova Scotia</option>
                    <option value="ON"> Ontario</option>
                    <option value="PE"> Prince Edward Island</option>
                    <option value="QC"> Quebec</option>
                    <option value="SK"> Saskatchewan</option>
                    <option value="NT"> Northwest Territories</option>
                    <option value="NU"> Nunavut</option>
                    <option value="YT"> Yukon</option>
                </select>
            </div>

            <div class="input-group">
                <span class="input-group-addon"><span class="glyphicon glyphicon-globe"></span></span>
                <input class="form-control" name="zip" placeholder="Postal Code" type="number" maxlength="10" max="999999999" value="<?php echo(htmlspecialchars($zip)); ?>"  required/>
            </div>

            <div class="input-group">
                <span class="input-group-addon"><span class="glyphicon glyphicon-earphone"></span></span>
                <input class="form-control" name="phone" placeholder="Phone" type="tel" maxlength="15" value="<?php echo(htmlspecialchars($phone)); ?>"  required/>
            </div>

            <div class="input-group">
                <span class="input-group-addon"><span class="glyphicon glyphicon-star"></span></span>
                <select class="form-control" name="question"><option value="<?php echo(htmlspecialchars($question)); ?>"><?php echo(htmlspecialchars($question)); ?></option>
                    <option value="What is your maternal grandmother&#39;s maiden name?">What is your maternal grandmother&#39;s maiden name?</option>
                    <option value="What was the last name of your favorite teacher?">What was the last name of your favorite teacher?</option>
                    <option value="In what city did you meet your spouse/significant other?">In what city did you meet your spouse/significant other?</option>
                    <option value="What is your father&#39;s middle name?">What is your father&#39;s middle name?</option>
                    <option value="In what city were you born?">In what city were you born?</option>
                    <option value="What was the model of your first car?">What was the model of your first car?</option>
                    <option value="What is the name of your pet?">What is the name of your pet?</option>
                </select>
            </div>

            <div class="input-group">
                <span class="input-group-addon"><span class="glyphicon glyphicon-star-empty"></span></span>
                <input class="form-control" name="answer" placeholder="Security Answer" type="text" maxlength="60" value="<?php echo(htmlspecialchars($answer)); ?>"  required/>
            </div>

            <div class="input-group">
                <span class="input-group-addon"><span class="glyphicon glyphicon-lock"></span></span>
                <input class="form-control" name="newpassword" placeholder="Only if Changin-New Password" type="password" maxlength="31"/>
            </div>

            <div class="input-group">
                <span class="input-group-addon"><span class="glyphicon glyphicon-lock"></span></span>
                <input class="form-control" name="newconfirmation" placeholder="Only if Changing-New Confirmation" type="password" maxlength="31"/>
            </div>

            <div class="input-group">
                <span class="input-group-addon"><span class="glyphicon glyphicon-lock"></span></span>
                <input class="form-control" name="password" placeholder="Old Password" type="password" maxlength="31" required/>
            </div>

            <button  type="reset" id="clear" class="btn btn-lg btn-primary btn-block">CLEAR</button>



                <span class="input-group-btn">


                <button type="submit" class="btn btn-lg btn-success btn-block">
                    <span class="glyphicon glyphicon-pencil"></span>
                    &nbsp;  SUBMIT
                </button>
                </span>
        </fieldset>
    </form>
</div> <!-- /container -->



