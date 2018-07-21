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

            $rows .=<<<ROW
                <a href="{$_SERVER['PHP_SELF']}?idx={$key}">
                    <div class="item">
                        <div>{$info['name']}</div>
                        <div>{$info['role']}</div>
                        <div>
                            <ul>
                                <li>{$groups}</li>
                            </ul>
                        </div>
                    </div>
                </a>
ROW;
        }

        return $list = <<<LIST
            <style>
                a {
                  text-decoration: none;
                  color: #333;
                }
                a:hover div {
                  color: #fff;
                  background-color: #f41;
                }
                ul {
                  list-style: none;
                  padding-left: 0;
                  margin-top: 0;
                }
                .container {
                  background-color: #fff;
                  margin: 50px auto 0;
                  width: 500px;
                  font-size: 20px;
                  border: 2px solid #aaa;
                  border-radius: 10px;
                  overflow: hidden;
                }
                .header {
                  background-color: #00f;
                  padding:20px;
                  text-align: center;
                  color: #fff;
                  font-weight: 900;
                }
                .header > span {
                  margin-right: 15px;
                }
                .item {
                  display: flex;
                  justify-content: space-between;
                  padding: 10px 15px;
                }
                .item > div {
                  padding: 0 10px;
                }
            </style>
            
            <div class="container">
                <div class="header">
                    <span>{$name}</span>你好，請選擇登入身份
                </div>
                <div class="body">
                    {$rows}
                </div>
            </div>
LIST;

    }
