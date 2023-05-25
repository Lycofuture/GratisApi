<?php

/**
 * 获取问情API疯狂星期四文案
 *
 * @param string $hh HTML标签，用于分隔每行输出，默认为'<br>'
 * @param string $type 输出类型，可选值为'text'或'json'，默认为'text'
 * @return string 返回问情API疯狂星期四文案
 */
function getWenAn($hh = '<br>', $type = 'text') {
    // 验证和过滤输入参数
    $hh = filter_var($hh, FILTER_SANITIZE_STRING) ?? '<br>';
    $type = in_array($_GET['type'], ['text', 'json']) ? $_GET['type'] : 'text';

    // 缓存文件读取结果
    $fileContents = file_get_contents('kfcyl.txt');
    $a = explode("\n", $fileContents);

    // 随机选择一行
    $rand = array_rand($a);
    $rand_shuchu = trim($a[$rand]);

    // 构建响应
    if ($type === 'json') {
        $response = json_encode(['code' => 1, 'msg' => $rand_shuchu], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        header('Content-Type: application/json');
    } else {
        $response = "问情API疯狂星期四文案{$hh}{$rand_shuchu}Tips:问情网络科技技术支持";
        header('Content-Type: text/plain; charset=utf-8');
    }

    return $response;
}

// 输出问情API疯狂星期四文案
echo getWenAn();
