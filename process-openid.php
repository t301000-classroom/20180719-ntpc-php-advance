<?php
    require_once 'bootstarp.php';

    // 啟用略過 OpenID 流程時，使用假資料
    if (SKIP_OPENID_FLOW) {
        saveTempDataAndSelect(getFakeData());
    }


    /**********************************
     * 主程式區
     **********************************/

    $openid = new LightOpenID(OPENID_HOST);

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
            start($openid);

    }


    /**********************************
     * 函數區
     **********************************/

    /**
     * 啟動 OpenID 認證流程
     *
     * @param \LightOpenID $openid
     *
     * @throws \ErrorException
     */
    function start(LightOpenID $openid) {
        // global $openid;
        $openid->identity = 'https://openid.ntpc.edu.tw/';
        $openid->required = OPENID_REQUIRED;
        header('Location: ' . $openid->authUrl());
    }

    /**
     * 取得 OpenID 資料
     *
     * @param \LightOpenID $openid
     */
    function getData(LightOpenID $openid) {
        $attr = $openid->getAttributes();
        $tmp_array = explode('/', $openid->identity);
        $attr['username'] = end($tmp_array);

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

        saveTempDataAndSelect($userData);
    }

    /**
     * 導向網站登入頁重新登入
     */
    function reLogin() {
        header('Location: ' . LOGIN_PAGE);
    }


    /**
     * 暫存 OpenID 資料，導向身份選擇頁
     *
     * @param array $userData
     */
    function saveTempDataAndSelect(array $userData) {
        $_SESSION['tmp_user_data'] = $userData;

        header('Location: select-auth-info.php');
        die();
    }

    /**
     * 取得測試用之假資料
     *
     * @return array
     */
    function getFakeData() {
        return FAKE_USER;
    }
