<?php
  include "services/config.php";

  $my_firstname = $_SESSION['firstname']   ;
  $my_lastname = $_SESSION['lastname']  ;
  $my_password = $_SESSION['password']  ;
  $my_id = $_SESSION['id']  ;


  $balance_total = 0;

  $get_balance_total_query = "SELECT * FROM balance";
  $result = mysqli_query($db,$get_balance_total_query);
  while($row = mysqli_fetch_assoc($result)){
    $unitV = $row["unit_value"];
    $amount = $row["amount"];
    $balance_total += $unitV*$amount;
  }

  $myTRYtotal = 0;
  $getTRYtotal = "SELECT amount FROM balance WHERE currency= 'TRY'";
  $row = mysqli_fetch_assoc(mysqli_query($db,$getTRYtotal));
  if(isset($row)){
    $myTRYtotal = $row["amount"];
  }else {
    $myTRYtotal=0;
  }

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="author" content="Alperen Karavelioglu">
    <link rel="stylesheet" type="text/css" 
    href="index.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/semantic-ui/2.4.1/semantic.min.js"></script>
  <script src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.min.js"></script>
  <link rel="stylesheet" href="https://cdn.datatables.net/1.10.25/css/jquery.dataTables.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/semantic-ui/2.4.1/semantic.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/currency-flags/3.2.1/currency-flags.min.css">
    <title>User Dashboard </title>
</head>
<body>
    <!-- SIDE BAR -->
    <div class="sidenav">
      <div  class="ui container">
        <button id="sidenav-headers" class="ui active button">
          <a>FOREX APP</a>
          <a href="#" style="font-size: 0.8rem;">USER DASHBORAD</a>
        </button>
        <div style="margin: 0;" class="ui divider"></div>
        <img class="ui medium circular image" src="https://i.pinimg.com/564x/65/25/a0/6525a08f1df98a2e3a545fe2ace4be47.jpg">
        <p> <?php echo $my_firstname . " " . $my_lastname ?></p>
        <div style="margin: 0;" class="ui divider"></div>
      </div>

      <!-- NAVIGATION LINKS -->
      <div class="nav-links">
        <div id="nav-links" class="ui vertical labeled icon menu">
          <a id="market" href="index.php" class="item active">
            <i class="money bill alternate outline icon"></i>
            Markets
          </a>
          <a href="balance.php" id="balance" class="item">
            <i class="credit card outl icon"></i>
            Balance
          </a>
          <a href="history.php" id="history" class="item">
            <i class="history icon"></i>
            History
          </a>
          <div style="background-color: #fff;height: 55px;" class="ui fluid vertical animated button" tabindex="0">
            <div class="visible content">
              DETAILS
            </div>

            <!-- OPTIONS & EDIT PROFILE MODAL -->
            <div style="background-color: #fff;" class="hidden content">
              <button id="edit" class="ui tiny left attached button"><i class="edit icon"></i></button>
              <button id="options" class="right tiny attached ui button"><i class="cogs icon"></i></button>
            </div>
          </div>      
      </div>
      </div>
      </div>
      
      <!-- RIGHT SIDE OF THE PAGE |MAIN CONTENT| -->
      <div class="main">
        <!-- TOP BAR -->
        <div id="head" class="ui raised segment">
          <div id="currency-dropdown" class="ui search selection dropdown">
            <input type="hidden" name="currency">
            <i class="dropdown icon"></i>
            <div class="default text">Select Currency</div>
            <div id="select-currency-menu" class="menu">

            </div>
           </div>
          <div id="page-head" class="ui medium header">MARKETS</div>
          <div id="deposit" class="ui vertical animated button" tabindex="0">
            <div id="tot-money" class="visible content"><?php echo number_format($balance_total, 4);?> TRY</div>
            <p id="TRY_in_balance" style="display: none"><?php echo $myTRYtotal; ?></p>

            <div class="hidden content">
              DEPOSIT <i class="sign-in icon"></i>
            </div>
          </div>
        </div>

        <!-- CONTENT -->
        <div id="maindown">
          <div id="cards-grid" class="ui violet cards">
          </div>
          <div class="chart">
            <!-- TradingView Widget BEGIN -->
            <div class="tradingview-widget-container">
              <div class="tradingview-widget-container__widget"></div>
              <script id="widget" type="text/javascript" src="https://s3.tradingview.com/external-embedding/embed-widget-market-overview.js" async></script>
            </div>
            <!-- TradingView Widget END -->
          </div>
        </div>
      </div>
      <!------------------------ END OF DEFAULT VISIBLE CONTENT ------------------------->

      <!-- LOADER -->
      <div class="loader-wrapper">
        <div class="loader">
          <div class="bar1"></div>
          <div class="bar2"></div>
          <div class="bar3"></div>
          <div class="bar4"></div>
          <div class="bar5"></div>
          <div class="bar6"></div>
        </div>
      </div>

      <!------------------------------------ MODALS ------------------------------------->

      <!-- DEPOSIT BUTTON MODAL -->
      <div id="deposit-modal" class="ui basic modal">
        <div class="ui icon header"><p style="display: none;" id="hidden-deposit"></p><p id="warnx"></p>
          <div style="display: flex;justify-content: center;">
            <div style="margin-right: 10px;">
              <i class="cc mastercard icon"></i>
              <i class="cc paypal icon"></i>
            </div>
            <div style="margin-right: 10px;">
              <i class="cc visa icon"></i>
            <i class="cc stripe icon"></i>
            </div>
          </div>
          <div class="ui right labeled input">
            <label for="amount" class="ui label"><div class="ui compact menu">
              <div id="deposit-selection-dropdown" class="ui search selection dropdown">
              <input type="hidden" name="currency">
                <i class="dropdown icon"></i>
                <div style="display: flex;justify-content: space-evenly;align-items: baseline;font-size: 0.5;" class="default text">Select Currency</div>
                <div id="select-currency-menu" class="menu">
                  <div class="item">EURO (EUR)  <i class=" tiny euro sign icon"></i></div>
                  <div class="item">DOLLAR (USD) <i class=" tiny dollar sign icon"></i></div>
                  <div class="item">POUND (GBP) <i class=" tiny pound sign icon"></i></div>
                  <div class="item">LIRA (TRY) <i class=" tiny lira sign icon"></i></div>
                  <div class="item">YEN (JPY) <i class=" tiny yen sign icon"></i></div>
                </div>
                </div>
              </div>
            </label>
            
            <form action="services/depositMoney.php"  id="deposit_form" method="POST">
              <input style="height: 100%;text-align: center;" type="number" name="amount" placeholder="Amount" id="amount"></form>

            <div id="converted-currency" style="display: flex;justify-content: center;align-items: center;" class="ui basic label"></div>
          </div>
        </div>
        <div class="actions">
          <div style="float: left;" class="ui red cancel button">
            <i class="remove icon"></i> CANCEL
          </div>
          <div id="confirm-btn" class="ui disabled green ok button">
            <i class="checkmark icon"></i> CONFIRM
          </div>
        </div>
      </div>

      <!-- OPTIONS MENU BUTTON MODAL -->
      <div id="loader-modal" class="ui basic modal">
        <div id="loader" class="ui active centered inline loader"></div>
      </div>

      <!-- EDIT PROFILE BUTTON MODAL  -->
      <div id="edit-modal" class="ui modal">
        <i class="close icon"></i>
        <div style="background-color: #f4f4f4;" class="header">
          Edit Profile
        </div>
        <div class="image content">
          <div class="ui medium image">
            <img src="https://i.pinimg.com/564x/65/25/a0/6525a08f1df98a2e3a545fe2ace4be47.jpg">
          </div>
          <div style="margin-top: 15px;text-align: center;font-size: 1.2rem;" class="description">
            <form class="ui form" action="services/editProfileService.php" method="POST", id="edit_form">
              <h4 class="ui dividing header">Profile Information</h4>
              <div class="field">
                <label>Name</label>
                <div class="two fields">
                  <div class="field">
                    <input type="text" name="firstname" placeholder= <?php echo $my_firstname?>>
                  </div>
                  <div class="field">
                    <input type="text" name="lastname" placeholder=<?php echo $my_lastname?>>
                  </div>
                </div>
                <label>Password</label>
                  <div class="field">
                    <input id="password" type="password" name="password" placeholder="Password">
                    <div class="ui divider"></div>
                    <input id="password-repeat" type="password" name="password-repeat" placeholder="Password (Again)">
                  </div>
              </div>
            </form>
          </div>
        </div>
        <div class="actions">
                <button type="submit" form="edit_form" style="background-color: green;color: #fff;border: 1px solid green;border-radius: 3px;cursor: pointer;padding: 10px 15px">SAVE <i class="checkmark icon"></i></button>
        </div>
      </div>

      <!-- MARKET BUY/SELL BUTTON MODAL -->
      <div id="buy-sell-button" class="ui tiny modal">
        <div id="buy-sell-button-header" style="display: flex;justify-content: space-between;align-items: baseline;" class="header">
        </div>
        <div style="display: flex;justify-content: space-between;" class="content">
          <div class="ui labeled input">
            <input id="buy-base-input" type="number" placeholder="Amount">
          </div>
          <i class="exchange blue big icon"></i>
          <div class="ui labeled input">
            <input id="buy-current-input" type="number" placeholder="Amount">
          </div>
        </div>
        <div style="display: flex;justify-content: space-between;" class="actions">
          <div class="ui floated left negative button"><i class="thumbs down big icon"></i> CANCEL</div>
          <div class="ui floated left positive button"></div>
        </div>
      </div>
