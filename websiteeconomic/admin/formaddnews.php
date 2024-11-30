<?php
require('includes/header.php');
?>

<div class="container">

    <div class="card o-hidden border-0 shadow-lg my-5">
        <div class="card-body p-0">
            <!-- Nested Row within Card Body -->
            <div class="row">
                <div class="col-lg-12">
                    <div class="p-5">
                        <div class="text-center">
                            <h1 class="h4 text-gray-900 mb-4">Add new news</h1>
                        </div>
                        <form class="user" method="post" action="addnews.php" enctype="multipart/form-data">
                            <div class="form-group">
                                <input type="text" class="form-control form-control-user" id="name" name="name"
                                    aria-describedby="emailHelp" placeholder="News headline">
                            </div>

                            <div class="form-group">
                                <label class="form-label">Avatar for news</label>
                                <input type="file" class="form-control form-control-user" id="anh" name="anh">
                            </div>
                            <div class="form-group">
                                <label class="form-label">News Summary:</label>
                                <textarea name="sumary" class="form-control" placeholder="Enter...">

                        </textarea>
                            </div>
                            <div class="form-group">
                                <label class="form-label">News content:</label>
                                <textarea name="description" class="form-control" placeholder="Enter...">

                        </textarea>
                            </div>




                            <div class="form-group">
                                <label class="form-label">News category:</label>
                                <select class="form-control" name="category">
                                    <option>Select category</option>
                                    <?php
                                    require('../db/conn.php');
                                    $sql_str = "select * from newscategories order by name";
                                    $result = mysqli_query($conn, $sql_str);
                                    while ($row = mysqli_fetch_assoc($result)) {
                                        ?>
                                        <option value="<?php echo $row['id']; ?>">
                                            <?php echo $row['name']; ?>
                                        </option>
                                    <?php } ?>
                                </select>
                            </div>

                            <button class="btn btn-primary">Create new</button>
                        </form>
                        <hr>

                    </div>
                </div>
            </div>
        </div>
    </div>

</div>


<?php
require('includes/footer.php');
?>