<?php
require 'database.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Filtering Products</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <style>
        .card-image-top {
            width: 100%;
            height: 280px;
            object-fit: cover;
        }

        .navbar-nav {
            margin-left: auto;
        }
    </style>
</head>

<body>

    <h3 class="text-center text-light bg-info p-2">Ecommerce Website</h3>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container-fluid">
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item d-flex align-items-center">
                        <a class="nav-link active" aria-current="page" href="#">
                            <i class="fas fa-shopping-cart"></i>
                            <span id="cart-item" class="badge rounded-pill bg-danger"></span> Cart
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="#">Checkout</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-3">
                <h5>Filter Products</h5>
                <hr>
                <h6 class="text-info">Select Brand</h6>
                <ul class="list-group">
                    <?php
                    $sql = "SELECT DISTINCT brand_name FROM products ORDER BY brand_name";
                    $result = mysqli_query($con, $sql);
                    while ($row = mysqli_fetch_assoc($result)) {
                    ?>
                        <li class="list-group-item">
                            <div class="form-check">
                                <label class="form-check-label">
                                    <input type="checkbox" class="form-check-input product_check brands" value="<?php echo $row['brand_name']; ?>"> <?php echo $row['brand_name']; ?>
                                </label>
                            </div>
                        </li>
                    <?php
                    }
                    ?>
                </ul>

                <h6 class="text-info">Select Category</h6>
                <ul class="list-group">
                    <?php
                    $sql = "SELECT DISTINCT cat_name FROM products ORDER BY cat_name";
                    $result = mysqli_query($con, $sql);
                    while ($row = mysqli_fetch_assoc($result)) {
                    ?>
                        <li class="list-group-item">
                            <div class="form-check">
                                <label class="form-check-label">
                                    <input type="checkbox" class="form-check-input product_check category" value="<?php echo $row['cat_name']; ?>"> <?php echo $row['cat_name']; ?>
                                </label>
                            </div>
                        </li>
                    <?php
                    }
                    ?>
                </ul>
            </div>

            <div class="col-lg-9">
                <h5 class="text-center" id="textChange">All Products</h5>
                <div class="row" id="result"></div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            fetchResults();

            function fetchResults() {
                var selectedBrand = [];
                var selectedCat = [];

                $('.brands:checked').each(function() {
                    selectedBrand.push($(this).val());
                });

                $('.category:checked').each(function() {
                    selectedCat.push($(this).val());
                });

                $.ajax({
                    url: "action.php",
                    method: 'post',
                    data: {
                        brand: selectedBrand,
                        category: selectedCat
                    },
                    success: function(response) {
                        $('#result').html(response);
                        $('#textChange').text((selectedBrand.length || selectedCat.length) ? "Filtered Products" : "All Products");
                    }
                });
            }

            $(".product_check").on('change', function() {
                fetchResults();
            });

            $(document).on('click', '.addItemBtn', function() {
                var productId = $(this).data('product_id');
                var quantity = 1;

                $.ajax({
                    url: 'action.php',
                    type: 'post',
                    data: {
                        product_id: productId,
                        quantity: quantity
                    },
                    success: function(response) {
                        updateCartItemCount();
                        alert("Product added to cart successfully!");
                    },
                    error: function() {
                        alert('Error adding to cart');
                    }
                });
            });

            function updateCartItemCount() {
                $.ajax({
                    url: 'get_cart_count.php',
                    type: 'get',
                    success: function(response) {
                        $('#cart-item').text(response);
                    }
                });
            }
        });
    </script>

</body>

</html>