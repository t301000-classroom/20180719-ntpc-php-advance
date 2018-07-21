<?php
    require_once 'bootstarp.php';

    // 無暫存之 OpenID 資料則導向至網站登入頁
    ifNoTmpDataThenRedirectToLoginPage();

    /***************************************************
     *      主程式區
     ***************************************************/

    // 授權資訊
    $auth_info = $_SESSION['tmp_user_data']['auth_info'];

    // 登入規則
    $rules = OPENID_RULES;

    // 驗證登入規則
    if ( empty($rules) || is_null($rules) ) {
        // 空陣列 或 null 則不檢查，無條件放行
        $canLogin = true;
    } else {
        // 逐條檢查，first match
        foreach ($rules as $rule) {
            $canLogin = checkSingleRule($rule);
            if ($canLogin === true) {
                break;
            }
        }
    }

    // 除錯模式時輸出訊息
    if (DEBUG_MODE) {
        var_dump("****** 是否可以登入 *******", $canLogin);
        die();
    }

    if ($canLogin === true) {
        // 可以登入
        header('Location: ' . AFTER_OPENID_SUCCESS);
        die();
    } else {
        // 不能登入，導向至網站登入頁
        unset($_SESSION['tmp_user_data']);
        header('Location: ' . LOGIN_PAGE);
        die();
    }


    /***************************************************
     *      函數區
     ***************************************************/

    /**
     * 檢查單一筆規則
     *
     * @param array $rule
     *
     * @return bool
     */
    function checkSingleRule(array $rule) {
        global $auth_info;

        // 除錯模式時輸出訊息
        if (DEBUG_MODE) {
            var_dump('目前檢查規則：', $rule);
        }

        // 逐一檢查規則中之欄位，一不通過則拒絕並略過後續欄位檢查
        $result = false;
        foreach ($rule as $field => $value) {
            $value = is_array($value) ? $value : [$value];
            $auth_info[$field] = is_array($auth_info[$field]) ? $auth_info[$field] : [$auth_info[$field]];

            $result = (bool) array_intersect($value, $auth_info[$field]);

            // 除錯模式時輸出訊息
            if (DEBUG_MODE) {
                var_dump(
                    "目前檢查欄位： {$field}",
                    "持有值：", $auth_info[$field],
                    "必要值：", $value,
                    "結果：", $result, '-------------------'
                );
            }

            if (!$result) {
                // 只要有 1 個欄位不符合，立即跳出 foreach
                break;
            }
        }

        return $result;
    }
