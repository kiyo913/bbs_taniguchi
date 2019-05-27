<?php require_once('connect_reply.php'); ?>
<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="utf-8">
<title>ひと言掲示板</title>
<link rel="stylesheet" href="style.css">
</head>
<body>
<h1>ひと言掲示板 返信画面</h1>
<p>
    <h3><?php echo $_GET['id'];?>番さんへの返信</h3>
</p>
<!--名前、投稿内容が未記入の場合、エラーメッセージを表示-->
<?php if(!empty($error_message)):?>
    <ul class="error_message">
        <?php foreach( $error_message as $value ): ?>
            <li>・<?php echo $value; ?></li>
        <?php endforeach; ?>
    </ul>
<?php endif; ?>
<!--返信投稿画面-->
<?php 
    echo '<form method="post" action="reply.php?id=' . $_GET['id'] . '">';
?>
    <div class="input_wrap">
       <label for="name">ユーザー名</label>
       <input id="name" type="text" name="name" value="<?php if(!empty($_SESSION['name'])){echo $_SESSION['name'];}?>">
    </div>
    <div class="input_wrap">
       <label for="post_text">返信内容</label>
       <textarea id="post_text" name="post_text"></textarea>
    </div>
    <!--投稿するボタン-->
    <div class="input_wrap"><input type="submit" name="btn_submit" value="書き込む"></div>
    <!--指定したページ(index.php)へ移動するボタン-->
    <div class="btn"><input type="button" name="backbtn" onclick="location.href='index.php'" value="ホームへ戻る"></div>
</form>
<hr>
<section>
     <!--投稿一覧-->
<?php if(!empty($message_array)){ ?>
<?php foreach( $message_array as $value ){ ?>
<article>
    <div class="info">
        <h2><?php echo $value['name']; ?></h2>
        <time><?php echo date('Y年m月d日 H:i', strtotime($value['post_datetime'])); ?></time>
    </div>
    <p><?php echo $value['post_text']; ?></p>
</article>
<?php } ?>
<?php } ?>
</section>
</body>
</html>