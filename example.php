<?php

/*
 * This file is part of the her-cat/bosonnlp-sdk.
 *
 * (c) her-cat <i@her-cat.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

require_once 'vendor/autoload.php';

use HeXiangHui\BosonNLP\BosonNLP;

$bosonNLP = new BosonNLP('YOUR_API_TOKEN');

$result = $bosonNLP->sentiment('这家味道还不错', 'food');
// $result = $bosonNLP->sentiment(['这家味道还不错', '菜品太少了而且还不新鲜'], 'food');

print_r($result[0]);
// [非负面概率, 负面概率]
// [0.9991737012037423, 0.0008262987962577828]

$result = $bosonNLP->ner('成都商报记者 姚永忠', 2);
// $result = $bosonNLP->ner(['成都商报记者 姚永忠', '微软XP操作系统今日正式退休'], 2);

print_r($result[0]['word']);
// ["成都", "商报", "记者", "姚永忠"]

print_r($result[0]['tag']);
// ["ns", "n", "n", "nr"]

print_r($result[0]['entity']);
// [[0, 2, "product_name"], [2, 3, "job_title"], [3, 4, "person_name"]]

$result = $bosonNLP->depparser('我以最快的速度吃了午饭');
// $result = $bosonNLP->depparser(['我以最快的速度吃了午饭', '今天是周一']);

print_r($result[0]['head']);
// [6,6,3,4,5,1,-1,6,6]

print_r($result[0]['role']);
// ["SBJ","MNR","VMOD","DEC","NMOD","POBJ","ROOT","VMOD","OBJ"]

print_r($result[0]['tag']);
// ["PN","P","AD","VA","DEC","NN","VV","AS","NN"]

print_r($result[0]['word']);
// ["我", "以", "最", "快", "的", "速度", "吃", "了", "午饭"]

$result = $bosonNLP->keywords('今天是周一', 100);

print_r($result);
// [[0.9057485792650369, "周一"], [0.42163432672857687, "今天"], [0.042941528388678436, "是"]]

$result = $bosonNLP->classify('俄否决安理会谴责叙军战机空袭阿勒颇平民');
//$result = $bosonNLP->classify([
//    '俄否决安理会谴责叙军战机空袭阿勒颇平民',
//    '邓紫棋谈男友林宥嘉：我觉得我比他唱得好',
//    'Facebook收购印度初创公司',
//]);

echo $result[0].PHP_EOL;
// 5

$result = $bosonNLP->suggest('粉丝', 3);

print_r($result);
// [[0.99999999999999944, "粉丝/n"], [0.48602467961311008, "脑残粉/n"], [0.47638025976400938, "听众/n"]]

$result = $bosonNLP->tag('亚投行意向创始成员国确定为57个');
// $result = $bosonNLP->tag(['亚投行意向创始成员国确定为57个', '“流量贵”频被吐槽'], 1, 3, 0, 1);

$result = $bosonNLP->tag('亚投行意向创始成员国确定为57个');
//$result = $bosonNLP->tag(['亚投行意向创始成员国确定为57个', '“流量贵”频被吐槽'], 1, 3, 0, 1);

print_r($result[0]['word']);
// ["亚投行","意向","创始","成员国","确定","为","57","个"]

print_r($result[0]['tag']);
// ["n","n","vi","n","v","v","m","q"]

$result = $bosonNLP->convertTime('今天晚上8点到明天下午3点', date('Y-m-d'));

echo $result['timespan'][0].PHP_EOL;
// 2018-06-18 20:00:00

echo $result['timespan'][1].PHP_EOL;
// 2018-06-19 15:00:00

echo $result['type'].PHP_EOL;
// timespan_0

$result = $bosonNLP->summary(
    '',
    '腾讯科技讯（刘亚澜）10月22日消息，'.
    '前优酷土豆技术副总裁黄冬已于日前正式加盟芒果TV，出任CTO一职。'.
    '资料显示，黄冬历任土豆网技术副总裁、优酷土豆集团产品技术副总裁等职务，'.
    '曾主持设计、运营过优酷土豆多个大型高容量产品和系统。'.
    '此番加入芒果TV或与芒果TV计划自主研发智能硬件OS有关。'.
    '今年3月，芒果TV对外公布其全平台日均独立用户突破3000万，日均VV突破1亿，'.
    '但挥之不去的是业内对其技术能力能否匹配发展速度的质疑，'.
    '亟须招揽技术人才提升整体技术能力。'.
    '芒果TV是国内互联网电视七大牌照方之一，之前采取的是“封闭模式”与硬件厂商预装合作，'.
    '而现在是“开放下载”+“厂商预装”。'.
    '黄冬在加盟土豆网之前曾是国内FreeBSD（开源OS）社区发起者之一，'.
    '是研究并使用开源OS的技术专家，离开优酷土豆集团后其加盟果壳电子，'.
    '涉足智能硬件行业，将开源OS与硬件结合，创办魔豆智能路由器。'.
    '未来黄冬可能会整合其在开源OS、智能硬件上的经验，结合芒果的牌照及资源优势，'.
    '在智能硬件或OS领域发力。'.
    '公开信息显示，芒果TV在今年6月对外宣布完成A轮5亿人民币融资，估值70亿。'.
    '据芒果TV控股方芒果传媒的消息人士透露，芒果TV即将启动B轮融资。',
    0.1
);

echo $result.PHP_EOL;
// 腾讯科技讯（刘亚澜）10月22日消息，前优酷土豆技术副总裁黄冬已于日前正式加盟芒果TV，出任CTO一职。

$result = $bosonNLP->cluster('今天天气真好', '', 0.8, 0.45, 10);
//$result = $bosonNLP->cluster(['今天天气真好','今天天气不错'], '', 0.8, 0.45, 20);

print_r($result);

// 创建 Cluster 实例
$cluster = $bosonNLP->createCluster('今天天气真好');

// 上传数据
$cluster->push('今天天气不错');
$cluster->push(['点点楼头细雨', '当年戏马会东徐', '今日凄凉南浦']);

// 调用分析
$cluster->analysis(0.8, 0.45);

// 获取状态
$cluster->status();

// 设置等待时间
$cluster->wait(10);

// 主动查询任务状态，等待任务完成
// $cluster->wait(true);

// 获取结果
$result = $cluster->result();

// 清除结果
$cluster->clear();

print_r($result);

$result = $bosonNLP->comment('今天天气真好', '', 0.8, 0.45, 10);
//$result = $bosonNLP->comment(['今天天气真好','今天天气不错'], '', 0.8, 0.45, 20);

print_r($result);

// 创建 Comment 实例
$comment = $bosonNLP->createComment('今天天气真好');

// 上传数据
$comment->push('今天天气不错');
$comment->push(['点点楼头细雨', '当年戏马会东徐', '今日凄凉南浦']);

// 调用分析
$comment->analysis();

// 获取状态
$comment->status();

// 设置等待时间
$comment->wait(10);

// 主动查询任务状态，等待任务完成
// $comment->wait(true);

// 获取结果
$result = $comment->result();

// 清除结果
$comment->clear();

print_r($result);
