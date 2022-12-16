<?php

@include 'config.php';

session_start();


if(isset($_POST['add_to_cart'])){
   $product_id = $_POST['product_id']; 
   $product_name = $_POST['product_name'];
   $product_price = $_POST['product_price'];
   $product_image = $_POST['product_image'];
   $product_quantity = 1;

   $select_cart = mysqli_query($conn, "SELECT * FROM `cart` WHERE name = '$product_name' and payment = 'Unpaid' and id = '$product_id' and email = '$email'");

   if (isset($_SESSION['email'])){ 
       if(mysqli_num_rows($select_cart) > 0){
          $message[] = 'product already added to cart';
       }else{
          $insert_product = mysqli_query($conn, "INSERT INTO `cart`(name, price, image, quantity, email, payment) VALUES('$product_name', '$product_price', '$product_image', '$product_quantity','$email','Unpaid')");
          $message[] = 'product added to cart succesfully';
       }
   } else {
    ?>
    <script type="text/javascript">
        alert("Please Log in first!");
        window.location.href = "login.php";
    </script>
    <?php
   }

}

if(isset($_POST['product'])){
    $product_id = $_POST['product_id'];
    ?>
    <script type="text/javascript">
        window.location.href="description.php?id=<?php echo $product_id; ?>";
    </script>
    <?php
}


if (isset($_SESSION['email'])){
    $email = $_SESSION['email'];
  } else {
    $email = "";
  }

  $result = mysqli_query($conn,"SELECT * FROM registration WHERE email='$email'");
  $resultCheck = mysqli_num_rows($result);

  $roleDB = "";
  if($resultCheck > 0) {
    while($row = mysqli_fetch_assoc($result)) {
      $emailDB = $row['email'];
      $roleDB = $row['role'];
    }
  }
  
      if(isset($_GET['page_no']) && $_GET['page_no'] !== ""){
      $page_no = $_GET['page_no'];
    } else {
      $page_no = 1;
    }
    
    if (isset($_GET['search'])){
      $search = $_GET['search'];
    } else {
      $search = "";
    }

    if (isset($_GET['brand'])){
      $brand = $_GET['brand'];
    } else {
      $brand = "";
    }

    $value = $search.$brand;

    if (isset($_GET['category'])){
      $category = $_GET['category'];
    } else {
      $category = "";
    }

    if (isset($_GET['filter'])){
      $filter = $_GET['filter'];
    } else {
      $filter = "";
    }

    if (isset($_GET['page_no'])){
      $page_number = $_GET['page_no'];
    } else {
      $page_number = "";
    }



    
    $total_records_per_page = 12;
    $offset = ($page_no - 1) * $total_records_per_page;
    
    $previous_page = $page_no - 1;
    $next_page = $page_no + 1;
    
    if($search != '' || $brand != '' || $category == ''){
      $result_count = mysqli_query($conn, "SELECT COUNT(*) as total_records FROM `products` where category not in ('Upcoming Release') and name like '%$value%' ORDER BY price $filter");
    } else{
      $result_count = mysqli_query($conn, "SELECT COUNT(*) as total_records FROM `products` where category = '$category' and  category not in ('Upcoming Release') and name like '%$value%' ORDER BY price $filter");
    }
    
    $records = mysqli_fetch_array($result_count);
    
    $total_records = $records['total_records'];
    
    $total_no_of_pages = ceil($total_records / $total_records_per_page);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Products</title>
    <link rel="icon" type="image/x-icon" href="images/favicon.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
     integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
     <link rel="stylesheet" href="css/index.css?v=<?php echo time(); ?>">
</head>
<body>

<?php

if(isset($message)){
   foreach($message as $message){
      echo '<div class="message"><span>'.$message.'</span> <i class="fas fa-times" onclick="this.parentElement.style.display = `none`;"></i> </div>';
   };
};

