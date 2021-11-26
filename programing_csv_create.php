<?php
// var_dump($_POST);
// exit();

// 1,データを受け取る
$todo = $_POST['todo'];
$date = $_POST['programing_date'];
$commit = $_POST['commit_hours'];

//もし同じ日付のものが入った時に前のデータを上書きする

// print_r($_POST);
// exit;
// データ1件を1行にまとめる（最後に改行を入れる）
$write_data = "{$date} {$todo} {$commit} 時間". PHP_EOL;
// $write_data = "{$commit}" . PHP_EOL;
// var_dump($write_data);
// exit();

// 書き込み先のファイルを開く（なければ新たにファイルを作成）
// ファイルを開く．引数が`a`である部分に注目！
$file = fopen('data/text.csv','a');

// var_dump($file);
// exit();

// 他の人が書き込まないようロックする
flock($file, LOCK_EX);

// データを書き込む
fwrite($file, $write_data);

// ロックを解除する
flock($file, LOCK_UN);

// ファイルを閉じる
fclose($file);


$aryData = file("data/text.csv");
$aryUnique = array_unique($aryData);
// 書き込みﾓｰﾄﾞでｵｰﾌﾟﾝ
$fp = fopen("data/text.csv", "w");
flock($fp, LOCK_EX);
foreach ($aryUnique as $value) {
    //ﾌｧｲﾙに保存
    fputs($fp, $value);
}
flock($fp, LOCK_UN);
// ﾌｧｲﾙを閉じる
fclose($fp);

// 入力画面に移動
header("Location:programing_csv_read.php");