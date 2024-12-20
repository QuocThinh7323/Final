<?php
ob_start(); 

require('includes/header.php');

function anhdaidien($arrstr, $height){
    $arr = explode(';', $arrstr);
    return "<img src='$arr[0]' height='$height' />";
}

// Check if id is set in the URL and is a valid number
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = $_GET['id'];
} else {
    die("Invalid product ID.");
}

// Connect to the database
require('../db/conn.php');

$sql_str = "SELECT 
products.id as pid, summary, description, stock, price, disscounted_price,
products.name as pname, images, categories.name as cname, brands.name as bname,
products.status as pstatus
FROM products, categories, brands 
WHERE products.category_id = categories.id 
AND products.brand_id = brands.id 
AND products.id = $id";

$res = mysqli_query($conn, $sql_str);
$product = mysqli_fetch_assoc($res);

if (isset($_POST['btnUpdate'])) {
    // Get data from the form
    $name = $_POST['name'];
    $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $name)));
    $summary = $_POST['summary'];
    $description = $_POST['description'];
    $stock = $_POST['stock'];
    $originalprice = $_POST['originalprice'];
    $saleprice = !empty($_POST['saleprice']) ? $_POST['saleprice'] : 'NULL'; // Handle empty saleprice
    $category = $_POST['category'];
    $brand = $_POST['brand'];
    $status = $_POST['status'];

    // Handle image upload
    $countfiles = count($_FILES['anhs']['name']);
    if (!empty($_FILES['anhs']['name'][0])) { // if new images are uploaded
        // Delete old images
        $images_arr = explode(';', $product['images']);
        foreach ($images_arr as $img) {
            unlink($img);
        }

        // Add new images
        $imgs = '';
        for ($i = 0; $i < $countfiles; $i++) {
            $filename = $_FILES['anhs']['name'][$i];
            $location = "uploads/" . uniqid() . $filename;
            $extension = pathinfo($location, PATHINFO_EXTENSION);
            $extension = strtolower($extension);

            $valid_extensions = array("jpg", "jpeg", "png");

            if (in_array($extension, $valid_extensions)) {
                if (move_uploaded_file($_FILES['anhs']['tmp_name'][$i], $location)) {
                    $imgs .= $location . ";";
                }
            }
        }
        $imgs = substr($imgs, 0, -1);

        // Update the product with new images
        $sql_str = "UPDATE `products` 
        SET `name`='$name', 
        `slug`='$slug', 
        `description`='$description', 
        `summary`='$summary', 
        `stock`=$stock, 
        `price`=$originalprice, 
        `disscounted_price`=$saleprice, 
        `images`='$imgs', 
        `category_id`=$category, 
        `brand_id`=$brand,
        `status`='$status'
        WHERE `id`=$id";
    } else {
        // Update the product without changing images
        $sql_str = "UPDATE `products` 
        SET `name`='$name', 
        `slug`='$slug', 
        `description`='$description', 
        `summary`='$summary', 
        `stock`=$stock, 
        `price`=$originalprice, 
        `disscounted_price`=$saleprice, 
        `category_id`=$category, 
        `brand_id`=$brand,
        `status`='$status'
        WHERE `id`=$id";
    }
    
    // Execute the query
    mysqli_query($conn, $sql_str);

    // Redirect back to product list
    header("Location: ./listproduct.php");
    exit;
}


// Your HTML and form code goes here

ob_end_flush(); 
?>


<div class="container">
    <div class="card o-hidden border-0 shadow-lg my-5">
        <div class="card-body p-0">
            <div class="row">
                <div class="col-lg-12">
                    <div class="p-5">
                        <div class="text-center">
                            <h1 class="h4 text-gray-900 mb-4">Product Updates</h1>
                        </div>
                        <form class="user" method="post" action="#" enctype="multipart/form-data">
                            <div class="form-group">
                                <input type="text" class="form-control form-control-user" id="name" name="name" aria-describedby="emailHelp" placeholder="Product Name" value="<?= $product['pname'] ?>">
                            </div>

                            <div class="form-group">
                                <label class="form-label">Images for products</label>
                                <input type="file" class="form-control form-control-user" id="anhs" name="anhs[]" multiple>
                                <br>
                                Current photos:
                                <?php
                                $arr = explode(';', $product['images']);
                                foreach ($arr as $img)
                                    echo "<img src='$img' height='100px' />";
                                ?>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Product Summary:</label>
                                <textarea name="summary" class="form-control" placeholder="Enter..."><?= $product['summary'] ?></textarea>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Product description:</label>
                                <textarea name="description" class="form-control" placeholder="Enter..."><?= $product['description'] ?></textarea>
                            </div>

                            <div class="form-group row">
                                <div class="col-sm-4 mb-sm-0">
                                    <input type="text" class="form-control form-control-user" id="stock" name="stock" aria-describedby="emailHelp" placeholder="Input quantity:" value="<?= $product['stock'] ?>">
                                </div>
                                <div class="col-sm-4 mb-sm-0">
                                    <input type="text" class="form-control form-control-user" id="originalprice" name="originalprice" aria-describedby="emailHelp" placeholder="original price" value="<?= $product['price'] ?>">
                                </div>
                                <div class="col-sm-4 mb-sm-0">
                                    <input type="text" class="form-control form-control-user" id="saleprice" name="saleprice" aria-describedby="emailHelp" placeholder="sale price:" value="<?= $product['disscounted_price'] ?>">
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="form-label">Category:</label>
                                <select class="form-control" name="category">
                                    <option>Select Category</option>
                                    <?php
                                    $sql_str = "SELECT * FROM categories ORDER BY name";
                                    $result = mysqli_query($conn, $sql_str);
                                    while ($row = mysqli_fetch_assoc($result)) {
                                    ?>
                                        <option value="<?= $row['id']; ?>" <?= ($row['name'] == $product['cname']) ? "selected" : ""; ?>><?= $row['name']; ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Status</label>
                                <select name="status" class="form-control">
                                    <option value="Active" <?= $product['pstatus'] == 'Active' ? 'selected' : '' ?>>Active</option>
                                    <option value="Inactive" <?= $product['pstatus'] == 'Inactive' ? 'selected' : '' ?>>Inactive</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Brand:</label>
                                <select class="form-control" name="brand">
                                    <option>Select Brand</option>
                                    <?php
                                    $sql_str = "SELECT * FROM brands ORDER BY name";
                                    $result = mysqli_query($conn, $sql_str);
                                    while ($row = mysqli_fetch_assoc($result)) {
                                    ?>
                                        <option value="<?= $row['id']; ?>" <?= ($row['name'] == $product['bname']) ? "selected" : ""; ?>><?= $row['name']; ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                            <div class="form-group">
    <div class="text-center">
        <button class="btn btn-primary" name="btnUpdate">Update</button>
    </div>
</div>

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
