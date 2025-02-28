<?php
$servername = "localhost";
$username = "root";
$password = "";
$database = "agrijapan_db";

// Kết nối MySQL
$conn = new mysqli($servername, $username, $password, $database);
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}

// Thêm sản phẩm vào bảng `products`
$sql = "INSERT INTO products (categoryId, name, price, originalPrice, image, detail) 
        VALUES (1, 'xxxxx', 67900, 69900, 
        'https://zalo-miniapp.github.io/zaui-market/dummy/product/apples.png', 
        'Táo Fuji tươi ngon, giòn và ngọt tự nhiên. Được tuyển chọn kỹ càng từ những vườn cây chất lượng cao.')";

if ($conn->query($sql) === TRUE) {
    echo "Thêm sản phẩm thành công!";
    
    // Gọi export_json.php để cập nhật product.json
    file_get_contents("http://localhost/backend/export_json.php");
} else {
    echo "Lỗi: " . $conn->error;
}

$conn->close();
?>
