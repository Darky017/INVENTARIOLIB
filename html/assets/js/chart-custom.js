(function(jQuery) {

    "use strict";

// for apexchart
function apexChartUpdate(chartdetail) {
    let color = getComputedStyle(document.documentElement).getPropertyValue('--dark');
    if (detail.dark) {
      color = getComputedStyle(document.documentElement).getPropertyValue('--white');
    }
    chart.updateOptions({
      chart: {
        foreColor: color
      }
    })
  }
  
// for am chart
function amChartUpdate(chartdetail) {
  // let color = getComputedStyle(document.documentElement).getPropertyValue('--dark');
  if (detail.dark) {
    // color = getComputedStyle(document.documentElement).getPropertyValue('--white');
    chart.stroke = am4core.color(getComputedStyle(document.documentElement).getPropertyValue('--white'));
  }
  chart.validateData();
}

/*---------------------------------------------------------------------
   Apex Charts
-----------------------------------------------------------------------*/
if (jQuery("#apex-basic").length) {
  options = {
    chart: {
      height: 350,
      type: "line",
      zoom: {
        enabled: !1
      }
    },
    colors: ["#4788ff"],
    series: [{
      name: "Desktops",
      data: [1041355149626991148]
    }],
    dataLabels: {
      enabled: !1
    },
    stroke: {
      curve: "straight"
    },
    title: {
      text: "Product Trends by Month",
      align: "left"
    },
    grid: {
      row: {
        colors: ["#f3f3f3""transparent"],
        opacity: .5
      }
    },
    xaxis: {
      categories: ["Jan""Feb""Mar""Apr""May""Jun""Jul""Aug""Sep"]
    }
  };
  if(typeof ApexCharts !== typeof undefined){
    (chart = new ApexCharts(document.querySelector("#apex-basic")options)).render()
    const body = document.querySelector('body')
    if (body.classList.contains('dark')) {
      apexChartUpdate(chart{
        dark: true
      })
    }

    document.addEventListener('ChangeColorMode'function (e) {
      apexChartUpdate(charte.detail)
    })
  }
}
if (jQuery("#apex-line-area").length) {
  options = {
    chart: {
      height: 350,
      type: "area"
    },
    dataLabels: {
      enabled: !1
    },
    stroke: {
      curve: "smooth"
    },
    colors: ["#4788ff""#ff4b4b"],
    series: [{
      name: "series1",
      data: [3140285142109100]
    }{
      name: "series2",
      data: [11324532345241]
    }],
    xaxis: {
      type: "datetime",
      categories: ["2018-09-19T00:00:00""2018-09-19T01:30:00""2018-09-19T02:30:00""2018-09-19T03:30:00""2018-09-19T04:30:00""2018-09-19T05:30:00""2018-09-19T06:30:00"]
    },
    tooltip: {
      x: {
        format: "dd/MM/yy HH:mm"
      }
    }
  };
  (chart = new ApexCharts(document.querySelector("#apex-line-area")options)).render()
  const body = document.querySelector('body')
  if (body.classList.contains('dark')) {
    apexChartUpdate(chart{
      dark: true
    })
  }

  document.addEventListener('ChangeColorMode'function (e) {
    apexChartUpdate(charte.detail)
  })
}
if (jQuery("#apex-bar").length) {
  options = {
    chart: {
      height: 350,
      type: "bar"
    },
    plotOptions: {
      bar: {
        horizontal: !0
      }
    },
    dataLabels: {
      enabled: !1
    },
    colors: ["#4788ff"],
    series: [{
      data: [470540580690110012001380]
    }],
    xaxis: {
      categories: ["Netherlands""Italy""France""Japan""United States""China""Germany"]
    }
  };
  (chart = new ApexCharts(document.querySelector("#apex-bar")options)).render()
  const body = document.querySelector('body')
  if (body.classList.contains('dark')) {
    apexChartUpdate(chart{
      dark: true
    })
  }

  document.addEventListener('ChangeColorMode'function (e) {
    apexChartUpdate(charte.detail)
  })
}
if (jQuery("#apex-column").length) {
  options = {
    chart: {
      height: 350,
      type: "bar"
    },
    plotOptions: {
      bar: {
        horizontal: !1,
        columnWidth: "55%",
        endingShape: "rounded"
      }
    },
    dataLabels: {
      enabled: !1
    },
    stroke: {
      show: !0,
      width: 2,
      colors: ["transparent"]
    },
    colors: ["#4788ff""#37e6b0""#ff4b4b"],
    series: [{
      name: "Net Profit",
      data: [445557566158]
    }{
      name: "Revenue",
      data: [76851019887105]
    }{
      name: "Free Cash Flow",
      data: [354136264548]
    }],
    xaxis: {
      categories: ["Feb""Mar""Apr""May""Jun""Jul"]
    },
    yaxis: {
      title: {
        text: "$ (thousands)"
      }
    },
    fill: {
      opacity: 1
    },
    tooltip: {
      y: {
        formatter: function(e) {
          return "$ " + e + " thousands"
        }
      }
    }
  };
  (chart = new ApexCharts(document.querySelector("#apex-column")options)).render()
  const body = document.querySelector('body')
  if (body.classList.contains('dark')) {
    apexChartUpdate(chart{
      dark: true
    })
  }

  document.addEventListener('ChangeColorMode'function (e) {
    apexChartUpdate(charte.detail)
  })
}
if (jQuery("#apex-mixed-chart").length) {
  options = {
    chart: {
      height: 350,
      type: "line",
      stacked: !1
    },
    stroke: {
      width: [025],
      curve: "smooth"
    },
    plotOptions: {
      bar: {
        columnWidth: "50%"
      }
    },
    colors: ["#ff4b4b""#37e6b0""#4788ff"],
    series: [{
      name: "Facebook",
      type: "column",
      data: [2311222713223721442230]
    }{
      name: "Vine",
      type: "area",
      data: [4455416722432141562743]
    }{
      name: "Dribbble",
      type: "line",
      data: [3025363045356452593639]
    }],
    fill: {
      opacity: [.85.251],
      gradient: {
        inverseColors: !1,
        shade: "light",
        type: "vertical",
        opacityFrom: .85,
        opacityTo: .55,
        stops: [0100100100]
      }
    },
    labels: ["01/01/2003""02/01/2003""03/01/2003""04/01/2003""05/01/2003""06/01/2003""07/01/2003""08/01/2003""09/01/2003""10/01/2003""11/01/2003"],
    markers: {
      size: 0
    },
    xaxis: {
      type: "datetime"
    },
    yaxis: {
      min: 0
    },
    tooltip: {
      shared: !0,
      intersect: !1,
      y: {
        formatter: function(e) {
          return void 0 !== e ? e.toFixed(0) + " views" : e
        }
      }
    },
    legend: {
      labels: {
        useSeriesColors: !0
      },
      markers: {
        customHTML: [function() {
          return ""
        }function() {
          return ""
        }function() {
          return ""
        }]
      }
    }
  };
  (chart = new ApexCharts(document.querySelector("#apex-mixed-chart")options)).render()
  const body = document.querySelector('body')
  if (body.classList.contains('dark')) {
    apexChartUpdate(chart{
      dark: true
    })
  }

  document.addEventListener('ChangeColorMode'function (e) {
    apexChartUpdate(charte.detail)
  })
}
if (jQuery("#apex-candlestick-chart").length) {
  options = {
    chart: {
      height: 350,
      type: "candlestick"
    },
    colors: ["#4788ff""#37e6b0""#37e6b0"],
    series: [{
      data: [{
        x: new Date(15387786e5),
        y: [6629.816650.56623.046633.33]
      }{
        x: new Date(15387804e5),
        y: [6632.016643.5966206630.11]
      }{
        x: new Date(15387822e5),
        y: [6630.716648.956623.346635.65]
      }{
        x: new Date(1538784e6),
        y: [6635.6566516629.676638.24]
      }{
        x: new Date(15387858e5),
        y: [6638.24664066206624.47]
      }{
        x: new Date(15387876e5),
        y: [6624.536636.036621.686624.31]
      }{
        x: new Date(15387894e5),
        y: [6624.616632.266176626.02]
      }{
        x: new Date(15387912e5),
        y: [66276627.626584.226603.02]
      }{
        x: new Date(1538793e6),
        y: [66056608.036598.956604.01]
      }{
        x: new Date(15387948e5),
        y: [6604.56614.46602.266608.02]
      }{
        x: new Date(15387966e5),
        y: [6608.026610.686601.996608.91]
      }{
        x: new Date(15387984e5),
        y: [6608.916618.996608.016612]
      }{
        x: new Date(15388002e5),
        y: [66126615.136605.096612]
      }{
        x: new Date(1538802e6),
        y: [66126624.126608.436622.95]
      }{
        x: new Date(15388038e5),
        y: [6623.916623.9166156615.67]
      }{
        x: new Date(15388056e5),
        y: [6618.696618.7466106610.4]
      }{
        x: new Date(15388074e5),
        y: [66116622.786610.46614.9]
      }{
        x: new Date(15388092e5),
        y: [6614.96626.26613.336623.45]
      }{
        x: new Date(1538811e6),
        y: [6623.4866276618.386620.35]
      }{
        x: new Date(15388128e5),
        y: [6619.436620.356610.056615.53]
      }{
        x: new Date(15388146e5),
        y: [6615.536617.9366106615.19]
      }{
        x: new Date(15388164e5),
        y: [6615.196621.66608.26620]
      }{
        x: new Date(15388182e5),
        y: [6619.546625.176614.156620]
      }{
        x: new Date(153882e7),
        y: [6620.336634.156617.246624.61]
      }{
        x: new Date(15388218e5),
        y: [6625.9566266611.666617.58]
      }{
        x: new Date(15388236e5),
        y: [66196625.976595.276598.86]
      }{
        x: new Date(15388254e5),
        y: [6598.866598.8865706587.16]
      }{
        x: new Date(15388272e5),
        y: [6588.86660065806593.4]
      }{
        x: new Date(1538829e6),
        y: [6593.996598.8965856587.81]
      }{
        x: new Date(15388308e5),
        y: [6587.816592.736567.146578]
      }{
        x: new Date(15388326e5),
        y: [6578.356581.726567.396579]
      }{
        x: new Date(15388344e5),
        y: [6579.386580.926566.776575.96]
      }{
        x: new Date(15388362e5),
        y: [6575.9665896571.776588.92]
      }{
        x: new Date(1538838e6),
        y: [6588.9265946577.556589.22]
      }{
        x: new Date(15388398e5),
        y: [6589.36598.896589.16596.08]
      }{
        x: new Date(15388416e5),
        y: [6597.566006588.396596.25]
      }{
        x: new Date(15388434e5),
        y: [6598.0366006588.736595.97]
      }{
        x: new Date(15388452e5),
        y: [6595.976602.016588.176602]
      }{
        x: new Date(1538847e6),
        y: [660266076596.516599.95]
      }{
        x: new Date(15388488e5),
        y: [6600.636601.216590.396591.02]
      }{
        x: new Date(15388506e5),
        y: [6591.026603.0865916591]
      }{
        x: new Date(15388524e5),
        y: [65916601.3265856592]
      }{
        x: new Date(15388542e5),
        y: [6593.136596.0165906593.34]
      }{
        x: new Date(1538856e6),
        y: [6593.346604.766582.636593.86]
      }{
        x: new Date(15388578e5),
        y: [6593.866604.286586.576600.01]
      }{
        x: new Date(15388596e5),
        y: [6601.816603.216592.786596.25]
      }{
        x: new Date(15388614e5),
        y: [6596.256604.265906602.99]
      }{
        x: new Date(15388632e5),
        y: [6602.9966066584.996587.81]
      }{
        x: new Date(1538865e6),
        y: [6587.8165956583.276591.96]
      }{
        x: new Date(15388668e5),
        y: [6591.976596.0765856588.39]
      }{
        x: new Date(15388686e5),
        y: [6587.66598.216587.66594.27]
      }{
        x: new Date(15388704e5),
        y: [6596.44660165906596.55]
      }{
        x: new Date(15388722e5),
        y: [6598.9166056596.616600.02]
      }{
        x: new Date(1538874e6),
        y: [6600.5566056589.146593.01]
      }{
        x: new Date(15388758e5),
        y: [6593.15660565926603.06]
      }]
    }],
    title: {
      text: "CandleStick Chart",
      align: "left"
    },
    xaxis: {
      type: "datetime"
    },
    yaxis: {
      tooltip: {
        enabled: !0
      }
    }
  };
  (chart = new ApexCharts(document.querySelector("#apex-candlestick-chart")options)).render()
  const body = document.querySelector('body')
  if (body.classList.contains('dark')) {
    apexChartUpdate(chart{
      dark: true
    })
  }

  document.addEventListener('ChangeColorMode'function (e) {
    apexChartUpdate(charte.detail)
  })
}
if (jQuery("#apex-bubble-chart").length) {
  function generateData(eta) {
    for (var n = 0o = []; n < t;) {
      var r = Math.floor(Math.random() * (a.max - a.min + 1)) + a.min,
        i = Math.floor(61 * Math.random()) + 15;
      o.push([eri])e += 864e5n++
    }
    return o
  }
  options = {
    chart: {
      height: 350,
      type: "bubble"
    },
    dataLabels: {
      enabled: !1
    },
    series: [{
      name: "Product1",
      data: generateData(new Date("11 Feb 2017 GMT").getTime()20{
        min: 10,
        max: 40
      })
    }{
      name: "Product2",
      data: generateData(new Date("11 Feb 2017 GMT").getTime()20{
        min: 10,
        max: 40
      })
    }{
      name: "Product3",
      data: generateData(new Date("11 Feb 2017 GMT").getTime()20{
        min: 10,
        max: 40
      })
    }],
    fill: {
      type: "gradient"
    },
    colors: ["#4788ff""#37e6b0""#37e6b0"],
    title: {
      text: "3D Bubble Chart"
    },
    xaxis: {
      tickAmount: 12,
      type: "datetime",
      labels: {
        rotate: 0
      }
    },
    yaxis: {
      max: 40
    },
    theme: {
      palette: "palette2"
    }
  };
  (chart = new ApexCharts(document.querySelector("#apex-bubble-chart")options)).render()
  const body = document.querySelector('body')
  if (body.classList.contains('dark')) {
    apexChartUpdate(chart{
      dark: true
    })
  }

  document.addEventListener('ChangeColorMode'function (e) {
    apexChartUpdate(charte.detail)
  })
}
if (jQuery("#apex-scatter-chart").length) {
  options = {
    chart: {
      height: 350,
      type: "scatter",
      zoom: {
        enabled: !0,
        type: "xy"
      }
    },
    colors: ["#4788ff""#ff4b4b""#37e6b0"],
    series: [{
      name: "SAMPLE A",
      data: [
        [16.45.4],
        [21.72],
        [10.90],
        [10.98.2],
        [16.40],
        [16.41.8],
        [13.6.3],
        [13.60],
        [29.90],
        [27.12.3],
        [16.40],
        [13.63.7],
        [10.95.2],
        [16.46.5],
        [10.90],
        [24.57.1],
        [10.90],
        [8.14.7]
      ]
    }{
      name: "SAMPLE B",
      data: [
        [36.413.4],
        [1.711],
        [1.99],
        [1.913.2],
        [1.47],
        [6.48.8],
        [3.64.3],
        [1.610],
        [9.92],
        [7.115],
        [1.40],
        [3.613.7],
        [1.915.2],
        [6.416.5],
        [.910],
        [4.517.1],
        [10.910],
        [.114.7]
      ]
    }{
      name: "SAMPLE C",
      data: [
        [21.73],
        [23.63.5],
        [284],
        [27.1.3],
        [16.44],
        [13.60],
        [195],
        [22.43],
        [24.53],
        [32.63],
        [27.14],
        [29.66],
        [31.68],
        [21.65],
        [20.94],
        [22.40],
        [32.610.3],
        [29.720.8]
      ]
    }],
    xaxis: {
      tickAmount: 5,
      labels: {
        formatter: function(e) {
          return parseFloat(e).toFixed(1)
        }
      }
    },
    yaxis: {
      tickAmount: 5
    }
  };
  (chart = new ApexCharts(document.querySelector("#apex-scatter-chart")options)).render()
  const body = document.querySelector('body')
  if (body.classList.contains('dark')) {
    apexChartUpdate(chart{
      dark: true
    })
  }

  document.addEventListener('ChangeColorMode'function (e) {
    apexChartUpdate(charte.detail)
  })
}
if (jQuery("#apex-radialbar-chart").length) {
  options = {
    chart: {
      height: 350,
      type: "radialBar"
    },
    plotOptions: {
      radialBar: {
        dataLabels: {
          name: {
            fontSize: "22px"
          },
          value: {
            fontSize: "16px"
          },
          total: {
            show: !0,
            label: "Total",
            formatter: function(e) {
              return 249
            }
          }
        }
      }
    },
    series: [44556783],
    labels: ["Apples""Oranges""Bananas""Berries"],
    colors: ["#4788ff""#ff4b4b""#876cfe""#37e6b0"]
  };
  (chart = new ApexCharts(document.querySelector("#apex-radialbar-chart")options)).render()
  const body = document.querySelector('body')
  if (body.classList.contains('dark')) {
    apexChartUpdate(chart{
      dark: true
    })
  }

  document.addEventListener('ChangeColorMode'function (e) {
    apexChartUpdate(charte.detail)
  })
}
if (jQuery("#apex-pie-chart").length) {
  options = {
    chart: {
      width: 380,
      type: "pie"
    },
    labels: ["Team A""Team B""Team C""Team D""Team E"],
    series: [4455134322],
    colors: ["#4788ff""#ff4b4b""#876cfe""#37e6b0""#c8c8c8"],
    responsive: [{
      breakpoint: 480,
      options: {
        chart: {
          width: 200
        },
        legend: {
          position: "bottom"
        }
      }
    }]
  };
  (chart = new ApexCharts(document.querySelector("#apex-pie-chart")options)).render()
  const body = document.querySelector('body')
  if (body.classList.contains('dark')) {
    apexChartUpdate(chart{
      dark: true
    })
  }

  document.addEventListener('ChangeColorMode'function (e) {
    apexChartUpdate(charte.detail)
  })
}
if (jQuery("#advanced-chart").length) {
  var options = {
    series: [
    {
      name: 'Bob',
      data: [
        {
          x: 'Design',
          y: [
            new Date('2019-03-05').getTime(),
            new Date('2019-03-08').getTime()
          ]
        },
        {
          x: 'Code',
          y: [
            new Date('2019-03-02').getTime(),
            new Date('2019-03-05').getTime()
          ]
        },
        {
          x: 'Code',
          y: [
            new Date('2019-03-05').getTime(),
            new Date('2019-03-07').getTime()
          ]
        },
        {
          x: 'Test',
          y: [
            new Date('2019-03-03').getTime(),
            new Date('2019-03-09').getTime()
          ]
        },
        {
          x: 'Test',
          y: [
            new Date('2019-03-08').getTime(),
            new Date('2019-03-11').getTime()
          ]
        },
        {
          x: 'Validation',
          y: [
            new Date('2019-03-11').getTime(),
            new Date('2019-03-16').getTime()
          ]
        },
        {
          x: 'Design',
          y: [
            new Date('2019-03-01').getTime(),
            new Date('2019-03-03').getTime()
          ]
        }
      ]
    },
    {
      name: 'Joe',
      data: [
        {
          x: 'Design',
          y: [
            new Date('2019-03-02').getTime(),
            new Date('2019-03-05').getTime()
          ]
        },
        {
          x: 'Test',
          y: [
            new Date('2019-03-06').getTime(),
            new Date('2019-03-16').getTime()
          ]
        },
        {
          x: 'Code',
          y: [
            new Date('2019-03-03').getTime(),
            new Date('2019-03-07').getTime()
          ]
        },
        {
          x: 'Deployment',
          y: [
            new Date('2019-03-20').getTime(),
            new Date('2019-03-22').getTime()
          ]
        },
        {
          x: 'Design',
          y: [
            new Date('2019-03-10').getTime(),
            new Date('2019-03-16').getTime()
          ]
        }
      ]
    },
    {
      name: 'Dan',
      data: [
        {
          x: 'Code',
          y: [
            new Date('2019-03-10').getTime(),
            new Date('2019-03-17').getTime()
          ]
        },
        {
          x: 'Validation',
          y: [
            new Date('2019-03-05').getTime(),
            new Date('2019-03-09').getTime()
          ]
        },
      ]
    }
  ],
    colors: ["#4788ff""#ff4b4b""#37e6b0"],
    chart: {
    height: 450,
    type: 'rangeBar'
  },
  plotOptions: {
    bar: {
      horizontal: true,
      barHeight: '80%'
    }
  },
  xaxis: {
    type: 'datetime'
  },
  stroke: {
    width: 1
  },
  fill: {
    type: 'solid',
    opacity: 0.6
  },
  legend: {
    position: 'top',
    horizontalAlign: 'left'
  }
  };

  (chart = new ApexCharts(document.querySelector("#advanced-chart")options)).render()
  const body = document.querySelector('body')
  if (body.classList.contains('dark')) {
    apexChartUpdate(chart{
      dark: true
    })
  }

  document.addEventListener('ChangeColorMode'function (e) {
    apexChartUpdate(charte.detail)
  })
}
if (jQuery("#radar-multiple-chart").length) {
  var options = {
    series: [{
    name: 'Series 1',
    data: [8050304010020],
  }{
    name: 'Series 2',
    data: [203040802080],
  }{
    name: 'Series 3',
    data: [447678134310],
  }],
  colors: ["#4788ff""#ff4b4b""#37e6b0"],
    chart: {
    height: 350,
    type: 'radar',
    dropShadow: {
      enabled: true,
      blur: 1,
      left: 1,
      top: 1
    }
  },
  title: {
    text: 'Radar Chart - Multi Series'
  },
  stroke: {
    width: 2
  },
  fill: {
    opacity: 0.1
  },
  markers: {
    size: 0
  },
  xaxis: {
    categories: ['2011''2012''2013''2014''2015''2016']
  }
  };

  (chart = new ApexCharts(document.querySelector("#radar-multiple-chart")options)).render()
  const body = document.querySelector('body')
  if (body.classList.contains('dark')) {
    apexChartUpdate(chart{
      dark: true
    })
  }

  document.addEventListener('ChangeColorMode'function (e) {
    apexChartUpdate(charte.detail)
  })
}



/*---------------------------------------------------------------------
   Am Charts
-----------------------------------------------------------------------*/

    if(jQuery('#am-simple-chart').length){
      am4core.ready(function() {

      // Themes begin
      am4core.useTheme(am4themes_animated);
      // Themes end

      // Create chart instance
      var chart = am4core.create("am-simple-chart"am4charts.XYChart);
      chart.colors.list = [am4core.color("#32BDEA"),am4core.color("#6c757d")];

      // Add data
      chart.data = [{
        "country": "USA",
        "visits": 2025
      }{
        "country": "China",
        "visits": 1882
      }{
        "country": "Japan",
        "visits": 1809
      }{
        "country": "Germany",
        "visits": 1322
      }{
        "country": "UK",
        "visits": 1122
      }{
        "country": "France",
        "visits": 1114
      }];

      // Create axes

      var categoryAxis = chart.xAxes.push(new am4charts.CategoryAxis());
      categoryAxis.dataFields.category = "country";
      categoryAxis.renderer.grid.template.location = 0;
      categoryAxis.renderer.minGridDistance = 30;

      categoryAxis.renderer.labels.template.adapter.add("dy"function(dytarget) {
        if (target.dataItem && target.dataItem.index & 2 == 2) {
          return dy + 25;
        }
        return dy;
      });

      var valueAxis = chart.yAxes.push(new am4charts.ValueAxis());

      // Create series
      var series = chart.series.push(new am4charts.ColumnSeries());
      series.dataFields.valueY = "visits";
      series.dataFields.categoryX = "country";
      series.name = "Visits";
      series.columns.template.tooltipText = "{categoryX}: [bold]{valueY}[/]";
      series.columns.template.fillOpacity = .8;

      var columnTemplate = series.columns.template;
      columnTemplate.strokeWidth = 2;
      columnTemplate.strokeOpacity = 1;

      const body = document.querySelector('body')
      if (body.classList.contains('dark')) {
        amChartUpdate(chart{
          dark: true
        })
      }

      document.addEventListener('ChangeColorMode'function (e) {
        amChartUpdate(charte.detail)
      })

      }); // end am4core.ready()
   }

   if(jQuery('#am-columnlinr-chart').length){
      am4core.ready(function() {

      // Themes begin
      am4core.useTheme(am4themes_animated);
      // Themes end

      // Create chart instance
      var chart = am4core.create("am-columnlinr-chart"am4charts.XYChart);
       chart.colors.list = [am4core.color("#4788ff"),];

      // Export
      chart.exporting.menu = new am4core.ExportMenu();

      // Data for both series
      var data = [ {
        "year": "2009",
        "income": 23.5,
        "expenses": 21.1
      }{
        "year": "2010",
        "income": 26.2,
        "expenses": 30.5
      }{
        "year": "2011",
        "income": 30.1,
        "expenses": 34.9
      }{
        "year": "2012",
        "income": 29.5,
        "expenses": 31.1
      }{
        "year": "2013",
        "income": 30.6,
        "expenses": 28.2,
        "lineDash": "5,5",
      }{
        "year": "2014",
        "income": 34.1,
        "expenses": 32.9,
        "strokeWidth": 1,
        "columnDash": "5,5",
        "fillOpacity": 0.2,
        "additional": "(projection)"
      } ];

      /* Create axes */
      var categoryAxis = chart.xAxes.push(new am4charts.CategoryAxis());
      categoryAxis.dataFields.category = "year";
      categoryAxis.renderer.minGridDistance = 30;

      /* Create value axis */
      var valueAxis = chart.yAxes.push(new am4charts.ValueAxis());

      /* Create series */
      var columnSeries = chart.series.push(new am4charts.ColumnSeries());
      columnSeries.name = "Income";
      columnSeries.dataFields.valueY = "income";
      columnSeries.dataFields.categoryX = "year";

      columnSeries.columns.template.tooltipText = "[#fff font-size: 15px]{name} in {categoryX}:\n[/][#fff font-size: 20px]{valueY}[/] [#fff]{additional}[/]"
      columnSeries.columns.template.propertyFields.fillOpacity = "fillOpacity";
      columnSeries.columns.template.propertyFields.stroke = "stroke";
      columnSeries.columns.template.propertyFields.strokeWidth = "strokeWidth";
      columnSeries.columns.template.propertyFields.strokeDasharray = "columnDash";
      columnSeries.tooltip.label.textAlign = "middle";

      var lineSeries = chart.series.push(new am4charts.LineSeries());
      lineSeries.name = "Expenses";
      lineSeries.dataFields.valueY = "expenses";
      lineSeries.dataFields.categoryX = "year";

      lineSeries.stroke = am4core.color("#4788ff");
      lineSeries.strokeWidth = 3;
      lineSeries.propertyFields.strokeDasharray = "lineDash";
      lineSeries.tooltip.label.textAlign = "middle";

      var bullet = lineSeries.bullets.push(new am4charts.Bullet());
      bullet.fill = am4core.color("#fdd400"); // tooltips grab fill from parent by default
      bullet.tooltipText = "[#fff font-size: 15px]{name} in {categoryX}:\n[/][#fff font-size: 20px]{valueY}[/] [#fff]{additional}[/]"
      var circle = bullet.createChild(am4core.Circle);
      circle.radius = 4;
      circle.fill = am4core.color("#fff");
      circle.strokeWidth = 3;

      chart.data = data;

        const body = document.querySelector('body')
        if (body.classList.contains('dark')) {
          amChartUpdate(chart{
            dark: true
          })
        }

        document.addEventListener('ChangeColorMode'function (e) {
          amChartUpdate(charte.detail)
        })

      });
   }

   if(jQuery('#am-stackedcolumn-chart').length){
      am4core.ready(function() {

      // Themes begin
      am4core.useTheme(am4themes_animated);
      // Themes end

      // Create chart instance
      var chart = am4core.create("am-stackedcolumn-chart"am4charts.XYChart);
      chart.colors.list = [am4core.color("#ff4b4b"),
      am4core.color("#37e6b0"),
      am4core.color("#fe721c")];


      // Add data
      chart.data = [{
        "year": "2016",
        "europe": 2.5,
        "namerica": 2.5,
        "asia": 2.1,
        "lamerica": 0.3,
        "meast": 0.2
      }{
        "year": "2017",
        "europe": 2.6,
        "namerica": 2.7,
        "asia": 2.2,
        "lamerica": 0.3,
        "meast": 0.3
      }{
        "year": "2018",
        "europe": 2.8,
        "namerica": 2.9,
        "asia": 2.4,
        "lamerica": 0.3,
        "meast": 0.3
      }];

      // Create axes
      var categoryAxis = chart.xAxes.push(new am4charts.CategoryAxis());
      categoryAxis.dataFields.category = "year";
      categoryAxis.renderer.grid.template.location = 0;


      var valueAxis = chart.yAxes.push(new am4charts.ValueAxis());
      valueAxis.renderer.inside = true;
      valueAxis.renderer.labels.template.disabled = true;
      valueAxis.min = 0;

      // Create series
      function createSeries(fieldname) {

        // Set up series
        var series = chart.series.push(new am4charts.ColumnSeries());
        series.name = name;
        series.dataFields.valueY = field;
        series.dataFields.categoryX = "year";
        series.sequencedInterpolation = true;

        // Make it stacked
        series.stacked = true;

        // Configure columns
        series.columns.template.width = am4core.percent(60);
        series.columns.template.tooltipText = "[bold]{name}[/]\n[font-size:14px]{categoryX}: {valueY}";

        // Add label
        var labelBullet = series.bullets.push(new am4charts.LabelBullet());
        labelBullet.label.text = "{valueY}";
        labelBullet.locationY = 0.5;

        return series;
      }

      createSeries("europe""Europe");
      createSeries("namerica""North America");
      createSeries("asia""Asia-Pacific");

      // Legend
      chart.legend = new am4charts.Legend();

        const body = document.querySelector('body')
        if (body.classList.contains('dark')) {
          amChartUpdate(chart{
            dark: true
          })
        }

        document.addEventListener('ChangeColorMode'function (e) {
          amChartUpdate(charte.detail)
        })

      }); // end am4core.ready()
   }

   if(jQuery('#am-barline-chart').length){
      am4core.ready(function() {

      // Themes begin
      am4core.useTheme(am4themes_animated);
      // Themes end

      var chart = am4core.create("am-barline-chart"am4charts.XYChart);
      chart.colors.list = [am4core.color("#4788ff"),
      am4core.color("#37e6b0")];

      chart.data = [{
              "year": "2005",
              "income": 23.5,
              "expenses": 18.1
          }{
              "year": "2006",
              "income": 26.2,
              "expenses": 22.8
          }{
              "year": "2007",
              "income": 30.1,
              "expenses": 23.9
          }{
              "year": "2008",
              "income": 29.5,
              "expenses": 25.1
          }{
              "year": "2009",
              "income": 24.6,
              "expenses": 25
          }];

      //create category axis for years
      var categoryAxis = chart.yAxes.push(new am4charts.CategoryAxis());
      categoryAxis.dataFields.category = "year";
      categoryAxis.renderer.inversed = true;
      categoryAxis.renderer.grid.template.location = 0;

      //create value axis for income and expenses
      var valueAxis = chart.xAxes.push(new am4charts.ValueAxis());
      valueAxis.renderer.opposite = true;


      //create columns
      var series = chart.series.push(new am4charts.ColumnSeries());
      series.dataFields.categoryY = "year";
      series.dataFields.valueX = "income";
      series.name = "Income";
      series.columns.template.fillOpacity = 0.5;
      series.columns.template.strokeOpacity = 0;
      series.tooltipText = "Income in {categoryY}: {valueX.value}";

      //create line
      var lineSeries = chart.series.push(new am4charts.LineSeries());
      lineSeries.dataFields.categoryY = "year";
      lineSeries.dataFields.valueX = "expenses";
      lineSeries.name = "Expenses";
      lineSeries.strokeWidth = 3;
      lineSeries.tooltipText = "Expenses in {categoryY}: {valueX.value}";

      //add bullets
      var circleBullet = lineSeries.bullets.push(new am4charts.CircleBullet());
      circleBullet.circle.fill = am4core.color("#fff");
      circleBullet.circle.strokeWidth = 2;

      //add chart cursor
      chart.cursor = new am4charts.XYCursor();
      chart.cursor.behavior = "zoomY";

      //add legend
      chart.legend = new am4charts.Legend();

        const body = document.querySelector('body')
        if (body.classList.contains('dark')) {
          amChartUpdate(chart{
            dark: true
          })
        }

        document.addEventListener('ChangeColorMode'function (e) {
          amChartUpdate(charte.detail)
        })

      }); // end am4core.ready()
   }

   if(jQuery('#am-datedata-chart').length){
      am4core.ready(function() {

      // Themes begin
      am4core.useTheme(am4themes_animated);
      // Themes end

      // Create chart instance
      var chart = am4core.create("am-datedata-chart"am4charts.XYChart);
      chart.colors.list = [am4core.color("#57aeff")];

      // Add data
      chart.data = [{
        "date": "2012-07-27",
        "value": 13
      }{
        "date": "2012-07-28",
        "value": 11
      }{
        "date": "2012-07-29",
        "value": 15
      }{
        "date": "2012-07-30",
        "value": 16
      }{
        "date": "2012-07-31",
        "value": 18
      }{
        "date": "2012-08-01",
        "value": 13
      }{
        "date": "2012-08-02",
        "value": 22
      }{
        "date": "2012-08-03",
        "value": 23
      }{
        "date": "2012-08-04",
        "value": 20
      }{
        "date": "2012-08-05",
        "value": 17
      }{
        "date": "2012-08-06",
        "value": 16
      }{
        "date": "2012-08-07",
        "value": 18
      }{
        "date": "2012-08-08",
        "value": 21
      }{
        "date": "2012-08-09",
        "value": 26
      }{
        "date": "2012-08-10",
        "value": 24
      }{
        "date": "2012-08-11",
        "value": 29
      }{
        "date": "2012-08-12",
        "value": 32
      }{
        "date": "2012-08-13",
        "value": 18
      }{
        "date": "2012-08-14",
        "value": 24
      }{
        "date": "2012-08-15",
        "value": 22
      }{
        "date": "2012-08-16",
        "value": 18
      }{
        "date": "2012-08-17",
        "value": 19
      }{
        "date": "2012-08-18",
        "value": 14
      }{
        "date": "2012-08-19",
        "value": 15
      }{
        "date": "2012-08-20",
        "value": 12
      }{
        "date": "2012-08-21",
        "value": 8
      }{
        "date": "2012-08-22",
        "value": 9
      }{
        "date": "2012-08-23",
        "value": 8
      }{
        "date": "2012-08-24",
        "value": 7
      }{
        "date": "2012-08-25",
        "value": 5
      }{
        "date": "2012-08-26",
        "value": 11
      }{
        "date": "2012-08-27",
        "value": 13
      }{
        "date": "2012-08-28",
        "value": 18
      }{
        "date": "2012-08-29",
        "value": 20
      }{
        "date": "2012-08-30",
        "value": 29
      }{
        "date": "2012-08-31",
        "value": 33
      }{
        "date": "2012-09-01",
        "value": 42
      }{
        "date": "2012-09-02",
        "value": 35
      }{
        "date": "2012-09-03",
        "value": 31
      }{
        "date": "2012-09-04",
        "value": 47
      }{
        "date": "2012-09-05",
        "value": 52
      }{
        "date": "2012-09-06",
        "value": 46
      }{
        "date": "2012-09-07",
        "value": 41
      }{
        "date": "2012-09-08",
        "value": 43
      }{
        "date": "2012-09-09",
        "value": 40
      }{
        "date": "2012-09-10",
        "value": 39
      }{
        "date": "2012-09-11",
        "value": 34
      }{
        "date": "2012-09-12",
        "value": 29
      }{
        "date": "2012-09-13",
        "value": 34
      }{
        "date": "2012-09-14",
        "value": 37
      }{
        "date": "2012-09-15",
        "value": 42
      }{
        "date": "2012-09-16",
        "value": 49
      }{
        "date": "2012-09-17",
        "value": 46
      }{
        "date": "2012-09-18",
        "value": 47
      }{
        "date": "2012-09-19",
        "value": 55
      }{
        "date": "2012-09-20",
        "value": 59
      }{
        "date": "2012-09-21",
        "value": 58
      }{
        "date": "2012-09-22",
        "value": 57
      }{
        "date": "2012-09-23",
        "value": 61
      }{
        "date": "2012-09-24",
        "value": 59
      }{
        "date": "2012-09-25",
        "value": 67
      }{
        "date": "2012-09-26",
        "value": 65
      }{
        "date": "2012-09-27",
        "value": 61
      }{
        "date": "2012-09-28",
        "value": 66
      }{
        "date": "2012-09-29",
        "value": 69
      }{
        "date": "2012-09-30",
        "value": 71
      }{
        "date": "2012-10-01",
        "value": 67
      }{
        "date": "2012-10-02",
        "value": 63
      }{
        "date": "2012-10-03",
        "value": 46
      }{
        "date": "2012-10-04",
        "value": 32
      }{
        "date": "2012-10-05",
        "value": 21
      }{
        "date": "2012-10-06",
        "value": 18
      }{
        "date": "2012-10-07",
        "value": 21
      }{
        "date": "2012-10-08",
        "value": 28
      }{
        "date": "2012-10-09",
        "value": 27
      }{
        "date": "2012-10-10",
        "value": 36
      }{
        "date": "2012-10-11",
        "value": 33
      }{
        "date": "2012-10-12",
        "value": 31
      }{
        "date": "2012-10-13",
        "value": 30
      }{
        "date": "2012-10-14",
        "value": 34
      }{
        "date": "2012-10-15",
        "value": 38
      }{
        "date": "2012-10-16",
        "value": 37
      }{
        "date": "2012-10-17",
        "value": 44
      }{
        "date": "2012-10-18",
        "value": 49
      }{
        "date": "2012-10-19",
        "value": 53
      }{
        "date": "2012-10-20",
        "value": 57
      }{
        "date": "2012-10-21",
        "value": 60
      }{
        "date": "2012-10-22",
        "value": 61
      }{
        "date": "2012-10-23",
        "value": 69
      }{
        "date": "2012-10-24",
        "value": 67
      }{
        "date": "2012-10-25",
        "value": 72
      }{
        "date": "2012-10-26",
        "value": 77
      }{
        "date": "2012-10-27",
        "value": 75
      }{
        "date": "2012-10-28",
        "value": 70
      }{
        "date": "2012-10-29",
        "value": 72
      }{
        "date": "2012-10-30",
        "value": 70
      }{
        "date": "2012-10-31",
        "value": 72
      }{
        "date": "2012-11-01",
        "value": 73
      }{
        "date": "2012-11-02",
        "value": 67
      }{
        "date": "2012-11-03",
        "value": 68
      }{
        "date": "2012-11-04",
        "value": 65
      }{
        "date": "2012-11-05",
        "value": 71
      }{
        "date": "2012-11-06",
        "value": 75
      }{
        "date": "2012-11-07",
        "value": 74
      }{
        "date": "2012-11-08",
        "value": 71
      }{
        "date": "2012-11-09",
        "value": 76
      }{
        "date": "2012-11-10",
        "value": 77
      }{
        "date": "2012-11-11",
        "value": 81
      }{
        "date": "2012-11-12",
        "value": 83
      }{
        "date": "2012-11-13",
        "value": 80
      }{
        "date": "2012-11-14",
        "value": 81
      }{
        "date": "2012-11-15",
        "value": 87
      }{
        "date": "2012-11-16",
        "value": 82
      }{
        "date": "2012-11-17",
        "value": 86
      }{
        "date": "2012-11-18",
        "value": 80
      }{
        "date": "2012-11-19",
        "value": 87
      }{
        "date": "2012-11-20",
        "value": 83
      }{
        "date": "2012-11-21",
        "value": 85
      }{
        "date": "2012-11-22",
        "value": 84
      }{
        "date": "2012-11-23",
        "value": 82
      }{
        "date": "2012-11-24",
        "value": 73
      }{
        "date": "2012-11-25",
        "value": 71
      }{
        "date": "2012-11-26",
        "value": 75
      }{
        "date": "2012-11-27",
        "value": 79
      }{
        "date": "2012-11-28",
        "value": 70
      }{
        "date": "2012-11-29",
        "value": 73
      }{
        "date": "2012-11-30",
        "value": 61
      }{
        "date": "2012-12-01",
        "value": 62
      }{
        "date": "2012-12-02",
        "value": 66
      }{
        "date": "2012-12-03",
        "value": 65
      }{
        "date": "2012-12-04",
        "value": 73
      }{
        "date": "2012-12-05",
        "value": 79
      }{
        "date": "2012-12-06",
        "value": 78
      }{
        "date": "2012-12-07",
        "value": 78
      }{
        "date": "2012-12-08",
        "value": 78
      }{
        "date": "2012-12-09",
        "value": 74
      }{
        "date": "2012-12-10",
        "value": 73
      }{
        "date": "2012-12-11",
        "value": 75
      }{
        "date": "2012-12-12",
        "value": 70
      }{
        "date": "2012-12-13",
        "value": 77
      }{
        "date": "2012-12-14",
        "value": 67
      }{
        "date": "2012-12-15",
        "value": 62
      }{
        "date": "2012-12-16",
        "value": 64
      }{
        "date": "2012-12-17",
        "value": 61
      }{
        "date": "2012-12-18",
        "value": 59
      }{
        "date": "2012-12-19",
        "value": 53
      }{
        "date": "2012-12-20",
        "value": 54
      }{
        "date": "2012-12-21",
        "value": 56
      }{
        "date": "2012-12-22",
        "value": 59
      }{
        "date": "2012-12-23",
        "value": 58
      }{
        "date": "2012-12-24",
        "value": 55
      }{
        "date": "2012-12-25",
        "value": 52
      }{
        "date": "2012-12-26",
        "value": 54
      }{
        "date": "2012-12-27",
        "value": 50
      }{
        "date": "2012-12-28",
        "value": 50
      }{
        "date": "2012-12-29",
        "value": 51
      }{
        "date": "2012-12-30",
        "value": 52
      }{
        "date": "2012-12-31",
        "value": 58
      }{
        "date": "2013-01-01",
        "value": 60
      }{
        "date": "2013-01-02",
        "value": 67
      }{
        "date": "2013-01-03",
        "value": 64
      }{
        "date": "2013-01-04",
        "value": 66
      }{
        "date": "2013-01-05",
        "value": 60
      }{
        "date": "2013-01-06",
        "value": 63
      }{
        "date": "2013-01-07",
        "value": 61
      }{
        "date": "2013-01-08",
        "value": 60
      }{
        "date": "2013-01-09",
        "value": 65
      }{
        "date": "2013-01-10",
        "value": 75
      }{
        "date": "2013-01-11",
        "value": 77
      }{
        "date": "2013-01-12",
        "value": 78
      }{
        "date": "2013-01-13",
        "value": 70
      }{
        "date": "2013-01-14",
        "value": 70
      }{
        "date": "2013-01-15",
        "value": 73
      }{
        "date": "2013-01-16",
        "value": 71
      }{
        "date": "2013-01-17",
        "value": 74
      }{
        "date": "2013-01-18",
        "value": 78
      }{
        "date": "2013-01-19",
        "value": 85
      }{
        "date": "2013-01-20",
        "value": 82
      }{
        "date": "2013-01-21",
        "value": 83
      }{
        "date": "2013-01-22",
        "value": 88
      }{
        "date": "2013-01-23",
        "value": 85
      }{
        "date": "2013-01-24",
        "value": 85
      }{
        "date": "2013-01-25",
        "value": 80
      }{
        "date": "2013-01-26",
        "value": 87
      }{
        "date": "2013-01-27",
        "value": 84
      }{
        "date": "2013-01-28",
        "value": 83
      }{
        "date": "2013-01-29",
        "value": 84
      }{
        "date": "2013-01-30",
        "value": 81
      }];

      // Set input format for the dates
      chart.dateFormatter.inputDateFormat = "yyyy-MM-dd";

      // Create axes
      var dateAxis = chart.xAxes.push(new am4charts.DateAxis());
      var valueAxis = chart.yAxes.push(new am4charts.ValueAxis());

      // Create series
      var series = chart.series.push(new am4charts.LineSeries());
      series.dataFields.valueY = "value";
      series.dataFields.dateX = "date";
      series.tooltipText = "{value}"
      series.strokeWidth = 2;
      series.minBulletDistance = 15;

      // Drop-shaped tooltips
      series.tooltip.background.cornerRadius = 20;
      series.tooltip.background.strokeOpacity = 0;
      series.tooltip.pointerOrientation = "vertical";
      series.tooltip.label.minWidth = 40;
      series.tooltip.label.minHeight = 40;
      series.tooltip.label.textAlign = "middle";
      series.tooltip.label.textValign = "middle";

      // Make bullets grow on hover
      var bullet = series.bullets.push(new am4charts.CircleBullet());
      bullet.circle.strokeWidth = 2;
      bullet.circle.radius = 4;
      bullet.circle.fill = am4core.color("#fff");

      var bullethover = bullet.states.create("hover");
      bullethover.properties.scale = 1.3;

      // Make a panning cursor
      chart.cursor = new am4charts.XYCursor();
      chart.cursor.behavior = "panXY";
      chart.cursor.xAxis = dateAxis;
      chart.cursor.snapToSeries = series;

      // Create vertical scrollbar and place it before the value axis
      chart.scrollbarY = new am4core.Scrollbar();
      chart.scrollbarY.parent = chart.leftAxesContainer;
      chart.scrollbarY.toBack();

      // Create a horizontal scrollbar with previe and place it underneath the date axis
      chart.scrollbarX = new am4charts.XYChartScrollbar();
      chart.scrollbarX.series.push(series);
      chart.scrollbarX.parent = chart.bottomAxesContainer;

      dateAxis.start = 0.79;
      dateAxis.keepSelection = true;

        const body = document.querySelector('body')
        if (body.classList.contains('dark')) {
          amChartUpdate(chart{
            dark: true
          })
        }

        document.addEventListener('ChangeColorMode'function (e) {
          amChartUpdate(charte.detail)
        })


      }); // end am4core.ready()
   }
   if(jQuery('#am-linescrollzomm-chart').length){
      am4core.ready(function() {

      // Themes begin
      am4core.useTheme(am4themes_animated);
      // Themes end

      // Create chart instance
      var chart = am4core.create("am-linescrollzomm-chart"am4charts.XYChart);
      chart.colors.list = [am4core.color("#57aeff")];

      // Add data
      chart.data = generateChartData();

      // Create axes
      var dateAxis = chart.xAxes.push(new am4charts.DateAxis());
      dateAxis.renderer.minGridDistance = 50;

      var valueAxis = chart.yAxes.push(new am4charts.ValueAxis());

      // Create series
      var series = chart.series.push(new am4charts.LineSeries());
      series.dataFields.valueY = "visits";
      series.dataFields.dateX = "date";
      series.strokeWidth = 2;
      series.minBulletDistance = 10;
      series.tooltipText = "{valueY}";
      series.tooltip.pointerOrientation = "vertical";
      series.tooltip.background.cornerRadius = 20;
      series.tooltip.background.fillOpacity = 0.5;
      series.tooltip.label.padding(12,12,12,12)

      // Add scrollbar
      chart.scrollbarX = new am4charts.XYChartScrollbar();
      chart.scrollbarX.series.push(series);

      // Add cursor
      chart.cursor = new am4charts.XYCursor();
      chart.cursor.xAxis = dateAxis;
      chart.cursor.snapToSeries = series;

      function generateChartData() {
          var chartData = [];
          var firstDate = new Date();
          firstDate.setDate(firstDate.getDate() - 1000);
          var visits = 1200;
          for (var i = 0; i < 500; i++) {
              var newDate = new Date(firstDate);
              newDate.setDate(newDate.getDate() + i);

              visits += Math.round((Math.random()<0.5?1:-1)*Math.random()*10);

              chartData.push({
                  date: newDate,
                  visits: visits
              });
          }
          return chartData;
      }

        const body = document.querySelector('body')
        if (body.classList.contains('dark')) {
          amChartUpdate(chart{
            dark: true
          })
        }

        document.addEventListener('ChangeColorMode'function (e) {
          amChartUpdate(charte.detail)
        })

      });
   }

   if(jQuery('#am-zoomable-chart').length){
      am4core.ready(function() {

      // Themes begin
      am4core.useTheme(am4themes_animated);
      // Themes end

      // Create chart instance
      var chart = am4core.create("am-zoomable-chart"am4charts.XYChart);
      chart.colors.list = [am4core.color("#57aeff")];

      // Add data
      chart.data = [ {
        "date": "2012-07-27",
        "value": 13
      }{
        "date": "2012-07-28",
        "value": 11
      }{
        "date": "2012-07-29",
        "value": 15
      }{
        "date": "2012-07-30",
        "value": 16
      }{
        "date": "2012-07-31",
        "value": 18
      }{
        "date": "2012-08-01",
        "value": 13
      }{
        "date": "2012-08-02",
        "value": 22
      }{
        "date": "2012-08-03",
        "value": 23
      }{
        "date": "2012-08-04",
        "value": 20
      }{
        "date": "2012-08-05",
        "value": 17
      }{
        "date": "2012-08-06",
        "value": 16
      }{
        "date": "2012-08-07",
        "value": 18
      }{
        "date": "2012-08-08",
        "value": 21
      }{
        "date": "2012-08-09",
        "value": 26
      }{
        "date": "2012-08-10",
        "value": 24
      }{
        "date": "2012-08-11",
        "value": 29
      }{
        "date": "2012-08-12",
        "value": 32
      }{
        "date": "2012-08-13",
        "value": 18
      }{
        "date": "2012-08-14",
        "value": 24
      }{
        "date": "2012-08-15",
        "value": 22
      }{
        "date": "2012-08-16",
        "value": 18
      }{
        "date": "2012-08-17",
        "value": 19
      }{
        "date": "2012-08-18",
        "value": 14
      }{
        "date": "2012-08-19",
        "value": 15
      }{
        "date": "2012-08-20",
        "value": 12
      }{
        "date": "2012-08-21",
        "value": 8
      }{
        "date": "2012-08-22",
        "value": 9
      }{
        "date": "2012-08-23",
        "value": 8
      }{
        "date": "2012-08-24",
        "value": 7
      }{
        "date": "2012-08-25",
        "value": 5
      }{
        "date": "2012-08-26",
        "value": 11
      }{
        "date": "2012-08-27",
        "value": 13
      }{
        "date": "2012-08-28",
        "value": 18
      }{
        "date": "2012-08-29",
        "value": 20
      }{
        "date": "2012-08-30",
        "value": 29
      }{
        "date": "2012-08-31",
        "value": 33
      }{
        "date": "2012-09-01",
        "value": 42
      }{
        "date": "2012-09-02",
        "value": 35
      }{
        "date": "2012-09-03",
        "value": 31
      }{
        "date": "2012-09-04",
        "value": 47
      }{
        "date": "2012-09-05",
        "value": 52
      }{
        "date": "2012-09-06",
        "value": 46
      }{
        "date": "2012-09-07",
        "value": 41
      }{
        "date": "2012-09-08",
        "value": 43
      }{
        "date": "2012-09-09",
        "value": 40
      }{
        "date": "2012-09-10",
        "value": 39
      }{
        "date": "2012-09-11",
        "value": 34
      }{
        "date": "2012-09-12",
        "value": 29
      }{
        "date": "2012-09-13",
        "value": 34
      }{
        "date": "2012-09-14",
        "value": 37
      }{
        "date": "2012-09-15",
        "value": 42
      }{
        "date": "2012-09-16",
        "value": 49
      }{
        "date": "2012-09-17",
        "value": 46
      }{
        "date": "2012-09-18",
        "value": 47
      }{
        "date": "2012-09-19",
        "value": 55
      }{
        "date": "2012-09-20",
        "value": 59
      }{
        "date": "2012-09-21",
        "value": 58
      }{
        "date": "2012-09-22",
        "value": 57
      }{
        "date": "2012-09-23",
        "value": 61
      }{
        "date": "2012-09-24",
        "value": 59
      }{
        "date": "2012-09-25",
        "value": 67
      }{
        "date": "2012-09-26",
        "value": 65
      }{
        "date": "2012-09-27",
        "value": 61
      }{
        "date": "2012-09-28",
        "value": 66
      }{
        "date": "2012-09-29",
        "value": 69
      }{
        "date": "2012-09-30",
        "value": 71
      }{
        "date": "2012-10-01",
        "value": 67
      }{
        "date": "2012-10-02",
        "value": 63
      }{
        "date": "2012-10-03",
        "value": 46
      }{
        "date": "2012-10-04",
        "value": 32
      }{
        "date": "2012-10-05",
        "value": 21
      }{
        "date": "2012-10-06",
        "value": 18
      }{
        "date": "2012-10-07",
        "value": 21
      }{
        "date": "2012-10-08",
        "value": 28
      }{
        "date": "2012-10-09",
        "value": 27
      }{
        "date": "2012-10-10",
        "value": 36
      }{
        "date": "2012-10-11",
        "value": 33
      }{
        "date": "2012-10-12",
        "value": 31
      }{
        "date": "2012-10-13",
        "value": 30
      }{
        "date": "2012-10-14",
        "value": 34
      }{
        "date": "2012-10-15",
        "value": 38
      }{
        "date": "2012-10-16",
        "value": 37
      }{
        "date": "2012-10-17",
        "value": 44
      }{
        "date": "2012-10-18",
        "value": 49
      }{
        "date": "2012-10-19",
        "value": 53
      }{
        "date": "2012-10-20",
        "value": 57
      }{
        "date": "2012-10-21",
        "value": 60
      }{
        "date": "2012-10-22",
        "value": 61
      }{
        "date": "2012-10-23",
        "value": 69
      }{
        "date": "2012-10-24",
        "value": 67
      }{
        "date": "2012-10-25",
        "value": 72
      }{
        "date": "2012-10-26",
        "value": 77
      }{
        "date": "2012-10-27",
        "value": 75
      }{
        "date": "2012-10-28",
        "value": 70
      }{
        "date": "2012-10-29",
        "value": 72
      }{
        "date": "2012-10-30",
        "value": 70
      }{
        "date": "2012-10-31",
        "value": 72
      }{
        "date": "2012-11-01",
        "value": 73
      }{
        "date": "2012-11-02",
        "value": 67
      }{
        "date": "2012-11-03",
        "value": 68
      }{
        "date": "2012-11-04",
        "value": 65
      }{
        "date": "2012-11-05",
        "value": 71
      }{
        "date": "2012-11-06",
        "value": 75
      }{
        "date": "2012-11-07",
        "value": 74
      }{
        "date": "2012-11-08",
        "value": 71
      }{
        "date": "2012-11-09",
        "value": 76
      }{
        "date": "2012-11-10",
        "value": 77
      }{
        "date": "2012-11-11",
        "value": 81
      }{
        "date": "2012-11-12",
        "value": 83
      }{
        "date": "2012-11-13",
        "value": 80
      }{
        "date": "2012-11-18",
        "value": 80
      }{
        "date": "2012-11-19",
        "value": 87
      }{
        "date": "2012-11-20",
        "value": 83
      }{
        "date": "2012-11-21",
        "value": 85
      }{
        "date": "2012-11-22",
        "value": 84
      }{
        "date": "2012-11-23",
        "value": 82
      }{
        "date": "2012-11-24",
        "value": 73
      }{
        "date": "2012-11-25",
        "value": 71
      }{
        "date": "2012-11-26",
        "value": 75
      }{
        "date": "2012-11-27",
        "value": 79
      }{
        "date": "2012-11-28",
        "value": 70
      }{
        "date": "2012-11-29",
        "value": 73
      }{
        "date": "2012-11-30",
        "value": 61
      }{
        "date": "2012-12-01",
        "value": 62
      }{
        "date": "2012-12-02",
        "value": 66
      }{
        "date": "2012-12-03",
        "value": 65
      }{
        "date": "2012-12-04",
        "value": 73
      }{
        "date": "2012-12-05",
        "value": 79
      }{
        "date": "2012-12-06",
        "value": 78
      }{
        "date": "2012-12-07",
        "value": 78
      }{
        "date": "2012-12-08",
        "value": 78
      }{
        "date": "2012-12-09",
        "value": 74
      }{
        "date": "2012-12-10",
        "value": 73
      }{
        "date": "2012-12-11",
        "value": 75
      }{
        "date": "2012-12-12",
        "value": 70
      }{
        "date": "2012-12-13",
        "value": 77
      }{
        "date": "2012-12-14",
        "value": 67
      }{
        "date": "2012-12-15",
        "value": 62
      }{
        "date": "2012-12-16",
        "value": 64
      }{
        "date": "2012-12-17",
        "value": 61
      }{
        "date": "2012-12-18",
        "value": 59
      }{
        "date": "2012-12-19",
        "value": 53
      }{
        "date": "2012-12-20",
        "value": 54
      }{
        "date": "2012-12-21",
        "value": 56
      }{
        "date": "2012-12-22",
        "value": 59
      }{
        "date": "2012-12-23",
        "value": 58
      }{
        "date": "2012-12-24",
        "value": 55
      }{
        "date": "2012-12-25",
        "value": 52
      }{
        "date": "2012-12-26",
        "value": 54
      }{
        "date": "2012-12-27",
        "value": 50
      }{
        "date": "2012-12-28",
        "value": 50
      }{
        "date": "2012-12-29",
        "value": 51
      }{
        "date": "2012-12-30",
        "value": 52
      }{
        "date": "2012-12-31",
        "value": 58
      }{
        "date": "2013-01-01",
        "value": 60
      }{
        "date": "2013-01-02",
        "value": 67
      }{
        "date": "2013-01-03",
        "value": 64
      }{
        "date": "2013-01-04",
        "value": 66
      }{
        "date": "2013-01-05",
        "value": 60
      }{
        "date": "2013-01-06",
        "value": 63
      }{
        "date": "2013-01-07",
        "value": 61
      }{
        "date": "2013-01-08",
        "value": 60
      }{
        "date": "2013-01-09",
        "value": 65
      }{
        "date": "2013-01-10",
        "value": 75
      }{
        "date": "2013-01-11",
        "value": 77
      }{
        "date": "2013-01-12",
        "value": 78
      }{
        "date": "2013-01-13",
        "value": 70
      }{
        "date": "2013-01-14",
        "value": 70
      }{
        "date": "2013-01-15",
        "value": 73
      }{
        "date": "2013-01-16",
        "value": 71
      }{
        "date": "2013-01-17",
        "value": 74
      }{
        "date": "2013-01-18",
        "value": 78
      }{
        "date": "2013-01-19",
        "value": 85
      }{
        "date": "2013-01-20",
        "value": 82
      }{
        "date": "2013-01-21",
        "value": 83
      }{
        "date": "2013-01-22",
        "value": 88
      }{
        "date": "2013-01-23",
        "value": 85
      }{
        "date": "2013-01-24",
        "value": 85
      }{
        "date": "2013-01-25",
        "value": 80
      }{
        "date": "2013-01-26",
        "value": 87
      }{
        "date": "2013-01-27",
        "value": 84
      }{
        "date": "2013-01-28",
        "value": 83
      }{
        "date": "2013-01-29",
        "value": 84
      }{
        "date": "2013-01-30",
        "value": 81
      } ];

      // Create axes
      var dateAxis = chart.xAxes.push(new am4charts.DateAxis());
      dateAxis.renderer.grid.template.location = 0;
      dateAxis.renderer.minGridDistance = 50;

      var valueAxis = chart.yAxes.push(new am4charts.ValueAxis());

      // Create series
      var series = chart.series.push(new am4charts.LineSeries());
      series.dataFields.valueY = "value";
      series.dataFields.dateX = "date";
      series.strokeWidth = 3;
      series.fillOpacity = 0.5;

      // Add vertical scrollbar
      chart.scrollbarY = new am4core.Scrollbar();
      chart.scrollbarY.marginLeft = 0;

      // Add cursor
      chart.cursor = new am4charts.XYCursor();
      chart.cursor.behavior = "zoomY";
      chart.cursor.lineX.disabled = true;

        const body = document.querySelector('body')
        if (body.classList.contains('dark')) {
          amChartUpdate(chart{
            dark: true
          })
        }

        document.addEventListener('ChangeColorMode'function (e) {
          amChartUpdate(charte.detail)
        })

      }); // end am4core.ready()
   }
   if(jQuery('#am-radar-chart').length){
      am4core.ready(function() {

      // Themes begin
      am4core.useTheme(am4themes_animated);
      // Themes end

      /* Create chart instance */
      var chart = am4core.create("am-radar-chart"am4charts.RadarChart);
      chart.colors.list = [am4core.color("#4788ff")];

      /* Add data */
      chart.data = [{
        "country": "Lithuania",
        "litres": 501
      }{
        "country": "Czechia",
        "litres": 301
      }{
        "country": "Ireland",
        "litres": 266
      }{
        "country": "Germany",
        "litres": 165
      }{
        "country": "Australia",
        "litres": 139
      }{
        "country": "Austria",
        "litres": 336
      }{
        "country": "UK",
        "litres": 290
      }{
        "country": "Belgium",
        "litres": 325
      }{
        "country": "The Netherlands",
        "litres": 40
      }];

      /* Create axes */
      var categoryAxis = chart.xAxes.push(new am4charts.CategoryAxis());
      categoryAxis.dataFields.category = "country";

      var valueAxis = chart.yAxes.push(new am4charts.ValueAxis());
      valueAxis.renderer.axisFills.template.fill = chart.colors.getIndex(2);
      valueAxis.renderer.axisFills.template.fillOpacity = 0.05;

      /* Create and configure series */
      var series = chart.series.push(new am4charts.RadarSeries());
      series.dataFields.valueY = "litres";
      series.dataFields.categoryX = "country";
      series.name = "Sales";
      series.strokeWidth = 3;

        const body = document.querySelector('body')
        if (body.classList.contains('dark')) {
          amChartUpdate(chart{
            dark: true
          })
        }

        document.addEventListener('ChangeColorMode'function (e) {
          amChartUpdate(charte.detail)
        })

      }); // end am4core.ready()
   }
   if(jQuery('#am-polar-chart').length){
      am4core.ready(function() {

      // Themes begin
      am4core.useTheme(am4themes_animated);
      // Themes end

      /* Create chart instance */
      var chart = am4core.create("am-polar-chart"am4charts.RadarChart);

      /* Add data */
      chart.data = [ {
        "direction": "N",
        "value": 8
      }{
        "direction": "NE",
        "value": 9
      }{
        "direction": "E",
        "value": 4.5
      }{
        "direction": "SE",
        "value": 3.5
      }{
        "direction": "S",
        "value": 9.2
      }{
        "direction": "SW",
        "value": 8.4
      }{
        "direction": "W",
        "value": 11.1
      }{
        "direction": "NW",
        "value": 10
      } ];

      /* Create axes */
      var categoryAxis = chart.xAxes.push(new am4charts.CategoryAxis());
      categoryAxis.dataFields.category = "direction";

      var valueAxis = chart.yAxes.push(new am4charts.ValueAxis());
      //valueAxis.renderer.gridType = "polygons";

      var range = categoryAxis.axisRanges.create();
      range.category = "NW";
      range.endCategory = "NW";
      range.axisFill.fill = am4core.color("#4788ff");
      range.axisFill.fillOpacity = 0.3;

      var range2 = categoryAxis.axisRanges.create();
      range2.category = "N";
      range2.endCategory = "N";
      range2.axisFill.fill = am4core.color("#ff4b4b");
      range2.axisFill.fillOpacity = 0.3;

      var range3 = categoryAxis.axisRanges.create();
      range3.category = "SE";
      range3.endCategory = "SW";
      range3.axisFill.fill = am4core.color("#37e6b0");
      range3.axisFill.fillOpacity = 0.3;
      range3.locations.endCategory = 0;

      /* Create and configure series */
      var series = chart.series.push(new am4charts.RadarSeries());
      series.dataFields.valueY = "value";
      series.dataFields.categoryX = "direction";
      series.name = "Wind direction";
      series.strokeWidth = 3;
      series.fillOpacity = 0.2;

        const body = document.querySelector('body')
        if (body.classList.contains('dark')) {
          amChartUpdate(chart{
            dark: true
          })
        }

        document.addEventListener('ChangeColorMode'function (e) {
          amChartUpdate(charte.detail)
        })

      }); // end am4core.ready()
   }

   if(jQuery('#am-polarscatter-chart').length){
      am4core.ready(function() {

      // Themes begin
      am4core.useTheme(am4themes_animated);
      // Themes end

      /* Create chart instance */
      var chart = am4core.create("am-polarscatter-chart"am4charts.RadarChart);
       chart.colors.list = [am4core.color("#4788ff"),am4core.color("#fe721c"),am4core.color("#37e6b0")];

      /* Add data */
      chart.data = [{
        "country": "Lithuania",
        "litres": 501,
        "units": 250
      }{
        "country": "Czech Republic",
        "litres": 301,
        "units": 222
      }{
        "country": "Ireland",
        "litres": 266,
        "units": 179
      }{
        "country": "Germany",
        "litres": 165,
        "units": 298
      }{
        "country": "Australia",
        "litres": 139,
        "units": 299
      }];

      /* Create axes */
      var xAxis = chart.xAxes.push(new am4charts.ValueAxis());
      xAxis.renderer.maxLabelPosition = 0.99;

      var yAxis = chart.yAxes.push(new am4charts.ValueAxis());
      yAxis.renderer.labels.template.verticalCenter = "bottom";
      yAxis.renderer.labels.template.horizontalCenter = "right";
      yAxis.renderer.maxLabelPosition = 0.99;
      yAxis.renderer.labels.template.paddingBottom = 1;
      yAxis.renderer.labels.template.paddingRight = 3;

      /* Create and configure series */
      var series1 = chart.series.push(new am4charts.RadarSeries());
      series1.bullets.push(new am4charts.CircleBullet());
      series1.strokeOpacity = 0;
      series1.dataFields.valueX = "x";
      series1.dataFields.valueY = "y";
      series1.name = "Series #1";
      series1.sequencedInterpolation = true;
      series1.sequencedInterpolationDelay = 10;
      series1.data = [
        { "x": 83"y": 5.1 },
        { "x": 44"y": 5.8 },
        { "x": 76"y": 9 },
        { "x": 2"y": 1.4 },
        { "x": 100"y": 8.3 },
        { "x": 96"y": 1.7 },
        { "x": 68"y": 3.9 },
        { "x": 0"y": 3 },
        { "x": 100"y": 4.1 },
        { "x": 16"y": 5.5 },
        { "x": 71"y": 6.8 },
        { "x": 100"y": 7.9 },
        { "x": 35"y": 8 },
        { "x": 44"y": 6 },
        { "x": 64"y": 0.7 },
        { "x": 53"y": 3.3 },
        { "x": 92"y": 4.1 },
        { "x": 43"y": 7.3 },
        { "x": 15"y": 7.5 },
        { "x": 43"y": 4.3 },
        { "x": 90"y": 9.9 }
      ];

      var series2 = chart.series.push(new am4charts.RadarSeries());
      series2.bullets.push(new am4charts.CircleBullet());
      series2.strokeOpacity = 0;
      series2.dataFields.valueX = "x";
      series2.dataFields.valueY = "y";
      series2.name = "Series #2";
      series2.sequencedInterpolation = true;
      series2.sequencedInterpolationDelay = 10;
      series2.data = [
        { "x": 178"y": 1.3 },
        { "x": 129"y": 3.4 },
        { "x": 99"y": 2.4 },
        { "x": 80"y": 9.9 },
        { "x": 118"y": 9.4 },
        { "x": 103"y": 8.7 },
        { "x": 91"y": 4.2 },
        { "x": 151"y": 1.2 },
        { "x": 168"y": 5.2 },
        { "x": 168"y": 1.6 },
        { "x": 152"y": 1.2 },
        { "x": 138"y": 7.7 },
        { "x": 107"y": 3.9 },
        { "x": 124"y": 0.7 },
        { "x": 130"y": 2.6 },
        { "x": 86"y": 9.2 },
        { "x": 169"y": 7.5 },
        { "x": 122"y": 9.9 },
        { "x": 100"y": 3.8 },
        { "x": 172"y": 4.1 },
        { "x": 140"y": 7.3 },
        { "x": 161"y": 2.3 },
        { "x": 141"y": 0.9 }
      ];

      var series3 = chart.series.push(new am4charts.RadarSeries());
      series3.bullets.push(new am4charts.CircleBullet());
      series3.strokeOpacity = 0;
      series3.dataFields.valueX = "x";
      series3.dataFields.valueY = "y";
      series3.name = "Series #3";
      series3.sequencedInterpolation = true;
      series3.sequencedInterpolationDelay = 10;
      series3.data = [
        { "x": 419"y": 4.9 },
        { "x": 417"y": 5.5 },
        { "x": 434"y": 0.1 },
        { "x": 344"y": 2.5 },
        { "x": 279"y": 7.5 },
        { "x": 307"y": 8.4 },
        { "x": 279"y": 9 },
        { "x": 220"y": 8.4 },
        { "x": 201"y": 9.7 },
        { "x": 288"y": 1.2 },
        { "x": 333"y": 7.4 },
        { "x": 308"y": 1.9 },
        { "x": 330"y": 8 },
        { "x": 408"y": 1.7 },
        { "x": 274"y": 0.8 },
        { "x": 296"y": 3.1 },
        { "x": 279"y": 4.3 },
        { "x": 379"y": 5.6 },
        { "x": 175"y": 6.8 }
      ];

      /* Add legend */
      chart.legend = new am4charts.Legend();

      /* Add cursor */
      chart.cursor = new am4charts.RadarCursor();

        const body = document.querySelector('body')
        if (body.classList.contains('dark')) {
          amChartUpdate(chart{
            dark: true
          })
        }

        document.addEventListener('ChangeColorMode'function (e) {
          amChartUpdate(charte.detail)
        })

      }); // end am4core.ready()
   }
   if(jQuery('#am-3dpie-chart').length){
      am4core.ready(function() {

      // Themes begin
      am4core.useTheme(am4themes_animated);
      // Themes end

      var chart = am4core.create("am-3dpie-chart"am4charts.PieChart3D);
      chart.hiddenState.properties.opacity = 0; // this creates initial fade-in

      chart.legend = new am4charts.Legend();

      chart.data = [
        {
          country: "Lithuania",
          litres: 501.9,
          fill: "red"
        },
        {
          country: "Germany",
          litres: 165.8
        },
        {
          country: "Australia",
          litres: 139.9
        },
        {
          country: "Austria",
          litres: 128.3
        },
        {
          country: "UK",
          litres: 99
        },
        {
          country: "Belgium",
          litres: 60
        }
      ];

      var series = chart.series.push(new am4charts.PieSeries3D());
      series.colors.list = [am4core.color("#4788ff"),am4core.color("#37e6b0"),am4core.color("#ff4b4b"),
      am4core.color("#fe721c"),am4core.color("#876cfe"),am4core.color("#01041b")];
      series.dataFields.value = "litres";
      series.dataFields.category = "country";

        const body = document.querySelector('body')
        if (body.classList.contains('dark')) {
          amChartUpdate(chart{
            dark: true
          })
        }

        document.addEventListener('ChangeColorMode'function (e) {
          amChartUpdate(charte.detail)
        })

      }); // end am4core.ready()
   }

   if(jQuery('#am-layeredcolumn-chart').length){
      am4core.ready(function() {

      // Themes begin
      am4core.useTheme(am4themes_animated);
      // Themes end

      // Create chart instance
      var chart = am4core.create("am-layeredcolumn-chart"am4charts.XYChart);
      chart.colors.list = [am4core.color("#37e6b0"),am4core.color("#4788ff")];

      // Add percent sign to all numbers
      chart.numberFormatter.numberFormat = "#.#'%'";

      // Add data
      chart.data = [{
          "country": "USA",
          "year2004": 3.5,
          "year2005": 4.2
      }{
          "country": "UK",
          "year2004": 1.7,
          "year2005": 3.1
      }{
          "country": "Canada",
          "year2004": 2.8,
          "year2005": 2.9
      }{
          "country": "Japan",
          "year2004": 2.6,
          "year2005": 2.3
      }{
          "country": "France",
          "year2004": 1.4,
          "year2005": 2.1
      }{
          "country": "Brazil",
          "year2004": 2.6,
          "year2005": 4.9
      }];

      // Create axes
      var categoryAxis = chart.xAxes.push(new am4charts.CategoryAxis());
      categoryAxis.dataFields.category = "country";
      categoryAxis.renderer.grid.template.location = 0;
      categoryAxis.renderer.minGridDistance = 30;

      var valueAxis = chart.yAxes.push(new am4charts.ValueAxis());
      valueAxis.title.text = "GDP growth rate";
      valueAxis.title.fontWeight = 800;

      // Create series
      var series = chart.series.push(new am4charts.ColumnSeries());
      series.dataFields.valueY = "year2004";
      series.dataFields.categoryX = "country";
      series.clustered = false;
      series.tooltipText = "GDP grow in {categoryX} (2004): [bold]{valueY}[/]";

      var series2 = chart.series.push(new am4charts.ColumnSeries());
      series2.dataFields.valueY = "year2005";
      series2.dataFields.categoryX = "country";
      series2.clustered = false;
      series2.columns.template.width = am4core.percent(50);
      series2.tooltipText = "GDP grow in {categoryX} (2005): [bold]{valueY}[/]";

      chart.cursor = new am4charts.XYCursor();
      chart.cursor.lineX.disabled = true;
      chart.cursor.lineY.disabled = true;

        const body = document.querySelector('body')
        if (body.classList.contains('dark')) {
          amChartUpdate(chart{
            dark: true
          })
        }

        document.addEventListener('ChangeColorMode'function (e) {
          amChartUpdate(charte.detail)
        })

      }); // end am4core.ready()
   }

/*---------------------------------------------------------------------
   Morris Charts
-----------------------------------------------------------------------*/

if(jQuery('#morris-line-chart').length){
  new Morris.Line({
    // ID of the element in which to draw the chart.
    element: 'morris-line-chart',
    // Chart data records -- each entry in this array corresponds to a point on
    // the chart.
    data: [
      { year: '2008'value: 20 },
      { year: '2009'value: 10 },
      { year: '2010'value: 5 },
      { year: '2011'value: 5 },
      { year: '2012'value: 20 }
    ],
    // The name of the data record attribute that contains x-values.
    xkey: 'year',
    // A list of names of data record attributes that contain y-values.
    ykeys: ['value'],
    // Labels for the ykeys -- will be displayed when you hover over the
    // chart.
    labels: ['Value'],
    lineColors: ['#4788ff']
  });
}

if(jQuery('#morris-bar-chart').length){
 Morris.Bar({
element: 'morris-bar-chart',
data: [
  {x: '2011 Q1'y: 3z: 2a: 3},
  {x: '2011 Q2'y: 2z: nulla: 1},
  {x: '2011 Q3'y: 0z: 2a: 4},
  {x: '2011 Q4'y: 2z: 4a: 3}
],
xkey: 'x',
ykeys: ['y''z''a'],
labels: ['Y''Z''A'],
barColors: ['#4788ff''#fe721c''#37e6b0'],
hoverCallback: function (indexoptionscontentrow) {
                    return '';
                  }
}).on('click'function(irow){
console.log(irow);
});
}

if(jQuery('#morris-area-chart').length){
  var area = new Morris.Area({
    element: 'morris-area-chart',
    resize: true,
    data: [
      {y: '2011 Q1'item1: 2666item2: 2666},
      {y: '2011 Q2'item1: 2778item2: 2294},
      {y: '2011 Q3'item1: 4912item2: 1969},
      {y: '2011 Q4'item1: 3767item2: 3597},
      {y: '2012 Q1'item1: 6810item2: 1914},
      {y: '2012 Q2'item1: 5670item2: 4293},
      {y: '2012 Q3'item1: 4820item2: 3795},
      {y: '2012 Q4'item1: 15073item2: 5967},
      {y: '2013 Q1'item1: 10687item2: 4460},
      {y: '2013 Q2'item1: 8432item2: 5713}
    ],
    xkey: 'y',
    ykeys: ['item1''item2'],
    labels: ['Item 1''Item 2'],
    lineColors: ['#75e275''#75bcff'],
    hoverCallback: function (indexoptionscontentrow) {
                    return '';
                  }
  });
}

if(jQuery('#morris-donut-chart').length){
  var donut = new Morris.Donut({
    element: 'morris-donut-chart',
    resize: true,
    colors: ["#4788ff""#ff4b4b""#37e6b0"],
    data: [
      {label: "Download Sales"value: 12},
      {label: "In-Store Sales"value: 30},
      {label: "Mail-Order Sales"value: 20}
    ],
    hideHover: 'auto'
  });
}

/*---------------------------------------------------------------------
   High Charts
-----------------------------------------------------------------------*/
if (jQuery("#high-basicline-chart").length && Highcharts.chart("high-basicline-chart"{
    chart: {
      type: "spline",
      inverted: !0
    },
    title: {
      text: "Atmosphere Temperature by Altitude"
    },
    subtitle: {
      text: "According to the Standard Atmosphere Model"
    },
    xAxis: {
      reversed: !1,
      title: {
        enabled: !0,
        text: "Altitude"
      },
      labels: {
        format: "{value} km"
      },
      maxPadding: .05,
      showLastLabel: !0
    },
    yAxis: {
      title: {
        text: "Temperature"
      },
      labels: {
        format: "{value}°"
      },
      lineWidth: 2
    },
    legend: {
      enabled: !1
    },
    tooltip: {
      headerFormat: "<b>{series.name}</b><br/>",
      pointFormat: "{point.x} km: {point.y}°C"
    },
    plotOptions: {
      spline: {
        marker: {
          enable: !1
        }
      }
    },
    series: [{
      name: "Temperature",
      color: "#4788ff",
      data: [
        [015],
        [10-50],
        [20-56.5],
        [30-46.5],
        [40-22.1],
        [50-2.5],
        [60-27.7],
        [70-55.7],
        [80-76.5]
      ]
    }]
  })jQuery("#high-area-chart").length && Highcharts.chart("high-area-chart"{
    chart: {
      type: "areaspline"
    },
    title: {
      text: "Average fruit consumption during one week"
    },
    legend: {
      layout: "vertical",
      align: "left",
      verticalAlign: "top",
      x: 150,
      y: 100,
      floating: !0,
      borderWidth: 1,
      backgroundColor: Highcharts.defaultOptions.legend.backgroundColor || "#FFFFFF"
    },
    xAxis: {
      categories: ["Monday""Tuesday""Wednesday""Thursday""Friday""Saturday""Sunday"],
      plotBands: [{
        from: 4.5,
        to: 6.5,
        color: "rgba(68170213.2)"
      }]
    },
    yAxis: {
      title: {
        text: "Fruit units"
      }
    },
    tooltip: {
      shared: !0,
      valueSuffix: " units"
    },
    credits: {
      enabled: !1
    },
    plotOptions: {
      areaspline: {
        fillOpacity: .5
      }
    },
    series: [{
      name: "John",
      color: "#4788ff",
      data: [343541012]
    }{
      name: "Jane",
      color: "#37e6b0",
      data: [1343354]
    }]
  })jQuery("#high-columnndbar-chart").length && Highcharts.chart("high-columnndbar-chart"{
    chart: {
      type: "bar"
    },
    title: {
      text: "Stacked bar chart"
    },
    xAxis: {
      categories: ["Apples""Oranges""Pears""Grapes""Bananas"]
    },
    yAxis: {
      min: 0,
      title: {
        text: "Total fruit consumption"
      }
    },
    legend: {
      reversed: !0
    },
    plotOptions: {
      series: {
        stacking: "normal"
      }
    },
    series: [{
      name: "John",
      color: "#4788ff",
      data: [53472]
    }{
      name: "Jane",
      color: "#ff4b4b",
      data: [22321]
    }{
      name: "Joe",
      color: "#37e6b0",
      data: [34425]
    }]
  })jQuery("#high-pie-chart").length && Highcharts.chart("high-pie-chart"{
    chart: {
      plotBackgroundColor: null,
      plotBorderWidth: null,
      plotShadow: !1,
      type: "pie"
    },
    colorAxis: {},
    title: {
      text: "Browser market shares in January2018"
    },
    tooltip: {
      pointFormat: "{series.name}: <b>{point.percentage:.1f}%</b>"
    },
    plotOptions: {
      pie: {
        allowPointSelect: !0,
        cursor: "pointer",
        dataLabels: {
          enabled: !0,
          format: "<b>{point.name}</b>: {point.percentage:.1f} %"
        }
      }
    },
    series: [{
      name: "Brands",
      colorByPoint: !0,
      data: [{
        name: "Chrome",
        y: 61.41,
        sliced: !0,
        selected: !0,
        color: "#4788ff"
      }{
        name: "Internet Explorer",
        y: 11.84,
        color: "#ff4b4b"
      }{
        name: "Firefox",
        y: 10.85,
        color: "#65f9b3"
      }{
        name: "Edge",
        y: 4.67,
        color: "#6ce6f4"
      }{
        name: "Other",
        y: 2.61
      }]
    }]
  })jQuery("#high-scatterplot-chart").length && Highcharts.chart("high-scatterplot-chart"{
    chart: {
      type: "scatter",
      zoomType: "xy"
    },
    accessibility: {
      description: "A scatter plot compares the height and weight of 507 individuals by gender. Height in centimeters is plotted on the X-axis and weight in kilograms is plotted on the Y-axis. The chart is interactiveand each data point can be hovered over to expose the height and weight data for each individual. The scatter plot is fairly evenly divided by gender with females dominating the left-hand side of the chart and males dominating the right-hand side. The height data for females ranges from 147.2 to 182.9 centimeters with the greatest concentration between 160 and 165 centimeters. The weight data for females ranges from 42 to 105.2 kilograms with the greatest concentration at around 60 kilograms. The height data for males ranges from 157.2 to 198.1 centimeters with the greatest concentration between 175 and 180 centimeters. The weight data for males ranges from 53.9 to 116.4 kilograms with the greatest concentration at around 80 kilograms."
    },
    title: {
      text: "Height Versus Weight of 507 Individuals by Gender"
    },
    subtitle: {
      text: "Source: Heinz  2003"
    },
    xAxis: {
      title: {
        enabled: !0,
        text: "Height (cm)"
      },
      startOnTick: !0,
      endOnTick: !0,
      showLastLabel: !0
    },
    yAxis: {
      title: {
        text: "Weight (kg)"
      }
    },
    legend: {
      layout: "vertical",
      align: "left",
      verticalAlign: "top",
      x: 100,
      y: 70,
      floating: !0,
      backgroundColor: Highcharts.defaultOptions.chart.backgroundColor,
      borderWidth: 1
    },
    plotOptions: {
      scatter: {
        marker: {
          radius: 5,
          states: {
            hover: {
              enabled: !0,
              lineColor: "rgb(100,100,100)"
            }
          }
        },
        states: {
          hover: {
            marker: {
              enabled: !1
            }
          }
        },
        tooltip: {
          headerFormat: "<b>{series.name}</b><br>",
          pointFormat: "{point.x} cm{point.y} kg"
        }
      }
    },
    series: [{
      name: "Female",
      color: "rgba(2238383.5)",
      data: [
        [161.251.6],
        [167.559],
        [159.549.2],
        [15763],
        [155.853.6],
        [17059],
        [159.147.6],
        [16669.8],
        [176.266.8],
        [160.275.2],
        [172.762],
        [15549.2],
        [156.567.2],
        [16453.8],
        [160.954.4]
      ],
      color: "#4788ff"
    }{
      name: "Male",
      color: "rgba(119152191.5)",
      data: [
        [17465.6],
        [175.371.8],
        [193.580.7],
        [186.572.6],
        [187.278.8],
        [181.574.8],
        [18486.4],
        [184.578.4],
        [17562],
        [18481.6],
        [180.193],
        [175.580.9],
        [180.672.7],
        [184.468],
        [175.570.9],
        [180.383.2],
        [180.383.2]
      ],
      color: "#ff4b4b"
    }]
  })jQuery("#high-linendcolumn-chart").length && Highcharts.chart("high-linendcolumn-chart"{
    chart: {
      zoomType: "xy"
    },
    title: {
      text: "Average Monthly Temperature and Rainfall in Tokyo"
    },
    subtitle: {
      text: "Source: WorldClimate.com"
    },
    xAxis: [{
      categories: ["Jan""Feb""Mar""Apr""May""Jun""Jul""Aug""Sep""Oct""Nov""Dec"],
      crosshair: !0
    }],
    yAxis: [{
      labels: {
        format: "{value}°C",
        style: {
          color: Highcharts.getOptions().colors[1]
        }
      },
      title: {
        text: "Temperature",
        style: {
          color: Highcharts.getOptions().colors[1]
        }
      }
    }{
      title: {
        text: "Rainfall",
        style: {
          color: Highcharts.getOptions().colors[0]
        }
      },
      labels: {
        format: "{value} mm",
        style: {
          color: Highcharts.getOptions().colors[0]
        }
      },
      opposite: !0
    }],
    tooltip: {
      shared: !0
    },
    legend: {
      layout: "vertical",
      align: "left",
      x: 120,
      verticalAlign: "top",
      y: 100,
      floating: !0,
      backgroundColor: Highcharts.defaultOptions.legend.backgroundColor || "rgba(255,255,255,0.25)"
    },
    series: [{
      name: "Rainfall",
      type: "column",
      yAxis: 1,
      data: [49.971.5106.4129.2144176135.6148.5216.4194.195.654.4],
      color: "#4788ff",
      tooltip: {
        valueSuffix: " mm"
      }
    }{
      name: "Temperature",
      type: "spline",
      data: [76.99.514.518.221.525.226.523.318.313.99.6],
      color: "#ff4b4b",
      tooltip: {
        valueSuffix: "°C"
      }
    }]
  })jQuery("#high-dynamic-chart").length && Highcharts.chart("high-dynamic-chart"{
    chart: {
      type: "spline",
      animation: Highcharts.svg,
      marginRight: 10,
      events: {
        load: function() {
          var e = this.series[0];
          setInterval(function() {
            var t = (new Date).getTime(),
              a = Math.random();
            e.addPoint([ta]!0!0)
          }1e3)
        }
      }
    },
    time: {
      useUTC: !1
    },
    title: {
      text: "Live random data"
    },
    accessibility: {
      announceNewData: {
        enabled: !0,
        minAnnounceInterval: 15e3,
        announcementFormatter: function(eta) {
          return !!a && "New point added. Value: " + a.y
        }
      }
    },
    xAxis: {
      type: "datetime",
      tickPixelInterval: 150
    },
    yAxis: {
      title: {
        text: "Value"
      },
      plotLines: [{
        value: 0,
        width: 1,
        color: "#808080"
      }]
    },
    tooltip: {
      headerFormat: "<b>{series.name}</b><br/>",
      pointFormat: "{point.x:%Y-%m-%d %H:%M:%S}<br/>{point.y:.2f}"
    },
    legend: {
      enabled: !1
    },
    exporting: {
      enabled: !1
    },
    series: [{
      name: "Random data",
      color: "#4788ff",
      data: function() {
        var et = [],
          a = (new Date).getTime();
        for (e = -19; e <= 0; e += 1) t.push({
          x: a + 1e3 * e,
          y: Math.random()
        });
        return t
      }()
    }]
  })jQuery("#high-3d-chart").length) {
  var chart = new Highcharts.Chart({
    chart: {
      renderTo: "high-3d-chart",
      type: "column",
      options3d: {
        enabled: !0,
        alpha: 15,
        beta: 15,
        depth: 50,
        viewDistance: 25
      }
    },
    title: {
      text: "Chart rotation demo"
    },
    subtitle: {
      text: "Test options by dragging the sliders below"
    },
    plotOptions: {
      column: {
        depth: 25
      }
    },
    series: [{
      data: [29.971.5106.4129.2144176135.6148.5216.4194.195.654.4],
      color: "#4788ff"
    }]
  });

  function showValues() {
    $("#alpha-value").html(chart.options.chart.options3d.alpha)$("#beta-value").html(chart.options.chart.options3d.beta)$("#depth-value").html(chart.options.chart.options3d.depth)
  }
  $("#sliders input").on("input change"function() {
    chart.options.chart.options3d[this.id] = parseFloat(this.value)showValues()chart.redraw(!1)
  })showValues()
}
if (jQuery("#high-gauges-chart").length && Highcharts.chart("high-gauges-chart"{
    chart: {
      type: "gauge",
      plotBackgroundColor: null,
      plotBackgroundImage: null,
      plotBorderWidth: 0,
      plotShadow: !1
    },
    title: {
      text: "Speedometer"
    },
    pane: {
      startAngle: -150,
      endAngle: 150,
      background: [{
        backgroundColor: {
          linearGradient: {
            x1: 0,
            y1: 0,
            x2: 0,
            y2: 1
          },
          stops: [
            [0"#FFF"],
            [1"#333"]
          ]
        },
        borderWidth: 0,
        outerRadius: "109%"
      }{
        backgroundColor: {
          linearGradient: {
            x1: 0,
            y1: 0,
            x2: 0,
            y2: 1
          },
          stops: [
            [0"#333"],
            [1"#FFF"]
          ]
        },
        borderWidth: 1,
        outerRadius: "107%"
      }{}{
        backgroundColor: "#DDD",
        borderWidth: 0,
        outerRadius: "105%",
        innerRadius: "103%"
      }]
    },
    yAxis: {
      min: 0,
      max: 200,
      minorTickInterval: "auto",
      minorTickWidth: 1,
      minorTickLength: 10,
      minorTickPosition: "inside",
      minorTickColor: "#666",
      tickPixelInterval: 30,
      tickWidth: 2,
      tickPosition: "inside",
      tickLength: 10,
      tickColor: "#666",
      labels: {
        step: 2,
        rotation: "auto"
      },
      title: {
        text: "km/h"
      },
      plotBands: [{
        from: 0,
        to: 120,
        color: "#55BF3B"
      }{
        from: 120,
        to: 160,
        color: "#DDDF0D"
      }{
        from: 160,
        to: 200,
        color: "#DF5353"
      }]
    },
    series: [{
      name: "Speed",
      data: [80],
      tooltip: {
        valueSuffix: " km/h"
      }
    }]
  }function(e) {
    e.renderer.forExport || setInterval(function() {
      var ta = e.series[0].points[0],
        n = Math.round(20 * (Math.random() - .5));
      ((t = a.y + n) < 0 || t > 200) && (t = a.y - n)a.update(t)
    }3e3)
  })jQuery("#high-barwithnagative-chart").length) {
  var categories = ["0-4""5-9""10-14""15-19""20-24""25-29""30-34""35-39""40-44""45-49""50-54""55-59""60-64""65-69""70-74""75-79""80-84""85-89""90-94""95-99""100 + "];
  Highcharts.chart("high-barwithnagative-chart"{
    chart: {
      type: "bar"
    },
    title: {
      text: "Population pyramid for Germany2018"
    },
    subtitle: {
      text: 'Source: <a href="http://populationpyramid.net/germany/2018/">Population Pyramids of the World from 1950 to 2100</a>'
    },
    accessibility: {
      point: {
        descriptionFormatter: function(e) {
          return e.index + 1 + "Age " + e.category + "" + Math.abs(e.y) + "%. " + e.series.name + "."
        }
      }
    },
    xAxis: [{
      categories: categories,
      reversed: !1,
      labels: {
        step: 1
      },
      accessibility: {
        description: "Age (male)"
      }
    }{
      opposite: !0,
      reversed: !1,
      categories: categories,
      linkedTo: 0,
      labels: {
        step: 1
      },
      accessibility: {
        description: "Age (female)"
      }
    }],
    yAxis: {
      title: {
        text: null
      },
      labels: {
        formatter: function() {
          return Math.abs(this.value) + "%"
        }
      },
      accessibility: {
        description: "Percentage population",
        rangeDescription: "Range: 0 to 5%"
      }
    },
    plotOptions: {
      series: {
        stacking: "normal"
      }
    },
    tooltip: {
      formatter: function() {
        return "<b>" + this.series.name + "age " + this.point.category + "</b><br/>Population: " + Highcharts.numberFormat(Math.abs(this.point.y)1) + "%"
      }
    },
    series: [{
      name: "Male",
      data: [-2.2-2.1-2.2-2.4-2.7-3-3.3-3.2-2.9-3.5-4.4-4.1-0],
      color: "#4788ff"
    }{
      name: "Female",
      data: [2.122.12.32.62.93.23.12.93.40],
      color: "#ff4b4b"
    }]
  })
}

/*--------------Widget Chart 1----------------*/

var options = {
    chart: {
        height: 80,
        type: 'area',
        sparkline: {
            enabled: true
        },
        group: 'sparklines',

    },
    dataLabels: {
        enabled: false
    },
    stroke: {
        width: 3,
        curve: 'smooth'
    },
    fill: {
        type: 'gradient',
        gradient: {
            shadeIntensity: 1,
            opacityFrom: 0.5,
            opacityTo: 0,
        }
    },

    series: [{
        name: 'series1',
        data: [6015503070]
    }],
    colors: ['#50b5ff'],

    xaxis: {
        type: 'datetime',
        categories: ["2018-08-19T00:00:00""2018-09-19T01:30:00""2018-10-19T02:30:00""2018-11-19T01:30:00""2018-12-19T01:30:00"],
    },
    tooltip: {
        x: {
            format: 'dd/MM/yy HH:mm'
        },
    }
};

if(jQuery('#chart-1').length){
    var chart = new ApexCharts(
        document.querySelector("#chart-1"),
        options
    );
    chart.render();
}


/*--------------Widget Chart 2----------------*/
var options = {
    chart: {
        height: 80,
        type: 'area',
        sparkline: {
            enabled: true
        },
        group: 'sparklines',

    },
    dataLabels: {
        enabled: false
    },
    stroke: {
        width: 3,
        curve: 'smooth'
    },
    fill: {
        type: 'gradient',
        gradient: {
            shadeIntensity: 1,
            opacityFrom: 0.5,
            opacityTo: 0,
        }
    },
    series: [{
        name: 'series1',
        data: [7040603060]
    }],
    colors: ['#fd7e14'],

    xaxis: {
        type: 'datetime',
        categories: ["2018-08-19T00:00:00""2018-09-19T01:30:00""2018-10-19T02:30:00""2018-11-19T01:30:00""2018-12-19T01:30:00"],
    },
    tooltip: {
        x: {
            format: 'dd/MM/yy HH:mm'
        },
    }
};

if(jQuery('#chart-2').length){
    var chart = new ApexCharts(
        document.querySelector("#chart-2"),
        options
    );

    chart.render();
}

/*--------------Widget Chart 3----------------*/
var options = {
    chart: {
        height: 80,
        type: 'area',
        sparkline: {
            enabled: true
        },
        group: 'sparklines',

    },
    dataLabels: {
        enabled: false
    },
    stroke: {
        width: 3,
        curve: 'smooth'
    },
    fill: {
        type: 'gradient',
        gradient: {
            shadeIntensity: 1,
            opacityFrom: 0.5,
            opacityTo: 0,
        }
    },
    series: [{
        name: 'series1',
        data: [6040604070]
    }],
    colors: ['#49f0d3'],

    xaxis: {
        type: 'datetime',
        categories: ["2018-08-19T00:00:00""2018-09-19T01:30:00""2018-10-19T02:30:00""2018-11-19T01:30:00""2018-12-19T01:30:00"],
    },
    tooltip: {
        x: {
            format: 'dd/MM/yy HH:mm'
        },
    }
};
if(jQuery('#chart-3').length){
    var chart = new ApexCharts(
        document.querySelector("#chart-3"),
        options
    );
    chart.render();
}

/*--------------Widget Chart 4----------------*/
var options = {
    chart: {
        height: 80,
        type: 'area',
        sparkline: {
            enabled: true
        },
        group: 'sparklines',

    },
    dataLabels: {
        enabled: false
    },
    stroke: {
        width: 3,
        curve: 'smooth'
    },
    fill: {
        type: 'gradient',
        gradient: {
            shadeIntensity: 1,
            opacityFrom: 0.5,
            opacityTo: 0,
        }
    },
    series: [{
        name: 'series1',
        data: [7530603560]
    }],
    colors: ['#ff9b8a'],

    xaxis: {
        type: 'datetime',
        categories: ["2018-08-19T00:00:00""2018-09-19T01:30:00""2018-10-19T02:30:00""2018-11-19T01:30:00""2018-12-19T01:30:00"],
    },
    tooltip: {
        x: {
            format: 'dd/MM/yy HH:mm'
        },
    }
};

if(jQuery('#chart-4').length){
    var chart = new ApexCharts(
        document.querySelector("#chart-4"),
        options
    );

    chart.render();
}

/*--------------Widget Box----------------*/

if(jQuery('#iq-chart-box1').length){
    var options = {
      series: [{
        name: "Total sales",
        data: [10103510]
    }],
      colors: ["#50b5ff"],
      chart: {
      height: 50,
      width: 100,
      type: 'line',
      sparkline: {
          enabled: true,
      },
      zoom: {
        enabled: false
      }
    },
    dataLabels: {
      enabled: false
    },
    stroke: {
      curve: 'straight'
    },
    title: {
      text: '',
      align: 'left'
    },
    grid: {
      row: {
        colors: ['#f3f3f3''transparent']// takes an array which will be repeated on columns
        opacity: 0.5
      },
    },
    xaxis: {
      categories: ['Jan''Feb''Mar'],
    }
    };

    var chart = new ApexCharts(document.querySelector("#iq-chart-box1")options);
    chart.render();
  const body = document.querySelector('body')
  if (body.classList.contains('dark')) {
    apexChartUpdate(chart{
      dark: true
    })
  }

  document.addEventListener('ChangeColorMode'function (e) {
    apexChartUpdate(charte.detail)
  })
}
if(jQuery('#iq-chart-box2').length){
    var options = {
      series: [{
        name: "Sale Today",
        data: [10103510]
    }],
      colors: ["#ff9b8a"],
      chart: {
      height: 50,
      width: 100,
      type: 'line',
      sparkline: {
          enabled: true,
      },
      zoom: {
        enabled: false
      }
    },
    dataLabels: {
      enabled: false
    },
    stroke: {
      curve: 'straight'
    },
    title: {
      text: '',
      align: 'left'
    },
    grid: {
      row: {
        colors: ['#f3f3f3''transparent']// takes an array which will be repeated on columns
        opacity: 0.5
      },
    },
    xaxis: {
      categories: ['Jan''Feb''Mar'],
    }
    };

    var chart = new ApexCharts(document.querySelector("#iq-chart-box2")options);
    chart.render();
  const body = document.querySelector('body')
  if (body.classList.contains('dark')) {
    apexChartUpdate(chart{
      dark: true
    })
  }

  document.addEventListener('ChangeColorMode'function (e) {
    apexChartUpdate(charte.detail)
  })
}
if(jQuery('#iq-chart-box3').length){
    var options = {
      series: [{
        name: "Total Classon",
        data: [10103510]
    }],
      colors: ["#49f0d3"],
      chart: {
      height: 50,
      width: 100,
      type: 'line',
      sparkline: {
          enabled: true,
      },
      zoom: {
        enabled: false
      }
    },
    dataLabels: {
      enabled: false
    },
    stroke: {
      curve: 'straight'
    },
    title: {
      text: '',
      align: 'left'
    },
    grid: {
      row: {
        colors: ['#f3f3f3''transparent']// takes an array which will be repeated on columns
        opacity: 0.5
      },
    },
    xaxis: {
      categories: ['Jan''Feb''Mar'],
    }
    };

    var chart = new ApexCharts(document.querySelector("#iq-chart-box3")options);
    chart.render();
  const body = document.querySelector('body')
  if (body.classList.contains('dark')) {
    apexChartUpdate(chart{
      dark: true
    })
  }

  document.addEventListener('ChangeColorMode'function (e) {
    apexChartUpdate(charte.detail)
  })
}
if(jQuery('#iq-chart-box4').length){
    var options = {
      series: [{
        name: "Total Profit",
        data: [10103510]
    }],
      colors: ["#fd7e14"],
      chart: {
      height: 50,
      width: 100,
      type: 'line',
      sparkline: {
          enabled: true,
      },
      zoom: {
        enabled: false
      }
    },
    dataLabels: {
      enabled: false
    },
    stroke: {
      curve: 'straight'
    },
    title: {
      text: '',
      align: 'left'
    },
    grid: {
      row: {
        colors: ['#f3f3f3''transparent']// takes an array which will be repeated on columns
        opacity: 0.5
      },
    },
    xaxis: {
      categories: ['Jan''Feb''Mar'],
    }
    };

    var chart = new ApexCharts(document.querySelector("#iq-chart-box4")options);
    chart.render();
  const body = document.querySelector('body')
  if (body.classList.contains('dark')) {
    apexChartUpdate(chart{
      dark: true
    })
  }

  document.addEventListener('ChangeColorMode'function (e) {
    apexChartUpdate(charte.detail)
  })
}
/*--------------Widget End----------------*/

/*-------------- Widget Chart ----------------*/
if (jQuery("#ethernet-chart-01").length) {
    var options = {
      series: [{
        name: "Desktops",
        data: [53062051810]
    }],
    colors: ['#4788ff'],
      chart: {
      height: 60,
      width: 100,
      type: 'line',
      zoom: {
        enabled: false
      },
      sparkline: {
        enabled: true,
      }
    },
    dataLabels: {
      enabled: false
    },
    stroke: {
      curve: 'smooth',
      width: 3
    },
    title: {
      text: '',
      align: 'left'
    },
    grid: {
      row: {
        colors: ['#f3f3f3''transparent']// takes an array which will be repeated on columns
        opacity: 0.5
      },
    },
    xaxis: {
      categories: ['Jan''Feb''Mar''Apr''May''Jun'],
    }
    };

    var chart = new ApexCharts(document.querySelector("#ethernet-chart-01")options);
    chart.render();
  const body = document.querySelector('body')
  if (body.classList.contains('dark')) {
    apexChartUpdate(chart{
      dark: true
    })
  }

  document.addEventListener('ChangeColorMode'function (e) {
    apexChartUpdate(charte.detail)
  })
}
if (jQuery("#ethernet-chart-02").length) {
    var options = {
      series: [{
        name: "Desktops",
        data: [52041831510]
    }],
    colors: ['#1ee2ac'],
      chart: {
      height: 60,
      width: 100,
      type: 'line',
      zoom: {
        enabled: false
      },
      sparkline: {
        enabled: true,
      }
    },
    dataLabels: {
      enabled: false
    },
    stroke: {
      curve: 'smooth',
      width: 3
    },
    title: {
      text: '',
      align: 'left'
    },
    grid: {
      row: {
        colors: ['#f3f3f3''transparent']// takes an array which will be repeated on columns
        opacity: 0.5
      },
    },
    xaxis: {
      categories: ['Jan''Feb''Mar''Apr''May''Jun'],
    }
    };

    var chart = new ApexCharts(document.querySelector("#ethernet-chart-02")options);
    chart.render();
  const body = document.querySelector('body')
  if (body.classList.contains('dark')) {
    apexChartUpdate(chart{
      dark: true
    })
  }

  document.addEventListener('ChangeColorMode'function (e) {
    apexChartUpdate(charte.detail)
  })
}
if (jQuery("#ethernet-chart-03").length) {
    var options = {
      series: [{
        name: "Desktops",
        data: [5206185154]
    }],
    colors: ['#ff4b4b'],
      chart: {
      height: 60,
      width: 100,
      type: 'line',
      zoom: {
        enabled: false
      },
      sparkline: {
        enabled: true,
      }
    },
    dataLabels: {
      enabled: false
    },
    stroke: {
      curve: 'smooth',
      width: 3
    },
    title: {
      text: '',
      align: 'left'
    },
    grid: {
      row: {
        colors: ['#f3f3f3''transparent']// takes an array which will be repeated on columns
        opacity: 0.5
      },
    },
    xaxis: {
      categories: ['Jan''Feb''Mar''Apr''May''Jun'],
    }
    };

    var chart = new ApexCharts(document.querySelector("#ethernet-chart-03")options);
    chart.render();
  const body = document.querySelector('body')
  if (body.classList.contains('dark')) {
    apexChartUpdate(chart{
      dark: true
    })
  }

  document.addEventListener('ChangeColorMode'function (e) {
    apexChartUpdate(charte.detail)
  })
}
if (jQuery("#ethernet-chart-04").length) {
    var options = {
      series: [{
        name: "Desktops",
        data: [51532051813]
    }],
    colors: ['#4788ff'],
      chart: {
      height: 60,
      width: 100,
      type: 'line',
      zoom: {
        enabled: false
      },
      sparkline: {
        enabled: true,
      }
    },
    dataLabels: {
      enabled: false
    },
    stroke: {
      curve: 'smooth',
      width: 3
    },
    title: {
      text: '',
      align: 'left'
    },
    grid: {
      row: {
        colors: ['#f3f3f3''transparent']// takes an array which will be repeated on columns
        opacity: 0.5
      },
    },
    xaxis: {
      categories: ['Jan''Feb''Mar''Apr''May''Jun'],
    }
    };

    var chart = new ApexCharts(document.querySelector("#ethernet-chart-04")options);
    chart.render();
  const body = document.querySelector('body')
  if (body.classList.contains('dark')) {
    apexChartUpdate(chart{
      dark: true
    })
  }

  document.addEventListener('ChangeColorMode'function (e) {
    apexChartUpdate(charte.detail)
  })
}

/*-------------- Widget Chart End ----------------*/

/*--------------Widget Chart ----------------*/
function getNewSeries(et) {
  var a = e + TICKINTERVAL;
  lastDate = a;
  for (var n = 0; n < data.length - 10; n++) data[n].x = a - XAXISRANGE - TICKINTERVALdata[n].y = 0;
  data.push({
    x: a,
    y: Math.floor(Math.random() * (t.max - t.min + 1)) + t.min
  })
}
if (jQuery("#chart-9").length) {
    var chart9 = new ApexCharts(document.querySelector("#chart-9")options);
    chart9.render()window.setInterval(function() {
        getNewSeries(lastDate{
            min: 10,
            max: 90
        })jQuery("#chart-9").length && chart9.updateSeries([{
            data: data
        }])
    }1e3)
}

function generateData(eta) {
    for (var n = 0o = []; n < t;) {
        var r = Math.floor(750 * Math.random()) + 1,
            i = Math.floor(Math.random() * (a.max - a.min + 1)) + a.min,
            c = Math.floor(61 * Math.random()) + 15;
        o.push([ric])864e5n++
    }
    return o
}
options = {
    chart: {
        height: 440,
        type: "bubble",
        stacked: !1,
        toolbar: {
            show: !1
        },
        animations: {
            enabled: !0,
            easing: "linear",
            dynamicAnimation: {
                speed: 1e3
            }
        },
        sparkline: {
            enabled: !0
        },
        group: "sparklines"
    },
    dataLabels: {
        enabled: !1
    },
    series: [{
        name: "Bubble1",
        data: generateData(new Date("11 Feb 2017 GMT").getTime()10{
            min: 10,
            max: 60
        })
    }{
        name: "Bubble2",
        data: generateData(new Date("11 Feb 2017 GMT").getTime()10{
            min: 10,
            max: 60
        })
    }{
        name: "Bubble3",
        data: generateData(new Date("11 Feb 2017 GMT").getTime()10{
            min: 10,
            max: 60
        })
    }{
        name: "Bubble4",
        data: generateData(new Date("11 Feb 2017 GMT").getTime()10{
            min: 10,
            max: 60
        })
    }],
    fill: {
        opacity: .8
    },
    title: {
        show: !1
    },
    xaxis: {
        tickAmount: 8,
        type: "category",
        labels: {
            show: !1
        }
    },
    yaxis: {
        max: 70,
        labels: {
            show: !1
        }
    },
    legend: {
        show: !1
    }
};

/*-------------- Widget Chart End ----------------*/
  
/*---------------------------------------------------------------------
   Editable Table
-----------------------------------------------------------------------*/
const $tableID = $('#table');
 const $BTN = $('#export-btn');
 const $EXPORT = $('#export');

 const newTr = `
<tr class="hide">
  <td class="pt-3-half" contenteditable="true">Example</td>
  <td class="pt-3-half" contenteditable="true">Example</td>
  <td class="pt-3-half" contenteditable="true">Example</td>
  <td class="pt-3-half" contenteditable="true">Example</td>
  <td class="pt-3-half" contenteditable="true">Example</td>
  <td class="pt-3-half">
    <span class="table-up"><a href="#!" class="indigo-text"><i class="fas fa-long-arrow-alt-up" aria-hidden="true"></i></a></span>
    <span class="table-down"><a href="#!" class="indigo-text"><i class="fas fa-long-arrow-alt-down" aria-hidden="true"></i></a></span>
  </td>
  <td>
    <span class="table-remove"><button type="button" class="btn btn-danger btn-rounded btn-sm my-0 waves-effect waves-light">Remove</button></span>
  </td>
</tr>`;

 $('.table-add').on('click''i'() => {

   const $clone = $tableID.find('tbody tr').last().clone(true).removeClass('hide table-line');

   if ($tableID.find('tbody tr').length === 0) {

     $('tbody').append(newTr);
   }

   $tableID.find('table').append($clone);
 });

 $tableID.on('click''.table-remove'function () {

   $(this).parents('tr').detach();
 });

 $tableID.on('click''.table-up'function () {

   const $row = $(this).parents('tr');

   if ($row.index() === 1) {
     return;
   }

   $row.prev().before($row.get(0));
 });

 $tableID.on('click''.table-down'function () {

   const $row = $(this).parents('tr');
   $row.next().after($row.get(0));
 });

 // A few jQuery helpers for exporting only
 jQuery.fn.pop = [].pop;
 jQuery.fn.shift = [].shift;

 $BTN.on('click'() => {

   const $rows = $tableID.find('tr:not(:hidden)');
   const headers = [];
   const data = [];

   // Get the headers (add special header logic here)
   $($rows.shift()).find('th:not(:empty)').each(function () {

     headers.push($(this).text().toLowerCase());
   });

   // Turn all existing rows into a loopable array
   $rows.each(function () {
     const $td = $(this).find('td');
     const h = {};

     // Use the headers from earlier to name our hash keys
     headers.forEach((headeri) => {

       h[header] = $td.eq(i).text();
     });

     data.push(h);
   });

   // Output the result
   $EXPORT.text(JSON.stringify(data));
 });

/*---------------------------------------------------------------------
    Form Wizard - 1
-----------------------------------------------------------------------*/

$(document).ready(function(){

    var current_fsnext_fsprevious_fs; //fieldsets
    var opacity;
    var current = 1;
    var steps = $("fieldset").length;

    setProgressBar(current);

    $(".next").click(function(){

    current_fs = $(this).parent();
    next_fs = $(this).parent().next();

    //Add Class Active
    $("#top-tab-list li").eq($("fieldset").index(next_fs)).addClass("active");
    $("#top-tab-list li").eq($("fieldset").index(current_fs)).addClass("done");

    //show the next fieldset
    next_fs.show();
    //hide the current fieldset with style
    current_fs.animate({opacity: 0}{
    step: function(now) {
    // for making fielset appear animation
    opacity = 1 - now;

    current_fs.css({
    'display': 'none',
    'position': 'relative',

    });

    next_fs.css({'opacity': opacity});
    },
    duration: 500
    });
    setProgressBar(++current);
    });

    $(".previous").click(function(){

    current_fs = $(this).parent();
    previous_fs = $(this).parent().prev();

    //Remove class active
    $("#top-tab-list li").eq($("fieldset").index(current_fs)).removeClass("active");
    $("#top-tab-list li").eq($("fieldset").index(previous_fs)).removeClass("done");

    //show the previous fieldset
    previous_fs.show();

    //hide the current fieldset with style
    current_fs.animate({opacity: 0}{
    step: function(now) {
    // for making fielset appear animation
    opacity = 1 - now;

    current_fs.css({
    'display': 'none',
    'position': 'relative'
    });
    previous_fs.css({'opacity': opacity});
    },
    duration: 500
    });
    setProgressBar(--current);
    });

    function setProgressBar(curStep){
    var percent = parseFloat(100 / steps) * curStep;
    percent = percent.toFixed();
    $(".progress-bar")
    .css("width",percent+"%")
    }

    $(".submit").click(function(){
    return false;
    })

});

 /*---------------------------------------------------------------------
   validate form wizard
-----------------------------------------------------------------------*/

$(document).ready(function () {

    var navListItems = $('div.setup-panel div a'),
            allWells = $('.setup-content'),
            allNextBtn = $('.nextBtn');

    allWells.hide();

    navListItems.click(function (e) {
        e.preventDefault();
        var $target = $($(this).attr('href')),
                $item = $(this);

        if (!$item.hasClass('disabled')) {
            navListItems.addClass('active');
            $item.parent().addClass('active');
            allWells.hide();
            $target.show();
            $target.find('input:eq(0)').focus();
        }
    });

    allNextBtn.click(function(){
        var curStep = $(this).closest(".setup-content"),
            curStepBtn = curStep.attr("id"),
            nextStepWizard = $('div.setup-panel div a[href="#' + curStepBtn + '"]').parent().next().children("a"),
            curInputs = curStep.find("input[type='text'],input[type='email'],input[type='password'],input[type='url'],textarea"),
            isValid = true;

        $(".form-group").removeClass("has-error");
        for(var i=0; i<curInputs.length; i++){
            if (!curInputs[i].validity.valid){
                isValid = false;
                $(curInputs[i]).closest(".form-group").addClass("has-error");
            }
        }

        if (isValid)
            nextStepWizard.removeAttr('disabled').trigger('click');
    });

    $('div.setup-panel div a.active').trigger('click');
});
 /*---------------------------------------------------------------------
   Vertical form wizard
-----------------------------------------------------------------------*/
$(document).ready(function(){

    var current_fsnext_fsprevious_fs; //fieldsets
    var opacity;
    var current = 1;
    var steps = $("fieldset").length;

    setProgressBar(current);

    $(".next").click(function(){

    current_fs = $(this).parent();
    next_fs = $(this).parent().next();

    //Add Class Active
    $("#top-tabbar-vertical li").eq($("fieldset").index(next_fs)).addClass("active");

    //show the next fieldset
    next_fs.show();
    //hide the current fieldset with style
    current_fs.animate({opacity: 0}{
    step: function(now) {
    // for making fielset appear animation
    opacity = 1 - now;

    current_fs.css({
    'display': 'none',
    'position': 'relative'
    });
    next_fs.css({'opacity': opacity});
    },
    duration: 500
    });
    setProgressBar(++current);
    });

    $(".previous").click(function(){

    current_fs = $(this).parent();
    previous_fs = $(this).parent().prev();

    //Remove class active
    $("#top-tabbar-vertical li").eq($("fieldset").index(current_fs)).removeClass("active");

    //show the previous fieldset
    previous_fs.show();

    //hide the current fieldset with style
    current_fs.animate({opacity: 0}{
    step: function(now) {
    // for making fielset appear animation
    opacity = 1 - now;

    current_fs.css({
    'display': 'none',
    'position': 'relative'
    });
    previous_fs.css({'opacity': opacity});
    },
    duration: 500
    });
    setProgressBar(--current);
    });

    function setProgressBar(curStep){
    var percent = parseFloat(100 / steps) * curStep;
    percent = percent.toFixed();
    $(".progress-bar")
    .css("width",percent+"%")
    }

    $(".submit").click(function(){
    return false;
    })

});


/*---------------------------------------------------------------------
   Profile Image Edit
-----------------------------------------------------------------------*/

$(document).ready(function() {
    var readURL = function(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();

            reader.onload = function (e) {
                $('.profile-pic').attr('src'e.target.result);
            }

            reader.readAsDataURL(input.files[0]);
        }
    }


    $(".file-upload").on('change'function(){
        readURL(this);
    });

    $(".upload-button").on('click'function() {
       $(".file-upload").click();
    });
});

// ratting
$(function() {
  if(typeof $.fn.barrating !== typeof undefined){
    $('#example').barrating({
      theme: 'fontawesome-stars'
    });
    $('#bars-number').barrating({
      theme: 'bars-1to10'
    });
    $('#movie-rating').barrating('show',{
      theme: 'bars-movie'
    });
    $('#movie-rating').barrating('set''Mediocre');
    $('#pill-rating').barrating({
      theme: 'bars-pill',
      showValues: true,
      showSelectedRating: false,
      onSelect: function(valuetext) {
        alert('Selected rating: ' + value);
    }
    });
  } 
  if (typeof $.fn.mdbRate !== typeof undefined) {
    $('#rateMe1').mdbRate();
    $('#face-rating').mdbRate();
  }
});

// quill
if (jQuery("#editor").length) {
  var quill = new Quill('#editor'{
  theme: 'snow'
  });
}
  // With Tooltip
  if (jQuery("#quill-toolbar").length) {
  var quill = new Quill('#quill-toolbar'{
      modules: {
        toolbar: '#quill-tool'
      },
      placeholder: 'Compose an epic...',
      theme: 'snow'
  });
  // Enable all tooltips
  $('[data-toggle="tooltip"]').tooltip();

  // Can control programmatically too
  $('.ql-italic').mouseover();
  setTimeout(function() {
      $('.ql-italic').mouseout();
  }2500);
}
  // file upload
  $(".custom-file-input").on("change"function() {
    var fileName = $(this).val().split("\\").pop();
    $(this).siblings(".custom-file-label").addClass("selected").html(fileName);
  });

  /*---------------------------------------------------------------------
   Dashboard Charts
  ---------------------------------------------------------------------*/
  if (jQuery("#layout1-chart1").length) {
    options = {
      chart: {
        height: 350,
        type: "candlestick"
      },
      colors: ["#32BDEA""#FF7E41"],
      series: [{
        data: [{
          x: new Date(15387786e5),
          y: [6629.816650.56623.046633.33]
        }{
          x: new Date(15387804e5),
          y: [6632.016643.5966206630.11]
        }{
          x: new Date(15387822e5),
          y: [6630.716648.956623.346635.65]
        }{
          x: new Date(1538784e6),
          y: [6635.6566516629.676638.24]
        }{
          x: new Date(15387858e5),
          y: [6638.24664066206624.47]
        }{
          x: new Date(15387876e5),
          y: [6624.536636.036621.686624.31]
        }{
          x: new Date(15387894e5),
          y: [6624.616632.266176626.02]
        }{
          x: new Date(15387912e5),
          y: [66276627.626584.226603.02]
        }{
          x: new Date(1538793e6),
          y: [66056608.036598.956604.01]
        }{
          x: new Date(15387948e5),
          y: [6604.56614.46602.266608.02]
        }{
          x: new Date(15387966e5),
          y: [6608.026610.686601.996608.91]
        }{
          x: new Date(15387984e5),
          y: [6608.916618.996608.016612]
        }{
          x: new Date(15388002e5),
          y: [66126615.136605.096612]
        }{
          x: new Date(1538802e6),
          y: [66126624.126608.436622.95]
        }{
          x: new Date(15388038e5),
          y: [6623.916623.9166156615.67]
        }{
          x: new Date(15388056e5),
          y: [6618.696618.7466106610.4]
        }{
          x: new Date(15388074e5),
          y: [66116622.786610.46614.9]
        }{
          x: new Date(15388092e5),
          y: [6614.96626.26613.336623.45]
        }{
          x: new Date(1538811e6),
          y: [6623.4866276618.386620.35]
        }{
          x: new Date(15388128e5),
          y: [6619.436620.356610.056615.53]
        }{
          x: new Date(15388146e5),
          y: [6615.536617.9366106615.19]
        }{
          x: new Date(15388164e5),
          y: [6615.196621.66608.26620]
        }{
          x: new Date(15388182e5),
          y: [6619.546625.176614.156620]
        }{
          x: new Date(153882e7),
          y: [6620.336634.156617.246624.61]
        }{
          x: new Date(15388218e5),
          y: [6625.9566266611.666617.58]
        }{
          x: new Date(15388236e5),
          y: [66196625.976595.276598.86]
        }{
          x: new Date(15388254e5),
          y: [6598.866598.8865706587.16]
        }{
          x: new Date(15388272e5),
          y: [6588.86660065806593.4]
        }{
          x: new Date(1538829e6),
          y: [6593.996598.8965856587.81]
        }{
          x: new Date(15388308e5),
          y: [6587.816592.736567.146578]
        }{
          x: new Date(15388326e5),
          y: [6578.356581.726567.396579]
        }{
          x: new Date(15388344e5),
          y: [6579.386580.926566.776575.96]
        }{
          x: new Date(15388362e5),
          y: [6575.9665896571.776588.92]
        }{
          x: new Date(1538838e6),
          y: [6588.9265946577.556589.22]
        }{
          x: new Date(15388398e5),
          y: [6589.36598.896589.16596.08]
        }{
          x: new Date(15388416e5),
          y: [6597.566006588.396596.25]
        }{
          x: new Date(15388434e5),
          y: [6598.0366006588.736595.97]
        }{
          x: new Date(15388452e5),
          y: [6595.976602.016588.176602]
        }{
          x: new Date(1538847e6),
          y: [660266076596.516599.95]
        }{
          x: new Date(15388488e5),
          y: [6600.636601.216590.396591.02]
        }{
          x: new Date(15388506e5),
          y: [6591.026603.0865916591]
        }{
          x: new Date(15388524e5),
          y: [65916601.3265856592]
        }{
          x: new Date(15388542e5),
          y: [6593.136596.0165906593.34]
        }{
          x: new Date(1538856e6),
          y: [6593.346604.766582.636593.86]
        }{
          x: new Date(15388578e5),
          y: [6593.866604.286586.576600.01]
        }{
          x: new Date(15388596e5),
          y: [6601.816603.216592.786596.25]
        }{
          x: new Date(15388614e5),
          y: [6596.256604.265906602.99]
        }{
          x: new Date(15388632e5),
          y: [6602.9966066584.996587.81]
        }{
          x: new Date(1538865e6),
          y: [6587.8165956583.276591.96]
        }{
          x: new Date(15388668e5),
          y: [6591.976596.0765856588.39]
        }{
          x: new Date(15388686e5),
          y: [6587.66598.216587.66594.27]
        }{
          x: new Date(15388704e5),
          y: [6596.44660165906596.55]
        }{
          x: new Date(15388722e5),
          y: [6598.9166056596.616600.02]
        }{
          x: new Date(1538874e6),
          y: [6600.5566056589.146593.01]
        }{
          x: new Date(15388758e5),
          y: [6593.15660565926603.06]
        }]
      }],
      title: {
        text: "$45,78956",
        align: "left"
      },
      xaxis: {
        type: "datetime"
      },
      yaxis: {
        tooltip: {
          enabled: !0
        },
        labels: {
          offsetX: -2,
          offsetY: 0,
          minWidth: 30,
          maxWidth: 30,
        }
      },
      plotOptions: {
        candlestick: {
          colors: {
            upward: '#FF7E41',
            downward: '#32BDEA'
          }
        }
      }
    };
    (chart = new ApexCharts(document.querySelector("#layout1-chart1")options)).render()
    const body = document.querySelector('body')
    if (body.classList.contains('dark')) {
      apexChartUpdate(chart{
        dark: true
      })
    }
  
    document.addEventListener('ChangeColorMode'function (e) {
      apexChartUpdate(charte.detail)
    })
  }
  if(jQuery('#layout1-chart-2').length){
    am4core.ready(function() {

    // Themes begin
    am4core.useTheme(am4themes_animated);
    // Themes end
    
    // Create chart instance
    var chart = am4core.create("layout1-chart-2"am4charts.XYChart);
    chart.colors.list = [
		  am4core.color("#32BDEA"),
		  am4core.color("#32BDEA"),
		  am4core.color("#32BDEA"),
		  am4core.color("#32BDEA"),
		  am4core.color("#32BDEA"),
		  am4core.color("#32BDEA"),
		  am4core.color("#32BDEA"),
		  am4core.color("#32BDEA"),
		  am4core.color("#32BDEA")
		];
    chart.scrollbarX = new am4core.Scrollbar();
    
    // Add data
    chart.data = [{
      "country": "Jan",
      "visits": 3025
    }{
      "country": "Feb",
      "visits": 1882
    }{
      "country": "Mar",
      "visits": 1809
    }{
      "country": "Apr",
      "visits": 1322
    }{
      "country": "May",
      "visits": 1122
    }{
      "country": "Jun",
      "visits": 1114
    }{
      "country": "Jul",
      "visits": 984
    }{
      "country": "Aug",
      "visits": 711
    }];
    
    prepareParetoData();
    
    function prepareParetoData(){
        var total = 0;
    
        for(var i = 0; i < chart.data.length; i++){
            var value = chart.data[i].visits;
            total += value;
        }
    
        var sum = 0;
        for(var i = 0; i < chart.data.length; i++){
            var value = chart.data[i].visits;
            sum += value;   
            chart.data[i].pareto = sum / total * 100;
        }    
    }
    
    // Create axes
    var categoryAxis = chart.xAxes.push(new am4charts.CategoryAxis());
    categoryAxis.dataFields.category = "country";
    categoryAxis.renderer.grid.template.location = 0;
    categoryAxis.renderer.minGridDistance = 60;
    categoryAxis.tooltip.disabled = true;
    
    var valueAxis = chart.yAxes.push(new am4charts.ValueAxis());
    valueAxis.renderer.minWidth = 50;
    valueAxis.min = 0;
    valueAxis.cursorTooltipEnabled = false;

    // Create series
    var series = chart.series.push(new am4charts.ColumnSeries());
    series.sequencedInterpolation = true;
    series.dataFields.valueY = "visits";
    series.dataFields.categoryX = "country";
    series.tooltipText = "[{categoryX}: bold]{valueY}[/]";
    series.columns.template.strokeWidth = 0;
    
    series.tooltip.pointerOrientation = "vertical";
    
    series.columns.template.column.cornerRadiusTopLeft = 10;
    series.columns.template.column.cornerRadiusTopRight = 10;
    series.columns.template.column.fillOpacity = 0.8;
    
    // on hovermake corner radiuses bigger
    var hoverState = series.columns.template.column.states.create("hover");
    hoverState.properties.cornerRadiusTopLeft = 0;
    hoverState.properties.cornerRadiusTopRight = 0;
    hoverState.properties.fillOpacity = 1;
    
    series.columns.template.adapter.add("fill"function(filltarget) {
      return chart.colors.getIndex(target.dataItem.index);
    })
    
    
    var paretoValueAxis = chart.yAxes.push(new am4charts.ValueAxis());
    paretoValueAxis.renderer.opposite = true;
    paretoValueAxis.min = 0;
    paretoValueAxis.max = 100;
    paretoValueAxis.strictMinMax = true;
    paretoValueAxis.renderer.grid.template.disabled = true;
    paretoValueAxis.numberFormatter = new am4core.NumberFormatter();
    paretoValueAxis.numberFormatter.numberFormat = "#'%'"
    paretoValueAxis.cursorTooltipEnabled = false;
    
    var paretoSeries = chart.series.push(new am4charts.LineSeries())
    paretoSeries.dataFields.valueY = "pareto";
    paretoSeries.dataFields.categoryX = "country";
    paretoSeries.yAxis = paretoValueAxis;
    paretoSeries.tooltipText = "pareto: {valueY.formatNumber('#.0')}%[/]";
    paretoSeries.bullets.push(new am4charts.CircleBullet());
    paretoSeries.strokeWidth = 2;
    paretoSeries.stroke = new am4core.InterfaceColorSet().getFor("alternativeBackground");
    paretoSeries.strokeOpacity = 0.5;
    
    // Cursor
    chart.cursor = new am4charts.XYCursor();
    chart.cursor.behavior = "panX";
    
    }); // end am4core.ready()
  }
  if (jQuery("#layout1-chart-3").length) {    
    options = {
      series: [{
        name: "Desktops",
        data: [172315282232]
    }],
    colors: ['#FF7E41'],
      chart: {
      height: 150,
      type: 'line',
      zoom: {
        enabled: false
      },
      dropShadow: {
        enabled: true,
        color: '#000',
        top: 12,
        left: 1,
        blur: 2,
        opacity: 0.2
      },
      toolbar: {
        show: false
      },
      sparkline: {
        enabled: true,
      }
    },
    dataLabels: {
      enabled: false
    },
    stroke: {
      curve: 'smooth',
      width: 3
    },
    title: {
      text: '',
      align: 'left'
    },
    grid: {
      row: {
        colors: ['#f3f3f3''transparent']// takes an array which will be repeated on columns
        opacity: 0.5
      },
    },
    xaxis: {
      categories: ['Jan''Feb''Mar''Apr''May''Jun'],
    }
    };
    const chart = new ApexCharts(document.querySelector("#layout1-chart-3")options);
    chart.render();
    const body = document.querySelector('body')
    if (body.classList.contains('dark')) {
      apexChartUpdate(chart{
        dark: true
      })
    }

    document.addEventListener('ChangeColorMode'function (e) {
      apexChartUpdate(charte.detail)
    })
  }
  if (jQuery("#layout1-chart-4").length) {    
    options = {
      series: [{
        name: "Desktops",
        data: [172315282232]
    }],
    colors: ['#32BDEA'],
      chart: {
      height: 150,
      type: 'line',
      zoom: {
        enabled: false
      },
      dropShadow: {
        enabled: true,
        color: '#000',
        top: 12,
        left: 1,
        blur: 2,
        opacity: 0.2
      },
      toolbar: {
        show: false
      },
      sparkline: {
        enabled: true,
      }
    },
    dataLabels: {
      enabled: false
    },
    stroke: {
      curve: 'smooth',
      width: 3
    },
    title: {
      text: '',
      align: 'left'
    },
    grid: {
      row: {
        colors: ['#f3f3f3''transparent']// takes an array which will be repeated on columns
        opacity: 0.5
      },
    },
    xaxis: {
      categories: ['Jan''Feb''Mar''Apr''May''Jun'],
    }
    };
    const chart = new ApexCharts(document.querySelector("#layout1-chart-4")options);
    chart.render();
    const body = document.querySelector('body')
    if (body.classList.contains('dark')) {
      apexChartUpdate(chart{
        dark: true
      })
    }

    document.addEventListener('ChangeColorMode'function (e) {
      apexChartUpdate(charte.detail)
    })
  }
  if (jQuery("#layout1-chart-5").length) {    
    options = {
      series: [{
      name: 'Total Likes',
      data: [868084958375887686938565]
    }{
      name: 'Total Share',
      data: [767276857469806878857755]
    }],
      chart: {
      type: 'bar',
      height: 300
    },
    colors: ['#32BDEA','#FF7E41'],
        plotOptions: {
          bar: {
            horizontal: false,
            columnWidth: '30%',
            endingShape: 'rounded'
          },
        },
        dataLabels: {
          enabled: false
        },
        stroke: {
          show: true,
          width: 3,
          colors: ['transparent']
        },
        xaxis: {
          categories: ["Jan""Feb""Mar""Apr""May""Jun""Jul""Aug""Sep""Oct""Nov""Dec"],
          labels: {
            minWidth: 0,
            maxWidth: 0
          }
        },
      yaxis: {
        show: true,
        labels: {
          minWidth: 20,
          maxWidth: 20
        }
      },
        fill: {
          opacity: 1
        },
        tooltip: {
          y: {
            formatter: function (val) {
              return "$ " + val + " thousands"
            }
          }
        }
    };
    const chart = new ApexCharts(document.querySelector("#layout1-chart-5")options);
    chart.render();
    const body = document.querySelector('body')
    if (body.classList.contains('dark')) {
      apexChartUpdate(chart{
        dark: true
      })
    }

    document.addEventListener('ChangeColorMode'function (e) {
      apexChartUpdate(charte.detail)
    })
  }

  /*---------------------------------------------------------------------
   Report Charts
  ---------------------------------------------------------------------*/
  if (jQuery("#report-chart1").length) {
    options = {
      chart: {
        height: 350,
        type: "candlestick"
      },
      series: [{
        data: [{
          x: new Date(15387786e5),
          y: [6629.816650.56623.046633.33]
        }{
          x: new Date(15387804e5),
          y: [6632.016643.5966206630.11]
        }{
          x: new Date(15387822e5),
          y: [6630.716648.956623.346635.65]
        }{
          x: new Date(1538784e6),
          y: [6635.6566516629.676638.24]
        }{
          x: new Date(15387858e5),
          y: [6638.24664066206624.47]
        }{
          x: new Date(15387876e5),
          y: [6624.536636.036621.686624.31]
        }{
          x: new Date(15387894e5),
          y: [6624.616632.266176626.02]
        }{
          x: new Date(15387912e5),
          y: [66276627.626584.226603.02]
        }{
          x: new Date(1538793e6),
          y: [66056608.036598.956604.01]
        }{
          x: new Date(15387948e5),
          y: [6604.56614.46602.266608.02]
        }{
          x: new Date(15387966e5),
          y: [6608.026610.686601.996608.91]
        }{
          x: new Date(15387984e5),
          y: [6608.916618.996608.016612]
        }{
          x: new Date(15388002e5),
          y: [66126615.136605.096612]
        }{
          x: new Date(1538802e6),
          y: [66126624.126608.436622.95]
        }{
          x: new Date(15388038e5),
          y: [6623.916623.9166156615.67]
        }{
          x: new Date(15388056e5),
          y: [6618.696618.7466106610.4]
        }{
          x: new Date(15388074e5),
          y: [66116622.786610.46614.9]
        }{
          x: new Date(15388092e5),
          y: [6614.96626.26613.336623.45]
        }{
          x: new Date(1538811e6),
          y: [6623.4866276618.386620.35]
        }{
          x: new Date(15388128e5),
          y: [6619.436620.356610.056615.53]
        }{
          x: new Date(15388146e5),
          y: [6615.536617.9366106615.19]
        }{
          x: new Date(15388164e5),
          y: [6615.196621.66608.26620]
        }{
          x: new Date(15388182e5),
          y: [6619.546625.176614.156620]
        }{
          x: new Date(153882e7),
          y: [6620.336634.156617.246624.61]
        }{
          x: new Date(15388218e5),
          y: [6625.9566266611.666617.58]
        }{
          x: new Date(15388236e5),
          y: [66196625.976595.276598.86]
        }{
          x: new Date(15388254e5),
          y: [6598.866598.8865706587.16]
        }{
          x: new Date(15388272e5),
          y: [6588.86660065806593.4]
        }{
          x: new Date(1538829e6),
          y: [6593.996598.8965856587.81]
        }{
          x: new Date(15388308e5),
          y: [6587.816592.736567.146578]
        }{
          x: new Date(15388326e5),
          y: [6578.356581.726567.396579]
        }{
          x: new Date(15388344e5),
          y: [6579.386580.926566.776575.96]
        }{
          x: new Date(15388362e5),
          y: [6575.9665896571.776588.92]
        }{
          x: new Date(1538838e6),
          y: [6588.9265946577.556589.22]
        }{
          x: new Date(15388398e5),
          y: [6589.36598.896589.16596.08]
        }{
          x: new Date(15388416e5),
          y: [6597.566006588.396596.25]
        }{
          x: new Date(15388434e5),
          y: [6598.0366006588.736595.97]
        }{
          x: new Date(15388452e5),
          y: [6595.976602.016588.176602]
        }{
          x: new Date(1538847e6),
          y: [660266076596.516599.95]
        }{
          x: new Date(15388488e5),
          y: [6600.636601.216590.396591.02]
        }{
          x: new Date(15388506e5),
          y: [6591.026603.0865916591]
        }{
          x: new Date(15388524e5),
          y: [65916601.3265856592]
        }{
          x: new Date(15388542e5),
          y: [6593.136596.0165906593.34]
        }{
          x: new Date(1538856e6),
          y: [6593.346604.766582.636593.86]
        }{
          x: new Date(15388578e5),
          y: [6593.866604.286586.576600.01]
        }{
          x: new Date(15388596e5),
          y: [6601.816603.216592.786596.25]
        }{
          x: new Date(15388614e5),
          y: [6596.256604.265906602.99]
        }{
          x: new Date(15388632e5),
          y: [6602.9966066584.996587.81]
        }{
          x: new Date(1538865e6),
          y: [6587.8165956583.276591.96]
        }{
          x: new Date(15388668e5),
          y: [6591.976596.0765856588.39]
        }{
          x: new Date(15388686e5),
          y: [6587.66598.216587.66594.27]
        }{
          x: new Date(15388704e5),
          y: [6596.44660165906596.55]
        }{
          x: new Date(15388722e5),
          y: [6598.9166056596.616600.02]
        }]
      }],
      title: {
        text: "$45,78956",
        align: "left"
      },
      xaxis: {
        type: "datetime"
      },
      yaxis: {
        tooltip: {
          enabled: !0
        },
        labels: {
          offsetX: -2,
          offsetY: 0,
          minWidth: 30,
          maxWidth: 30,
        }
      },
      plotOptions: {
        candlestick: {
          colors: {
            upward: '#FF7E41',
            downward: '#32BDEA'
          }
        }
      }
    };
    (chart = new ApexCharts(document.querySelector("#report-chart1")options)).render()
    const body = document.querySelector('body')
    if (body.classList.contains('dark')) {
      apexChartUpdate(chart{
        dark: true
      })
    }
  
    document.addEventListener('ChangeColorMode'function (e) {
      apexChartUpdate(charte.detail)
    })
  }
  if (jQuery("#report-chart02").length) {
    var options = {
      series: [
      {
        name: 'Desktops',
        data: [
          {
            x: 'ABC',
            y: 10
          },
          {
            x: 'DEF',
            y: 60
          },
          {
            x: 'XYZ',
            y: 41
          }
        ]
      },
      {
        name: 'Mobile',
        data: [
          {
            x: 'ABCD',
            y: 10
          },
          {
            x: 'DEFG',
            y: 20
          },
          {
            x: 'WXYZ',
            y: 51
          },
          {
            x: 'PQR',
            y: 30
          },
          {
            x: 'MNO',
            y: 20
          },
          {
            x: 'CDE',
            y: 30
          }
        ]
      }
    ],
      legend: {
      show: false
    },
    chart: {
      height: 350,
      type: 'treemap'
    },
    title: {
      text: 'Multi-dimensional Treemap',
      align: 'center'
    }
    };

    (chart = new ApexCharts(document.querySelector("#report-chart02")options)).render()
    const body = document.querySelector('body')
    if (body.classList.contains('dark')) {
      apexChartUpdate(chart{
        dark: true
      })
    }
  
    document.addEventListener('ChangeColorMode'function (e) {
      apexChartUpdate(charte.detail)
    })
  }
  if (jQuery('#report-chart2').length) {
    am4core.ready(function() {

      // Themes begin
      am4core.useTheme(am4themes_animated);
      // Themes end
      
      // create chart
      var chart = am4core.create("report-chart2"am4charts.TreeMap);
      chart.hiddenState.properties.opacity = 0; // this makes initial fade in effect
      chart.colors.list = [am4core.color("#32bdea"),am4core.color("#ff7e41")am4core.color("#e83e8c")];
      
      chart.data = [{
        name: "First",
        children: [
          {
            name: "",
            value: 130,
          },
          {
            name: "",
            value: 90,
          },
          {
            name: "",
            value: 80,
          }
        ]
      },
      {
        name: "Second",
        children: [
          {
            name: "",
            value: 150
          },
          {
            name: "",
            value: 40
          },
          {
            name: "",
            value: 100
          }
        ]
      },
      {
        name: "Third",
        children: [
          {
            name: "",
            value: 250
          },
          {
            name: "",
            value: 148
          },
          {
            name: "",
            value: 126
          },
          {
            name: "",
            value: 26
          }
        ]
      }];
      
      chart.colors.step = 2;
      
      // define data fields
      chart.dataFields.value = "value";
      chart.dataFields.name = "name";
      chart.dataFields.children = "children";
      
      chart.zoomable = false;
      var bgColor = new am4core.InterfaceColorSet().getFor("background");
      
      // level 0 series template
      var level0SeriesTemplate = chart.seriesTemplates.create("0");
      var level0ColumnTemplate = level0SeriesTemplate.columns.template;
      
      level0ColumnTemplate.column.cornerRadius(10101010);
      level0ColumnTemplate.fillOpacity = 0;
      level0ColumnTemplate.strokeWidth = 4;
      level0ColumnTemplate.strokeOpacity = 0;
      
      // level 1 series template
      var level1SeriesTemplate = chart.seriesTemplates.create("1");
      var level1ColumnTemplate = level1SeriesTemplate.columns.template;
      
      level1SeriesTemplate.tooltip.animationDuration = 0;
      level1SeriesTemplate.strokeOpacity = 1;
      
      level1ColumnTemplate.column.cornerRadius(10101010)
      level1ColumnTemplate.fillOpacity = 1;
      level1ColumnTemplate.strokeWidth = 4;
      level1ColumnTemplate.stroke = bgColor;
      
      var bullet1 = level1SeriesTemplate.bullets.push(new am4charts.LabelBullet());
      bullet1.locationY = 0.5;
      bullet1.locationX = 0.5;
      bullet1.label.text = "{name}";
      bullet1.label.fill = am4core.color("#ffffff");
      
      chart.maxLevels = 2;
      
      }); // end am4core.ready()
  }
  if (jQuery("#report-chart3").length) {
    options = {
      series: [
      {
        name: 'Bob',
        data: [
          {
            x: 'Design',
            y: [
              new Date('2019-03-05').getTime(),
              new Date('2019-03-08').getTime()
            ]
          },
          {
            x: 'Code',
            y: [
              new Date('2019-03-02').getTime(),
              new Date('2019-03-05').getTime()
            ]
          },
          {
            x: 'Code',
            y: [
              new Date('2019-03-05').getTime(),
              new Date('2019-03-07').getTime()
            ]
          },
          {
            x: 'Test',
            y: [
              new Date('2019-03-03').getTime(),
              new Date('2019-03-09').getTime()
            ]
          },
          {
            x: 'Test',
            y: [
              new Date('2019-03-08').getTime(),
              new Date('2019-03-11').getTime()
            ]
          },
          {
            x: 'Validation',
            y: [
              new Date('2019-03-11').getTime(),
              new Date('2019-03-16').getTime()
            ]
          },
          {
            x: 'Design',
            y: [
              new Date('2019-03-01').getTime(),
              new Date('2019-03-03').getTime()
            ]
          }
        ]
      },
      {
        name: 'Joe',
        data: [
          {
            x: 'Design',
            y: [
              new Date('2019-03-02').getTime(),
              new Date('2019-03-05').getTime()
            ]
          },
          {
            x: 'Test',
            y: [
              new Date('2019-03-06').getTime(),
              new Date('2019-03-16').getTime()
            ]
          },
          {
            x: 'Code',
            y: [
              new Date('2019-03-03').getTime(),
              new Date('2019-03-07').getTime()
            ]
          },
          {
            x: 'Deployment',
            y: [
              new Date('2019-03-20').getTime(),
              new Date('2019-03-22').getTime()
            ]
          },
          {
            x: 'Design',
            y: [
              new Date('2019-03-10').getTime(),
              new Date('2019-03-16').getTime()
            ]
          }
        ]
      },
      {
        name: 'Dan',
        data: [
          {
            x: 'Code',
            y: [
              new Date('2019-03-10').getTime(),
              new Date('2019-03-17').getTime()
            ]
          },
          {
            x: 'Validation',
            y: [
              new Date('2019-03-05').getTime(),
              new Date('2019-03-09').getTime()
            ]
          },
        ]
      }
    ],
      chart: {
      height: 350,
      type: 'rangeBar'
    },
    colors: ['#32BDEA''#e83e8c''#FF7E41'],
    plotOptions: {
      bar: {
        horizontal: true,
        barHeight: '80%'
      }
    },
    xaxis: {
      type: 'datetime'
    },
    
    stroke: {
      width: 1
    },
    fill: {
      type: 'solid',
      opacity: 1
    },
    legend: {
      position: 'top',
      horizontalAlign: 'left'
    }
    };

     (chart = new ApexCharts(document.querySelector("#report-chart3")options)).render()
     const body = document.querySelector('body')
     if (body.classList.contains('dark')) {
       apexChartUpdate(chart{
         dark: true
       })
     }
   
     document.addEventListener('ChangeColorMode'function (e) {
       apexChartUpdate(charte.detail)
     })
  }
  if (jQuery("#report-chart4").length) {   
    options = {
      series: [{
      name: "SAMPLE A",
      data: [
      [16.45.4][10.97.4],[10.98.2][16.41.8][13.60.3] [27.12.3] [13.63.7][10.95.2][16.46.5] [24.57.1][10.90][8.14.7] [21.71.8][29.91.5][27.10.8][22.12]]
    },{
      name: "SAMPLE B",
      data: [
      [36.413.4][1.711][1.47] [3.613.7][1.915.2][6.416.5][0.910][4.517.1][10.910][0.114.7][910][12.711.8][2.110][2.510][27.110][2.911.5][7.110.8][2.112]]
    },{
      name: "SAMPLE C",
      data: [
      [21.73][23.63.5][24.63][29.93][21.720][195][22.43][24.53][32.63] [21.65][20.94][22.40][32.610.3][29.720.8][24.50.8][21.40][21.76.9][28.67.7]]
    }],
      chart: {
      height: 350,
      type: 'scatter',
      zoom: {
        enabled: true,
        type: 'xy'
      }
    },
    colors: ['#32BDEA''#e83e8c''#FF7E41'],
    xaxis: {
      tickAmount: 10,
      labels: {
        formatter: function(val) {
          return parseFloat(val).toFixed(1)
        }
      }
    },
    yaxis: {
      tickAmount: 7,
      show: true,
      labels: {
        minWidth: 20,
        maxWidth: 20
      }
    }
    };

     (chart = new ApexCharts(document.querySelector("#report-chart4")options)).render()
     const body = document.querySelector('body')
     if (body.classList.contains('dark')) {
       apexChartUpdate(chart{
         dark: true
       })
     }
   
     document.addEventListener('ChangeColorMode'function (e) {
       apexChartUpdate(charte.detail)
     })
  }

})(jQuery);


