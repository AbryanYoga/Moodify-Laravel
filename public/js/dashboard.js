/* ========================================================
   DASHBOARD CHART SCRIPT (Moodify Premium UI)
   ======================================================== */

document.addEventListener('DOMContentLoaded', function () {
    const ctx = document.getElementById('genreChart');
    if (!ctx) return;

    // Get raw data from attributes (we'll set these up on the canvas element in the view)
    const labels = JSON.parse(ctx.getAttribute('data-labels') || '[]');
    const dataValues = JSON.parse(ctx.getAttribute('data-values') || '[]');

    let myChart = null;

    function getThemeColors() {
        const isLight = document.documentElement.classList.contains('light');
        return {
            textColor: isLight ? '#707090' : '#7a7a9a',
            gridColor: isLight ? 'rgba(0, 0, 0, 0.05)' : 'rgba(255, 255, 255, 0.05)',
            barColorStart: isLight ? 'rgba(124, 92, 191, 0.85)' : 'rgba(200, 180, 250, 0.85)',
            barColorEnd: isLight ? 'rgba(192, 82, 122, 0.85)' : 'rgba(244, 168, 199, 0.85)',
            borderColor: isLight ? '#7c5cbf' : '#c8b4fa'
        };
    }

    function buildChart() {
        const colors = getThemeColors();

        // Create gradient
        const gradient = ctx.getContext('2d').createLinearGradient(0, 0, 0, 300);
        gradient.addColorStop(0, colors.barColorStart);
        gradient.addColorStop(1, colors.barColorEnd);

        if (myChart) {
            myChart.destroy();
        }

        myChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Total Playlist',
                    data: dataValues,
                    backgroundColor: gradient,
                    borderColor: colors.borderColor,
                    borderWidth: 1.5,
                    borderRadius: 8,
                    borderSkipped: false,
                    hoverBackgroundColor: colors.borderColor
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        backgroundColor: document.documentElement.classList.contains('light') ? '#ffffff' : '#16161f',
                        titleColor: document.documentElement.classList.contains('light') ? '#18171f' : '#f0eff8',
                        bodyColor: document.documentElement.classList.contains('light') ? '#707090' : '#7a7a9a',
                        borderColor: colors.gridColor,
                        borderWidth: 1,
                        padding: 12,
                        cornerRadius: 12,
                        displayColors: false,
                        callbacks: {
                            label: function (context) {
                                return ` ♬ ${context.parsed.y} playlist`;
                            }
                        }
                    }
                },
                scales: {
                    x: {
                        grid: {
                            display: false
                        },
                        ticks: {
                            color: colors.textColor,
                            font: {
                                family: 'DM Sans, sans-serif',
                                size: 12,
                                weight: '500'
                            }
                        }
                    },
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: colors.gridColor,
                            drawTicks: false
                        },
                        ticks: {
                            color: colors.textColor,
                            precision: 0,
                            font: {
                                family: 'DM Sans, sans-serif',
                                size: 11
                            }
                        }
                    }
                }
            }
        });
    }

    // Build the initial chart
    buildChart();

    // Listen to theme changes from the global script
    document.addEventListener('themechanged', function () {
        buildChart();
    });
});
