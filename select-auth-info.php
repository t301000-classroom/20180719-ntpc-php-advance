<?php
    session_start();

    if (!isset($_SESSION['tmp_user_data'])) {
        header('Location: index.php');
        die();
    }

    $auth_infos = $_SESSION['tmp_user_data']['auth_info'];

    // 只有 1 個身份，直接去檢查是否可以登入
    if (count($auth_infos) === 1) {
        header('Location: check.php');
        die();
    }

    if (isset($_GET['idx'])) {
        $_SESSION['tmp_user_data']['auth_info'] = $auth_infos[(int) $_GET['idx']];
        header('Location: check.php');
        die();
    }

    $name = $_SESSION['tmp_user_data']['name'];
?>

<div>
    <h3><?= $name ?> 你好，請選擇登入身份</h3>
    <?php
        foreach ($auth_infos as $key => $info) {
            $groups = implode(',', $info['groups']);
            echo "<p><a href='{$_SERVER['PHP_SELF']}?idx={$key}'>{$info['name']} {$info['role']} {$groups}</a></p>";
        }
    ?>
</div>

