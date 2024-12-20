<?php
require('includes/header.php');
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

$sql = "SELECT COUNT(*) as total_contacts FROM contact_message"; // Adjust table name if needed
$result = $conn->query($sql);

$total_contacts = 0;
if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $total_contacts = $row['total_contacts'];
}
// 
$sql = "SELECT SUM(od.qty * od.price) AS monthly_earnings, MONTH(o.created_at) AS month 
        FROM order_details od 
        JOIN orders o ON od.order_id = o.id
        WHERE YEAR(o.created_at) = YEAR(CURDATE()) 
        AND o.status = 'Delivered'
        GROUP BY MONTH(o.created_at)";

$result = $conn->query($sql);

$monthly_earnings = 0;
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $monthly_earnings = $row['monthly_earnings']; // You can modify to handle multiple months if needed
    }
}
// $conn->close();
$sql = "SELECT SUM(od.qty * od.price) AS year_earnings, YEAR(o.created_at) AS month 
        FROM order_details od 
        JOIN orders o ON od.order_id = o.id
        WHERE YEAR(o.created_at) = YEAR(CURDATE())
        AND o.status = 'Delivered'
        GROUP BY YEAR(o.created_at)";

$result = $conn->query($sql);

$year_earnings = 0;
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $year_earnings = $row['year_earnings']; // You can modify to handle multiple months if needed
    }
}

$sql = "SELECT SUM(od.qty * od.price) AS monthly_revenue, MONTH(o.created_at) AS month 
        FROM order_details od 
        JOIN orders o ON od.order_id = o.id
        WHERE YEAR(o.created_at) = YEAR(CURDATE())
        AND o.status = 'Delivered'
        GROUP BY MONTH(o.created_at)";

$result = mysqli_query($conn, $sql);

$months = [];
$monthly_revenues = [];

while ($row = mysqli_fetch_assoc($result)) {
    $months[] = $row['month']; 
    $monthly_revenues[] = $row['monthly_revenue']; 
}

?>
<?php
// Query top-selling products with image, price, and total revenue
$sql = "SELECT p.id, p.name, p.images, p.price, 
               SUM(od.qty) AS total_quantity, 
               SUM(od.qty * p.price) AS total_revenue
        FROM order_details od
        JOIN products p ON od.product_id = p.id
        JOIN orders o ON od.order_id = o.id
        WHERE o.status = 'Delivered'
        GROUP BY p.id, p.name, p.images, p.price
        ORDER BY total_quantity DESC
        LIMIT 5";


$result = $conn->query($sql);

$top_selling_products = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
       
        $anh_arr = explode(';', $row['images']); 
        $row['images_arr'] = $anh_arr; 

        $top_selling_products[] = $row; 
    }
}

// Assuming you already have the connection to your database
// SQL query to get categories and product counts
$sql = "SELECT c.name, COUNT(p.id) AS TotalProducts 
        FROM Categories c 
        LEFT JOIN Products p 
        ON c.id = p.category_id 
        GROUP BY c.name";

$result = $conn->query($sql);

$categories = [];
$data = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $categories[] = $row['name'];
        $data[] = $row['TotalProducts'];
    }
}
// Query to fetch product reviews with product name, user name, rating, comment, and timestamp
$sql_reviews = "SELECT p.name AS product_name, u.username AS user_name, f.rating, f.comment, f.created_at
                FROM feedback f
                JOIN products p ON f.product_id = p.id
                JOIN users u ON f.user_id = u.user_id
                ORDER BY f.created_at DESC
                LIMIT 5"; // Adjust limit based on requireme

$result_reviews = $conn->query($sql_reviews);

$reviews = [];
if ($result_reviews->num_rows > 0) {
    while ($row = $result_reviews->fetch_assoc()) {
        $reviews[] = $row;
    }
}
//total all accounts
$sql_account = "SELECT COUNT(*) AS total_accounts FROM users";  // Replace 'users' with the actual table name

$result = $conn->query($sql_account);

