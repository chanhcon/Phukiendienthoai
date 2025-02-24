<?php
// Kết nối cơ sở dữ liệu
$conn = connectdb();

// Lấy các tham số lọc
$filter = $_GET['filter'] ?? '';
$query = $_GET['query'] ?? '';
cateid = $_GET['cateid'] ?? '';
$subcate_id = $_GET['subcateid'] ?? '';
$min_price = $_GET['minprice'] ?? 0;
$max_price = $_GET['maxprice'] ?? 0;

// Xây dựng câu truy vấn SQL
$sql = "SELECT * FROM tbl_sanpham WHERE 1=1";
if ($cateid) $sql .= " AND ma_danhmuc = '$cateid'";
if ($subcate_id) $sql .= " AND id_dmphu = '$subcate_id'";
if ($query) $sql .= " AND tensp LIKE '%$query%'";
if ($min_price && $max_price) $sql .= " AND don_gia BETWEEN '$min_price' AND '$max_price'";

// Thêm bộ lọc
$sortOptions = [
    'pricedesc' => 'don_gia DESC',
    'priceinsc' => 'don_gia ASC',
    'newest' => 'ngay_nhap DESC',
    'mostview' => 'so_luot_xem DESC'
];
if (isset($sortOptions[$filter])) {
    $sql .= " ORDER BY " . $sortOptions[$filter];
}

// Phân trang
$limit = 12;
$current_page = $_GET['page'] ?? 1;
$start = ($current_page - 1) * $limit;
$sql .= " LIMIT $start, $limit";

// Thực thi truy vấn
$stmt = $conn->prepare($sql);
$stmt->execute();
$product_list = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!-- Giao diện cửa hàng -->
<div class="shop-section container">
    <h1 class="text-center">Cửa hàng</h1>
    <div class="row">
        <!-- Bộ lọc -->
        <div class="col-lg-3">
            <form method="GET">
                <input type="text" name="query" placeholder="Tìm kiếm..." value="<?= $query ?>">
                <select name="filter">
                    <option value="">Lọc sản phẩm</option>
                    <option value="newest" <?= $filter == 'newest' ? 'selected' : '' ?>>Mới nhất</option>
                    <option value="pricedesc" <?= $filter == 'pricedesc' ? 'selected' : '' ?>>Giá cao đến thấp</option>
                    <option value="priceinsc" <?= $filter == 'priceinsc' ? 'selected' : '' ?>>Giá thấp đến cao</option>
                    <option value="mostview" <?= $filter == 'mostview' ? 'selected' : '' ?>>Xem nhiều nhất</option>
                </select>
                <button type="submit">Lọc</button>
            </form>
        </div>

        <!-- Danh sách sản phẩm -->
        <div class="col-lg-9 row">
            <?php foreach ($product_list as $item): ?>
                <div class="col-lg-4 col-md-6">
                    <div class="product-item">
                        <img src="../uploads/<?= explode(',', $item['images'])[0] ?>" alt="<?= $item['tensp'] ?>">
                        <h6><?= $item['tensp'] ?></h6>
                        <p><?= number_format($item['don_gia']) ?> VND</p>
                        <a href="index.php?act=detailproduct&id=<?= $item['masanpham'] ?>">Chi tiết</a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>