?>
    <?php include 'header.php' ?>

    <!-- Banner -->
    <div class="container mt-4 mb-4 banner ">
        <div id="carouselExampleIndicators" class="carousel slide  carousel_product" data-ride="carousel" data-interval="4000">
            <div class="carousel-indicators">
            <?php 
                  $select_banner = mysqli_query($conn, "SELECT * FROM `carousel`");
                  $count1=0;
                  $count_item=1;
                  if(mysqli_num_rows($select_banner) > 0 ){
                      while($row = mysqli_fetch_assoc($select_banner)){
                        if($count1 < 1){
                          $active_button = 'active';
                          $true='true';
                        } else {
                          $active_button = '';
                          $true='';
                        }
                ?>
              <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="<?php echo $count_item-1 ?>" class="<?php echo $active_button ?>" aria-current="<?php echo $true ?>" aria-label="Slide <?php echo $count_item ?>"></button>
              <?php
                      $count1++;
                      $count_item++;
                    }  
                  }
                ?>
            </div>
            <div class="carousel-inner rounded-mid">  
                <?php 
                  $select_banner = mysqli_query($conn, "SELECT * FROM `carousel`");
                  $count2=0;
                  if(mysqli_num_rows($select_banner) > 0 ){
                      while($row = mysqli_fetch_assoc($select_banner)){
                        if($count2 < 1){
                          $active_item = 'active';
                        } else {
                          $active_item = '';
                        }
                ?>
                  <div class="carousel-item <?php echo $active_item ?> carousel_item_product">
                    <img src="uploaded_img/<?php echo $row['image']; ?>" class="d-block w-100" alt="...">
                  </div>
                <?php
                      $count2++;
                    }  
                  }
                ?>
            </div>
            <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="prev">
              <span class="carousel-control-prev-icon" aria-hidden="true"></span>
              <span class="visually-hidden">Previous</span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="next">
              <span class="carousel-control-next-icon" aria-hidden="true"></span>
              <span class="visually-hidden">Next</span>
            </button>
          </div>
    </div>


