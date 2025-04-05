// Fetch My Performance
fetch("/getMyPerformance")
    .then((response) => response.json())
    .then((data) => {
        if (data.success && data.data.length > 0) {
            let supplier = data.data[0]; // Get the first supplier's data

            // Convert decimal percentages to whole numbers
            let onTimeDeliveryRate =
                supplier.on_time_delivery_rate <= 1
                    ? Math.round(supplier.on_time_delivery_rate * 100)
                    : Math.round(supplier.on_time_delivery_rate);
            let churnRisk =
                supplier.churn_risk <= 1
                    ? Math.round(supplier.churn_risk * 100)
                    : Math.round(supplier.churn_risk);
            let overallQualityMetrics =
                supplier.overall_quality_metrics <= 1
                    ? Math.round(supplier.overall_quality_metrics * 100)
                    : Math.round(supplier.overall_quality_metrics);
            let responseTime =
                supplier.response_time <= 1
                    ? Math.round(supplier.return_rate * 100)
                    : Math.round(supplier.return_rate);

            let overallRating =
                supplier.overall_rating <= 1
                    ? Math.round(supplier.overall_rating * 20) // Convert 5-star scale to percentage
                    : Math.round(supplier.overall_rating * 20);

            // Function to get rating based on thresholds
            function getRating(value, thresholds, labels) {
                for (let i = 0; i < thresholds.length; i++) {
                    if (value >= thresholds[i]) return labels[i];
                }
                return labels[labels.length - 1]; // Default to last label if no match
            }

            // Define rating criteria
            const ratingCriteria = {
                onTimeDeliveryRate: {
                    thresholds: [95, 90, 80],
                    labels: [
                        "EXCELLENT",
                        "VERY GOOD",
                        "GOOD",
                        "NEEDS IMPROVEMENT",
                    ],
                },
                churnRisk: {
                    thresholds: [10, 25, 50, 75],
                    labels: [
                        "VERY LOW RISK",
                        "LOW RISK",
                        "MODERATE RISK",
                        "HIGH RISK",
                        "CRITICAL RISK",
                    ],
                },
                overallQualityMetrics: {
                    thresholds: [90, 80, 70, 60],
                    labels: [
                        "EXCELLENT",
                        "VERY GOOD",
                        "GOOD",
                        "FAIR",
                        "NEEDS IMPROVEMENT",
                    ],
                },
                responseTime: {
                    thresholds: [1, 3, 6, 12, 24],
                    labels: [
                        "EXCELLENT",
                        "GOOD",
                        "AVERAGE",
                        "POOR",
                        "VERY POOR",
                    ],
                },
            };

            // Update UI elements
            document.getElementById("performanceRating").innerText = getRating(
                onTimeDeliveryRate,
                ratingCriteria.onTimeDeliveryRate.thresholds,
                ratingCriteria.onTimeDeliveryRate.labels
            );
            document.getElementById("churnRate").innerText = getRating(
                churnRisk,
                ratingCriteria.churnRisk.thresholds,
                ratingCriteria.churnRisk.labels
            );
            document.getElementById("qualityRate").innerText = getRating(
                overallQualityMetrics,
                ratingCriteria.overallQualityMetrics.thresholds,
                ratingCriteria.overallQualityMetrics.labels
            );
            document.getElementById("responseTime").innerText = getRating(
                responseTime,
                ratingCriteria.responseTime.thresholds,
                ratingCriteria.responseTime.labels
            );

            // Chart Configurations
            var options = {
                series: [onTimeDeliveryRate],
                chart: { height: 100, width: 70, type: "radialBar" },
                grid: { padding: { top: 0, right: 0, bottom: 0, left: 0 } },
                plotOptions: {
                    radialBar: {
                        hollow: { size: "50%" },
                        dataLabels: {
                            name: { show: false, color: "#fff" },
                            value: {
                                show: true,
                                color: "#333",
                                offsetY: 5,
                                fontSize: "15px",
                            },
                        },
                    },
                },
                colors: ["#ecf0f4"],
                fill: {
                    type: "gradient",
                    gradient: {
                        shade: "dark",
                        type: "diagonal1",
                        shadeIntensity: 0.8,
                        gradientToColors: ["#1b00ff"],
                        inverseColors: false,
                        opacityFrom: [1, 0.2],
                        opacityTo: 1,
                        stops: [0, 100],
                    },
                },
            };

            var options2 = {
                series: [churnRisk],
                chart: { height: 100, width: 70, type: "radialBar" },
                grid: { padding: { top: 0, right: 0, bottom: 0, left: 0 } },
                plotOptions: {
                    radialBar: {
                        hollow: { size: "50%" },
                        dataLabels: {
                            name: { show: false, color: "#fff" },
                            value: {
                                show: true,
                                color: "#333",
                                offsetY: 5,
                                fontSize: "15px",
                            },
                        },
                    },
                },
                colors: ["#ecf0f4"],
                fill: {
                    type: "gradient",
                    gradient: {
                        shade: "dark",
                        type: "diagonal1",
                        shadeIntensity: 1,
                        gradientToColors: ["#009688"],
                        inverseColors: false,
                        opacityFrom: [1, 0.2],
                        opacityTo: 1,
                        stops: [0, 100],
                    },
                },
            };

            var options3 = {
                series: [overallQualityMetrics],
                chart: { height: 100, width: 70, type: "radialBar" },
                grid: { padding: { top: 0, right: 0, bottom: 0, left: 0 } },
                plotOptions: {
                    radialBar: {
                        hollow: { size: "50%" },
                        dataLabels: {
                            name: { show: false, color: "#fff" },
                            value: {
                                show: true,
                                color: "#333",
                                offsetY: 5,
                                fontSize: "15px",
                            },
                        },
                    },
                },
                colors: ["#ecf0f4"],
                fill: {
                    type: "gradient",
                    gradient: {
                        shade: "dark",
                        type: "diagonal1",
                        shadeIntensity: 0.8,
                        gradientToColors: ["#f56767"],
                        inverseColors: false,
                        opacityFrom: [1, 0.2],
                        opacityTo: 1,
                        stops: [0, 100],
                    },
                },
            };

            var options4 = {
                series: [responseTime],
                chart: { height: 100, width: 70, type: "radialBar" },
                grid: { padding: { top: 0, right: 0, bottom: 0, left: 0 } },
                plotOptions: {
                    radialBar: {
                        hollow: { size: "50%" },
                        dataLabels: {
                            name: { show: false, color: "#fff" },
                            value: {
                                show: true,
                                color: "#333",
                                offsetY: 5,
                                fontSize: "15px",
                            },
                        },
                    },
                },
                colors: ["#ecf0f4"],
                fill: {
                    type: "gradient",
                    gradient: {
                        shade: "dark",
                        type: "diagonal1",
                        shadeIntensity: 0.8,
                        gradientToColors: ["#2979ff"],
                        inverseColors: false,
                        opacityFrom: [1, 0.5],
                        opacityTo: 1,
                        stops: [0, 100],
                    },
                },
            };

            var options5 = {
                series: [overallRating], // This will be updated dynamically for Overall Rating
                chart: {
                    height: 350,
                    type: "radialBar",
                    offsetY: 0,
                },
                colors: ["#0B132B", "#222222"],
                plotOptions: {
                    radialBar: {
                        startAngle: -135,
                        endAngle: 135,
                        dataLabels: {
                            name: {
                                fontSize: "16px",
                                color: undefined,
                                offsetY: 120,
                            },
                            value: {
                                offsetY: 76,
                                fontSize: "22px",
                                color: undefined,
                                formatter: function (val) {
                                    return val + "%";
                                },
                            },
                        },
                    },
                },
                fill: {
                    type: "gradient",
                    gradient: {
                        shade: "dark",
                        shadeIntensity: 0.15,
                        inverseColors: false,
                        opacityFrom: 1,
                        opacityTo: 1,
                        stops: [0, 50, 65, 91],
                    },
                },
                stroke: {
                    dashArray: 4,
                },
                labels: ["Overall Rating"],
            };

            // Render Charts
            new ApexCharts(document.querySelector("#chart"), options).render();
            new ApexCharts(
                document.querySelector("#chart2"),
                options2
            ).render();
            new ApexCharts(
                document.querySelector("#chart3"),
                options3
            ).render();
            new ApexCharts(
                document.querySelector("#chart4"),
                options4
            ).render();
            new ApexCharts(
                document.querySelector("#chart5"),
                options5
            ).render();
        } else {
            console.warn("No data available for supplier performance.");
        }
    })
    .catch((error) =>
        console.error("Error fetching supplier performance:", error)
    );

