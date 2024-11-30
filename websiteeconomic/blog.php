<?php
// Database connection
session_start();
$is_homepage = false;
require_once('./db/conn.php');
require_once('components/header.php');

// Fetch all news
$sql_str = "select * from news order by created_at desc limit 0,3 ";
$newsList = mysqli_query($conn, $sql_str);
while ($row = mysqli_fetch_assoc($newsList))

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Latest News</title>
    <style>
        h1 {
    font-size: 45px; /* Adjust the font size as per your preference */
}
        /* Custom styling */
        .card {
            border: none;
            transition: transform 0.2s ease-in-out;
        }
        .card:hover {
            transform: scale(1.05);
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }
        .blog__item__pic img {
            width: 100%;
            height: 200px;
            object-fit: cover;
        }
        .blog__item__text {
            padding: 15px;
        }
        .blog__item__text h5 {
            font-weight: bold;
            margin-bottom: 15px;
        }
        .blog__item__text ul {
            padding-left: 0;
            list-style-type: none;
        }
        .blog__item__text ul li {
            display: inline-block;
            margin-right: 10px;
            font-size: 14px;
            color: #888;
        }
        .search-box {
            margin-bottom: 30px;
        }
        .search-box input {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        /* Remove underline from links */
        a {
            text-decoration: none;
            color: inherit; /* Optional: Keeps the original text color */
        }
        a:hover {
            text-decoration: underline; /* Optional: Add underline on hover if needed */
        }
        
    </style>
</head>
<body>


<div class="container mt-5">
<h1 class="mb-4 text-center font-weight-bold">Latest News</h1>
    
    <!-- Search box for filtering news -->
    <div class="row search-box">
        <div class="col-md-12">
            <input type="text" placeholder="Search news..." id="searchInput" onkeyup="filterNews()">
        </div>
    </div>

    <div class="row" id="newsContainer">
        <?php if ($newsList): ?>
            <?php foreach ($newsList as $news): ?>
            <div class="col-lg-4 col-md-6 col-sm-12 mb-4 news-item">
                <div class="card">
                    <div class="blog__item__pic">
                        <img src="<?= 'admin/' . htmlspecialchars($news['avatar']) ?>" alt="News Image" class="card-img-top">
                    </div>
                    <div class="card-body blog__item__text">
                        <ul>
                            <li><i class="fa fa-calendar-o"></i> <?= htmlspecialchars($news['created_at']) ?></li>
                            <li><i class="fa fa-comment-o"></i> 5 comments</li>
                        </ul>
                        <h5 class="card-title">
                            <a href="news.php?id=<?= $news['id'] ?>"><?= htmlspecialchars($news['title']) ?></a>
                        </h5>
                        <p class="card-text"><?= htmlspecialchars($news['sumary']) ?></p>
                        <a href="news.php?id=<?= $news['id'] ?>" class="btn btn-primary">Read more</a>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>No news found.</p>
        <?php endif; ?>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

<script>
// News filtering function
function filterNews() {
    var input, filter, newsContainer, newsItems, title, i, txtValue;
    input = document.getElementById("searchInput");
    filter = input.value.toLowerCase();
    newsContainer = document.getElementById("newsContainer");
    newsItems = newsContainer.getElementsByClassName("news-item");

    for (i = 0; i < newsItems.length; i++) {
        title = newsItems[i].getElementsByClassName("card-title")[0];
        txtValue = title.textContent || title.innerText;
        if (txtValue.toLowerCase().indexOf(filter) > -1) {
            newsItems[i].style.display = "";
        } else {
            newsItems[i].style.display = "none";
        }
    }
}
</script>

<?php require_once('components/footer.php'); ?>
</body>
</html>
