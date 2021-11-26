<?php

$todo = $_POST['todo'];
$date = $_POST['programing_date'];
$commit = $_POST['commit_hours'];
// while($date){

// }
// exit;

// 1.出力用の変数を用意する．
$str = '';

//配列
$array = [];

// 2.csv ファイルを開く（読み取り専用）．
$file = fopen('data/text.csv', 'r+');

// 3.ファイルをロックする．
flock($file, LOCK_EX);

// 4.txt ファイルのデータを読み込み，繰り返し処理を用いて出力用の変数に入れる．
if ($file) { //ファイルがひらけているよねの確認！！
  while ($line = fgets($file)) { //fgetsで＄fileを読みにいき1行分取り出す＝＞＄lineの変数に入れる
    // var_dump($line);
    // exit;
    $str .= "<tr><td>{$line}</td></tr>"; //1行を$strに足していく！
    array_push($array, $line);
  }
}

for ($i = 0; $i < count($array); $i++) {
  $next_array[] = explode(' ', str_replace(PHP_EOL, '', $array[$i])); //文字列を配列にする//最後の改行を消す
}
// for ($i = 0; $i < count($array); $i++) {
//   $next_array; //文字列を配列にする//最後の改行を消す
// }
sort($next_array);

// $samplearray = [];
// for ($i = 0; $i < count($next_array); $i++) {
//   if (count($samplearray) == 0) {
//     array_push($samplearray, $next_array[$i]);
//     var_dump($samplearray);
//     exit;
//   } else {
//     for ($n = 0; $n < count($samplearray); $n++) {
//       if ($samplearray[$n][0] == $next_array[$i][0]) {
//         $samplearray[$n][2] = $next_array[$i][2];
//       } else {
//         array_push($samplearray, $next_array[$i]);
//       }
//     }
//   }
//   // $str .= "<tr><td>{$next_array[$i][0]}</td><td>{$next_array[$i][1]}</td><td>{$next_array[$i][2]}</td><td>{$next_array[$i][3]}</td></tr>";
//   // array_push($samplearray, $next_array[$i][0])
// }

// // var_dump($samplearray);
// // exit;
//   for ($n = 0; $n < count($samplearray); $n++) {
//     $str .= "<tr><td>{$samplearray[$n][0]}</td><td>{$samplearray[$n][1]}</td><td>{$samplearray[$n][2]}</td><td>{$samplearray[$n][3]}</td></tr>";
//   }
/// var_dump($next_array);
// exit;
// var_dump($next_array);
// exit;
// $date_array = array_unique(array_column($next_array, '0'));
$date_array = array_column($next_array, '0');
// $unique = array_unique($date_array);

// var_dump($date_array);
// exit;

$unique = array_unique($date_array, SORT_REGULAR);
// var_dump($unique);
// exit;

// $uniques =[];
// for ($i = 0; $i < count($unique); $i++) {
// array_push($uniques,array_unique($unique[$i]));
// }
// var_dump($uniques);
// exit;

// var_dump($unique);
// exit;

// if($date_array){

// }

// var_dump($date_array);
// exit;

//連想配列の先頭or0番目のvalueが同じだったらデータを上書きする
//連想配列の先頭or0番目で同じものを探す


// 5.ロックを解除する．
flock($file, LOCK_UN);

// 6.ファイルを閉じる．
fclose($file);

?>

<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Programing recorder</title>
  <link rel="stylesheet" href="css/style.css">
</head>

