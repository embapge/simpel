<div id="{!! $chartId !!}"></div>

@script
    <script>
        $(document).ready(function() {
            console.log({!! $series !!});
            console.log({!! $label !!});
            let cardColor, headingColor, axisColor, shadeColor, borderColor;

            cardColor = "#fff";
            headingColor = "#566a7f";
            axisColor = "#a1acb8";
            borderColor = "#eceef1";

            let config = {
                colors: {
                    primary: '#696cff',
                    secondary: '#8592a3',
                    success: '#71dd37',
                    info: '#03c3ec',
                    warning: '#ffab00',
                    danger: '#ff3e1d',
                    dark: '#233446',
                    black: '#000',
                    white: '#fff',
                    body: '#f4f5fb',
                    headingColor: '#566a7f',
                    axisColor: '#a1acb8',
                    borderColor: '#eceef1'
                }
            };

            const chartOrderStatistics = document.querySelector(
                    "#{!! $chartId !!}"
                ),
                orderChartConfig = {
                    chart: {
                        height: 165,
                        width: 130,
                        type: "donut",
                    },
                    labels: {!! $label !!},
                    series: {!! $series !!},
                    colors: [
                        config.colors.primary,
                        config.colors.secondary,
                        config.colors.info,
                        config.colors.success,
                    ],
                    // stroke: {
                    //     width: 5,
                    //     colors: cardColor,
                    // },
                    dataLabels: {
                        enabled: false,
                        formatter: function(val, opt) {
                            return parseInt(val);
                        },
                    },
                    legend: {
                        show: false,
                    },
                    grid: {
                        padding: {
                            top: 0,
                            bottom: 0,
                            right: 15,
                        },
                    },
                    plotOptions: {
                        pie: {
                            donut: {
                                size: "75%",
                                labels: {
                                    show: true,
                                    value: {
                                        fontSize: "1.5rem",
                                        fontFamily: "Public Sans",
                                        color: headingColor,
                                        offsetY: -15,
                                        formatter: function(val) {
                                            return parseInt(val);
                                        },
                                    },
                                    name: {
                                        offsetY: 20,
                                        fontFamily: "Public Sans",
                                    },
                                    total: {
                                        show: true,
                                        fontSize: "0.8125rem",
                                        color: axisColor,
                                        label: "Total",
                                        formatter: function(w) {
                                            return w.config.series.reduce((total, series) => total +
                                                series, 0);
                                        },
                                    },
                                },
                            },
                        },
                    },
                };
            if (
                typeof chartOrderStatistics !== undefined &&
                chartOrderStatistics !== null
            ) {
                const statisticsChart = new ApexCharts(
                    chartOrderStatistics,
                    orderChartConfig
                );
                statisticsChart.render();
            }
        });
    </script>
@endscript