<div class="container">
    <div class="row">
        <div class="col col-12 col-md-2">
        <div class="flex">
          <div class="tab my-4 mt-5">
            <p class="h4">Filter</p>
            <p class="">By Category</p>
            <div>
              <button class="tablinks button-27 mb-1" onclick="openCategory('')">All</button>
            </div>
            <div>
              <button class="tablinks button-27 mb-1" onclick="openCategory('Men')">Men</button>
            </div>
            <div>
              <button class="tablinks button-27 mb-1" onclick="openCategory('Women')">Women</button>
            </div>
            <div>
              <button class="tablinks button-27 mb-1" onclick="openCategory('Unisex')">Unisex</button>
            </div>
            <div>
              <button class="tablinks button-27 mb-3" onclick="openCategory('Kids')">Kids</button>
            </div>
            <p class="">By Brand</p>
            <div>
              <button class="tablinks button-27 mb-1" onclick="openBrand('Under Armour')">Under Armour</button>
            </div>
            <div>
              <button class="tablinks button-27 mb-1" onclick="openBrand('Anta')">Anta</button>
            </div>
            <div>
              <button class="tablinks button-27 mb-1" onclick="openBrand('Nike')">Nike</button>
            </div>
            <div>
              <button class="tablinks button-27 mb-1"onclick="openBrand('Jordan')">Jordan</button>
            </div>
            <div>
              <button class="tablinks button-27 mb-1" onclick="openBrand('Adidas')">Adidas</button>
            </div>
            <div>
              <button class="tablinks button-27 mb-1" onclick="openBrand('Vans')">Vans</button>
            </div>
            <div>
              <button class="tablinks button-27 mb-1" onclick="openBrand('Converse')">Converse</button>
            </div>
            <div>
              <button class="tablinks button-27 mb-1" onclick="openBrand('Puma')">Puma</button>
            </div>
          </div>
        </div>
        </div>
        <div class="col">
        <div id="All" class="tabcontent">
          <div class="row">
            <section class="py-5">
              <div class="container">
                  <div class="row">

                    <div class="col mb-4">
                      <div class="">
                            <span class="h3"> 
                              <?php
                                if($search != ''){
                                  echo 'Search results for '; echo'<strong>'; echo $search; echo '</strong>';
                                } elseif ($brand != '') {
                                  echo $brand;
                                } elseif ($category != ''){
                                  echo $category;
                                } else {
                                  echo 'All';
                                }
                              ?>
                      </div>
                    </div>
                    <div class="col mb-4  text-end">
                      <div class=" btn-group text-center">
                            <h5 class="me-3 mt-1">Price: </h5>
                            <button type="button" class="btn btn-danger dropdown-toggle text-center" data-bs-toggle="dropdown" aria-expanded="false">
                              Filter
                            </button>
                            <ul class="dropdown-menu">
                              <li><a class="dropdown-item" href="?filter=desc&category=<?php echo $category?>&brand=<?php echo $brand?>&search=<?php echo $search?>&page_no=<?php echo $page_number?>">High to Low</a></li>
                              <li><a class="dropdown-item" href="?filter=asc&category=<?php echo $category?>&brand=<?php echo $brand?>&search=<?php echo $search?>&page_no=<?php echo $page_number?>">Low to High</a></li>
                            </ul>
                      </div>
                    </div>
                  </div>
                      <div class="row gx-4 gx-lg-5 row-cols-2 row-cols-md-3 row-cols-xl-4 justify-content-center">
                        <?php
                          if($search != '' || $brand != '' || $category == ''){
                            $select_products = mysqli_query($conn, "SELECT * FROM `products` where category not in ('Upcoming Release') and name like '%$value%' ORDER BY price $filter LIMIT $offset, $total_records_per_page ");
                          } else {
                            $select_products = mysqli_query($conn, "SELECT * FROM `products` where category = '$category' and category not in ('Upcoming Release') and name like '%$value%' ORDER BY price $filter LIMIT $offset, $total_records_per_page");
                          }
                          if(mysqli_num_rows($select_products) > 0 ){
                        
                              while($row = mysqli_fetch_assoc($select_products)){
                          ?>
                            <div class="col mb-5 text-center hoverBox">
                                <div class="card h-100 text-center border border-gray productBorder">
                                  <form action="" method="post">
                                    <button class="button_product" name="product">
                                        <img src="uploaded_img/<?php echo $row['image']; ?>" alt="" height="150px" id="product">
                                        <h4 ><?php echo $row['name']; ?></h4>
                                        <div >₱<?php echo number_format($row['price']); ?></div>
                                        <input type="hidden" name="product_id" value="<?php echo $row['id']; ?>">
                                        <input type="hidden" name="product_name" value="<?php echo $row['name']; ?>">
                                        <input type="hidden" name="product_price" value="<?php echo $row['price']; ?>">
                                        <input type="hidden" name="product_image" value="<?php echo $row['image']; ?>">
                                    </button>
                                </form>
                                </div>
                            </div>
                          <?php
                            }
                          }
                          ?>
                      </div>
                      <div class="container d-flex justify-content-center pages">
                        <nav aria-label="Page navigation example">
                          <ul class="pagination">
                            <li class="page-item <?= ($page_no <=1) ?
                             ' disabled ' : '';?>"><a class="page-link " <?= ($page_no > 1)? 'href=?page_no=' .
                             $previous_page.'&category='.$category.'&brand='.$brand.'&search='.$search.'&filter='.$filter : '';?>>Previous</a></li>

                            <?php 
                              for($counter = 1; $counter <=$total_no_of_pages; $counter++){
                              ?>
                              <?php if($page_no != $counter){ ?>
                                <li class="page-item"><a class="page-link " href="?
                                page_no=<?= $counter;?>&category=<?= $category;?>&brand=<?= $brand;?>&search=<?= $search;?>&filter=<?= $filter;?>"><?= $counter;?></a></li>
                            <?php }else{ ?>
                                <li class="page-item active"><a class="page-link"><?=
                                   $counter;?> </a></li>
                                <?php }  ?>
                              <?php } ?>
                            <li class="page-item <?= ($page_no >=$total_no_of_pages)? ' disabled ' : ''?>"><a class="page-link" <?= ($page_no < $total_no_of_pages)? 'href=?page_no=' .$next_page .'&search='.$search.'&brand='.$brand.'&filter='.$filter  : '';?>>Next</a></li>
                          </ul>
                        </nav> 
                      </div>
                      <div class="container d-flex justify-content-center p-10">
                        <strong>Page <?php echo $page_no; ?> of <?php echo $total_no_of_pages; ?></strong>
                      </div>     
                    </div>
                  </section>
              </div>
        </div>
        </div>
    </div>
</div>    


    <!-- Footer  -->
    <?php include 'footer.php' ?>

    <script>
        function openBrand(value){
            window.location.href = "products.php?brand=" + value;
        }
    </script>   
    
    <script>
        function openCategory(value){
            window.location.href = "products.php?category=" + value;
        }
    </script> 


    <script>
        function myFunction() {
        document.getElementById("myDropdown").classList.toggle("show");
        }

        window.onclick = function(event) {
        if (!event.target.matches('.dropbtn')) {
            var dropdowns = document.getElementsByClassName("dropdown-content");
            var i;
            for (i = 0; i < dropdowns.length; i++) {
            var openDropdown = dropdowns[i];
            if (openDropdown.classList.contains('show')) {
                openDropdown.classList.remove('show');
            }
            }
        }
        }
    </script>     
    <script src="https://code.jquery.com/jquery-3.6.0.js"
    integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk="
    crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" 
    integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" 
    crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" 
    integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" 
    integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous"></script>
</body>
</html>