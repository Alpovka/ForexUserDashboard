<?php
  include "services/config.php";

  $my_firstname = $_SESSION['firstname']   ;
  $my_lastname = $_SESSION['lastname']  ;
  $my_password = $_SESSION['password']  ;
  $my_id = $_SESSION['id']  ;

  $get_history_queue = "SELECT * FROM history";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="author" content="Alperen Karavelioglu">
    <link rel="stylesheet" type="text/css" 
    href="history.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/semantic-ui/2.4.1/semantic.min.js"></script>
  <script src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.min.js"></script>
  <link rel="stylesheet" href="https://cdn.datatables.net/1.10.25/css/jquery.dataTables.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/semantic-ui/2.4.1/semantic.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/currency-flags/3.2.1/currency-flags.min.css">
    <title>User Dashboard</title>
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
          <a id="market" href="index.php" class="item">
            <i class="money bill alternate outline icon"></i>
            Markets
          </a>
          <a href="balance.php" id="balance" class="item">
            <i class="credit card outl icon"></i>
            Balance
          </a>
          <a href="history.php" id="history" class="item active">
            <i class="history icon"></i>
            History
          </a>
          <div style="background-color: #fff;height: 48px;" class="ui fluid vertical animated button" tabindex="0">
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
        <div id="head" class="ui outline purple raised segment">
           <div id="page-head" class="ui medium header">HISTORY</div>
        </div>

        <!-- CONTENT -->
        <div id="maindown">
          <!-- TABLE OF HISTORY -->
          <table style="text-align: center;" class="ui selectable celled table">
            <thead>
              <tr>
                <th>Date</th>
                <th>Pair</th>
                <th>Side</th>
                <th>Price</th>
                <th>Amount</th>
                <th>Total</th>
                <th></th>
              </tr>
            </thead>
            <tbody>


              <?php
                $result = mysqli_query($db,$get_history_queue);
                while($row = mysqli_fetch_assoc($result)){
                  $id = $row["id"];
                  $date = $row["date"];
                  $side = $row["side"];
                  $pair = $row["pair"];
                  $target = substr($pair, -3);
                  $price = $row["price"];
                  $amount = $row["amount"];
                  $total = $row["total"];
                  
                  $color = "";
                  if($side == "BUY"){
                    $color = "green";
                  }
                  else if($side == "SELL"){
                    $color = "red";
                  }
                  else{
                    $color = "blue";
                  }

                  echo '<tr>';
                  echo   '<td>' . $date . '</td>';
                  echo   '<td class="pair">' . $pair . '</td>';
                  echo   '<td style="color:'. $color . '">' . $side . '</td>';
                  echo   '<td>' . $price . '</td>';
                  echo   '<td>' . $amount . '</td>';
                  echo   '<td>' . $total . " " . $target . '</td>';
                  echo   '<td><div style="padding: 0;cursor: default" class="ui red animated fade button" tabindex="0">';
                  echo       '<form action="services/deleteHistoryItem.php" method="POST">';
                  echo             '<div class="visible content">';
                  echo                '<input type="hidden" value= ' .$id  . ' name="DeleteButton">';
                  echo              '<button type="submit" style="background: none;color: inherit;border: none;cursor:pointer;padding: 0;transform: translateX(15%);margin: 0;height: 40px;width: 55px;"> DELETE </button>';
                  echo            '</div>';
                  echo        '</form>';
                  echo       '<div class="hidden content">';
                  echo         '<form action="services/deleteHistoryItem.php" method="POST">';
                  echo            '<input type="hidden" value= ' .$id  . ' name="DeleteButton">';
                  echo              '<button type="submit" style="background: red;color: inherit;border: 1px solid #fff;border-radius: 3px;cursor:pointer;transform: translateY(-10%)"> DELETE </button>';
                  echo        '</form>';
                  echo       '</div>';
                  echo     '</div></td>';
                  echo '</tr>';
                }
              ?>
              
            </tbody>
          </table>
        </div>
      </div>
       <!--********************** END OF DEFAULT VISIBLE CONTENT ****************************-->

          <!---------------------------------- MODALS -------------------------------------------->
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
                <form class="ui form" id="edit_form" action="services/editProfileService.php" method="POST">
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
</body>
<script>
    $(document).ready(function(){
      $(".ui.dropdown").dropdown(); // Dropdown Button Initialization
      $('.ui.modal').modal(); // Modal Initializion

      /***** Password edit validation *****/
      $("button[form=edit_form]").on("click", (e) => {
        var password = $("#password")[0].value;
        var passwordRepeat = $("#password-repeat")[0].value;
        if(password != passwordRepeat){
          e.preventDefault();
          alert("Password doesn't match!");
        }
      })

      // MODAL TRIGGERS
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
    });
  </script>
  <script src="history.js"></script>
</html>