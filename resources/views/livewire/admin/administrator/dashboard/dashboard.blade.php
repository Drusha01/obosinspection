<div>
    <div class="content">
        <!-- Begin Page Content -->
        <div class="container-fluid">
            <!-- Page Heading -->
            <div class="d-sm-flex align-items-center justify-content-between mt-4 mb-4">
                <h1 class="h3 mb-0 text-gray-800">{{$title}}</h1>
            </div>

            <!-- Dashboard Cards Row -->
            <div class="row">
                <!-- Total Inspected Business Card -->
                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card border-left-primary shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                        TOTAL INSPECTED BUSINESS
                                    </div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">{{$dashboard['total_inspected_business']}}</div>
                                </div>
                                <div class="col-auto">
                                    <i class="fa-solid fa-briefcase fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Inspected Business Without Violation Card -->
                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card border-left-success shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                        INSPECTED BUSINESS WITHOUT VIOLATION
                                    </div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">{{$dashboard['total_inspected_business_without_violation']}}</div>
                                </div>
                                <div class="col-auto">
                                    <i class="fa-solid fa-building-circle-check fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Inspected Business With Violation Card -->
                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card border-left-info shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                        INSPECTED BUSINESS WITH VIOLATION
                                    </div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">{{$dashboard['total_inspected_business_with_violation']}}</div>
                                </div>
                                <div class="col-auto">
                                    <i class="fa-solid fa-triangle-exclamation fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Total Issued Certificate Card -->
                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card border-left-warning shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                        TOTAL ISSUED CERTIFICATE
                                    </div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">{{$dashboard['total_issued_certificate']}}</div>
                                </div>
                                <div class="col-auto">
                                    <i class="fa-solid fa-file fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Charts Row -->
            <div class="row">
                <!-- Area Chart -->
                <div class="col-xl-8 col-lg-7">
                    <div class="card shadow mb-4">
                        <!-- Card Header -->
                        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                            <h6 class="m-0 font-weight-bold text-primary">Monthly Inspected Business</h6>
                        </div>
                        <!-- Card Body -->
                        <div class="card-body">
                            <div class="chart-area">
                                <canvas id="myAreaChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Pie Chart -->
                <div class="col-xl-4 col-lg-5">
                    <div class="card shadow mb-4">
                        <!-- Card Header -->
                        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                            <h6 class="m-0 font-weight-bold text-primary">Certificate Application Type</h6>
                            <div class="dropdown no-arrow">
                                <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
                                </a>
                                <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in" aria-labelledby="dropdownMenuLink">
                                    <div class="dropdown-header">Dropdown Header:</div>
                                    <a class="dropdown-item" href="#">Action</a>
                                    <a class="dropdown-item" href="#">Another action</a>
                                    <div class="dropdown-divider"></div>
                                    <a class="dropdown-item" href="#">Something else here</a>
                                </div>
                            </div>
                        </div>
                        <!-- Card Body -->
                        <div class="card-body">
                            <div class="chart-pie pt-4 pb-2">
                                <canvas id="myPieChart"></canvas>
                            </div>
                            <div class="mt-4 text-center small" id="chartLegend">
                                <?php 
                                    $color = ['#2e59d9', '#17a673', '#2c9faf', '#d4a017', '#c22d2d'];
                                    for ($i=0; $i < count($dashboard['certificate_application_types']) ; $i++) { 
                                        // array_push($color,$rand = substr(md5(microtime()),rand(0,26),5));
                                        if($i+1 != count($dashboard['certificate_application_types'])){
                                            echo '<span class="mx-2"><i class="fas fa-circle" style="color:'.$color[$i].';"></i> ( '.$dashboard['certificate_application_types'][$i]->certificate_application_types.' ) '.$dashboard['certificate_application_types'][$i]->application_type_name.'</span>';
                                        }else{
                                            echo '<span class="mx-2"><i class="fas fa-circle" style="color:'.$color[$i].';"></i> ( '.$dashboard['certificate_application_types'][$i]->certificate_application_types.' ) '.$dashboard['certificate_application_types'][$i]->application_type_name.'</span>';
                                        }
                                        
                                    }    
                                ?>
                                
                                <!-- Legends will be dynamically inserted here -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Scroll to Top Button -->
            <a class="scroll-to-top rounded" href="#page-top">
                <i class="fas fa-angle-up"></i>
            </a>
        </div>
    </div>
    <script>

        var labels = [
         
            <?php 
                for ($i=0; $i < count($dashboard['montly_inspected_business']) ; $i++) { 
                    if($i+1 != count($dashboard['montly_inspected_business'])){
                        echo '\''.$dashboard['montly_inspected_business'][$i]->month_name.'\',';
                    }else{
                        echo '\''.$dashboard['montly_inspected_business'][$i]->month_name.'\'';
                    }
                    
                }    
            ?>
        ];
        var businessCounts = [
            <?php 
                for ($i=0; $i < count($dashboard['montly_inspected_business']) ; $i++) { 
                    if($i+1 != count($dashboard['montly_inspected_business'])){
                        echo $dashboard['montly_inspected_business'][$i]->montly_inspected_business.',';
                    }else{
                        echo $dashboard['montly_inspected_business'][$i]->montly_inspected_business;
                    }
                    
                }    
            ?>
        ];

        var ctx = document.getElementById("myAreaChart");
        var myLineChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                    label: "Inspected Businesses",
                    lineTension: 0.3,
                    backgroundColor: "rgba(78, 115, 223, 0.05)",
                    borderColor: "rgba(78, 115, 223, 1)",
                    pointRadius: 3,
                    pointBackgroundColor: "rgba(78, 115, 223, 1)",
                    pointBorderColor: "rgba(78, 115, 223, 1)",
                    pointHoverRadius: 3,
                    pointHoverBackgroundColor: "rgba(78, 115, 223, 1)",
                    pointHoverBorderColor: "rgba(78, 115, 223, 1)",
                    pointHitRadius: 10,
                    pointBorderWidth: 2,
                    data: businessCounts,
                }],
            },
            options: {
                maintainAspectRatio: false,
                layout: {
                    padding: {
                        left: 10,
                        right: 25,
                        top: 25,
                        bottom: 0
                    }
                },
                scales: {
                    xAxes: [{
                        time: {
                            unit: 'date'
                        },
                        gridLines: {
                            display: false,
                            drawBorder: false
                        },
                        ticks: {
                            maxTicksLimit: 7
                        }
                    }],
                    yAxes: [{
                        ticks: {
                            maxTicksLimit: 5,
                            padding: 10,
                        },
                        gridLines: {
                            color: "rgb(234, 236, 244)",
                            zeroLineColor: "rgb(234, 236, 244)",
                            drawBorder: false,
                            borderDash: [2],
                            zeroLineBorderDash: [2]
                        }
                    }],
                },
                legend: {
                    display: false
                },
                tooltips: {
                    backgroundColor: "rgb(255,255,255)",
                    bodyFontColor: "#858796",
                    titleMarginBottom: 10,
                    titleFontColor: '#6e707e',
                    titleFontSize: 14,
                    borderColor: '#dddfeb',
                    borderWidth: 1,
                    xPadding: 15,
                    yPadding: 15,
                    displayColors: false,
                    intersect: false,
                    mode: 'index',
                    caretPadding: 10,
                    callbacks: {
                        label: function(tooltipItem, chart) {
                            var datasetLabel = chart.datasets[tooltipItem.datasetIndex].label || '';
                            return datasetLabel + ': ' + tooltipItem.yLabel;
                        }
                    }
                }
            }
        });
        

        var labels = [
            <?php 
                for ($i=0; $i < count($dashboard['certificate_application_types']) ; $i++) { 
                    if($i+1 != count($dashboard['certificate_application_types'])){
                        echo '\''.$dashboard['certificate_application_types'][$i]->application_type_name.'\',';
                    }else{
                        echo '\''.$dashboard['certificate_application_types'][$i]->application_type_name.'\'';
                    }
                    
                }    
            ?>
        ];
            var data = [
                <?php 
                    for ($i=0; $i < count($dashboard['certificate_application_types']) ; $i++) { 
                        if($i+1 != count($dashboard['certificate_application_types'])){
                            echo '\''.$dashboard['certificate_application_types'][$i]->certificate_application_types.'\',';
                        }else{
                            echo '\''.$dashboard['certificate_application_types'][$i]->certificate_application_types.'\'';
                        }
                        
                    }    
                ?>
            ];
            var colors = [
                <?php 
                    for ($i=0; $i < count($dashboard['certificate_application_types']) ; $i++) { 
                        if($i+1 != count($dashboard['certificate_application_types'])){
                            echo '\''.$color[$i].'\',';
                        }else{
                            echo '\''.$color[$i].'\'';
                        }
                        
                    }    
                ?>
            ];
            var hoverColors = [
                <?php 
                    for ($i=0; $i < count($dashboard['certificate_application_types']) ; $i++) { 
                        if($i+1 != count($dashboard['certificate_application_types'])){
                            echo '\''.$color[$i].'\',';
                        }else{
                            echo '\''.$color[$i].'\'';
                        }
                        
                    }    
                ?>
            ];

         
                
            var ctx = document.getElementById("myPieChart").getContext('2d');
            var myPieChart = new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: labels,
                    datasets: [{
                        data: data,
                        backgroundColor: colors,
                        hoverBackgroundColor: hoverColors,
                        hoverBorderColor: "rgba(234, 236, 244, 1)",
                    }],
                },
                options: {
                    maintainAspectRatio: false,
                    tooltips: {
                        backgroundColor: "rgb(255,255,255)",
                        bodyFontColor: "#858796",
                        borderColor: '#dddfeb',
                        borderWidth: 1,
                        xPadding: 15,
                        yPadding: 15,
                        displayColors: false,
                        caretPadding: 10,
                    },
                    legend: {
                        display: false
                    },
                    cutoutPercentage: 80,
                },
            });
    </script>

</div>