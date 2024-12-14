<?php
require 'database.php';

if (isset($_POST['product_id']) && $_POST['product_id']) {
    $product_id = $_POST['product_id'];
    $quantity = $_POST['quantity'];

    $sql = "SELECT * FROM cart WHERE product_id = '$product_id'";
    $result = mysqli_query($con, $sql);

    if (mysqli_num_rows($result) > 0) {
        $update_sql = "UPDATE cart SET quantity = quantity + 1 WHERE product_id = '$product_id'";
        if (!mysqli_query($con, $update_sql)) {
            echo "Error updating product quantity: " . mysqli_error($con);
        }
    } else {
        $add_sql = "INSERT INTO cart (product_id, quantity) VALUES ('$product_id', '$quantity')";
        if (!mysqli_query($con, $add_sql)) {
            echo "Error adding to cart: " . mysqli_error($con);
        }
    }
}

$brand = isset($_POST['brand']) ? $_POST['brand'] : [];
$category = isset($_POST['category']) ? $_POST['category'] : [];

$sql = "SELECT * FROM products";

$filterConditions = [];

if (!empty($brand)) {
    $brandConditions = [];
    foreach ($brand as $b) {
        $brandConditions[] = "brand_name = '" . mysqli_real_escape_string($con, $b) . "'";
    }
    if (!empty($brandConditions)) {
        $filterConditions[] = "(" . implode(" OR ", $brandConditions) . ")";
    }
}

if (!empty($category)) {
    $categoryConditions = [];
    foreach ($category as $c) {
        $categoryConditions[] = "cat_name = '" . mysqli_real_escape_string($con, $c) . "'";
    }
    if (!empty($categoryConditions)) {
        $filterConditions[] = "(" . implode(" OR ", $categoryConditions) . ")";
    }
}

if (count($filterConditions) > 0) {
    $sql .= " WHERE " . implode(' AND ', $filterConditions);
}

$result = mysqli_query($con, $sql);
$output = '';

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $output .= '<div class="col-md-3 mb-2">
                        <div class="card">
                            <div class="card-body">
                                <img src="images/' . htmlspecialchars($row['image']) . '" class="img-fluid card-image-top">
                                <p>
                                    Name: ' . htmlspecialchars($row['cat_name']) . '<br>
                                    Price: ' . htmlspecialchars($row['price']) . '
                                </p>
                            </div>
                            <div class="card-footer p-1">
                                    <button type="button" class="btn btn-primary addItemBtn" data-product_id="' . $row['id'] . '">Add to cart</button>
                            </div>
                        </div>
                    </div>';
    }
} else {
    $output = "<h3>No results found</h3>";
}

echo $output;
