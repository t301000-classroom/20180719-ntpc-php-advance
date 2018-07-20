<?php
    require_once 'config.php';
    session_start();

    // 非正常程序進入
    if (!isset($_SESSION['tmp_user_data'])) {
        header('Location: index.php');
        die();
    }

    // 授權資訊
    $auth_info = $_SESSION['tmp_user_data']['auth_info'];

    // 登入規則
    $rules = OPENID_RULES;

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
        // 不能登入
        unset($_SESSION['tmp_user_data']);
        header('Location: ' . LOGIN_PAGE);
        die();
    }




    function checkSingleRule(array $rule) {
        global $auth_info;

        // 除錯模式時輸出訊息
        if (DEBUG_MODE) {
            var_dump('目前檢查規則：', $rule);
        }

        $result = false;
        foreach ($rule as $field => $value) {

            // if ($field === 'groups') {
            //     $result = in_array('社工', ['資訊組長', '教學組長', '註冊組長']);
            //     var_dump($result);
            // } else {
            //     $result = $auth_info[$field] === $value;
            // }

            // switch ($field) {
            //     case 'id':
            //     case 'name':
            //     case 'role':
            //     case 'title':
            //         // if (is_array($value)) {
            //         //     $result = in_array($auth_info[$field], $value);
            //         // } else {
            //         //     $result = $auth_info[$field] === $value;
            //         // }
            //         $result = is_array($value) ?
            //             in_array($auth_info[$field], $value) : $auth_info[$field] === $value;
            //         break;
            //     case 'groups':
            //         $intersect = is_array($value) ?
            //             count(array_intersect($value, $auth_info['groups'])) : (int) in_array($value, $auth_info['groups']);
            //         $result = ( $intersect > 0 );
            // }

            $value = is_array($value) ? $value : [$value];
            $auth_info[$field] = is_array($auth_info[$field]) ? $auth_info[$field] : [$auth_info[$field]];

            $result = (bool) array_intersect($value, $auth_info[$field]);

            // 除錯模式時輸出訊息
            if (DEBUG_MODE) {
                var_dump(
                    "目前檢查欄位： {$field}",
                    "持有值：", $auth_info[$field],
                    "必要值：", $value,
                    "結果： {$result}", '-------------------'
                );
            }

            if (!$result) {
                // 只要有 1 個欄位不符合，立即跳出 foreach
                break;
            }
        }

        return $result;
    }



    // 規則-必要值       持有值
    // $value          $auth_info[$field]
    // ------------------------------------
    // string          string  <====
    // $value    ===   $auth_info[$field]
    //
    // string          array  *====== groups
    //  in_array($value, $auth_info[$field])
    //  in_array('資訊組長', ['資訊組長'])
    //
    //
    // array           string  <=====
    // in_array($auth_info[$field], $value)
    // in_array('教師',  ['學生', '家長'])
    //
    // array           array *====== groups
    // ['資訊組長', '教學組長']     ['教學組長', '註冊組長']
    //   $intersect =  array_intersect(['資訊組長', '教學組長'], ['教學組長', '註冊組長'])
    //   count($intersect)  > 0 ?


















