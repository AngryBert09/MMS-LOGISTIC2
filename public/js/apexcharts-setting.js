var options = {
    series: [
        {
            name: "Likes",
            data: [],
        },
    ],
    chart: {
        height: 350,
        type: "line",
        toolbar: { show: false },
    },
    noData: {
        text: "No data available",
        align: "center",
        verticalAlign: "middle",
        offsetX: 0,
        offsetY: 0,
        style: { color: "#ccc", fontSize: "14px", fontFamily: "Arial" },
    },
    grid: { show: false, padding: { left: 0, right: 0 } },
    stroke: { width: 7, curve: "smooth" },
    xaxis: { type: "datetime", categories: [] },
    title: {
        text: "Social Media",
        align: "left",
        style: { fontSize: "16px", color: "#666" },
    },
};
var chart = new ApexCharts(document.querySelector("#chart1"), options);
chart.render();

var options2 = {
    series: [
        {
            name: "series1",
            data: [],
        },
        {
            name: "series2",
            data: [],
        },
    ],
    chart: {
        height: 350,
        type: "area",
        toolbar: { show: false },
    },
    noData: {
        text: "No data available",
        align: "center",
        verticalAlign: "middle",
        style: { color: "#ccc", fontSize: "14px" },
    },
    grid: { show: false, padding: { left: 0, right: 0 } },
    dataLabels: { enabled: false },
    stroke: { curve: "smooth" },
    xaxis: { type: "datetime", categories: [] },
    tooltip: { x: { format: "dd/MM/yy HH:mm" } },
};
var chart2 = new ApexCharts(document.querySelector("#chart2"), options2);
chart2.render();

var options3 = {
    series: [
        {
            name: "Net Profit",
            data: [],
        },
        {
            name: "Revenue",
            data: [],
        },
        {
            name: "Free Cash Flow",
            data: [],
        },
    ],
    chart: {
        type: "bar",
        height: 350,
        toolbar: { show: false },
    },
    noData: {
        text: "No data available",
        align: "center",
        verticalAlign: "middle",
    },
    plotOptions: {
        bar: {
            horizontal: false,
            columnWidth: "25%",
            endingShape: "rounded",
        },
    },
    dataLabels: { enabled: false },
    stroke: { show: true, width: 2, colors: ["transparent"] },
    xaxis: { categories: [] },
    yaxis: { title: { text: "$(thousands)" } },
    fill: { opacity: 1 },
    tooltip: {
        y: {
            formatter: function (val) {
                return "$" + val + " thousands";
            },
        },
    },
};
var chart3 = new ApexCharts(document.querySelector("#chart3"), options3);
chart3.render();

var options4 = {
    series: [{ data: [] }, { data: [] }],
    chart: {
        type: "bar",
        height: 430,
        toolbar: { show: false },
    },
    noData: { text: "No data available" },
    grid: { show: false, padding: { left: 0, right: 0 } },
    plotOptions: {
        bar: {
            horizontal: true,
            dataLabels: { position: "top" },
        },
    },
    dataLabels: {
        enabled: true,
        offsetX: -6,
        style: { fontSize: "12px", colors: ["#fff"] },
    },
    stroke: { show: true, width: 1, colors: ["#fff"] },
    xaxis: { categories: [] },
};
var chart4 = new ApexCharts(document.querySelector("#chart4"), options4);
chart4.render();

var options5 = {
    series: [
        { name: "Team A", type: "column", data: [] },
        { name: "Team B", type: "area", data: [] },
        { name: "Team C", type: "line", data: [] },
    ],
    chart: {
        height: 350,
        type: "line",
        stacked: false,
        toolbar: { show: false },
    },
    noData: { text: "No data available" },
    grid: { show: false, padding: { left: 0, right: 0 } },
    stroke: { width: [0, 2, 5], curve: "smooth" },
    plotOptions: { bar: { columnWidth: "20%" } },
    fill: { opacity: [0.85, 0.25, 1] },
    labels: [],
    markers: { size: 0 },
    xaxis: { type: "datetime" },
    yaxis: { title: { text: "Points" }, min: 0 },
    tooltip: {
        shared: true,
        intersect: false,
        y: {
            formatter: function (y) {
                return y ? y.toFixed(0) + " points" : y;
            },
        },
    },
};
var chart5 = new ApexCharts(document.querySelector("#chart5"), options5);
chart5.render();

var options6 = {
    series: [
        { name: "Bob", data: [] },
        { name: "Joe", data: [] },
        { name: "Dan", data: [] },
    ],
    chart: { height: 450, type: "rangeBar", toolbar: { show: false } },
    noData: { text: "No data available" },
    grid: { show: false, padding: { left: 0, right: 0 } },
    plotOptions: { bar: { horizontal: true, barHeight: "80%" } },
    xaxis: { type: "datetime" },
    stroke: { width: 1 },
    fill: { type: "solid", opacity: 0.6 },
    legend: { position: "top", horizontalAlign: "left" },
};
var chart6 = new ApexCharts(document.querySelector("#chart6"), options6);
chart6.render();

