function chart1() {
    var ctx = document.getElementById("intervalChart").getContext("2d");
    var chart = new Chart(ctx, {
        type: "line",
        data: {
            labels: [],
            datasets: [
                {
                    label: "CPM",
                    data: [],
                    backgroundColor: "rgba(75, 192, 192, 0.2)",
                    borderColor: "rgba(75, 192, 192, 1)",
                    borderWidth: 1,
                    fill: "start",
                },
            ],
        },
        options: {
            animation: {
                duration: 0,
            },
            tooltips: {
                intersect: false,
                backgroundColor: "rgba(113, 88, 203, 1)",
                titleFontSize: 16,
                titleFontStyle: "400",
                titleSpacing: 4,
                titleMarginBottom: 8,
                bodyFontSize: 12,
                bodyFontStyle: "400",
                bodySpacing: 4,
                xPadding: 8,
                yPadding: 8,
                cornerRadius: 4,
                displayColors: false,
                callbacks: {
                    title: function (t, d) {
                        const o = d.datasets.map(
                            (ds) => ds.data[t[0].index] + "%"
                        );

                        return o.join(", ");
                    },
                    label: function (t, d) {
                        return d.labels[t.index];
                    },
                },
            },
            title: {
                text: "Sensor Data",
                display: true,
            },
            maintainAspectRatio: true,
            spanGaps: false,
            elements: {
                line: {
                    tension: 0.3,
                },
            },
            plugins: {
                filler: {
                    propagate: false,
                },
            },
            scales: {
                xAxes: [
                    {
                        scaleLabel: {
                            display: true,
                            labelString: "Waktu",
                        },
                        ticks: {
                            autoSkip: false,
                            maxRotation: 0,
                        },
                    },
                ],
            },
        },
    });
    function formatTime(timestamp) {
        var date = new Date(timestamp);
        var hours = date.getHours();
        var minutes = "0" + date.getMinutes();
        var seconds = "0" + date.getSeconds();
        return hours + ":" + minutes.substr(-2) + ":" + seconds.substr(-2);
    }

    function fetchIntervalData() {
        $.ajax({
            url: "/chart/data",
            method: "GET",
            success: function (data) {
                var slicedData = data.slice(0, 20).reverse();
                chart.data.labels = slicedData.map((sensor) =>
                    formatTime(sensor.waktu)
                );
                chart.data.datasets[0].data = slicedData.map(
                    (sensor) => sensor.cpm
                );
                chart.update({
                    duration: 0,
                    lazy: false,
                });
            },
        });
    }

    fetchIntervalData();
    setInterval(fetchIntervalData, 1000);
}

// function chart2() {
//     var ctx = document.getElementById("myChart").getContext("2d");
//     var chart = new Chart(ctx, {
//         type: "line",
//         data: {
//             labels: [],
//             datasets: [
//                 {
//                     label: "Rata- Rata CPM",
//                     data: [],
//                     backgroundColor: "rgba(113, 88, 203, .15)",
//                     borderColor: "rgba(113, 88, 203, 1)",
//                     borderWidth: 1,
//                     fill: "start",
//                 },
//                 {
//                     label: "Rata - Rata Suhu",
//                     data: [],
//                     backgroundColor: "rgba(161, 201, 249, .15)",
//                     borderColor: "rgba(161, 201, 249, 1)",
//                     borderWidth: 1,
//                     fill: "start",
//                 },
//             ],
//         },
//         options: {
//             animation: {
//                 duration: 0,
//             },
//             tooltips: {
//                 intersect: false,
//                 backgroundColor: "rgba(113, 88, 203, 1)",
//                 titleFontSize: 16,
//                 titleFontStyle: "400",
//                 titleSpacing: 4,
//                 titleMarginBottom: 8,
//                 bodyFontSize: 12,
//                 bodyFontStyle: "400",
//                 bodySpacing: 4,
//                 xPadding: 8,
//                 yPadding: 8,
//                 cornerRadius: 4,
//                 displayColors: false,
//                 callbacks: {
//                     title: function (t, d) {
//                         const o = d.datasets.map(
//                             (ds) => ds.data[t[0].index] + "%"
//                         );

//                         return o.join(", ");
//                     },
//                     label: function (t, d) {
//                         return d.labels[t.index];
//                     },
//                 },
//             },
//             title: {
//                 text: "Sensor Data",
//                 display: true,
//             },
//             maintainAspectRatio: true,
//             spanGaps: false,
//             elements: {
//                 line: {
//                     tension: 0.3,
//                 },
//             },
//             plugins: {
//                 filler: {
//                     propagate: false,
//                 },
//             },
//             scales: {
//                 xAxes: [
//                     {
//                         scaleLabel: {
//                             display: true,
//                             labelString: "Waktu",
//                         },
//                         ticks: {
//                             autoSkip: false,
//                             maxRotation: 0,
//                         },
//                     },
//                 ],
//                 yAxes: [
//                     {
//                         scaleLabel: {
//                             display: true,
//                             labelString: "Value",
//                         },
//                     },
//                 ],
//             },
//         },
//     });

//     function fetchAvgCondition() {
//         $.ajax({
//             url: "/chart/sensor",
//             method: "GET",
//             success: function (data) {
//                 var latestData = data.slice(-20);

//                 var labels = [];
//                 var cpmData = [];
//                 var temperatureData = [];

