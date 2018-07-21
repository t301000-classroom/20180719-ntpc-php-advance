<?php
    session_start();
    require_once 'openid.php';
    require_once 'config.php';

    // 已登入則導向回網站首頁
    ifLoginedThenRedirectToHomePage();


    /***************************************************
     *      函數區
     ***************************************************/

    /**
     * 檢查是否已登入
     */
    function ifLoginedThenRedirectToHomePage() {
        if (isset($_SESSION[SESSION_NAME_FOR_LOGINED])) {
            // 已登入，則導向至網站首頁
            header('Location: ' . SITE_HOME_PAGE);
            die();
        }
    }


    /**
     * 無暫存之 OpenID 資料則導向至網站登入頁
     */
    function ifNoTmpDataThenRedirectToLoginPage() {
        if (!isset($_SESSION['tmp_user_data'])) {
            header('Location: ' . LOGIN_PAGE);
            die();
        }
    }
