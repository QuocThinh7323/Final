<?php 
require('includes/header.php');
?>

<div class="container mt-5">
    <div class="card shadow-lg border-0">
        <div class="card-body p-5">
            <div class="text-center mb-4">
                <h2 class="h4 text-gray-900">Add New Product</h2>
            </div>
            
            <form class="user" method="post" action="addproduct.php" enctype="multipart/form-data">
                
                <!-- Product Name -->
                <div class="form-group">
                    <label for="name" class="form-label">Product Name</label>
                    <input type="text" class="form-control" id="name" name="name" placeholder="Enter product name" required>
                </div>
                
                <!-- Images Upload -->
                <div class="form-group">
                    <label for="anhs" class="form-label">Images for product</label>
                    <input type="file" class="form-control" id="anhs" name="anhs[]" multiple required>
                </div>
                
                <!-- Summary -->
                <div class="form-group">
                    <label for="sumary" class="form-label">Product Summary</label>
                    <textarea name="sumary" class="form-control" id="sumary" rows="3" placeholder="Enter product summary" required></textarea>
                </div>
                
                <!-- Description -->
                <div class="form-group">
                    <label for="description" class="form-label">Product Description</label>
                    <textarea name="description" class="form-control" id="description" rows="5" placeholder="Enter detailed product description" required></textarea>
                </div>

                <!-- Stock, Original Price, and Sale Price -->
                <div class="form-row">
                    <div class="col-md-4 mb-3">
                        <label for="stock" class="form-label">Stock Quantity</label>
                        <input type="number" class="form-control" id="stock" name="stock" placeholder="Quantity" required>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="originalprice" class="form-label">Original Price</label>
                        <input type="number" step="0.01" class="form-control" id="originalprice" name="originalprice" placeholder="Original price" required>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="saleprice" class="form-label">Sale Price</label>
                        <input type="number" step="0.01" class="form-control" id="saleprice" name="saleprice" placeholder="Sale price" >
                    </div>
                </div>
                
                <!-- Creation Date -->
                <div class="form-group">
                    <label for="creation_date" class="form-label">Creation Date</label>
                    <input type="date" class="form-control" id="creation_date" name="creation_date">
                </div>
                
                <!-- Category Dropdown -->
                <div class="form-group">
                    <label for="category" class="form-label">Category</label>
                    <select class="form-control" id="category" name="category" required>
                        <option selected disabled>Select category</option>
                        <?php 
                        require('../db/conn.php');
                        $sql_str = "select * from categories order by name";
                        $result = mysqli_query($conn, $sql_str);
                        while ($row = mysqli_fetch_assoc($result)){
                        ?>
                            <option value="<?php echo $row['id'];?>"><?php echo $row['name'];?></option>
                        <?php } ?>
                    </select>
                </div>

                <!-- Brand Dropdown -->
                <div class="form-group">
                    <label for="brand" class="form-label">Brand</label>
                    <select class="form-control" id="brand" name="brand" required>
                        <option selected disabled>Select brand</option>
                        <?php 
                        require('../db/conn.php');
                        $sql_str = "select * from brands order by name";
                        $result = mysqli_query($conn, $sql_str);
                        while ($row = mysqli_fetch_assoc($result)){
                        ?>
                            <option value="<?php echo $row['id'];?>"><?php echo $row['name'];?></option>
                        <?php } ?>
                    </select>
                </div>
                
                <!-- Submit Button -->
                <button class="btn btn-primary btn-block">Create Product</button>
                
            </form>
        </div>
    </div>
</div>

<?php
require('includes/footer.php');
?>