//                 latestData.forEach(function (item) {
//                     labels.push(item.waktu);
//                     cpmData.push(item.average_cpm);
//                     temperatureData.push(item.average_temperature);
//                 });

//                 chart.data.labels = labels;
//                 chart.data.datasets[0].data = cpmData;
//                 chart.data.datasets[1].data = temperatureData;
//                 chart.update({
//                     duration: 0,
//                     lazy: false,
//                 });
//             },
//         });
//     }

//     fetchAvgCondition();
//     setInterval(fetchAvgCondition, 60000);
// }

function chart2() {
    var ctx = document.getElementById("myChart").getContext("2d");
    var chart = new Chart(ctx, {
        type: "bar", // Mengubah tipe grafik menjadi bar chart
        data: {
            labels: [],
            datasets: [
                {
                    label: "Rata- Rata CPM",
                    data: [],
                    backgroundColor: "rgba(113, 88, 203, .15)",
                    borderColor: "rgba(113, 88, 203, 1)",
                    borderWidth: 1,
                    fill: "start",
                },
                {
                    label: "Rata - Rata Suhu",
                    data: [],
                    backgroundColor: "rgba(161, 201, 249, .15)",
                    borderColor: "rgba(161, 201, 249, 1)",
                    borderWidth: 1,
                    fill: "start",
                },
            ],
        },
        options: {
            animation: {
                duration: 0,
            },
            tooltips: {
                intersect: false,
                backgroundColor: "rgba(113, 88, 203, 1)",
                titleFontSize: 16,
                titleFontStyle: "400",
                titleSpacing: 4,
                titleMarginBottom: 8,
                bodyFontSize: 12,
                bodyFontStyle: "400",
                bodySpacing: 4,
                xPadding: 8,
                yPadding: 8,
                cornerRadius: 4,
                displayColors: false,
                callbacks: {
                    title: function (t, d) {
                        const o = d.datasets.map(
                            (ds) => ds.data[t[0].index] + "%"
                        );

                        return o.join(", ");
                    },
                    label: function (t, d) {
                        return d.labels[t.index];
                    },
                },
            },
            title: {
                text: "Sensor Data",
                display: true,
            },
            maintainAspectRatio: true,
            spanGaps: false,
            elements: {
                line: {
                    tension: 0.3,
                },
            },
            plugins: {
                filler: {
                    propagate: false,
                },
            },
            scales: {
                xAxes: [
                    {
                        scaleLabel: {
                            display: true,
                            labelString: "Waktu",
                        },
                        ticks: {
                            autoSkip: false,
                            maxRotation: 0,
                        },
                    },
                ],
                yAxes: [
                    {
                        scaleLabel: {
                            display: true,
                            labelString: "Value",
                        },
                    },
                ],
            },
        },
    });

    function fetchAvgCondition() {
        $.ajax({
            url: "/chart/sensor",
            method: "GET",
            success: function (data) {
                var latestData = data.slice(-20);

                var labels = [];
                var cpmData = [];
                var temperatureData = [];

                latestData.forEach(function (item) {
                    labels.push(item.waktu);
                    cpmData.push(item.average_cpm);
                    temperatureData.push(item.average_temperature);
                });

                chart.data.labels = labels;
                chart.data.datasets[0].data = cpmData;
                chart.data.datasets[1].data = temperatureData;
                chart.update({
                    duration: 0,
                    lazy: false,
                });
            },
        });
    }

    fetchAvgCondition();
    setInterval(fetchAvgCondition, 60000);
}

function fetchLatestData() {
    function fetchData() {
        $.ajax({
            url: "/chart/latestData",
            method: "GET",
            success: function (data) {
                $("#latestCpm").text(data.cpm + " CPM");

                var radiationLevel = "";
                if (data.cpm <= 5) {
                    radiationLevel = "Rendah";
                } else if (data.cpm >= 10) {
                    radiationLevel = "Tinggi";
                    var alertHTML = `
                        <div class="alert alert-warning solid alert-right-icon alert-dismissible fade show">
                            <span><i class="mdi mdi-alert"></i></span>
                            <button type="button" class="close h-100" data-dismiss="alert" aria-label="Close">
                                <span><i class="mdi mdi-close"></i></span>
                            </button>
                            <strong>Peringatan!</strong> Tingkat radiasi tinggi di sekitar.
                        </div>
                    `;
                    $("#alert-container").html(alertHTML);
                } else {
                    radiationLevel = "Sedang";
                }

                $("#radiationLevel").text(radiationLevel);
                $("#latestTemp").text(data.temp + " °C");
                $("#latestHumidity").text(data.humidity + " %");
            },
        });
    }

    fetchData();
    setInterval(fetchData, 500);
}

function latestData() {
    function fetchData() {
        $.ajax({
            url: "/chart/latestData",
            method: "GET",
            success: function (data) {
                $("#latestCpm").text(data.cpm + " CPM");

                var radiationLevel = "";
                if (data.cpm <= 5) {
                    radiationLevel = "Rendah";
                } else if (data.cpm >= 10) {
                    radiationLevel = "Tinggi";
                } else {
                    radiationLevel = "Sedang";
                }

                $("#radiationLevel").text(radiationLevel);
                $("#latestTemp").text(data.temp + " °C");
                $("#latestHumidity").text(data.humidity + " %");
            },
        });
    }

    fetchData();

    setInterval(fetchData, 500);
}

latestData();

chart1();
chart2();
