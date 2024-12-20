<?php 



$id = $_GET['id'];


require('../db/conn.php');

$sql_str = "select 
* from news where id=$id";


$res = mysqli_query($conn, $sql_str);

$news = mysqli_fetch_assoc($res);

if (isset($_POST['btnUpdate'])){
   
   $name = $_POST['title'];
   $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $name)));
   $summary = $_POST['summary'];
   $description = $_POST['description'];
  
   $category = $_POST['category'];
  
  
 

   if (!empty($_FILES['anh']['name'])){
    
   
    unlink($news['avatar']);
    
    
    
    // $imgs = '';
    // for($i=0;$i<$countfiles;$i++){
        $filename = $_FILES['anh']['name'];

        ## Location
        $location = "uploads/news/".uniqid().$filename;
                    //pathinfo ( string $path [, int $options = PATHINFO_DIRNAME | PATHINFO_BASENAME | PATHINFO_EXTENSION | PATHINFO_FILENAME ] ) : mixed
        $extension = pathinfo($location,PATHINFO_EXTENSION);
        $extension = strtolower($extension);

        ## File upload allowed extensions
        $valid_extensions = array("jpg","jpeg","png");

        $response = 0;
        ## Check file extension
        if(in_array(strtolower($extension), $valid_extensions)) {

       
            ## Upload file
                                //$_FILES['file']['tmp_name']: $_FILES['file']['tmp_name'] - The temporary filename of the file in which the uploaded file was stored on the server.
            if(move_uploaded_file($_FILES['anh']['tmp_name'],$location)){

                // $imgs .= $location . ";";
            }
        }

    // }

    // echo substr($imgs, 0, -1); exit;
    
  
    $sql_str = "UPDATE `news` 
        SET `title`='$name', 
        `slug`='$slug', 
        `description`='$description', 
        `sumary`='$summary', 
        `avatar`='$location', 
        `newscategory_id`=$category, 
        `updated_at`=now()
        WHERE `id`=$id
        ";
   } else {
    $sql_str = "UPDATE `news` 
        SET `title`='$name', 
        `slug`='$slug', 
        `description`='$description', 
        `sumary`='$summary', 
        `newscategory_id`=$category, 
        `updated_at`=now()
        WHERE `id`=$id
        ";
   }
   

//    echo $sql_str; exit;

   mysqli_query($conn, $sql_str);

   
   header("location: ./listnews.php");
} else {
    require('includes/header.php');
?>

<div class="container">

<div class="card o-hidden border-0 shadow-lg my-5">
    <div class="card-body p-0">
      
        <div class="row">
            <div class="col-lg-12">
                <div class="p-5">
                    <div class="text-center">
                        <h1 class="h4 text-gray-900 mb-4">Update News</h1>
                    </div>
                    <form class="user" method="post" action="#" enctype="multipart/form-data">                        
                    <div class="form-group">
                        <input type="text" class="form-control form-control-user"
                            id="title" name="title" aria-describedby="emailHelp"
                            placeholder="Product Name"
                            value="<?=$news['title']?>">
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">Avatar</label>
                        <input type="file" class="form-control form-control-user"
                            id="anh" name="anh" 
                            >
                        <br>
                        Current picture:
                        <?php $avatar = $news['avatar']; ?>
                        <img src='<?=$avatar?>' height='100px' />
                       
                    </div>
                    <div class="form-group">
                        <label class="form-label">News Summary:</label>
                        <textarea name="summary" class="form-control" placeholder="Enter...">
                        <?=$news['sumary']?>
                        </textarea>
                    </div>
                    <div class="form-group">
                    <label class="form-label">News Content:</label>
                        <textarea name="description" class="form-control" placeholder="Enter...">
                        <?=$news['description']?>
                        </textarea>
                    </div>
                    
                   
                   
                   
                    <div class="form-group">
                        <label class="form-label">News Category:</label>
                        <select class="form-control" name="category">
                            <option>Select category</option>
                            <?php 
    // require('../db/conn.php');
    $sql_str = "select * from newscategories order by name";
    $result = mysqli_query($conn, $sql_str);
    while ($row = mysqli_fetch_assoc($result)){
        ?>
        <option value="<?php echo $row['id'];?>"
            <?php
                if ($row['id'] == $news['newscategory_id'])
                    echo "selected";

            ?>
        ><?php echo $row['name'];?></option>
        <?php } ?>
                        </select>
                    </div>
                    
                    <button class="btn btn-primary" name="btnUpdate">Update</button>
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
}
?>