<body>
  <!-- chart.js -->
  <div class="sum_container">
    <div id="sum_sum_sum"></div>
    <div id="average_commit_id"></div>
  </div>
  <div class="canvas_size">
    <canvas id="my_chart">
      Canvas not supported....
    </canvas>
  </div>
  <form action="programing_csv_create.php" method="POST">
    <fieldset>
      <legend><i>・・・ プログラミングの記録・・・ </i></legend>
      <!-- <a href="programing_csv_read.php">一覧画面</a> -->
      <div class="re_container">
        <div>
          todo: <input type="text" name="todo" value="Programing">
        </div>
        <div class="date_cl">
          date: <input type="date" name="programing_date" value="<?= date('Y-m-d'); ?>">
        </div>
        <div>
          commit hours: <select name="commit_hours" id="commit_hours"></select>
        </div>
      </div>
      <div class="submit_button">
        <button>submit</button>
      </div>

    </fieldset>
  </form>


  <!-- chart.js end -->
  <fieldset>
    <legend><i id="slide_toggle">・・・ Log ・・・</i></legend>
    <!-- <a href="programing_csv_input.php">入力画面</a> -->
    <table id="log_table">

      <tbody>
        <?= $str ?>
      </tbody>


    </table>
  </fieldset>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.1/Chart.min.js" integrity="sha512-HCG6Vbdg4S+6MkKlMJAm5EHJDeTZskUdUMTb8zNcUKoYNDteUQ0Zig30fvD9IXnRv7Y0X4/grKCnNoQ21nF2Qw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
  <script>
    (function() {

      $("#slide_toggle").click(function() {
        $("#log_table").slideToggle();
        // $(this).toggleClass('is-active').next('tbody').slideToggle('500');
      });
      //24時間のセレクター
      const start = 00;
      const end = 24;
      let option = "";

      for (let i = start; i <= end; i++) {
        option += '<option>' + i + '</option>';
      }
      $('#commit_hours').html(option);

      //PHPのデータをjsで使えるようにする
      const commitArray = <?= json_encode($next_array) ?>;

      //chart.jsでチャートを作っていく！！
      let sum_commit = 0;
      let average_commit = 0;
      const hours_array = []; //時間の配列を作る！！！
      const date_array = []; //日にちの配列を作る！！！
      const total_array = []; //日々の合計！！
      const average_array = [];

      console.log(commitArray);

      //合計を出していく
      for (i = 0; i < commitArray.length; i++) { //'<='ではなく'<'！！
        date_array.push(commitArray[i][0]); //日付！！
        hours_array.push(commitArray[i][2]); //commit 時間！！


        if (i == 0) {
          total_array.push(Number(commitArray[i][2]));
          // console.log(total_array);
        } else {
          total_array.push(Number(total_array[i - 1]) + Number(commitArray[i][2]));
        }

      }

      for (i = 0; i < commitArray.length; i++) {
        average_array.push(total_array[i] / (i + 1));
      }

      // console.log(total_array);
      // console.log(date_array);
      // console.log(hours_array);
      // console.log(sum_commit);
      // console.log(average_array);

      //合計時間計算
      for (i = 0, len = hours_array.length; i < len; i++) {
        sum_commit += Number(hours_array[i]);
        // console.log(hours_array.length);
        average_commit = Math.round((sum_commit / hours_array.length) * 10) / 10;
      }
      // console.log(sum_commit);
      console.log(average_commit);
      $('#sum_sum_sum').html("👑 TOTAL-commit   " + sum_commit + " 時間 👑");
      $('#average_commit_id').html("🍎 AVERAGE-commit    " + average_commit + " 時間 🍎");


      //chart.js
      'use strict';
      var type = 'bar'; //線グラフ line/bar,/rader/
      var data = {
        labels: date_array, //書き換える！！
        datasets: [{
          yAxisID: 'commit-axes',
          label: 'COMMIT-time',
          data: hours_array, //書き換える！！！
          borderColor: 'red',
          borderWidth: 3,
          type: 'line',
          fill: false,
          // backgroundColor:'',
        }, {
          yAxisID: 'commit-axes',
          label: 'AVERARE-time',
          data: average_array, //書き換える！！！
          borderColor: 'blue',
          borderWidth: 1.5,
          type: 'line',
          fill: false,
          // backgroundColor:'',
        }, {
          yAxisID: 'commit-sum-axes',
          label: 'TOTAL-commit-time',
          data: total_array,
          type: 'bar',
          fill: false,
          backgroundColor: 'hsla(360, 100%, 3%, 0.18)',
        }]
      };

      var options = {
        scales: {
          yAxes: [{
            ticks: { //commit時間の縦軸
              fontSize: 18,
              suggestedMin: 0,
              suggestedMax: 24,
              stepSize: 3,
              callback: function(value, index, values) {
                return value + 'h';
              }
            },
            id: 'commit-axes',
            type: 'linear',
            position: 'left',
          }, { //commit時間の縦軸//ここまで
            ticks: { //commit総時間の縦軸
              fontSize: 18,
              suggestedMin: 0,
              suggestedMax: 1000,
              // stepSize: ,
              callback: function(value, index, values) {
                return value + 'h';
              }
            },

            id: 'commit-sum-axes',
            type: 'linear',
            position: 'right',
            gridLines: {
              display: false
            } //commit総時間の縦軸//ここまで
          }]
        },
        title: {
          display: true,
          text: 'Programming chart',
          fontSize: 25,
          // position: 'left'
        },
        legend: {
          position: 'top',
        }
      };
      var ctx = document.getElementById('my_chart').getContext('2d');
      var myChart = new Chart(ctx, {
        type: type,
        data: data,
        options: options,
      });
    })();
  </script>

</body>

</html>