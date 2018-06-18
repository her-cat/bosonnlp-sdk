<?php
/**
 * Created by PhpStorm.
 * User: HeXiangHui
 * Date: 2018/6/18
 * Time: 15:53
 */

namespace HeXiangHui\BosonNLP;


/**
 * Class BaseApi
 *
 * @package HeXiangHui\BosonNLP
 */
class BaseApi
{
    /**
     * Your api token.
     * @var
     */
    protected $apiToken;

    /**
     * 情感分析
     */
    const SENTIMENT_URL = 'http://api.bosonnlp.com/sentiment/analysis?%s';

    /**
     * 命名实体识别
     */
    const NER_URL = 'http://api.bosonnlp.com/ner/analysis?sensitivity=';

    /**
     * 依存文法分析
     */
    const DEPPARSER_URL = 'http://api.bosonnlp.com/depparser/analysis';

    /**
     * 关键词提取
     */
    const KEYWORD_URL = 'http://api.bosonnlp.com/keywords/analysis?';

    /**
     * 新闻分类
     */
    const CLASSIFY_URL = 'http://api.bosonnlp.com/classify/analysis';

    /**
     * 语义联想
     */
    const SUGGEST_URL = 'http://api.bosonnlp.com/suggest/analysis?top_k=';

    /**
     * 分词与词性标注
     */
    const TAG_URL = 'http://api.bosonnlp.com/tag/analysis?';

    /**
     * 时间转换
     */
    const TIME_URL = 'http://api.bosonnlp.com/time/analysis?';

    /**
     * 新闻摘要
     */
    const SUMMARY_URL = 'http://api.bosonnlp.com/summary/analysis';

    /**
     * 文本聚类上传
     */
    const CLUSTER_PUSH_URL = 'http://api.bosonnlp.com/cluster/push/%s';

    /**
     * 文本聚类分析
     */
    const CLUSTER_ANALYSIS_URL = 'http://api.bosonnlp.com/cluster/analysis/%s?';

    /**
     * 查看文本聚类任务状态
     */
    const CLUSTER_STATUS_URL = 'http://api.bosonnlp.com/cluster/status/%s';

    /**
     * 获取文本聚类分析结果
     */
    const CLUSTER_RESULT_URL = 'http://api.bosonnlp.com/cluster/result/%s';

    /**
     * 清除文本聚类数据
     */
    const CLUSTER_CLEAR_URL = 'http://api.bosonnlp.com/cluster/clear/%s';

    /**
     * 典型意见上传
     */
    const COMMENT_PUSH_URL = 'http://api.bosonnlp.com/comments/push/%s';

    /**
     * 典型意见分析
     */
    const COMMENT_ANALYSIS_URL = 'http://api.bosonnlp.com/comments/analysis/%s?';

    /**
     * 查看典型意见任务状态
     */
    const COMMENT_STATUS_URL = 'http://api.bosonnlp.com/comments/status/%s';

    /**
     * 获取典型意见分析结果
     */
    const COMMENT_RESULT_URL = 'http://api.bosonnlp.com/comments/result/%s';

    /**
     * 清除典型意见数据
     */
    const COMMENT_CLEAR_URL = 'http://api.bosonnlp.com/comments/clear/%s';

    /**
     * BosonNLP constructor.
     * @param $api_token
     */
    public function __construct($api_token)
    {
        $this->apiToken = $api_token;
    }

    protected function request($url, $body = array(), $header = array(), $method = 'POST')
    {
        array_push($header, 'Accept: application/json');
        array_push($header, 'Content-Type: application/json');
        array_push($header, "X-Token: {$this->apiToken}");

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_TIMEOUT, 60);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 2);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        switch ($method) {
            case 'GET':
                curl_setopt($ch, CURLOPT_HTTPGET, TRUE);
                break;
            case 'POST':
                curl_setopt($ch, CURLOPT_POST, TRUE);
                break;
            case 'PUT':
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
                break;
            case 'DELETE':
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'DELETE');
                break;
        }

        curl_setopt($ch, CURLOPT_USERAGENT, 'SSTS Browser/1.0');
        curl_setopt($ch, CURLOPT_ENCODING, 'gzip');
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);

        if ($body) {
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($body, JSON_UNESCAPED_UNICODE));
        }

        if ($header) {
            curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        }

        $response = curl_exec($ch);
        curl_close($ch);

        return $this->parseResponse($response);
    }

    /**
     * parse response
     * @param $result
     * @return array
     * @throws Exception
     */
    private function parseResponse($result)
    {
        $response = json_decode($result, true);

        if (isset($response['status']) && is_numeric($response['status'])) {
            throw new \Exception($response['message'], $response['status']);
        }

        if (!is_array($response)) {
            return $result;
        }

        return $response;
    }

    protected function generateId($length = 32)
    {
        $pattern = '1234567890abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLOMNOPQRSTUVWXYZ';

        $id = '';
        for($i=0; $i < $length; $i++) {
            $id .= $pattern{mt_rand(0,35)};    //生成php随机数
        }

        return $id;
    }
}