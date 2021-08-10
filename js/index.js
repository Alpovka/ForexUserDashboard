// Definin variables for insertCurrency function from the DOM
const selectCurrency = document.getElementById("select-currency-menu");
const cards = document.getElementById("cards-grid");
const selectedCurrency = document.getElementById("currency-dropdown");
const currencyBalanceElement = document.getElementById("tot-money");
const currencyBalance = currencyBalanceElement.textContent;
const balance = currencyBalance.split(" ")[0];
const currency = currencyBalance.split(" ")[1];


const insertCurrency = async function(base){  // Inserts every currency from the API to proper places
    
    cards.style.removeProperty("grid-template-columns"); 
    cards.style.gridColumn = "1 / 3";

    // Loading icon while API datas are exerting
    selectedCurrency.addEventListener("change", () => {
          cards.innerHTML = `<div class="ui segment">
        <div class="ui active inverted dimmer">
          <div class="ui medium text loader">Loading on ${base} basis...</div>
        </div>
      </div>`
    })

    const API = await fetch(`https://v6.exchangerate-api.com/v6/16d3608d197604757a4c6b9f/latest/${base}`).then(res => res.json())

    cards.style.setProperty("grid-template-columns", "repeat(auto-fit, minmax(175px, 1fr))")
    cards.style.removeProperty("grid-column");
    cards.innerHTML = "";
    
    currencies = API.conversion_rates;
    currencyArray = Object.entries(currencies);
    currencyArray.shift();

    // Iterator in conversion_rates array for inserting  
    currencyArray.forEach((chunk,_index) => {
       var sourceCurrency = chunk[0];
       var sourceCurrencyLowerCase = chunk[0].toLowerCase();
       var exchangeCurrency = API.base_code;
       var currencyValue = chunk[1];

       // Iterate over data and insert to dropdown button
       var newNodeItem = document.createElement("div"); 
       newNodeItem.style.display = "flex";
       newNodeItem.style.justifyContent = "space-evenly"
       newNodeItem.className = "item";
       newNodeItem.setAttribute("data-value", sourceCurrencyLowerCase);
       newNodeItem.innerHTML = `<i class='currency-flag currency-flag-${sourceCurrencyLowerCase}'></i> ${sourceCurrency}`
       selectCurrency.appendChild(newNodeItem);

       // Iterate over data and insert to card items
       var newNodeCard = document.createElement("div");
       newNodeCard.className = "card";
       newNodeCard.style.width = "190px";
       newNodeCard.style.maxHeight = "150px";
       newNodeCard.innerHTML = `<div style="background: linear-gradient(180deg,#f4f4f4, white);" class="content">
                                  <div class="cart-header">
                                    <div class="ui header">
                                      ${sourceCurrency}
                                    </div>
                                    <div style="color: goldenrod;" class="meta">
                                      ${exchangeCurrency}
                                    </div>
                                  </div>
                                  <div class="description">
                                    ${(1 / parseFloat(currencyValue)).toFixed(5).toString()}
                                  </div>
                                </div>
                                <div class="extra content">
                                  <div class="ui two buttons">
                                    <button id="main-buy-button" class="ui inverted green button">BUY</button>
                                    <button onclick="window.location.href='balance.php'" class="ui inverted red button">SELL</button>
                                  </div>
                                </div>`
        cards.appendChild(newNodeCard);

        // Event listener for every HTMLCollection element
        Array.from(buySellButton).forEach(function (button) {
          button.addEventListener("click", (e) => {
            if(e.target.innerText == "BUY")
              floatedPositiveButton.innerHTML = `<i class="horizontally flipped big thumbs up icon"></i> BUY`
            else
            floatedPositiveButton.innerHTML = `<i class="horizontally flipped big thumbs up icon"></i> SELL`
        
            var leftValue = e.target.parentElement.parentElement.previousElementSibling.firstElementChild.firstElementChild.innerText;
            var rightValue = e.target.parentElement.parentElement.previousElementSibling.firstElementChild.lastElementChild.innerText;
            var middleValue = e.target.parentElement.parentElement.previousElementSibling.lastElementChild.innerText;
        
            buyButtonModalHeader.innerHTML = `${leftValue}<div class="ui tag labels">
                                              <p class="ui blue large label">
                                                ${middleValue}
                                              </p>
                                            </div>
                                            <div style="color: goldenrod;" class="header">${rightValue}</div>`
        
            buyLeftInput.addEventListener("keyup", (e) => {
              buyRightInput.value = (parseFloat(e.target.value) * parseFloat(middleValue)).toFixed(5).toString();
            })
            buyLeftInput.value = ""
        
            buyRightInput.addEventListener("keyup", (e) => {
              buyLeftInput.value = (parseFloat(e.target.value) / parseFloat(middleValue)).toFixed(5).toString();
            })
            buyRightInput.value = ""
          })
        });        
    });
}

