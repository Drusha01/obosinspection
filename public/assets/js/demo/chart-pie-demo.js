fetch('./json_response/pie-chart-data.php')
            .then(response => response.json())
            .then(result => {
                if (result.error) {
                    console.error(result.error);
                    return;
                }

                var labels = result.labels;
                var data = result.data;
                var colors = ['#4e73df', '#1cc88a', '#36b9cc', '#f6c23e', '#e74a3b'];
                var hoverColors = ['#2e59d9', '#17a673', '#2c9faf', '#d4a017', '#c22d2d'];

                var legendContainer = document.getElementById('chartLegend');
                legendContainer.innerHTML = ''; // Clear any existing content

                labels.forEach((label, index) => {
                    var color = colors[index % colors.length];
                    var span = document.createElement('span');
                    span.className = 'mr-2';
                    span.innerHTML = `<i class="fas fa-circle" style="color:${color}"></i> ${label}`;
                    legendContainer.appendChild(span);
                });
                
                var ctx = document.getElementById("myPieChart").getContext('2d');
                var myPieChart = new Chart(ctx, {
                    type: 'doughnut',
                    data: {
                        labels: result.labels,
                        datasets: [{
                            data: result.data,
                            backgroundColor: ['#4e73df', '#1cc88a', '#36b9cc', '#f6c23e', '#e74a3b'],
                            hoverBackgroundColor: ['#2e59d9', '#17a673', '#2c9faf', '#d4a017', '#c22d2d'],
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
            })
            .catch(error => {
                console.error('Error fetching data:', error);
            });