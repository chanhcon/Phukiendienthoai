<?php
ob_start();
session_start();
include "../models/connectdb.php";
include "../models/user.php";
// $FOLDER_VAR = "/";
// $ROOT_URL = $_SERVER['DOCUMENT_ROOT'] . "$FOLDER_VAR";
include "../../global.php";
include "$ROOT_URL/pdo-library.php";
include "$ROOT_URL/DAO/user.php";
?>

<?php
if (isset($_POST['loginbtn']) && $_POST['loginbtn']) {
    $error = array();
    $email = $_POST['email'];
    $password = $_POST['password'];
    // Đối chiếu password
    //Validate form
    if (empty($email)) {
        $error['email'] = "Không để trống email!";
    }
    if (empty($password)) {
        $error['password'] = "Không để trống password!";
    }
    if (!$error) {
        $password = md5($password);
        $islogined = checkuser2($email, $password);
        if ($islogined === -1) {
            echo '<div class="alert-warning alert text-center" style="">Email hoặc password không chính xác</div>';
        } else {
            $kq = getuserinfo2($email, $password);
            $role = $kq[0]['vai_tro'];
            if ($role == 1 || $role == 2) {
                $_SESSION['role'] = $role;
                $_SESSION['username'] = $kq[0]['ho_ten'];
                $_SESSION['iduser'] = $kq[0]['id'];
                $_SESSION['img'] = $kq[0]['hinh_anh'];
                header('Location: ../index.php');
            } else {
                echo '
                    <script>
                        window.alert("Email hoặc mật khẩu của bạn không đúng. Xin vui lòng nhập lại!");
                    </script>
                ';
            }
        }
    }
}
?>

<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" href="../assets/images/favicon-32x32.png" type="image/png" />
    <!-- Bootstrap CSS -->
    <link href="../assets/css/bootstrap.min.css" rel="stylesheet" />
    <link href="../assets/css/bootstrap-extended.css" rel="stylesheet" />
    <link href="../assets/css/style.css" rel="stylesheet" />
    <link href="../assets/css/icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css">

    <!-- loader-->
    <link href="../assets/css/pace.min.css" rel="stylesheet" />
    <style>
        body {
            background-color: #f3f4f7;
        }

        .wrapper {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .authentication-card {
            width: 100%;
            max-width: 450px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        .card-body {
            padding: 3rem;
        }

        .card-title {
            font-size: 24px;
            font-weight: bold;
            text-align: center;
            color: #ff7f00;
        }

        .form-control {
            border-radius: 30px;
            padding-left: 40px;
            font-size: 16px;
        }

        .form-control:focus {
            border-color: #ff7f00;
            box-shadow: 0 0 5px rgba(255, 127, 0, 0.5);
        }

        .search-icon {
            position: absolute;
            top: 50%;
            left: 10px;
            transform: translateY(-50%);
        }

        .btn-primary-login {
            background-color: #ff7f00;
            color: white;
            border-radius: 30px;
            font-weight: bold;
            transition: background-color 0.3s;
        }

        .btn-primary-login:hover {
            background-color: #d56900;
            border: none;
        }

        .form-check-label {
            font-size: 14px;
            color: #ff7f00;
        }

        .text-end a {
            color: #ff7f00;
            font-weight: bold;
        }

        .error-message-login {
            color: red;
            font-weight: 500;
            margin-top: 5px;
            margin-left: 5px;
        }

        label.error {
            color: red;
            font-weight: 550;
        }

        .images img {
            width: 100%;
            border-radius: 10px;
        }

        .login-separater {
            text-align: center;
            margin: 1.5rem 0;
            position: relative;
            font-weight: bold;
            color: #777;
        }

        .login-separater hr {
            border: 1px solid #ff7f00;
            width: 50%;
            margin: auto;
        }

        .login-separater span {
            position: absolute;
            left: 50%;
            transform: translateX(-50%);
            background-color: #fff;
            padding: 0 10px;
            top: -12px;
        }
    </style>

    <title>Admin Golden Bee Group</title>
</head>

<body>

    <div class="wrapper">
        <div class="authentication-card">
            <div class="row g-0">
                <div class="col-lg-6 d-flex align-items-center justify-content-center images">
                    <img src="../assets/images/error/login-admin.png" class="img-fluid" alt="login-image">
                </div>
                <div class="col-lg-6">
                    <div class="card-body p-4 p-sm-5">
                        <h5 class="card-title">Đăng nhập</h5>
                        <p class="card-text mb-5 text-center">Đăng nhập để vào trang quản trị admin</p>
                        <form class="form-body" id="form-login-admin" action="./login.php" method="post">
                            <input type="hidden" name="csrf_token" value="<?= generateCSRFToken() ?>">

                            <div class="row g-3">

                                <div class="col-12">
                                    <label for="inputEmailAddress" class="form-label">Địa chỉ email </label>
                                    <div class="ms-auto position-relative">
                                        <div class="position-absolute top-50 translate-middle-y search-icon px-3">
                                            <i class="bi bi-envelope-fill"></i>
                                        </div>
                                        <input type="email" class="form-control ps-5" id="inputEmailAddress" placeholder="Email" name="email">
                                    </div>
                                    <p class="error-message-login">
                                        <?php echo isset($error['email']) ? $error['email'] : ''; ?></p>
                                </div>

                                <div class="col-12">
                                    <label for="inputChoosePassword" class="form-label">Nhập mật khẩu</label>
                                    <div class="ms-auto position-relative">
                                        <div class="position-absolute top-50 translate-middle-y search-icon px-3">
                                            <i class="bi bi-lock-fill"></i>
                                        </div>
                                        <input type="password" class="form-control ps-5" id="inputChoosePassword" placeholder="Mật khẩu" name="password">
                                    </div>
                                    <p class="error-message-login">
                                        <?php echo isset($error['password']) ? $error['password'] : ''; ?>
                                    </p>
                                </div>

                                <div class="col-6">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" id="flexSwitchCheckChecked" checked="">
                                        <label class="form-check-label" for="flexSwitchCheckChecked">Nhớ thông tin</label>
                                    </div>
                                </div>
                                <div class="col-6 text-end"> <a href="./forgot.php">Quên mật khẩu?</a></div>

                                <div class="col-12">
                                    <div class="d-grid">
                                        <input type="submit" name="loginbtn" class="btn btn-primary btn-primary-login" value="Đăng nhập" />
                                    </div>
                                </div>

                                <div class="col-12">
                                    <p class="mb-0 text-center">Vẫn chưa có tài khoản ? <a href="./">Đăng ký tại đây</a></p>
                                </div>

                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap bundle JS -->
    <script src="../assets/js/bootstrap.bundle.min.js"></script>
    <!--plugins-->
    <script src="../assets/js/jquery.min.js"></script>
    <script src="../assets/js/jquery.validate.min.js"></script>
    <script>
        src = "../assets/js/additional-methods.min.js"
    </script>

    <script src="../assets/js/pages/validate.js"></script>

</body>

</html>
