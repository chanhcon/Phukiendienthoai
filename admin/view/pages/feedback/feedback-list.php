

<div class="card">
    <div class="card-body">
        <div class="d-flex align-items-center">
            <h5 class="mb-0">Danh sách feedback người dùng</h5>
            <form class="ms-auto position-relative" method="POST">
                <div class="position-absolute top-50 translate-middle-y search-icon px-3"><i class="bi bi-search"></i>
                </div>
                <input class="form-control ps-5" type="text" name="search" placeholder="Tìm kiếm feedback" value="<?php echo htmlspecialchars($search_query, ENT_QUOTES, 'UTF-8'); ?>">
            </form>
        </div>
        <div class="mt-3 table-responsive">
            <table class="table align-middle table-hover">
                <thead class="table-secondary">
                    <tr>
                        <th scope="col">#ID</th>
                        <th scope="col" style="min-width:140px;">Tên người dùng</th>
                        <th scope="col">Email</th>
                        <th scope="col" style="min-width:130px;">Số điện thoại</th>
                        <th scope="col" style="min-width:150px;">Chủ đề</th>
                        <th scope="col">Nội dung</th>
                        <th scope="col">Ngày gửi</th>
                        <th scope="col" style="min-width:100px;">Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    foreach ($feedback_list as $feedback) {
                        echo '
                        <tr>
                            <td scope="row">#' . htmlspecialchars($feedback['id'], ENT_QUOTES, 'UTF-8') . '</td>
                            <td>' . htmlspecialchars($feedback['name'], ENT_QUOTES, 'UTF-8') . '</td>
                            <td>' . htmlspecialchars($feedback['email'], ENT_QUOTES, 'UTF-8') . '</td>
                            <td>' . htmlspecialchars($feedback['phone'], ENT_QUOTES, 'UTF-8') . '</td>
                            <td>' . htmlspecialchars($feedback['title'], ENT_QUOTES, 'UTF-8') . '</td>
                            <td style="width: 200px; word-wrap: break-word">' . htmlspecialchars($feedback['content'], ENT_QUOTES, 'UTF-8') . '</td>
                            <td>' . date('d-m-Y H:i:s', strtotime($feedback['date_create'])) . '</td>
                            <td style="cursor: pointer">
                                <a href="reply-feedback.php?id=' . $feedback['id'] . '" style="font-weight: 550;">Reply</a>
                            </td>
                        </tr>';
                    }
                    ?>
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <nav>
