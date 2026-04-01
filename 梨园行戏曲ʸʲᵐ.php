<?php
/**
 * 梨园行戏曲 - PHP 适配版
 * 基于 JS 规则重构，支持分类浏览、搜索、详情、播放解析
 */

header('Content-Type: application/json; charset=utf-8');
error_reporting(0);

// ================= 全局配置 =================
$HOST = 'https://fly.daoran.tv';
$HEADERS = [
    'User-Agent' => 'okhttp/3.12.10',
    'Connection' => 'Keep-Alive',
    'Accept-Encoding' => 'gzip',
    'md5' => 'SkvyrWqK9QHTdCT12Rhxunjx+WwMTe9y4KwgeASFDhbYabRSPskR0Q==',
    'Content-Type' => 'application/json; charset=UTF-8',
    'Cookie' => 'JSESSIONID=41ABA76E6D45A44D6419B3F26A0851ED'
];
$USER_ID = 'd4b29595b6fe764e09078a0dad7352ff';
$DEFAULT_IMG = 'https://img0.baidu.com/it/u=4079405848,3806507810&fm=253&fmt=auto&app=138&f=JPEG?w=500&h=750';

// 分类映射（原 class_name 和 class_url）
$CATEGORIES = [
    ['type_id' => 'yuju', 'type_name' => '豫剧'],
    ['type_id' => 'hmx', 'type_name' => '黄梅戏'],
    ['type_id' => 'yueju', 'type_name' => '越剧'],
    ['type_id' => 'jingju', 'type_name' => '京剧'],
    ['type_id' => 'pingju', 'type_name' => '评剧'],
    ['type_id' => 'quju', 'type_name' => '曲剧'],
    ['type_id' => 'hnzz', 'type_name' => '坠子'],
    ['type_id' => 'qinq', 'type_name' => '秦腔'],
    ['type_id' => 'hbbz', 'type_name' => '河北梆子'],
    ['type_id' => 'chaoju', 'type_name' => '潮剧'],
    ['type_id' => 'gddx', 'type_name' => '粤剧'],
    ['type_id' => 'huju', 'type_name' => '沪剧'],
    ['type_id' => 'ejx', 'type_name' => '二夹弦'],
    ['type_id' => 'kunqu', 'type_name' => '昆曲'],
    ['type_id' => 'hnqs', 'type_name' => '河南琴书'],
    ['type_id' => 'huaiju', 'type_name' => '淮剧'],
    ['type_id' => 'danxian', 'type_name' => '单弦'],
    ['type_id' => 'xqx', 'type_name' => '西秦戏'],
    ['type_id' => 'wuju', 'type_name' => '婺剧'],
    ['type_id' => 'SDBZ', 'type_name' => '上党梆子'],
    ['type_id' => 'bzx', 'type_name' => '白字戏'],
    ['type_id' => 'hndgs', 'type_name' => '河南大鼓书'],
    ['type_id' => 'yued', 'type_name' => '越调'],
    ['type_id' => 'dianju', 'type_name' => '滇剧'],
    ['type_id' => 'tkdq', 'type_name' => '太康道情'],
    ['type_id' => 'MZYY', 'type_name' => '民族音乐'],
    ['type_id' => 'yangju', 'type_name' => '扬剧'],
    ['type_id' => 'other', 'type_name' => '其他'],
    ['type_id' => 'else', 'type_name' => '曲艺晚会'],
    ['type_id' => 'ERT', 'type_name' => '二人台'],
    ['type_id' => 'blbz', 'type_name' => '北路梆子'],
    ['type_id' => 'caidiao', 'type_name' => '彩调'],
    ['type_id' => 'lq', 'type_name' => '乐腔'],
    ['type_id' => 'WK', 'type_name' => '老年大学'],
    ['type_id' => 'lvjv', 'type_name' => '吕剧'],
    ['type_id' => 'tjsd', 'type_name' => '天津时调'],
    ['type_id' => 'xq', 'type_name' => '戏曲'],
    ['type_id' => 'liuqx', 'type_name' => '柳琴戏'],
    ['type_id' => 'jydg', 'type_name' => '京韵大鼓'],
    ['type_id' => 'pyx', 'type_name' => '皮影戏'],
    ['type_id' => 'xj', 'type_name' => '湘剧'],
    ['type_id' => 'spd', 'type_name' => '四平调'],
    ['type_id' => 'qiongju', 'type_name' => '琼剧'],
    ['type_id' => 'xiju', 'type_name' => '锡剧'],
    ['type_id' => 'pingshu', 'type_name' => '评书'],
    ['type_id' => 'shaojv', 'type_name' => '绍剧'],
    ['type_id' => 'jddg', 'type_name' => '京东大鼓'],
    ['type_id' => 'luju', 'type_name' => '庐剧'],
    ['type_id' => 'huaju', 'type_name' => '话剧'],
    ['type_id' => 'xhdg', 'type_name' => '西河大鼓'],
    ['type_id' => 'huagx', 'type_name' => '花鼓戏'],
    ['type_id' => 'chuanju', 'type_name' => '川剧'],
    ['type_id' => 'xiangsheng', 'type_name' => '相声'],
    ['type_id' => 'wb', 'type_name' => '宛梆'],
    ['type_id' => 'jzyg', 'type_name' => '晋中秧歌'],
    ['type_id' => 'caichaxi', 'type_name' => '采茶戏'],
    ['type_id' => 'pujv', 'type_name' => '蒲剧'],
    ['type_id' => 'hj', 'type_name' => '汉剧'],
    ['type_id' => 'minju', 'type_name' => '闽剧'],
    ['type_id' => 'jinju', 'type_name' => '晋剧'],
    ['type_id' => 'bjqs', 'type_name' => '北京琴书'],
    ['type_id' => 'sgj', 'type_name' => '山歌剧'],
    ['type_id' => 'jiju', 'type_name' => '吉剧'],
    ['type_id' => 'zzx', 'type_name' => '正字戏'],
    ['type_id' => 'gj', 'type_name' => '赣剧'],
    ['type_id' => 'chuju', 'type_name' => '楚剧'],
    ['type_id' => 'dpd', 'type_name' => '大平调'],
    ['type_id' => 'bdld', 'type_name' => '保定老调']
];

