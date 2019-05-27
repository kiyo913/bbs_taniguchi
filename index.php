<?php require_once('connect.php'); ?>
<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="utf-8">
<title>ひと言掲示板</title>
<link rel="stylesheet" href="style.css">
</head>
<body>
<h1>ひと言掲示板</h1>
<!--名前、投稿内容が未記入の場合、エラーメッセージを表示-->
<?php if(!empty($error_message)):?>
    <ul class="error_message">
        <?php foreach( $error_message as $massage_array ): ?>
            <li>・<?php echo $massage_array; ?></li>
        <?php endforeach; ?>
    </ul>
<?php endif; ?>
<!--投稿画面-->
<form method="post" action="index.php">
    <div class="input_wrap">
        <label for="name">ユーザー名</label>
        <input id="name" type="text" name="name" value="<?php if(!empty($_SESSION['name'])){echo $_SESSION['name'];}?>">
    </div>
    <div class="input_wrap">
       <label for="post_text">投稿内容</label>
       <textarea id="post_text" name="post_text"></textarea>
    </div>
    <div class="input_wrap"><input type="submit" name="btn_submit" value="書き込む"></div>
    <div class="btn"><input type="button" name="backbtn" onclick="location.href='index.php'" value="先頭ページへ戻る"></div>
</form>
<hr>
<section>
<?php
/**
*ページ番号が表紙される機能

*@param  $limit　 最大ページ
*@param  $page　  現在のページ
*@param  $disp　　1ページに表示される件数

*/
function paging($limit,$page,$disp=5){
    
     
    $next = $page+1;//前のページ番号
    $prev = $page-1;//次のページ番号

    //ページ番号リンク用
    $start =  ($page-floor($disp/2) > 0) ? ($page-floor($disp/2)) : 1;//始点、floorで端数の切り捨て

    $end =  ($start > 1) ? ($page+floor($disp/2)) : $disp;//終点

    $start = ($limit < $end)? $start-($end-$limit):$start;//始点再計算

    if($page != 1 ) {//最初のページ以外で「前へ」を表示
         print '<a href="?page='.$prev.'">&laquo; 前へ</a>';
    }

    //最初のページへのリンク
    if($start >= floor($disp/2)){
        print '<a href="?page=1">1</a>';
        if($start > floor($disp/2)) print "..."; //ドットの表示
    }

    for($i=$start; $i <= $end ; $i++){//ページリンク表示ループ
        $class = ($page == $i) ? ' class="current"':"";//現在地を表すCSSクラス

        if($i <= $limit && $i > 0 )//1以上最大ページ数以下の場合
            print '<a href="?page='.$i.'"'.$class.'>'.$i.'</a>';//ページ番号リンク表示
    }

    //最後のページへのリンク
    if($limit > $end){
        if($limit-1 > $end ) print "...";    //ドットの表示
        print '<a href="?page='.$limit.'">'.$limit.'</a>';
    }

    if($page < $limit){//最後のページ以外で「次へ」を表示
        print '<a href="?page='.$next.'">次へ &raquo;</a>';
    }
}
/**
*表示するデータの処理

*@param  $page 現在のページ
*@param  $max  1ページに表示される件数

*/
function disp_log($page,$max){
    global $logdata,$count;
    $start = ($page == 1)? 0 : ($page-1) * $max;
    $end   = ($page * $max);
    
    for($i=$start;$i<$end;$i++){
        if($i >= $count){break;}
?>  
<article>
    <!--一覧表示-->
    <div class="info">
        <h2><?php echo $logdata[$i]['id']; ?></h2>
        <h2><?php echo $logdata[$i]['name']; ?></h2>
        <time><?php echo date('Y年m月d日 H:i', strtotime ($logdata[$i]['post_datetime'])); ?></time>
        <!--返信画面へ移動-->
        <form method="post" action="reply.php" id="reply">
        <?php echo '<a href="reply.php?id=' .  $logdata[$i]['id']. '">返信</a>'; ?>
        </form>
        <p><?php echo $logdata[$i]['post_text']; ?></p>
    </div>
</article>
<?php   
    }
}
/**
*URLに"00"や文字が入力された場合の対処

*@param $page 現在のページ
*@returu filter_var 指定したフィルタでデータをフィルタリングする

*/
function is_decimal($page) {
    //FILTER_VALIDATE_INT = 値が整数かどうか検証し、成功なら整数に変換
    return filter_var($page, FILTER_VALIDATE_INT) !== false;
}

$page = empty($_GET["page"])? 1:$_GET["page"];//ページ番号が"0"の時、1ページへ移動
$logdata = $message_array;//投稿内容をlogdataへ
$count =  sizeof($message_array);//ログの数
$max = 5;//1ページあたりの表示数
$limit = ceil($count/$max);//最大ページ数

if($page > $limit){  //最大ページを超えたURLを入力した際、1ページへ
    $page = $limit;       // $page = $limit;で最後のページへ
}
//関数の呼び出し
if(is_decimal($page)){
    paging($limit,$page);
    disp_log($page,$max);
    is_decimal($page);
}else{
    echo "URLが間違っています";
}
?>
</section>
</body>
</html>
