<?php
    require_once 'bootstarp.php';

    // 無暫存之 OpenID 資料則導向至網站登入頁
    ifNoTmpDataThenRedirectToLoginPage();

    /***************************************************
     *      主程式區
     ***************************************************/

    $auth_infos = $_SESSION['tmp_user_data']['auth_info'];

    // 只有 1 個身份，直接進入下一頁面，檢查登入規則流程
    if (count($auth_infos) === 1) {
        $_SESSION['tmp_user_data']['auth_info'] = $auth_infos[0];
        header('Location: check.php');
        die();
    }

    // 選擇列表中某一個項目後
    if (isset($_GET['idx'])) {
        $_SESSION['tmp_user_data']['auth_info'] = $auth_infos[(int) $_GET['idx']];
        // 進入下一頁面，檢查登入規則流程
        header('Location: check.php');
        die();
    }

    // 顯示身份列表
    echo showList($auth_infos);



    /***************************************************
    *      函數區
    ***************************************************/

    /**
     * 顯示身份列表
     *
     * @param array $auth_infos
     *
     * @return string
     */
    function showList(array $auth_infos) {
        // 姓名
        $name = $_SESSION['tmp_user_data']['name'];

        $rows = "";
        foreach ($auth_infos as $key => $info) {
            $groups = implode(',', $info['groups']);
            $rows .= "<p><a href='{$_SERVER['PHP_SELF']}?idx={$key}'>{$info['name']} {$info['role']} {$groups}</a></p>";
        }
        return $list = <<<LIST
            <div>
                <h3>{$name} 你好，請選擇登入身份</h3>
                {$rows}
            </div>
LIST;

    }




