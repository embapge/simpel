<div id="{!! $chartId !!}"></div>

@script
    <script>
        $(document).ready(function() {
            let cardColor, headingColor, axisColor, shadeColor, borderColor;

            cardColor = "#fff";
            headingColor = "#566a7f";
            axisColor = "#a1acb8";
            borderColor = "#eceef1";
            // Income Chart - Area chart
            // --------------------------------------------------------------------
            const incomeChartEl = document.querySelector("#{!! $chartId !!}"),
                incomeChartConfig = {
                    series: [{
                        data: {!! $series !!},
                    }, ],
                    chart: {
                        height: 280,
                        parentHeightOffset: 0,
                        parentWidthOffset: 0,
                        toolbar: {
                            show: false,
                        },
                        type: "area",
                    },
                    dataLabels: {
                        enabled: false,
                    },
                    stroke: {
                        width: 2,
                        curve: "smooth",
                    },
                    legend: {
                        show: false,
                    },
                    markers: {
                        size: 6,
                        colors: "transparent",
                        strokeColors: "transparent",
                        strokeWidth: 4,
                        discrete: [{
                            fillColor: "fff",
                            seriesIndex: 0,
                            dataPointIndex: 7,
                            strokeColor: "#696cff",
                            strokeWidth: 2,
                            size: 6,
                            radius: 8,
                        }, ],
                        hover: {
                            size: 7,
                        },
                    },
                    colors: ["#696cff"],
                    fill: {
                        type: "gradient",
                        gradient: {
                            shade: shadeColor,
                            shadeIntensity: 0.6,
                            opacityFrom: 0.5,
                            opacityTo: 0.25,
                            stops: [0, 95, 100],
                        },
                    },
                    grid: {
                        borderColor: borderColor,
                        strokeDashArray: 3,
                        padding: {
                            top: -20,
                            bottom: -8,
                            left: -10,
                            right: 8,
                        },
                    },
                    xaxis: {
                        categories: {!! $categories !!},
                        axisBorder: {
                            show: false,
                        },
                        axisTicks: {
                            show: false,
                        },
                        labels: {
                            show: true,
                            style: {
                                fontSize: "13px",
                                colors: axisColor,
                            },
                        },
                    },
                    yaxis: {
                        labels: {
                            show: false,
                        },
                        min: 10,
                        max: 500,
                        tickAmount: 4,
                    },
                };
            if (typeof incomeChartEl !== undefined && incomeChartEl !== null) {
                const incomeChart = new ApexCharts(incomeChartEl, incomeChartConfig);
                incomeChart.render();
            }

            // Expenses Mini Chart - Radial Chart
            // --------------------------------------------------------------------
            const weeklyExpensesEl = document.querySelector("#expensesOfWeek1"),
                weeklyExpensesConfig = {
                    series: [65],
                    chart: {
                        width: 60,
                        height: 60,
                        type: "radialBar",
                    },
                    plotOptions: {
                        radialBar: {
                            startAngle: 0,
                            endAngle: 360,
                            strokeWidth: "8",
                            hollow: {
                                margin: 2,
                                size: "45%",
                            },
                            track: {
                                strokeWidth: "50%",
                                background: borderColor,
                            },
                            dataLabels: {
                                show: true,
                                name: {
                                    show: false,
                                },
                                value: {
                                    formatter: function(val) {
                                        return "$" + parseInt(val);
                                    },
                                    offsetY: 5,
                                    color: "#697a8d",
                                    fontSize: "13px",
                                    show: true,
                                },
                            },
                        },
                    },
                    fill: {
                        type: "solid",
                        colors: "#696cff",
                    },
                    stroke: {
                        lineCap: "round",
                    },
                    grid: {
                        padding: {
                            top: -10,
                            bottom: -15,
                            left: -10,
                            right: -10,
                        },
                    },
                    states: {
                        hover: {
                            filter: {
                                type: "none",
                            },
                        },
                        active: {
                            filter: {
                                type: "none",
                            },
                        },
                    },
                };
            if (typeof weeklyExpensesEl !== undefined && weeklyExpensesEl !== null) {
                const weeklyExpenses = new ApexCharts(
                    weeklyExpensesEl,
                    weeklyExpensesConfig
                );
                weeklyExpenses.render();
            }
        });
    </script>
@endscript
