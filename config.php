<?php
    // 網站 Host + Domain Name，非 80 port 要加上 port
    define('OPENID_HOST', 'localhost:8000');
    // 要取得之資料欄位
    define('OPENID_REQUIRED',  [
        'namePerson/friendly', // 暱稱
        'contact/email', // 公務信箱
        'namePerson', // 姓名
        'birthDate', // 出生年月日
        'person/gender', // 性別
        'contact/postalCode/home', // 識別碼
        'contact/country/home', // 單位：簡稱
        'pref/language', // 年級班級座號：6位數字串
        'pref/timezone' // 授權資訊，陣列
                        // 元素欄位:
                        // id 單位代碼
                        // name 單位全銜
                        // role 身分別
                        // title 職務別
                        // groups 職稱別，陣列
    ]);
    // 網站之登入頁面
    define('LOGIN_PAGE', 'http://localhost:8000/index.php');
    // OpenID 登入並通過規則檢查之後，要導向哪裡？
    define('AFTER_OPENID_SUCCESS', 'http://localhost:8000/index.php');
    // 開啟除錯模式，可查看規則檢查過程
    define('DEBUG_MODE', false);
    // 登入規則，空陣列 [] 或 null 代表不設限制
    define('OPENID_RULES', [
        // ['id' => '012345'],
        // ['id' => '014569', 'role' => '教師'],
        // ['id' => ['014569', '012345'], 'role' => '教師'],
        // ['id' => ['014569', '012345'], 'role' => ['教師', '家長']],
    ]);
    // 是否略過正常 OpenID 流程，使用假資料？
    define('SKIP_OPENID_FLOW', false);
    // 假資料
    define('FAKE_USER', [
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
                    '註冊組長'
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

            // 第 3 筆
            // [
            //     'id' => '014569',
            //     'name' => '新北市立育林國民中學',
            //     'role' => '家長',
            //     'title' => '教師兼組長',
            //     'groups' => [
            //         '資訊組長'
            //     ]
            // ]

        ]
    ]);

