<?php
// Database connection
require_once('../db/conn.php');

// Query for monthly sales
$monthlyQuery = "
    SELECT 
        YEAR(orders.created_at) AS sale_year,
        MONTH(orders.created_at) AS sale_month,
        SUM(order_details.qty) AS total_quantity_sold
    FROM orders
    JOIN order_details ON orders.id = order_details.order_id
    WHERE orders.status = 'Delivered'
    GROUP BY YEAR(orders.created_at), MONTH(orders.created_at)
    ORDER BY sale_year, sale_month";
$monthlyData = $pdo->query($monthlyQuery)->fetchAll(PDO::FETCH_ASSOC);

// Query for quarterly sales
$quarterlyQuery = "
    SELECT 
        YEAR(orders.created_at) AS sale_year,
        QUARTER(orders.created_at) AS sale_quarter,
        SUM(order_details.qty) AS total_quantity_sold
    FROM orders
    JOIN order_details ON orders.id = order_details.order_id
    WHERE orders.status = 'Delivered'
    GROUP BY YEAR(orders.created_at), QUARTER(orders.created_at)
    ORDER BY sale_year, sale_quarter";
$quarterlyData = $pdo->query($quarterlyQuery)->fetchAll(PDO::FETCH_ASSOC);

// Query for yearly sales
$yearlyQuery = "
    SELECT 
        YEAR(orders.created_at) AS sale_year,
        SUM(order_details.qty) AS total_quantity_sold
    FROM orders
    JOIN order_details ON orders.id = order_details.order_id
    WHERE orders.status = 'Delivered'
    GROUP BY YEAR(orders.created_at)
    ORDER BY sale_year";
$yearlyData = $pdo->query($yearlyQuery)->fetchAll(PDO::FETCH_ASSOC);

// Prepare data arrays for Chart.js
$months = [];
$monthlySales = [];
foreach ($monthlyData as $row) {
    $months[] = $row['sale_year'] . '-' . str_pad($row['sale_month'], 2, '0', STR_PAD_LEFT);
    $monthlySales[] = $row['total_quantity_sold'];
}

$quarters = [];
$quarterlySales = [];
foreach ($quarterlyData as $row) {
    $quarters[] = $row['sale_year'] . '-Q' . $row['sale_quarter'];
    $quarterlySales[] = $row['total_quantity_sold'];
}

$years = [];
$yearlySales = [];
foreach ($yearlyData as $row) {
    $years[] = $row['sale_year'];
    $yearlySales[] = $row['total_quantity_sold'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Sales Report</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        /* Flex container to display charts in a row */
        .chart-container {
            display: flex;
            justify-content: space-between;
            gap: 20px; /* Space between charts */
            margin: 20px;
        }

        /* Styling each chart to fit better */
        .chart-box {
            flex: 1;
            max-width: 32%; /* Each chart takes up around a third of the row */
        }

        canvas {
            width: 100%; /* Ensures each canvas fills its container */
            height: 300px; /* Fixed height for consistency */
        }
    </style>
</head>
<body>
    <h2>Sales Report</h2>
    <div class="chart-container">
        <div class="chart-box">
            <h3>Monthly Sales</h3>
            <canvas id="monthlyChart"></canvas>
        </div>
        <div class="chart-box">
            <h3>Quarterly Sales</h3>
            <canvas id="quarterlyChart"></canvas>
        </div>
        <div class="chart-box">
            <h3>Yearly Sales</h3>
            <canvas id="yearlyChart"></canvas>
        </div>
    </div>

    <script>
        // Monthly Sales Chart
        const monthlyCtx = document.getElementById('monthlyChart').getContext('2d');
        new Chart(monthlyCtx, {
            type: 'bar',
            data: {
                labels: <?php echo json_encode($months); ?>,
                datasets: [{
                    label: 'Total Quantity Sold',
                    data: <?php echo json_encode($monthlySales); ?>,
                    backgroundColor: 'rgba(54, 162, 235, 0.6)'
                }]
            },
            options: { responsive: true, scales: { y: { beginAtZero: true } } }
        });

        // Quarterly Sales Chart
        const quarterlyCtx = document.getElementById('quarterlyChart').getContext('2d');
        new Chart(quarterlyCtx, {
            type: 'bar',
            data: {
                labels: <?php echo json_encode($quarters); ?>,
                datasets: [{
                    label: 'Total Quantity Sold',
                    data: <?php echo json_encode($quarterlySales); ?>,
                    backgroundColor: 'rgba(75, 192, 192, 0.6)'
                }]
            },
            options: { responsive: true, scales: { y: { beginAtZero: true } } }
        });

        // Yearly Sales Chart
        const yearlyCtx = document.getElementById('yearlyChart').getContext('2d');
        new Chart(yearlyCtx, {
            type: 'bar',
            data: {
                labels: <?php echo json_encode($years); ?>,
                datasets: [{
                    label: 'Total Quantity Sold',
                    data: <?php echo json_encode($yearlySales); ?>,
                    backgroundColor: 'rgba(255, 159, 64, 0.6)'
                }]
            },
            options: { responsive: true, scales: { y: { beginAtZero: true } } }
        });
    </script>
</body>
</html>
