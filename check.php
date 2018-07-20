<?php
    session_start();

    // var_dump($_SESSION['tmp_user_data']);

    if (!isset($_SESSION['tmp_user_data'])) {
        header('Location: index.php');
        die();
    }


    $auth_info = $_SESSION['tmp_user_data']['auth_info'];
    // $auth_info = [
    //     'id' => '014569',
    //     'name' => '新北市立育林國民中學',
    //     'role' => '教師',
    //     'title' => '教師兼組長',
    //     'groups' => [
    //         '資訊組長', '教學組長', '註冊組長'
    //     ]
    // ];



    // $rule1 = ['id' => '019998'];
    $rule1 = ['id' => '014569', 'role' => '教師'   ];
    $rule1 = ['id' => '014569', 'role' => ['教師', '家長']   ];
    $canLogin = checkSingleRule($rule1);

    var_dump($canLogin);




    function checkSingleRule(array $rule) {
        global $auth_info;
        // $rule = ['id' => '014569'];
        $result = false;
        foreach ($rule as $field => $value) {

            // if ($field === 'groups') {
            //     $result = in_array('社工', ['資訊組長', '教學組長', '註冊組長']);
            //     var_dump($result);
            // } else {
            //     $result = $auth_info[$field] === $value;
            // }

            switch ($field) {
                case 'id':
                case 'name':
                case 'role':
                case 'title':
                    $result = $auth_info[$field] === $value;
                    break;
                case 'groups':
                    $result = in_array($value, $auth_info['groups']);
            }

            var_dump($field, $auth_info[$field], $value, $result, '------');

            if (!$result) {
                // 只要有 1 個欄位不符合，立即跳出 foreach
                break;
            }
        }

        return $result;
    }



    規則-必要值       持有值
    $value          $auth_info[$field]
    ------------------------------------
    string          string


    string          array


    array           string


    array           array


