var options7 = {
    series: [
        {
            data: [
                {
                    x: new Date(1538778600000),
                    y: [6629.81, 6650.5, 6623.04, 6633.33],
                },
                {
                    x: new Date(1538780400000),
                    y: [6632.01, 6643.59, 6620, 6630.11],
                },
                {
                    x: new Date(1538782200000),
                    y: [6630.71, 6648.95, 6623.34, 6635.65],
                },
                {
                    x: new Date(1538784000000),
                    y: [6635.65, 6651, 6629.67, 6638.24],
                },
                {
                    x: new Date(1538785800000),
                    y: [6638.24, 6640, 6620, 6624.47],
                },
                {
                    x: new Date(1538787600000),
                    y: [6624.53, 6636.03, 6621.68, 6624.31],
                },
                {
                    x: new Date(1538789400000),
                    y: [6624.61, 6632.2, 6617, 6626.02],
                },
                {
                    x: new Date(1538791200000),
                    y: [6627, 6627.62, 6584.22, 6603.02],
                },
                {
                    x: new Date(1538793000000),
                    y: [6605, 6608.03, 6598.95, 6604.01],
                },
                {
                    x: new Date(1538794800000),
                    y: [6604.5, 6614.4, 6602.26, 6608.02],
                },
                {
                    x: new Date(1538796600000),
                    y: [6608.02, 6610.68, 6601.99, 6608.91],
                },
                {
                    x: new Date(1538798400000),
                    y: [6608.91, 6618.99, 6608.01, 6612],
                },
                {
                    x: new Date(1538800200000),
                    y: [6612, 6615.13, 6605.09, 6612],
                },
                {
                    x: new Date(1538802000000),
                    y: [6612, 6624.12, 6608.43, 6622.95],
                },
                {
                    x: new Date(1538803800000),
                    y: [6623.91, 6623.91, 6615, 6615.67],
                },
                {
                    x: new Date(1538805600000),
                    y: [6618.69, 6618.74, 6610, 6610.4],
                },
                {
                    x: new Date(1538807400000),
                    y: [6611, 6622.78, 6610.4, 6614.9],
                },
                {
                    x: new Date(1538809200000),
                    y: [6614.9, 6626.2, 6613.33, 6623.45],
                },
                {
                    x: new Date(1538811000000),
                    y: [6623.48, 6627, 6618.38, 6620.35],
                },
                {
                    x: new Date(1538812800000),
                    y: [6619.43, 6620.35, 6610.05, 6615.53],
                },
                {
                    x: new Date(1538814600000),
                    y: [6615.53, 6617.93, 6610, 6615.19],
                },
                {
                    x: new Date(1538816400000),
                    y: [6615.19, 6621.6, 6608.2, 6620],
                },
                {
                    x: new Date(1538818200000),
                    y: [6619.54, 6625.17, 6614.15, 6620],
                },
                {
                    x: new Date(1538820000000),
                    y: [6620.33, 6634.15, 6617.24, 6624.61],
                },
                {
                    x: new Date(1538821800000),
                    y: [6625.95, 6626, 6611.66, 6617.58],
                },
                {
                    x: new Date(1538823600000),
                    y: [6619, 6625.97, 6595.27, 6598.86],
                },
                {
                    x: new Date(1538825400000),
                    y: [6598.86, 6598.88, 6570, 6587.16],
                },
                {
                    x: new Date(1538827200000),
                    y: [6588.86, 6600, 6580, 6593.4],
                },
            ],
        },
    ],
    chart: {
        type: "candlestick",
        height: 350,
        toolbar: {
            show: false,
        },
    },
    grid: {
        show: false,
    },
    title: {
        text: "CandleStick Chart",
        align: "left",
    },
    xaxis: {
        type: "datetime",
    },
    yaxis: {
        tooltip: {
            enabled: true,
        },
    },
};
var chart = new ApexCharts(document.querySelector("#chart7"), options7);
chart.render();

var options8 = {
    series: [44, 55, 41, 17, 15],
    chart: {
        type: "donut",
    },
    responsive: [
        {
            breakpoint: 480,
            options: {
                chart: {
                    width: 200,
                },
                legend: {
                    position: "bottom",
                },
            },
        },
    ],
};
var chart = new ApexCharts(document.querySelector("#chart8"), options8);
chart.render();

var options9 = {
    series: [76, 67, 61, 90],
    chart: {
        height: 390,
        type: "radialBar",
    },
    plotOptions: {
        radialBar: {
            offsetY: 0,
            startAngle: 0,
            endAngle: 270,
            hollow: {
                margin: 5,
                size: "40%",
                background: "transparent",
                image: undefined,
            },
            dataLabels: {
                name: {
                    show: false,
                },
                value: {
                    show: false,
                },
            },
        },
    },
    colors: ["#1ab7ea", "#0084ff", "#39539E", "#0077B5"],
    labels: ["Vimeo", "Messenger", "Facebook", "LinkedIn"],
    legend: {
        show: true,
        floating: true,
        fontSize: "14px",
        position: "left",
        offsetX: 40,
        offsetY: 15,
        labels: {
            useSeriesColors: true,
        },
        markers: {
            size: 0,
        },
        formatter: function (seriesName, opts) {
            return seriesName + ":  " + opts.w.globals.series[opts.seriesIndex];
        },
        itemMargin: {
            vertical: 3,
        },
    },
    responsive: [
        {
            breakpoint: 480,
            options: {
                legend: {
                    show: false,
                },
            },
        },
    ],
};
var chart = new ApexCharts(document.querySelector("#chart9"), options9);
chart.render();