/***********  BUY & SELL variable definitons from DOM ***********/
const floatedPositiveButton = document.querySelector(".ui.floated.left.positive.button");
const buyButtonModal = document.getElementById("buy-sell-button");
const buyButtonModalHeader = document.getElementById("buy-sell-button-header");
const buySellButton = document.getElementsByClassName("ui inverted button");
const buyLeftInput = document.getElementById("buy-base-input");
const buyRightInput = document.getElementById("buy-current-input");

// Event listener BUYING AND SELLING opt.
floatedPositiveButton.addEventListener("click", (e) => {
  if(parseFloat(buyRightInput.value) > parseFloat(document.getElementById("TRY_in_balance").innerText))
  {
    //no action
    alert("You don't have enough TRY.");
  }
  else{
    //execute BUY operation
    const targetCurrency = document.getElementById("buy-sell-button-header").innerText.split("\n")[0];
    const incoming_amount = document.getElementById("buy-base-input").value;
    const unit_value = document.getElementById("buy-sell-button-header").innerText.split("\n")[2];
    const outgoing_amount = document.getElementById("buy-current-input").value;

    console.log(targetCurrency, incoming_amount, outgoing_amount);
    window.location.href = 'services/buy.php?'+
      'currency=' + targetCurrency +
      "&unit_value=" + unit_value +
      "&incoming_amount=" + incoming_amount+
      "&outgoing_amount=" + outgoing_amount;
  }
})

// Currency conversion function. This function fires no matter what but Select-Currency-Dropdown needs change to work the way we want. 
function exchangeCurrency(){
  selectedCurrency.addEventListener("change", async (e) => {
    var selectedCurrency = e.target.value.toUpperCase()

    const jsonData = await fetch(`https://free.currconv.com/api/v7/convert?q=${currency}_${selectedCurrency}&compact=ultra&apiKey=7ecea26aa2a80027107a`).then(res => res.json()).catch(err => console.log(err.message));

    currencyBalanceElement.textContent = `${(parseFloat(balance) * parseFloat(Object.values(jsonData)[0])).toFixed(4).toString()} ${selectedCurrency}`
    cards.innerHTML = "";
    document.getElementById("select-currency-menu").innerHTML = "";
    insertCurrency(selectedCurrency); // Fires off every currency changes
  })
}


exchangeCurrency();
insertCurrency(currencyBalanceElement.textContent.split(" ")[1]);



/***********  Deposit necessary variable definitons from DOM ***********/
const warnLabel = document.getElementById("warnx");
const hiddenDepositType = document.getElementById("hidden-deposit");
const depositButton = document.getElementById("deposit");
const depositButtonSign = document.getElementById("tot-money").innerText.split(" ")[1];
const depositModalRight = document.getElementById("converted-currency");
const depositModalLeft = document.getElementById("deposit-selection-dropdown");
const depositModalMiddle = document.getElementById("amount");
const confirmButton = document.getElementById("confirm-btn");


// Show up when currency changes in deposit modal
depositModalLeft.addEventListener("change", (e) => {
    hiddenDepositType.innerText = e.target.value.split(" ")[1].slice(1,4).toUpperCase();
    warnLabel.innerText = "Press Enter for conversion";
})


// Confirm button disabling event
depositButton.addEventListener("click", () => {
  depositModalRight.innerHTML = "";
  confirmButton.className = "ui disabled green ok button";
  depositModalMiddle.value = "NaN";
})

// Conversion operations on deposit
depositModalMiddle.addEventListener("keypress", async (e) => {
  if(e.key === "Enter") {
    e.preventDefault();
    warnLabel.innerText = "";
    depositModalRight.innerHTML = `Converting...<i class="sync loading icon"></i>`;
    const jsonDatadeposit = await fetch(`https://free.currconv.com/api/v7/convert?q=${hiddenDepositType.innerText}_${depositButtonSign}&compact=ultra&apiKey=7ecea26aa2a80027107a`).then(res => res.json()).catch(err => console.log(err.message));

    var convertedVal = (parseFloat(depositModalMiddle.value) * parseFloat(Object.values(jsonDatadeposit)[0])).toFixed(4).toString()
    if(convertedVal == "NaN")
      depositModalRight.innerHTML = "0.0000";
    else{
      depositModalRight.innerHTML = convertedVal;
    }

    if(!(depositModalRight.innerHTML == "0.0000")){
      confirmButton.className = "ui green ok button";
    }else{
      confirmButton.className = "ui disabled green ok button";
    }
  }
})


