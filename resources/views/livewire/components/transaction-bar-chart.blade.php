{{-- @dd($data) --}}
<div id="{{ $chartId }}" class="px-2"></div>

@script
    <script>
        $(document).ready(function() {
            console.log("masukkkk");
            // console.log({{ $label }});
            let cardColor, headingColor, axisColor, shadeColor, borderColor;

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

            cardColor = config.colors.white;
            headingColor = config.colors.headingColor;
            axisColor = config
                .colors.axisColor;
            borderColor = config.colors.borderColor;

            // Total Revenue Report Chart - Bar Chart
            // --------------------------------------------------------------------
            const totalRevenueChartEl = document.querySelector("#{{ $chartId }}"),
                totalRevenueChartOptions = {
                    series: {!! $data !!},
                    chart: {
                        height: 560,
                        stacked: false,
                        type: "bar",
                        toolbar: {
                            show: false
                        },
                    },
                    plotOptions: {
                        bar: {
                            horizontal: false,
                            columnWidth: "60%",
                            borderRadius: 10,
                            startingShape: "rounded",
                            endingShape: "rounded",
                        },
                    },
                    colors: [
                        config.colors.secondary,
                        config.colors.danger,
                        config.colors.success,
                        config.colors.warning,
                    ],
                    dataLabels: {
                        enabled: false,
                    },
                    stroke: {
                        curve: "smooth",
                        width: 3,
                        lineCap: "round",
                        colors: [cardColor],
                    },
                    legend: {
                        show: true,
                        horizontalAlign: "left",
                        position: "top",
                        markers: {
                            height: 8,
                            width: 8,
                            radius: 12,
                            offsetX: -3,
                        },
                        labels: {
                            colors: axisColor,
                        },
                        itemMargin: {
                            horizontal: 10,
                        },
                    },
                    grid: {
                        borderColor: borderColor,
                        padding: {
                            top: 0,
                            bottom: -8,
                            left: 20,
                            right: 20,
                        },
                    },
                    xaxis: {
                        categories: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul"],
                        labels: {
                            style: {
                                fontSize: "13px",
                                colors: axisColor,
                            },
                        },
                        axisTicks: {
                            show: false,
                        },
                        axisBorder: {
                            show: false,
                        },
                    },
                    yaxis: {
                        labels: {
                            style: {
                                fontSize: "13px",
                                colors: axisColor,
                            },
                            formatter: function(val) {
                                return new Intl.NumberFormat("id-ID", {
                                    style: "currency",
                                    currency: "IDR",
                                    minimumFractionDigits: 0,
                                }).format(val)
                            }
                        },
                    },
                    tooltip: {
                        y: {
                            formatter: function(val) {
                                return new Intl.NumberFormat("id-ID", {
                                    style: "currency",
                                    currency: "IDR",
                                    minimumFractionDigits: 0,
                                }).format(val)
                            }
                        }
                    },
                    responsive: [{
                            breakpoint: 1700,
                            options: {
                                plotOptions: {
                                    bar: {
                                        borderRadius: 10,
                                        columnWidth: "32%",
                                    },
                                },
                            },
                        },
                        {
                            breakpoint: 1580,
                            options: {
                                plotOptions: {
                                    bar: {
                                        borderRadius: 10,
                                        columnWidth: "35%",
                                    },
                                },
                            },
                        },
                        {
                            breakpoint: 1440,
                            options: {
                                plotOptions: {
                                    bar: {
                                        borderRadius: 10,
                                        columnWidth: "42%",
                                    },
                                },
                            },
                        },
                        {
                            breakpoint: 1300,
                            options: {
                                plotOptions: {
                                    bar: {
                                        borderRadius: 10,
                                        columnWidth: "48%",
                                    },
                                },
                            },
                        },
                        {
                            breakpoint: 1200,
                            options: {
                                plotOptions: {
                                    bar: {
                                        borderRadius: 10,
                                        columnWidth: "40%",
                                    },
                                },
                            },
                        },
                        {
                            breakpoint: 1040,
                            options: {
                                plotOptions: {
                                    bar: {
                                        borderRadius: 11,
                                        columnWidth: "48%",
                                    },
                                },
                            },
                        },
                        {
                            breakpoint: 991,
                            options: {
                                plotOptions: {
                                    bar: {
                                        borderRadius: 10,
                                        columnWidth: "30%",
                                    },
                                },
                            },
                        },
                        {
                            breakpoint: 840,
                            options: {
                                plotOptions: {
                                    bar: {
                                        borderRadius: 10,
                                        columnWidth: "35%",
                                    },
                                },
                            },
                        },
                        {
                            breakpoint: 768,
                            options: {
                                plotOptions: {
                                    bar: {
                                        borderRadius: 10,
                                        columnWidth: "28%",
                                    },
                                },
                            },
                        },
                        {
                            breakpoint: 640,
                            options: {
                                plotOptions: {
                                    bar: {
                                        borderRadius: 10,
                                        columnWidth: "32%",
                                    },
                                },
                            },
                        },
                        {
                            breakpoint: 576,
                            options: {
                                plotOptions: {
                                    bar: {
                                        borderRadius: 10,
                                        columnWidth: "37%",
                                    },
                                },
                            },
                        },
                        {
                            breakpoint: 480,
                            options: {
                                plotOptions: {
                                    bar: {
                                        borderRadius: 10,
                                        columnWidth: "45%",
                                    },
                                },
                            },
                        },
                        {
                            breakpoint: 420,
                            options: {
                                plotOptions: {
                                    bar: {
                                        borderRadius: 10,
                                        columnWidth: "52%",
                                    },
                                },
                            },
                        },
                        {
                            breakpoint: 380,
                            options: {
                                plotOptions: {
                                    bar: {
                                        borderRadius: 10,
                                        columnWidth: "60%",
                                    },
                                },
                            },
                        },
                    ],
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
            if (
                typeof totalRevenueChartEl !== undefined &&
                totalRevenueChartEl !== null
            ) {
                const totalRevenueChart = new ApexCharts(
                    totalRevenueChartEl,
                    totalRevenueChartOptions
                );
                totalRevenueChart.render();
            }
        });
    </script>
@endscript
