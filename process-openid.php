<?php

    require_once 'openid.php';
    session_start();


    $skip = true;

    if ($skip) {
        // $_SESSION['tmp_user_data'] = getFakeData();
        // header('Location: select-auth-info.php');
        // die();
        saveTempDataAndSelect(getFakeData());
    }


    /**********************************
     * 主程式區
     **********************************/

    $openid = new LightOpenID('localhost:8000');

    switch ($openid->mode) {
        case 'cancel':
            // 取消授權
            reLogin();
            break;

        case 'id_res':
            // 同意授權
            // 驗證正確性，正確 => 拿資料，錯誤 => 回登入頁
            $openid->validate() ? getData($openid) : reLogin();
            break;

        default:
            // 開始 openid 認證流程
            // echo 'start openid flow';
            start($openid);

    }


    /**********************************
     * 函數區
     **********************************/



    function start(LightOpenID $openid) {
        // global $openid;
        $openid->identity = 'https://openid.ntpc.edu.tw/';
        $openid->required = [
            // 'namePerson/friendly', // 暱稱
            'contact/email', // 公務信箱
            'namePerson', // 姓名
            // 'birthDate', // 出生年月日
            // 'person/gender', // 性別
            // 'contact/postalCode/home', // 識別碼
            'contact/country/home', // 單位：簡稱
            // 'pref/language', // 年級班級座號：6位數字串
            'pref/timezone' // 授權資訊，陣列
                            // 元素欄位:
                            // id 單位代碼
                            // name 單位全銜
                            // role 身分別
                            // title 職務別
                            // groups 職稱別，陣列

        ];
        header('Location: ' . $openid->authUrl());
    }


    function getData(LightOpenID $openid) {
        $attr = $openid->getAttributes();
        // $attr['username'] = end(array_values(explode('/', $openid->identity)));
        $tmp_array = explode('/', $openid->identity);
        $attr['username'] = end($tmp_array);
        // var_dump($attr);

        $userData = [];
        $userData['username'] = $attr['username'];
        $userData['id_code'] = isset($attr['contact/postalCode/home']) ? $attr['contact/postalCode/home'] : null;
        $userData['name'] = isset($attr['namePerson']) ? $attr['namePerson'] : null;
        $userData['nickname'] = isset($attr['namePerson/friendly']) ? $attr['namePerson/friendly'] : null;
        $userData['gender'] = isset($attr['person/gender']) ? ($attr['person/gender'] == 'M' ? '男' : '女') : null;
        $userData['birthday'] = isset($attr['birthDate']) ? $attr['birthDate'] : null;
        $userData['email'] = isset($attr['contact/email']) ? $attr['contact/email'] : null;
        $userData['school'] = isset($attr['contact/country/home']) ? $attr['contact/country/home'] : null;
        $userData['grade'] = isset($attr['pref/language']) ? substr($attr['pref/language'], 0, 2) : null;
        $userData['class'] = isset($attr['pref/language']) ? substr($attr['pref/language'], 2, 2) : null;
        $userData['num'] = isset($attr['pref/language']) ? substr($attr['pref/language'], 4, 2) : null;
        $userData['auth_info'] = json_decode($attr['pref/timezone'], 1);

        // var_dump($userData);
        // $_SESSION['tmp_user_data'] = $userData;
        // header('Location: select-auth-info.php');
        saveTempDataAndSelect($userData);
    }


    function reLogin() {
        header('Location: index.php');
    }


    function saveTempDataAndSelect(array $userData) {
        $_SESSION['tmp_user_data'] = $userData;
        header('Location: select-auth-info.php');
        die();
    }


    function getFakeData() {
        return [
            'username' => 't301xxx',
            'id_code' => 'adaskaklcfjaskljlasjlsljklsjlsa',
            'name' => '王小明',
            'nickname' => '王小明',
            'gender' => '男',
            'birthday' => '2000-09-15',
            'email' => 't301xxx@apps.ntpc.edu.tw',
            'school' => '臺林國中',
            'grade' => '00',
            'class' => '00',
            'num' => '00',
            'auth_info' => [

                // 第 1 筆
                [
                    'id' => '014569',
                    'name' => '新北市立育林國民中學',
                    'role' => '教師',
                    'title' => '教師兼組長',
                    'groups' => [
                        '資訊組長', '註冊組長'
                    ]
                ],

                // 第 2 筆
                [
                    'id' => '014456',
                    'name' => '新北市立樹林國民小學',
                    'role' => '家長',
                    'title' => '其他',
                    'groups' => [
                        '家長', '志工'
                    ]
                ],

                [
                    'id' => '014569',
                    'name' => '新北市立育林國民中學',
                    'role' => '家長',
                    'title' => '教師兼組長',
                    'groups' => [
                        '資訊組長'
                    ]
                ]

            ]
        ];
    }
