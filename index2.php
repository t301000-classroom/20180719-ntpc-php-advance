<?php
require 'openid.php';
header("Content-Type:text/html; charset=utf-8");
try {

  // $openid->mode ==> null  cancel   id_res

    $openid = new LightOpenID('localhost:8000');
    if (!$openid->mode) {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $openid->identity = 'http://openid.ntpc.edu.tw/';
            $openid->required = array('namePerson/friendly', 'contact/email', 'namePerson', 'birthDate', 'person/gender', 'contact/postalCode/home', 'contact/country/home', 'pref/language', 'pref/timezone');
            header('Location: ' . $openid->authUrl());
        }
?>
<form action="" method="post">
        <input type="submit" value="登入" />
      </form>
<?php
    } elseif ($openid->mode == 'cancel') {
        echo '使用者取消';
    } else {
        if ($openid->validate()) {
            $attr = $openid->getAttributes();

            echo '<table border="1" cellspacing="0" cellpadding="10">';
            echo '<tr><td>帳號</td><td>' . end(array_values(explode('/', $openid->identity))) . '</td></tr>';
            echo '<tr><td>識別碼</td><td>' . $attr['contact/postalCode/home'] . '</td></tr>';
            echo '<tr><td>姓名</td><td>' . $attr['namePerson'] . '</td></tr>';
            echo '<tr><td>暱稱</td><td>' . $attr['namePerson/friendly'] . '</td></tr>';
            echo '<tr><td>性別</td><td>' . ($attr['person/gender'] == 'M' ? '男' : '女') . '</td></tr>';
            echo '<tr><td>出生年月日</td><td>' . $attr['birthDate'] . '</td></tr>';
            echo '<tr><td>公務信箱</td><td>' . $attr['contact/email'] . '</td></tr>';
            echo '<tr><td>單位</td><td>' . $attr['contact/country/home'] . '</td></tr>';
            echo '<tr><td>年級</td><td>' . substr($attr['pref/language'], 0, 2) . '</td></tr>';
            echo '<tr><td>班級</td><td>' . substr($attr['pref/language'], 2, 2) . '</td></tr>';
            echo '<tr><td>座號</td><td>' . substr($attr['pref/language'], 4, 2) . '</td></tr>';
            echo '</table>';
            echo '<p />';
            echo '<table border="1" cellspacing="0" cellpadding="10">';
            echo '<tr><td>單位代碼</td><td>單位名稱</td><td>身分別</td><td>職務別</td><td>職稱別</td></tr>';
            foreach (json_decode($attr['pref/timezone']) as $item) {
                echo '<tr>';
                echo '<td>' . $item->id . '</td>';
                echo '<td>' . $item->name . '</td>';
                echo '<td>' . $item->role . '</td>';
                echo '<td>' . $item->title . '</td>';
                echo '<td>' . implode('、', $item->groups) . '</td>';
                echo '</tr>';
            }
            echo '</table>';
        }
    }
} catch (ErrorException $e) {
    echo $e->getMessage();
}
