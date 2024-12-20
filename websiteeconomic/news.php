<?php
session_start();
$is_homepage = false;

require_once('./db/conn.php');


$idsp = $_GET['id'];
$sql_str = "select * from news where id=$idsp";
$result = mysqli_query($conn, $sql_str);
$row = mysqli_fetch_assoc($result);
$anh = $row['avatar'];
require_once('components/header.php');

?>

<!-- Blog Details Hero Begin -->
<section class="blog-details-hero set-bg" data-setbg="img/banner/bannerbackround.jpeg">
    <div class="container">
        <div class="row">
            <div class="col-lg-12 text-center">
                <div class="blog__details__hero__text">
                    <h2><?= $row['title'] ?></h2>
                    <ul>
                        <!-- <li>By Michael Scofield</li> -->
                        <li><?= $row['created_at'] ?></li>
                        <li>8 Comments</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- Blog Details Hero End -->

<!-- Blog Details Section Begin -->
<section class="blog-details spad">
    <div class="container">
        <div class="row">
            <div class="col-lg-4 col-md-5 order-md-1 order-2">
                <div class="blog__sidebar">
                    <div class="blog__sidebar__search">
                        <form action="#">
                            <input type="text" placeholder="Search...">
                            <button type="submit"><span class="icon_search"></span></button>
                        </form>
                    </div>
                    <div class="blog__sidebar__item">
                        <h4>Categories</h4>
                        <ul>
                            <li><a href="#">All</a></li>
                            <?php
                            $sql_str2 = "select * from newscategories order by id ";
                            $result2 = mysqli_query($conn, $sql_str2);
                            while ($row2 = mysqli_fetch_assoc($result2)) {
                            ?>
                                <li><a href="#"><?= $row2['name'] ?></a></li>
                            <?php } ?>
                        </ul>
                    </div>

                    <div class="blog__sidebar__item">
                        <h4>Recent News</h4>
                        <div class="blog__sidebar__recent">
                            <?php
                            $sql_str3 = "select * from news order by created_at desc limit 0, 3 ";
                            $result3 = mysqli_query($conn, $sql_str3);
                            while ($row3 = mysqli_fetch_assoc($result3)) {
                            ?>
                                <a href="#" class="blog__sidebar__recent__item">
                                    <div class="blog__sidebar__recent__item__pic">
                                        <img src="<?= 'admin/' . $row3['avatar'] ?>" width="70px" ; alt="">
                                    </div>
                                    <div class="blog__sidebar__recent__item__text">
                                        <h6><?= $row3['title'] ?></h6>
                                        <span><?= $row3['created_at'] ?></span>
                                    </div>
                                </a>
                            <?php } ?>

                        </div>
                    </div>
                    <div class="blog__sidebar__item">
                        <h4>Search By</h4>
                        <div class="blog__sidebar__item__tags">
                            <?php
                            $sql_str2 = "select * from newscategories order by id ";
                            $result2 = mysqli_query($conn, $sql_str2);
                            while ($row2 = mysqli_fetch_assoc($result2)) { ?>
                                <a href="#"><?= $row2['name'] ?></a>
                            <?php } ?>

                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-8 col-md-7 order-md-1 order-1">
                <div class="blog__details__text">
                    <?= $row['sumary'] ?>
                    <img src="<?= 'admin/' . $row['avatar'] ?>" alt="">
                    <?= $row['description'] ?>

                </div>

            </div>
        </div>
    </div>
</section>
<!-- Blog Details Section End -->

<!-- Related Blog Section Begin -->
<section class="related-blog spad">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="section-title related-blog-title">
                    <h2>Post You May Like</h2>
                </div>
            </div>
        </div>
        <div class="row">
            <?php
            $sql_str4 = "SELECT * FROM news WHERE newscategory_id = " . $row['newscategory_id'] . " AND id <> " . $row['id'];
            $result4 = mysqli_query($conn, $sql_str4);
            while ($row4 = mysqli_fetch_assoc($result4)) {
            ?>
                <div class="col-lg-4 col-md-4 col-sm-6">
                    <div class="blog__item">
                        <div class="blog__item__pic">
                            <img src="<?= 'admin/' . $row4['avatar'] ?>" alt="">
                        </div>
                        <div class="blog__item__text">
                            <ul>
                                <li><i class="fa fa-calendar-o"></i> <?= $row4['created_at'] ?></li>
                                <li><i class="fa fa-comment-o"></i> 5</li>
                            </ul>
                            <h5><a href="news.php?id=<?= $row4['id'] ?>"><?= $row4['title'] ?></a></h5>

                        </div>
                    </div>
                </div>
            <?php } ?>


        </div>
    </div>
</section>
<!-- Related Blog Section End -->


<?php require_once('components/footer.php'); ?>