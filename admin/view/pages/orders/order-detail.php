<?php
// Ensure 'iddh' is present in the URL
if (!isset($_GET['iddh'])) {
    die('Order ID is required');
}

$iddh = $_GET['iddh'];
$order_detail_list = getshoworderdetail($iddh);
$order_info = getorderinfo($iddh);

// Fetch order status and message
$trangthai = showStatus($order_info['trangthai'])[0];
$message = showStatus($order_info['trangthai'])[1];

// Fetch payment status
switch ($order_info['thanhtoan']) {
    case '0':
        $thanhtoan = "Chưa thanh toán";
        break;
    case '1':
        $thanhtoan = "Đã thanh toán";
        break;
    default:
        $thanhtoan = "Không xác định"; // Default case if something unexpected happens
        break;
}
?>

<div class="card">
    <div class="card-header py-3">
        <div class="row g-3 align-items-center">
            <div class="col-12 col-lg-3 col-md-6 me-auto">
                <h5 class="mb-1">Thời gian đặt: <?php echo htmlspecialchars($order_info['timeorder']); ?></h5>
                <p class="mb-0">Order ID : #<?php echo htmlspecialchars($order_info['id']); ?></p>
            </div>
            <div class="col-12 col-lg-3 col-md-6"></div>
            <div class="col-12 col-lg-3 col-6 col-md-3 hide-on-print">
                <?php
                // Generate status dropdown based on the current status
                switch ($order_info['trangthai']) {
                    case "1":
                        ?>
                        <select id="select-status" class="form-select">
                            <option value="-1">Thay đổi trạng thái đơn hàng</option>
                            <option value="2" <?php echo ($order_info['trangthai'] == 1) ? 'selected' : ''; ?>>Xác nhận đơn hàng</option>
                        </select>
                        <?php
                        break;
                    case "2":
                        ?>
                        <select id="select-status" class="form-select">
                            <option value="-1">Thay đổi trạng thái đơn hàng</option>
                            <option value="3" <?php echo ($order_info['trangthai'] == 2) ? 'selected' : ''; ?>>Đang gửi hàng</option>
                        </select>
                        <?php
                        break;
                    case "3":
                        ?>
                        <select id="select-status" class="form-select">
                            <option value="-1">Thay đổi trạng thái đơn hàng</option>
                            <option value="4" <?php echo ($order_info['trangthai'] == 3) ? 'selected' : ''; ?>>Đã gửi hàng thành công</option>
                            <option value="5" <?php echo ($order_info['trangthai'] == 4) ? 'selected' : ''; ?>>Giao hàng thất bại</option>
                            <option value="6" <?php echo ($order_info['trangthai'] == 5) ? 'selected' : ''; ?>>Đã hủy hàng</option>
                        </select>
                        <?php
                        break;
                    case "4":
                    case "5":
                    case "6":
                        ?>
                        <select disabled="true" class="form-select">
                            <option value="4" <?php echo ($order_info['trangthai'] == 4) ? 'selected' : ''; ?>>Giao hàng thành công</option>
                            <option value="5" <?php echo ($order_info['trangthai'] == 5) ? 'selected' : ''; ?>>Giao hàng thất bại</option>
                            <option value="6" <?php echo ($order_info['trangthai'] == 6) ? 'selected' : ''; ?>>Đơn hàng đã bị hủy</option>
                        </select>
                        <?php
                        break;
                    default:
                        ?>
                        <select disabled="true" class="form-select">
                            <option value="-1">Trạng thái không xác định</option>
                        </select>
                        <?php
                }
                ?>
            </div>
            <div class="col-12 col-lg-3 col-6 col-md-3 hide-on-print">
                <button type="button" class="btn btn-primary" onclick="changeStatus(<?php echo $_GET['iddh']; ?>)">Lưu</button>
                <a href="javascript:window.print()" type="button" class="btn btn-secondary"><i class="bi bi-printer-fill"></i> In</a>
            </div>
            <?php if (isset($order_info['trangthai']) && $order_info['trangthai'] == 6): ?>
                <p class="fw-bold fs-5">Lý do hủy đơn hàng: <?php echo htmlspecialchars($order_info['reason_destroy']); ?></p>
            <?php endif ?>
        </div>
    </div>
    <div class="card-body">
        <div class="row row-cols-1 row-cols-xl-2 row-cols-xxl-3">
            <div class="col">
                <div class="card border shadow-none radius-10">
                    <div class="card-body">
                        <div class="d-flex align-items-center gap-3">
                            <div class="icon-box bg-light-primary border-0">
                                <i class="bi bi-person text-primary"></i>
                            </div>
                            <div class="info">
                                <h6 class="mb-2">Khách hàng</h6>
                                <p class="mb-1"><?php echo htmlspecialchars($order_info['name']); ?></p>
                                <p class="mb-1"><?php echo htmlspecialchars($order_info['email']); ?></p>
                                <p class="mb-1"><?php echo htmlspecialchars($order_info['dienThoai']); ?></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="card border shadow-none radius-10">
                    <div class="card-body">
                        <div class="d-flex align-items-center gap-3">
                            <div class="icon-box bg-light-success border-0">
                                <i class="bi bi-truck text-success"></i>
                            </div>
                            <div class="info">
                                <h6 class="mb-2">Thông tin gửi hàng</h6>
                                <p class="mb-1"><strong>Shipping</strong>: GHN</p>
                                <p class="mb-1"><strong>Pttt</strong>: <?php echo htmlspecialchars($order_info['pttt']); ?></p>
                                <p class="mb-1"><strong>Trạng thái</strong>: <?php echo htmlspecialchars($message); ?></p>
                                <p class="mb-1"><strong>Trạng thái thanh toán</strong>: <?php echo htmlspecialchars($thanhtoan); ?></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
 
