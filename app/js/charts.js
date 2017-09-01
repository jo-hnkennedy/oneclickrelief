(function($) {
    "use strict"; // Start of use strict

    //--------------
    //- FLEX CHART -
    //--------------
    // Get context with jQuery - using jQuery's .get() method.
    if ($(".flexChart").length > 0) {
        //look in each piechart
        $(".flexChart").each(function() {
            //set data type
            var chartType = $(this).data('type') || 'area'; //line, area, bar, radar
            var chartData = $(this).data('data') || false;
            var chartTitle = $(this).data('title') || false;
            var chartTooltip= $(this).data('tooltip') || false;
            var chartScales= $(this).data('scales') || false;
            var chartHover= $(this).data('hover') || false;
            var chartOptions = $(this).data('options') || false;
            // Get context with jQuery - using jQuery's .get() method.
            var flexChartCanvas = $(this).get(0).getContext("2d");
            // This will get the first returned node in the jQuery collection.
            var flexChart = new Chart(flexChartCanvas);
            var flexChartData = chartData ? chartData : {
                labels: ["January", "February", "March", "April", "May", "June", "July"],
                datasets: [{
                    label: "Electronics",
                    fillColor: "rgba(210, 214, 222, 1)",
                    strokeColor: "rgba(210, 214, 222, 1)",
                    pointColor: "rgba(210, 214, 222, 1)",
                    pointStrokeColor: "#c1c7d1",
                    pointHighlightFill: "#fff",
                    pointHighlightStroke: "rgba(220,220,220,1)",
                    data: [65, 59, 80, 81, 56, 55, 40]
                }, {
                    label: "Digital Goods",
                    fillColor: "rgba(60,141,188,0.9)",
                    strokeColor: "rgba(60,141,188,0.8)",
                    pointColor: "#3b8bba",
                    pointStrokeColor: "rgba(60,141,188,1)",
                    pointHighlightFill: "#fff",
                    pointHighlightStroke: "rgba(60,141,188,1)",
                    data: [28, 48, 40, 19, 86, 27, 90]
                }]
            };

            var flexChartOptions = {
                //Boolean - If we should show the scale at all
                showScale: true,
                //Boolean - Whether grid lines are shown across the chart
                scaleShowGridLines: false,
                //String - Colour of the grid lines
                scaleGridLineColor: "rgba(0,0,0,.05)",
                //Number - Width of the grid lines
                scaleGridLineWidth: 1,
                //Boolean - Whether to show horizontal lines (except X axis)
                scaleShowHorizontalLines: true,
                //Boolean - Whether to show vertical lines (except Y axis)
                scaleShowVerticalLines: true,
                //Boolean - Whether the line is curved between points
                bezierCurve: true,
                //Number - Tension of the bezier curve between points
                bezierCurveTension: 0.3,
                //Boolean - Whether to show a dot for each point
                pointDot: false,
                //Number - Radius of each point dot in pixels
                pointDotRadius: 8,
                //Number - Pixel width of point dot stroke
                pointDotStrokeWidth: 2,
                //Number - amount extra to add to the radius to cater for hit detection outside the drawn point
                pointHitDetectionRadius: 20,
                //Boolean - Whether to show a stroke for datasets
                datasetStroke: true,
                //Number - Pixel width of dataset stroke
                datasetStrokeWidth: 2,
                //Boolean - Whether to fill the dataset with a color
                datasetFill: true,
                //String - A legend template
                legendTemplate: "<ul class=\"<%=name.toLowerCase()%>-legend\"><% for (var i=0; i<datasets.length; i++){%><li><span style=\"background-color:<%=datasets[i].lineColor%>\"></span><%if(datasets[i].label){%><%=datasets[i].label%><%}%></li><%}%></ul>",
                //Boolean - whether to maintain the starting aspect ratio or not when responsive, if set to false, will take up entire container
                maintainAspectRatio: true,
                //Boolean - whether to make the chart responsive to window resizing
                responsive: true
            };

            //check for chart title
            if( chartHover ) {
                flexChartOptions.hover = {
                    mode: chartHover,
                    intersect: true
                };
            }
            if( chartScales ) {
                flexChartOptions.scales = {
                    xAxes: [{
                        display: true,
                        scaleLabel: {
                            display: true,
                            labelString: chartScales[0]
                        }
                    }],
                    yAxes: [{
                        display: true,
                        scaleLabel: {
                            display: true,
                            labelString: chartScales[1]
                        },
                        ticks: {
                            min: 0,
                            max: 100,
                            stepSize: 5
                        }
                    }]
                };
            }
            //check for chart title
            if( chartTitle ) {
                flexChartOptions.title = {
                    display:true,
                    text:chartTitle
                };
            }
            if( chartTooltip ) {
                flexChartOptions.tooltips = {
                    mode: chartTooltip,
                    intersect: false
                };
            }

            //--------------
            //- LINE CHART -
            //--------------
            if( chartType == 'line' ) {
                //set data fill
                flexChartOptions.datasetFill = false;
                flexChartOptions.scaleShowGridLines = true;
                flexChartOptions.pointDot = true;
                //set line chart
                flexChart.Line(flexChartData, flexChartOptions);
            }

            //--------------
            //- AREA CHART -
            //--------------
            if( chartType == 'area') {
                flexChartOptions.pointDot = true;
                flexChart.Line(flexChartData, flexChartOptions);
            }


            //---------------
            //- RADAR CHART -
            //---------------
            if( chartType == 'radar') {
                flexChart.Line(flexChartData, flexChartOptions);
            }
            //------------------------
            //- PIE / DOUGHNUT CHART -
            //------------------------
            if( chartType == 'pie' || chartType == 'doughnut' ) {
                //set options
                flexChartOptions = {
                    //Boolean - Whether we should show a stroke on each segment
                    segmentShowStroke: true,
                    //String - The colour of each segment stroke
                    segmentStrokeColor: "#fff",
                    //Number - The width of each segment stroke
                    segmentStrokeWidth: 2,
                    //Number - The percentage of the chart that we cut out of the middle
                    percentageInnerCutout: 50, // This is 0 for Pie charts
                    //Number - Amount of animation steps
                    animationSteps: 100,
                    //String - Animation easing effect
                    animationEasing: "easeOutBounce",
                    //Boolean - Whether we animate the rotation of the Doughnut
                    animateRotate: true,
                    //Boolean - Whether we animate scaling the Doughnut from the centre
                    animateScale: false,
                    //Boolean - whether to make the chart responsive to window resizing
                    responsive: true,
                    // Boolean - whether to maintain the starting aspect ratio or not when responsive, if set to false, will take up entire container
                    maintainAspectRatio: true,
                    //String - A legend template
                    legendTemplate: "<ul class=\"<%=name.toLowerCase()%>-legend\"><% for (var i=0; i<segments.length; i++){%><li><span style=\"background-color:<%=segments[i].fillColor%>\"></span><%if(segments[i].label){%><%=segments[i].label%><%}%></li><%}%></ul>"
                };
                //check for doughnut
                if( chartType == 'doughnut' ) {
                    flexChart.Doughnut(flexChartData, flexChartOptions);
                }
                //check for pie
                if( chartType == 'pie' ) {
                    flexChartOptions.percentageInnerCutout = 0;
                    flexChart.Pie(flexChartData, flexChartOptions);
                }
            }

            //--------------
            //- LINE CHART -
            //--------------
            if( chartType == 'bar' || chartType == 'bar-stack' ) {
                //set options
                flexChartOptions = {
                    //Boolean - Whether the scale should start at zero, or an order of magnitude down from the lowest value
                    scaleBeginAtZero: true,
                    //Boolean - Whether grid lines are shown across the chart
                    scaleShowGridLines: true,
                    //String - Colour of the grid lines
                    scaleGridLineColor: "rgba(0,0,0,.05)",
                    //Number - Width of the grid lines
                    scaleGridLineWidth: 1,
                    //Boolean - Whether to show horizontal lines (except X axis)
                    scaleShowHorizontalLines: true,
                    //Boolean - Whether to show vertical lines (except Y axis)
                    scaleShowVerticalLines: true,
                    //Boolean - If there is a stroke on each bar
                    barShowStroke: true,
                    //Number - Pixel width of the bar stroke
                    barStrokeWidth: 2,
                    //Number - Spacing between each of the X value sets
                    barValueSpacing: 5,
                    //Number - Spacing between data sets within X values
                    barDatasetSpacing: 1,
                    //String - A legend template
                    legendTemplate: "<ul class=\"<%=name.toLowerCase()%>-legend\"><% for (var i=0; i<datasets.length; i++){%><li><span style=\"background-color:<%=datasets[i].fillColor%>\"></span><%if(datasets[i].label){%><%=datasets[i].label%><%}%></li><%}%></ul>",
                    //Boolean - whether to make the chart responsive
                    responsive: true,
                    maintainAspectRatio: true
                };
                //set data fill
                //flexChartOptions.datasetFill = false;
                //set scales if stacked
                if( chartType == 'bar-stack'){
                    //set scales
                    flexChartOptions.scales = {
                        xAxes: [{
                            stacked: true,
                        }],
                        yAxes: [{
                            stacked: true
                        }]
                    };
                }
                //check for chart options
                if( chartOptions ){
                    for(var c in chartOptions){
                        flexChartOptions[c] = chartOptions[c];
                    }
                }
                flexChart.Bar(flexChartData, flexChartOptions);
            }
        });
    }

})(jQuery); // End of use strict
