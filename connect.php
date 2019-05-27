<?php
//データベースの接続情報
define('DB_HOST','157.112.147.201');
define('DB_USER','ppftech_user1');
define('DB_PASS','user1234');
define('DB_NAME','ppftech_db1');
// タイムゾーン設定
date_default_timezone_set('Asia/Tokyo');
// 変数の初期化
$now_date = null;
$data = null;
$file_handle = null;
$split_data = null;
$message = array();
$message_array = array();
// $success_message = null;
$error_message = array();
$postdata = array();
session_start();

if( !empty($_POST['btn_submit'])){

    // 表示名の入力チェック
    if(empty($_POST['name'])){
        $error_message[] = '表示名を入力してください。';
    } else {
        $postdata['name'] = htmlspecialchars( $_POST['name'], ENT_QUOTES);
    }
    // メッセージの入力チェック
    if(empty($_POST['post_text'])) {
        $error_message[] = 'ひと言メッセージを入力してください。';
    } else {
        $postdata['post_text'] = htmlspecialchars( $_POST['post_text'], ENT_QUOTES);
        $postdata['post_text'] = preg_replace( '/\\r\\n|\\n|\\r/', '<br>', $postdata['post_text']);
        $_SESSION['name'] = $postdata['name'];
    }
    if(empty($error_message)){
        // データベースに接続
        $mysqli = new mysqli(DB_HOST,DB_USER,DB_PASS,DB_NAME);
        // 接続エラーの確認
        if($mysqli->connect_errno){
            $error_message[] = '書き込みに失敗しました。 エラー番号 '.$mysqli->connect_errno.':'.$mysqli->connect_error;
        }else{
            // 文字コード設定
            $mysqli->set_charset('utf8');
            
            // 書き込み日時を取得
            $now_date = date("Y-m-d H:i:s");
            
            // データを登録するSQL作成
            $sql = "INSERT INTO post_taniguchi (name,post_datetime,post_text) VALUES ('{$postdata['name']}','{$now_date}','{$postdata['post_text']}')";
            
            // データを登録
            $res = $mysqli->query($sql);
            
            if($res){
                header('Location: index.php'); //投稿終了時、index.phpへ移動し重複投稿を回避する
                exit();
            }else{
                $error_message[] = '書き込みに失敗しました。';
            }
            // データベースの接続を閉じる
            $mysqli->close();
        }
    }
}
//データベースに接続
$mysqli = new mysqli(DB_HOST,DB_USER,DB_PASS,DB_NAME);
//エラーの確認
if($mysqli->connect_errno){
    $error_message[] = 'データの読み込みに失敗。　エラー番号'.$mysqli->connect_errno.':'.$mysqli->connect_error;
}else{
    $mysqli->set_charset('utf8');
    $sql = "SELECT id,name,post_datetime,post_text FROM post_taniguchi WHERE parent_id = 0 ORDER BY post_datetime DESC";

    $res = $mysqli->query($sql);

    if($res){
        while ($row = $res->fetch_assoc()) {
            $message_array [] = $row;
            // echo $row["user_id"] . $row["name"] . "<br>";
        }
    }
    //データベースへの接続を閉じる
    $mysqli->close();

}
?>