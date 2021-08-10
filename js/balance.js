const fullTable = document.getElementsByTagName("tbody")[0].innerText.split("\n"); // Get the string version of the table.
const dataArray = []; // Empty array for pie chart data key 

// Every iteration will append a row object to the empty array
fullTable.forEach((row) => {
  rowSplitted = row.split("\t");
  currencyName = rowSplitted[0];
  totalValue = parseFloat(rowSplitted[3]);
  newObject = { name: currencyName, y: totalValue }
  dataArray.push(newObject)
})

// Radialize the colors
Highcharts.setOptions({
  colors: Highcharts.map(Highcharts.getOptions().colors, function (color) {
      return {
      radialGradient: {
          cx: 0.5,
          cy: 0.3,
          r: 0.7
      },
      stops: [
          [0, color],
          [1, Highcharts.color(color).brighten(-0.3).get('rgb')] // darken
      ]
      };
  })
  });

  // Build the chart
  Highcharts.chart('container-pie', {
  chart: {
      plotBackgroundColor: null,
      plotBorderWidth: null,
      plotShadow: false,
      type: 'pie'
  },
  title: {
      text: 'My total balance shares'
  },
  tooltip: {
      pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
  },
  accessibility: {
      point: {
      valueSuffix: '%'
      }
  },
  plotOptions: {
      pie: {
      allowPointSelect: true,
      cursor: 'pointer',
      dataLabels: {
          enabled: true,
          format: '<b>{point.name}</b>: {point.percentage:.1f} %',
          connectorColor: 'silver'
      }
      }
  },
  series: [{
      name: 'Share Currency',
      data: dataArray // Array full of objects
  }]
});

Highcharts.chart('container', {
    data: {
      table: 'datatable'
    },
    chart: {
      type: 'column'
    },
    title: {
      text: 'Individual currency details'
    },
    yAxis: {
      allowDecimals: false,
      title: {
        text: 'Units'
      }
    },
    tooltip: {
      formatter: function () {
        return '<b>' + this.series.name + '</b><br/>' +
          this.point.y + ' ' + this.point.name.toLowerCase();
      }
    }
  });

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

  