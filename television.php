<?php

function validate($televisions,$argv)
{
    $errors = [];
    $count_time = 0;
    $count_channel = 0;

    if (count($argv) % 2 != 0) {
        $errors['set'] = 'チャンネルと視聴時間はセットで入力してください';
    }

    foreach ($televisions as $television) {
        if ($television['0'] > 12 || $television['0'] < 1 || preg_match('/^([1-9]\d*|0)\.(\d+)?$/', $television['0'])) {
            $count_channel += 1;
        }
    }

    if (count($argv) % 2 == 0) {
        foreach ($televisions as $television) {
            if ($television['1'] > 1440 || $television['1'] < 1 || preg_match('/^([1-9]\d*|0)\.(\d+)?$/', $television['1'])) {
                $count_time += 1;
            }
        }
    }

    if ($count_channel > 0) {
        $errors['channel'] = 'チャンネルは1以上12以下の整数で入力してください';
    }

    if ($count_time > 0) {
        $errors['times'] = '視聴時間は1以上1440以下の整数で入力してください';
    }

    return $errors;
}

$tv_times = 0;
$count = 0;

// 最初のhogehoge.phpの値を削除
array_shift($argv);

// 2つずつの配列にする
$televisions = array_chunk($argv, 2);

// バリデーション
$errors = validate($televisions,$argv);

if (count($errors) > 0) {
    foreach ($errors as $error) {
        echo $error . PHP_EOL;
    }
    exit;
}

$num = 1;

// 各チャンネルごとに連想配列を作成
while ($num < 13) {
    foreach ($televisions as $television) {
        if ($television['0'] == $num) {
            ${'televisions' . $num}[] = [
                'channel' => $television['0'],
                'time' => $television['1']
            ];
        }
    }
    $num += 1;
}

// 総視聴時間の計算
foreach ($televisions as $television) {
    $tv_times = $tv_times + $television['1'];
}

if (!$tv_times == 0) {
    // 総視聴時間を時間に直し、四捨五入した後に表示
    echo round($tv_times / 60 , 1) . PHP_EOL;

    // 各チャンネルごとに視聴時間、視聴回数を計算し表示
    $num = 1;
    while ($num < 13) {
        if (!empty(${'televisions'.$num})) {
            ${'count_tv'.$num} = 0;
            ${'times_tv'.$num} = 0;
            foreach (${'televisions' . $num} as ${'television' . $num}) {
                ${'count_tv' . $num} += 1;
                ${'times_tv' . $num} = ${'times_tv' . $num} + ${'television' . $num}['time'];
            }

            echo $num . ' ' . ${'times_tv' . $num} . ' ' . ${'count_tv' . $num} . PHP_EOL;
        }
        $num += 1;
    }
} else {
    echo 'テレビの視聴は行っていません' . PHP_EOL;
}