$total_accounts = 0;
if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $total_accounts = $row['total_accounts'];
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sales Statistics</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        /* Adjust the size of the pie chart canvas */
        #categoryPieChart {
            max-width: 300px;
            /* Set a maximum width for the pie chart */
            max-height: 300px;
            /* Set a maximum height for the pie chart */
            margin: 0 auto;
            /* Center the chart */
        }
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
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Dashboard</h1>
</div>
<div class="row">
    <!-- Earnings (Year) Card Example -->
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-primary shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                            Earnings (Year)</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                            <?php echo "$" . number_format($year_earnings, 2); ?>
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-calendar fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Earnings (Monthly) Card Example -->
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-success shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                            Earnings (Monthly)</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                            <?php echo "$" . number_format($monthly_earnings, 2); ?>
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-dollar-sign fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Total Contacts Card Example -->
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-warning shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                            Total Contacts</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                            <?php echo $total_contacts; ?>
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-address-book fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Total Accounts Card Example -->
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-danger shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">
                            Total Accounts</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                            <?php echo $total_accounts; ?>
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-address-book fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

    
    <!-- Earnings Overview Chart -->

    <div class="col-lg-12 mb-4">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Revenue Overview</h5>
            </div>
            <div class="card-body">
                <canvas id="revenueOverviewChart"></canvas>
            </div>
        </div>
    </div>

  

        <h2>Sales Statistics</h2>
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
            <!-- Form to Display Top Selling Product -->
            <div class="container">
                <h1 class="mb-4">Top Selling Product</h1>
                <table class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>Image</th>
                            <th>Product Name</th>
                            <th>Price</th>
                            <th>Total Quantity</th>
                            <th>Total revenue</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($top_selling_products as $product): ?>
                            <tr>
                                <td>
                                    <?php if (!empty($product['images_arr'])): ?>
                                        
                                        <img src="<?php echo $product['images_arr'][0]; ?>" style="width: 100px; height: auto;" alt="<?php echo $product['name']; ?>">
                                    <?php endif; ?>
                                </td>
                                <td><?php echo $product['name']; ?></td>
                                <td>$<?php echo ($product['price']); ?></td>
                                <td><?php echo $product['total_quantity']; ?></td>
                                <td>$<?php echo ($product['total_revenue']); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <div class="row">
                <!-- Pie Chart by Category -->
                <div class="col-lg-6 mb-4">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Products by Category</h5>
                        </div>
                        <div class="card-body">
                            <canvas id="categoryPieChart"></canvas>
                        </div>
                    </div>
                </div>
                <!-- Product Reviews Table with Star Ratings -->
                <div class="col-lg-6 mb-4">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Latest Product Reviews</h5>
                        </div>
                        <div class="card-body">
                            <table class="table table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th>User Name</th>
                                        <th>Product Name</th>
                                        <th>Rating</th>
                                        <th>Review Comment</th>
                                        <th>Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($reviews as $review): ?>
                                        <tr>
                                            <td><?php echo $review['user_name']; ?></td> <!-- User name column -->
                                            <td><?php echo $review['product_name']; ?></td>
                                            <td>
                                                <?php
                                                // Hiển thị sao theo đánh giá
                                                for ($i = 1; $i <= 5; $i++) {
                                                    if ($i <= $review['rating']) {
                                                        echo '<i class="fas fa-star" style="color: gold;"></i>'; 
                                                    } else {
                                                        echo '<i class="far fa-star" style="color: gold;"></i>'; 
                                                    }
                                                }
                                                ?>
                                            </td>
                                            <td><?php echo $review['comment']; ?></td>
                                            <td><?php echo date('d-m-Y', strtotime($review['created_at'])); ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
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
    

                // Pie Chart by Category
                const categoryCtx = document.getElementById('categoryPieChart').getContext('2d');
                new Chart(categoryCtx, {
                    type: 'pie',
                    data: {
                        labels: <?php echo json_encode($categories); ?>,
                        datasets: [{
                            data: <?php echo json_encode($data); ?>,
                            backgroundColor: [
                                '#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0', '#9966FF', '#FF9F40', '#66FF66'
                            ],
                            hoverOffset: 4
                        }]
                    },
                    options: {
                        responsive: true,
                        plugins: {
                            legend: {
                                position: 'bottom', // Moves the category names below the pie chart
                                labels: {
                                    boxWidth: 20, // Adjusts the size of the legend box
                                    padding: 15 // Adds some spacing between the labels
                                }
                            },
                            tooltip: {
                                callbacks: {
                                    label: function(context) {
                                        return `${context.label}: ${context.raw}`;
                                    }
                                }
                            }
                        }
                    }
                });
                // Data for monthly revenue
                const monthlyRevenueLabels = <?php echo json_encode($months); ?>; // Array of months
                const monthlyRevenueData = <?php echo json_encode($monthly_revenues); ?>; // Total revenue per month

                // Create the Revenue Chart
                const revenueCtx = document.getElementById('revenueOverviewChart').getContext('2d');
                new Chart(revenueCtx, {
                    type: 'line',
                    data: {
                        labels: monthlyRevenueLabels, // Labels (months) on the X-axis
                        datasets: [{
                            label: 'Total Revenue ($)', // Label to display below chart
                            data: monthlyRevenueData, // Total revenue data on the Y-axis
                            backgroundColor: 'rgba(75, 192, 192, 0.2)', // Background color under the line
                            borderColor: 'rgba(75, 192, 192, 1)', // Border color of the line
                            borderWidth: 2, // Line thickness
                            fill: true, // Fill color under the line
                            tension: 0.5 // Curved line tension
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                display: true, // Ensure the legend is visible
                                position: 'bottom' // Move the legend to the bottom of the chart
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true, // Start the Y-axis from 0
                                title: {
                                    display: true,
                                    text: 'Revenue ($)' // Y-axis title in English
                                }
                            },
                            x: {
                                title: {
                                    display: true,
                                    text: 'Month' // X-axis title in English
                                }
                            }
                        },
                        layout: {
                            padding: {
                                top: 20 // Add padding to move the chart lower
                            }
                        }
                    }
                });
            </script>
</body>

</html>




<?php
require('includes/footer.php');
?>