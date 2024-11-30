    <?php
    session_start();
    $is_homepage = false;
    require_once('components/header.php');
    require_once('./db/conn.php');

    if (isset($_GET['keyword'])) {
        $keyword = str_replace('+', ' ', $_GET['keyword']);
    }
    // key word
    $category = $_GET['category'];
    $keyword = $_GET['keyword'];

    ?>

    <div class="container">
        <div class="row">
            <div class="col-lg-3">
                <div class="hero__categories">
                    <div class="hero__categories__all">
                        <i class="fa fa-bars"></i>
                        <span>Product Categories</span>
                    </div>
                    <ul>
                        <?php
                        require('./db/conn.php');
                        $sql_str = "SELECT * FROM categories ORDER BY name,id";
                        $result = mysqli_query($conn, $sql_str);
                        while ($row = mysqli_fetch_assoc($result)) {
                        ?>
                            <li><a href="category.php?id=<?= $row['id'] ?>"><?= htmlspecialchars($row['name']) ?></a></li>
                        <?php } ?>
                    </ul>
                </div>
            </div>
            <div class="col-lg-9">
                <div class="hero__search__form">
                    <form action="search.php" method="get">
                        <select name="category">
                            <option value='*'>All Categories</option>
                            <?php
                            require('./db/conn.php');
                            $sql_str = "SELECT * FROM categories ORDER BY name";
                            $result = mysqli_query($conn, $sql_str);
                            while ($row = mysqli_fetch_assoc($result)) {
                            ?>
                                <option value="<?= $row['id'] ?>"><?= $row['name'] ?> </option>
                            <?php } ?>
                        </select>
                        <div class="search-input">
                            <input type="text" name="keyword" placeholder="What do you need?">
                        </div>
                        <div class="search-button">
                            <button type="submit" class="site-btn">SEARCH</button>
                        </div>
                    </form>
                </div>
            </div>


        </div>
        <br>
        <br>
    </div>
    <!-- Breadcrumb Section Begin -->
    <section class="breadcrumb-section set-bg" data-setbg="img/banner/bannerlapop.jpg">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 text-center">
                    <div class="breadcrumb__text">
                        <h2>QT Store </h2>
                        <div class="breadcrumb__option">
                            <a href="./index.php">Home</a>
                            <span>Shop</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Breadcrumb Section End -->

    <!-- Product Section Begin -->
    <section class="product spad">
        <div class="container">
            <div class="row">
                <div class="col-lg-3 col-md-5">
                    <div class="sidebar">

                        <div class="sidebar__item">
                            <div class="latest-product__text">
                                <h4>Newest Product</h4>
                                <div class="latest-product__slider owl-carousel">
                                    <div class="latest-prdouct__slider__item">
                                        <?php
                                        $sql_str = "SELECT products.id as pid, products.name as pname, images, price, 
                                                IFNULL(AVG(feedback.rating), 0) as avg_rating
                                                FROM products
                                                LEFT JOIN feedback ON products.id = feedback.product_id
                                                GROUP BY products.id
                                                ORDER BY products.created_at DESC 
                                                LIMIT 0, 3";
                                        $result = mysqli_query($conn, $sql_str);
                                        while ($row = mysqli_fetch_assoc($result)) {
                                            $anh_arr = explode(';', $row['images']);
                                        ?>
                                            <a href="product.php?id=<?= $row['pid'] ?>" class="latest-product__item">
                                                <div class="latest-product__item__pic">
                                                    <img src="<?= "admin/" . $anh_arr[0] ?>" alt="">
                                                </div>
                                                <div class="latest-product__item__text">
                                                    <h6><?= $row['pname'] ?></h6>

                                                    <!-- Display star rating -->
                                                    <div class="rating">
                                                        <?php for ($i = 1; $i <= 5; $i++) { ?>
                                                            <i class="fa fa-star<?= $i <= round($row['avg_rating']) ? '' : '-o' ?>" style="color: gold;"></i>
                                                        <?php } ?>
                                                    </div>

                                                    <span>$ <?= $row['price'] ?></span>
                                                </div>
                                            </a>
                                        <?php
                                        }
                                        ?>
                                    </div>
                                    <div class="latest-prdouct__slider__item">
                                        <?php
                                        $sql_str = "SELECT products.id as pid, products.name as pname, images, price, 
                                                IFNULL(AVG(feedback.rating), 0) as avg_rating
                                                FROM products
                                                LEFT JOIN feedback ON products.id = feedback.product_id
                                                GROUP BY products.id
                                                ORDER BY products.created_at DESC 
                                                LIMIT 3, 3";
                                        $result = mysqli_query($conn, $sql_str);
                                        while ($row = mysqli_fetch_assoc($result)) {
                                            $anh_arr = explode(';', $row['images']);
                                        ?>
                                            <a href="product.php?id=<?= $row['pid'] ?>" class="latest-product__item">
                                                <div class="latest-product__item__pic">
                                                    <img src="<?= "admin/" . $anh_arr[0] ?>" alt="">
                                                </div>
                                                <div class="latest-product__item__text">
                                                    <h6><?= $row['pname'] ?></h6>

                                                    <!-- Display star rating -->
                                                    <div class="rating">
                                                        <?php for ($i = 1; $i <= 5; $i++) { ?>
                                                            <i class="fa fa-star<?= $i <= round($row['avg_rating']) ? '' : '-o' ?>" style="color: gold;"></i>
                                                        <?php } ?>
                                                    </div>

                                                    <span>$ <?= $row['price'] ?></span>
                                                </div>
                                            </a>
                                        <?php
                                        }
                                        ?>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-9 col-md-7">
                    <h3> Search Results</h3>
                    <div class="filter__item">
                        <div class="row">
                            <div class="col-lg-4 col-md-5">
                                <div class="filter__sort">
                                    <span>Sort By</span>

                                </div>
                            </div>
                            <?php
                            if ($category == '*') { // all categories
                                $sql_str = "
                                SELECT products.id as pid, products.name as pname, products.images, products.price, 
                                IFNULL(AVG(feedback.rating), 0) as avg_rating
                                FROM products
                                LEFT JOIN feedback ON products.id = feedback.product_id
                                WHERE products.name LIKE '%$keyword%' 
                                OR products.description LIKE '%$keyword%' 
                                OR products.summary LIKE '%$keyword%'
                                GROUP BY products.id
                                ORDER BY products.name";
                            } else {
                                $sql_str = "
                                SELECT products.id as pid, products.name as pname, products.images, products.price, 
                                IFNULL(AVG(feedback.rating), 0) as avg_rating
                                FROM products
                                LEFT JOIN feedback ON products.id = feedback.product_id
                                WHERE products.category_id = $category
                                AND (products.name LIKE '%$keyword%' 
                                OR products.description LIKE '%$keyword%' 
                                OR products.summary LIKE '%$keyword%')
                                GROUP BY products.id
                                ORDER BY products.name";
                            }


                            $result = mysqli_query($conn, $sql_str);

                            ?>
                            <div class="col-lg-4 col-md-4">
                                <div class="filter__found">
                                    <h6>Have <span><?= mysqli_num_rows($result) ?></span> Product</h6>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-3">
                                <!-- <div class="filter__option">
                                    <span class="icon_grid-2x2"></span>
                                    <span class="icon_ul"></span>
                                </div> -->
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <?php
                        while ($row = mysqli_fetch_assoc($result)) {
                            $anh_arr = explode(';', $row['images']);
                        ?>
                            <div class="col-lg-4 col-md-6 col-sm-6">
                                <div class="product__item">
                                    <div class="product__item__pic set-bg" data-setbg="<?= "admin/" . $anh_arr[0] ?>">
                                        <ul class="product__item__pic__hover">

                                            <!-- <li><a href="#"><i class="fa fa-shopping-cart"></i></a></li> -->
                                        </ul>
                                    </div>
                                    <div class="product__item__text">
                                        <h6><a href="product.php?id=<?= $row['pid'] ?>"><?= $row['pname'] ?></a></h6>

                                        <!-- Display star rating below the product name -->
                                        <div class="rating">
                                            <?php for ($i = 1; $i <= 5; $i++) { ?>
                                                <i class="fa fa-star<?= $i <= round($row['avg_rating']) ? '' : '-o' ?>" style="color: gold;"></i>
                                            <?php } ?>
                                        </div>
                                        <h5>$ <?= $row['price'] ?></h5>
                                    </div>
                                </div>
                            </div>
                        <?php } ?>

                    </div>

                    <hr>
                    <div class="product__discount">
                        <div class="section-title product__discount__title">
                            <h2>Product Discount</h2>
                        </div>
                        <div class="row">
                            <div class="product__discount__slider owl-carousel">
                                <?php
                                $sql_str = "SELECT products.id as pid, products.name as pname, categories.name as cname, 
                            ROUND((price - disscounted_price)/price*100) as discount, 
                            images, price, disscounted_price, 
                            IFNULL(AVG(feedback.rating), 0) as avg_rating
                            FROM products
                            JOIN categories ON products.category_id = categories.id
                            LEFT JOIN feedback ON products.id = feedback.product_id
                            GROUP BY products.id
                            ORDER BY discount DESC 
                            LIMIT 0, 6";

                                $result = mysqli_query($conn, $sql_str);
                                while ($row = mysqli_fetch_assoc($result)) {
                                    $anh_arr = explode(';', $row['images']);
                                ?>
                                    <div class="col-lg-4">
                                        <div class="product__discount__item">
                                            <div class="product__discount__item__pic set-bg"
                                                data-setbg="<?= "admin/" . $anh_arr[0] ?>">
                                                <div class="product__discount__percent">-<?= $row['discount'] ?>%</div>
                                               
                                            </div>
                                            <div class="product__discount__item__text">
                                                <span><?= $row['cname'] ?></span>
                                                <h5><a class="view-detail" href="product.php?id=<?= $row['pid'] ?>"><?= $row['pname'] ?></a></h5>
                                                <!-- Display star rating -->
                                                <div class="rating">
                                                    <?php for ($i = 1; $i <= 5; $i++) { ?>
                                                        <i class="fa fa-star<?= $i <= round($row['avg_rating']) ? '' : '-o' ?>" style="color: gold;"></i>
                                                    <?php } ?>
                                                </div>
                                                <div class="product__item__price">$ <?= $row['disscounted_price'] ?> <span> $ <?= $row['price'] ?></span></div>
                                            </div>
                                        </div>
                                    </div>
                                <?php } ?>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
    </section>
    <!-- Product Section End -->

    <?php

    require_once('components/footer.php');
    ?>