</body>
  <script >
    $(window).on("load", () => { // CSS loader beginning of the page  
      $(".loader-wrapper").fadeOut("slow");
    })

    $(document).ready(function(){
      $(".ui.dropdown").dropdown(); // Dropdown button initialization
      $('.ui.modal').modal(); // Modal initialization     
      
      /***** Password edit validation *****/
      $("button[form=edit_form]").on("click", (e) => {
        var password = $("#password")[0].value;
        var passwordRepeat = $("#password-repeat")[0].value;
        if(password != passwordRepeat){
          e.preventDefault();
          alert("Password doesn't match!");
        }
      })

      /****** TRIGGERING MODALS  ******/
      $("#edit").on("click", () => {        
        $('#edit-modal').modal('show');
      });

      $("#options").on("click", () => {
        $('#loader-modal').modal('show');
        $("#loader").show();
      });

      $("#deposit").on("click", () => {
        $('#deposit-modal').modal('show');
      });

      $(document).on('click', '#main-buy-button', function() {
          $("#buy-sell-button").modal("show");
      });

      $(".ui.green.ok.button").on("click", () => { // Sending proper data to database
        var currencySIGN = $("#hidden-deposit")[0].innerText;
        var amount = $("#amount")[0].value;

        var convertedValue = $("#converted-currency")[0].innerText;
        console.log(depositModalRight);

        console.log("currency:" + currencySIGN);
        window.location.href = 'services/depositMoney.php?currency=' + currencySIGN + "&convertedVal=" + convertedValue +  "&amount=" + amount; 

      });

  });
  </script>
  <script src="index.js"></script>
</html