fetch("/getTopSuppliers")
    .then((response) => response.json())
    .then((data) => {
        if (data.success && data.data.length > 0) {
            let suppliers = data.data;

            // Sort suppliers by overall_rating (descending) for better visualization
            suppliers.sort((a, b) => b.overall_rating - a.overall_rating);

            // Extract company names and overall ratings
            let companyNames = suppliers.map(
                (supplier) => supplier.company_name
            );
            let overallRatings = suppliers.map(
                (supplier) => supplier.overall_rating
            );

            // Generate distinct colors for each company
            const colors = [
                "#4CAF50", // Green
                "#FF9800", // Orange
                "#E91E63", // Pink
                "#2196F3", // Blue
                "#9C27B0", // Purple
                "#FFEB3B", // Yellow
                "#00BCD4", // Cyan
                "#F44336", // Red
                "#673AB7", // Deep Purple
                "#3F51B5", // Indigo
                "#8BC34A", // Light Green
                "#795548", // Brown
            ];

            var options6 = {
                chart: {
                    height: 400,
                    type: "bar",
                    parentHeightOffset: 0,
                    fontFamily: "Poppins, sans-serif",
                    toolbar: { show: false },
                },
                colors: colors, // ✅ Apply multiple colors
                grid: {
                    borderColor: "#c7d2dd",
                    strokeDashArray: 5,
                },
                plotOptions: {
                    bar: {
                        vertical: true, // ✅ Horizontal Bar Chart for Ranking
                        columnWidth: "10%",
                        endingShape: "rounded",
                        distributed: true, // ✅ Assign different colors per bar
                    },
                },
                dataLabels: { enabled: false },
                stroke: {
                    show: true,
                    width: 2,
                    colors: ["transparent"],
                },
                series: [
                    {
                        name: "Overall Rating",
                        data: overallRatings, // ✅ Only shows overall rating
                    },
                ],
                xaxis: {
                    categories: companyNames, // ✅ Display company names
                    labels: {
                        style: {
                            colors: ["#353535"],
                            fontSize: "14px",
                        },
                    },
                    axisBorder: { color: "#8fa6bc" },
                },
                yaxis: {
                    title: { text: "Top Suppliers" },
                    labels: {
                        style: {
                            colors: "#353535",
                            fontSize: "14px",
                        },
                    },
                    axisBorder: { color: "#f00" },
                },
                legend: {
                    horizontalAlign: "right",
                    position: "top",
                    fontSize: "14px",
                    offsetY: 0,
                    labels: { colors: "#353535" },
                    markers: { width: 10, height: 10, radius: 15 },
                    itemMargin: { vertical: 0 },
                },
                fill: { opacity: 1 },
                tooltip: {
                    style: {
                        fontSize: "14px",
                        fontFamily: "Poppins, sans-serif",
                    },
                    y: {
                        formatter: function (val) {
                            return val.toFixed(1) + " ★"; // ✅ Show rating with a star
                        },
                    },
                },
            };

            // Render chart
            var chart6 = new ApexCharts(
                document.querySelector("#chart6"),
                options6
            );
            chart6.render();
        } else {
            console.warn("No data available for top suppliers.");
        }
    })
    .catch((error) => console.error("Error fetching top suppliers:", error));

// datatable init
$("document").ready(function () {
    $(".data-table").DataTable({
        scrollCollapse: true,
        autoWidth: true,
        responsive: true,
        searching: false,
        bLengthChange: false,
        bPaginate: false,
        bInfo: false,
        columnDefs: [
            {
                targets: "datatable-nosort",
                orderable: false,
            },
        ],
        lengthMenu: [
            [10, 25, 50, -1],
            [10, 25, 50, "All"],
        ],
        language: {
            info: "_START_-_END_ of _TOTAL_ entries",
            searchPlaceholder: "Search",
            paginate: {
                next: '<i class="ion-chevron-right"></i>',
                previous: '<i class="ion-chevron-left"></i>',
            },
        },
    });
});
