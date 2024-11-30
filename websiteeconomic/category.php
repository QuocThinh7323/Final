<?php
session_start();
$is_homepage = false; // This is not the homepage
require_once('components/header.php');
require_once('./db/conn.php');
// Kiểm tra nếu người dùng đã đăng nhập và có vai trò 'user'
$user_role = isset($_SESSION['user_role']) ? $_SESSION['user_role'] : null;
// Check if the category ID is set in the query string
if (isset($_GET['id'])) {
    $category_id = (int)$_GET['id'];

    // Fetch the category details
    $category_sql = "SELECT * FROM categories WHERE id = $category_id";
    $category_result = mysqli_query($conn, $category_sql);
    $category = mysqli_fetch_assoc($category_result);

    // Fetch products based on category ID and their average ratings
    $sql_str = "SELECT products.id as pid, products.name as pname, images, price, 
    AVG(feedback.rating) as avg_rating
    FROM products 
    LEFT JOIN feedback ON products.id = feedback.product_id
    WHERE products.category_id = $category_id
    GROUP BY products.id";
    $result = mysqli_query($conn, $sql_str);
} else {
    // If no category is selected, redirect to homepage or display all products
    header("Location: index.php");
    exit;
}

?>

<!-- Categories Section Begin -->
<section class="featured spad">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="section-title">
                    <h2>Products in <?= htmlspecialchars($category['name']) ?></h2>
                </div>
            </div>
        </div>
        <div class="row featured__filter">
            <?php
            while ($row = mysqli_fetch_assoc($result)) {
                $anh_arr = explode(';', $row['images']);
            ?>
                <div class="col-lg-3 col-md-4 col-sm-6 mix">
                    <div class="featured__item">
                        <div class="featured__item__pic set-bg" data-setbg="<?= "admin/" . $anh_arr[0] ?>">
                            <ul class="featured__item__pic__hover">
                                <?php if ($user_role == 'User') { ?>
                                    <li><a href="#" class="add-to-cart" data-id="<?= $row['pid'] ?>" data-qty="1"><i class="fa fa-shopping-cart"></i></a></li>
                                <?php } ?>
                            </ul>
                        </div>
                        <div class="featured__item__text">
                            <h6><a href="product.php?id=<?= $row['pid'] ?>"><?= $row['pname'] ?></a></h6>
                          
                                                <!-- Display star rating -->
                                                <div class="rating">
                                                    <?php for ($i = 1; $i <= 5; $i++) { ?>
                                                        <i class="fa fa-star<?= $i <= round($row['avg_rating']) ? '' : '-o' ?>" style="color: gold;"></i>
                                                    <?php } ?>
                                                </div>
                                                <span>$ <?= $row['price'] ?></span>
                        </div>
                    </div>
                </div>
            <?php } ?>
        </div>
    </div>
</section>
<!-- Featured Section End -->

<!-- Additional sections like Banner, Blog, Footer can be added below -->
<?php
require_once('components/footer.php');
?>

<!-- Optional: Add your JavaScript for handling cart actions, etc. -->
<script>
    $(document).ready(function() {
        $('.add-to-cart').click(function(e) {
            e.preventDefault();
            var pid = $(this).data('id');
            var qty = $(this).data('qty');
            $.ajax({
                type: 'POST',
                url: '<?= $_SERVER['REQUEST_URI'] ?>',
                data: {
                    addtocart: true,
                    pid: pid,
                    qty: qty
                },
                success: function(response) {
                    alert('Sản phẩm đã được thêm vào giỏ hàng!');
                },
                error: function() {
                    alert('Có lỗi xảy ra, vui lòng thử lại.');
                }
            });
        });
    });
</script>