// ================= 请求函数 =================
function doPost($url, $data, $headers = []) {
    global $HEADERS;
    
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    
    $headerArray = [];
    foreach (array_merge($HEADERS, $headers) as $k => $v) {
        $headerArray[] = "$k: $v";
    }
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headerArray);
    
    $result = curl_exec($ch);
    curl_close($ch);
    return $result;
}

// ================= 路由逻辑 =================

$ac = $_GET['ac'] ?? null;
$t = $_GET['t'] ?? null;    // 分类ID
$pg = $_GET['pg'] ?? 1;     // 页码
$ids = $_GET['ids'] ?? null; // 专辑ID（用于详情）
$wd = $_GET['wd'] ?? null;   // 搜索关键词
$play = $_GET['play'] ?? null; // 播放参数

// 1. 播放解析
if ($play !== null) {
    // 格式：code?xxx（原JS中通过?分割获取code）
    $code = explode('?', $play)[1] ?? '';
    
    $data = json_encode([
        "item" => "o3",
        "mask" => 0,
        "nodeCode" => "001000",
        "project" => "lyhxcx",
        "px" => 2,
        "resCode" => $code,
        "userId" => $USER_ID
    ]);
    
    $html = doPost('https://fly.daoran.tv/API_ROP/play/get/playurl', $data);
    $json = json_decode($html, true);
    
    $url = $json['playUrls']['hd'] ?? '';
    
    if ($url) {
        echo json_encode([
            'parse' => 0, // 直接播放
            'url' => $url
        ]);
    } else {
        echo json_encode(['error' => '播放地址获取失败'], JSON_UNESCAPED_UNICODE);
    }
    exit;
}

// 2. 详情页（二级）
if (!empty($ids)) {
    // ids 格式：https://zheshiyitaiojialianjie.com?code
    $code = explode('?', $ids)[1] ?? '';
    
    $data = json_encode([
        "albumCode" => $code,
        "cur" => 1,
        "pageSize" => 100,
        "userId" => $USER_ID,
        "channel" => "oppo",
        "item" => "y9",
        "nodeCode" => "001000",
        "project" => "lyhxcx"
    ]);
    
    $html = doPost('https://fly.daoran.tv/API_ROP/album/res/list', $data);
    $json = json_decode($html, true);
    
    $list = $json['pb']['dataList'] ?? [];
    $urls = [];
    
    foreach ($list as $it) {
        $urls[] = $it['name'] . '$' . 'https://zheshiyitaiojialianjie.com?' . $it['code'];
    }
    
    $vod = [
        'vod_id' => $ids,
        'vod_name' => '戏曲专辑',
        'vod_pic' => $DEFAULT_IMG,
        'vod_content' => '',
        'vod_play_from' => '✨宝盒专享',
        'vod_play_url' => implode('#', $urls)
    ];
    
    echo json_encode(['list' => [$vod]], JSON_UNESCAPED_UNICODE);
    exit;
}

// 3. 搜索
if (!empty($wd)) {
    $data = json_encode([
        "cur" => 1,
        "free" => 0,
        "keyword" => $wd,
        "nodeCode" => "001000",
        "orderby" => "hot",
        "pageSize" => 200,
        "project" => "lyhxcx",
        "px" => 2,
        "sect" => [],
        "userId" => $USER_ID
    ]);
    
    $html = doPost('https://fly.daoran.tv/API_ROP/search/album/list', $data);
    $json = json_decode($html, true);
    
    $list = $json['pb']['dataList'] ?? [];
    $result = [];
    
    foreach ($list as $it) {
        $id = 'https://zheshiyitaiojialianjie.com?' . $it['code'];
        $result[] = [
            'vod_id' => $id,
            'vod_name' => $it['name'],
            'vod_pic' => $DEFAULT_IMG,
            'vod_remarks' => $it['des'] ?? ''
        ];
    }
    
    echo json_encode(['list' => $result, 'page' => intval($pg)], JSON_UNESCAPED_UNICODE);
    exit;
}

// 4. 分类列表
if (!empty($t)) {
    $data = json_encode([
        "cur" => intval($pg),
        "free" => 0,
        "orderby" => "hot",
        "pageSize" => 3000,
        "resType" => 1,
        "sect" => $t,
        "tagId" => 0,
        "userId" => $USER_ID,
        "channel" => "oppo",
        "item" => "y9",
        "nodeCode" => "001000",
        "project" => "lyhxcx"
    ]);
    
    $html = doPost('https://fly.daoran.tv/API_ROP/search/album/screen', $data);
    $json = json_decode($html, true);
    
    $list = $json['pb']['dataList'] ?? [];
    $result = [];
    
    foreach ($list as $it) {
        $id = 'https://zheshiyitaiojialianjie.com?' . $it['code'];
        $result[] = [
            'vod_id' => $id,
            'vod_name' => $it['name'],
            'vod_pic' => $DEFAULT_IMG,
            'vod_remarks' => $it['des'] ?? ''
        ];
    }
    
    echo json_encode(['list' => $result, 'page' => intval($pg)], JSON_UNESCAPED_UNICODE);
    exit;
}

// 5. 首页（返回分类）
echo json_encode([
    'class' => $CATEGORIES,
    'list' => [] // 首页不需要列表
], JSON_UNESCAPED_UNICODE);