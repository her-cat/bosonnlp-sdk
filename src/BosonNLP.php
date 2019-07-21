<?php

/*
 * This file is part of the her-cat/bosonnlp-sdk.
 *
 * (c) her-cat <i@her-cat.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace HeXiangHui\BosonNLP;

/**
 * Class BosonNLP.
 */
class BosonNLP extends BaseApi
{
    /**
     * @var Cluster
     */
    private $cluster;

    /**
     * @var Comment
     */
    private $comment;

    /**
     * 情感分析.
     *
     * @see http://docs.bosonnlp.com/sentiment.html
     *
     * @param string|array $content 文本内容
     * @param string       $model   行业模型名称
     *
     * @return array
     */
    public function sentiment($content, $model = 'general')
    {
        $url = sprintf(self::SENTIMENT_URL, $model);

        return $this->request($url, $content);
    }

    /**
     * 命名实体识别.
     *
     * @see http://docs.bosonnlp.com/ner.html
     *
     * @param string|array $content     文本内容
     * @param int          $sensitivity 调节准确率与召回率之间的平衡
     *
     * @return array
     */
    public function ner($content, $sensitivity = 3)
    {
        $url = self::NER_URL.$sensitivity;

        return $this->request($url, $content);
    }

    /**
     * 依存文法分析.
     *
     * @see http://docs.bosonnlp.com/depparser.html
     *
     * @param string|array $content 文本内容
     *
     * @return array
     */
    public function depparser($content)
    {
        return $this->request(self::DEPPARSER_URL, $content);
    }

    /**
     * 关键词提取.
     *
     * @see http://docs.bosonnlp.com/keywords.html
     *
     * @param string $content   文本内容
     * @param int    $top_k     返回结果条数
     * @param bool   $segmented bool 是否已经分词
     *
     * @return array
     */
    public function keywords($content, $top_k = 100, $segmented = false)
    {
        $query_string = ['top_k' => $top_k];
        if ($segmented) {
            $query_string['segmented'] = '';
        }

        $url = self::KEYWORD_URL.http_build_query($query_string);

        return $this->request($url, $content);
    }

    /**
     *新闻分类.
     *
     * @see http://docs.bosonnlp.com/classify.html
     *
     * @param string|array $content 文本内容
     *
     * @return array
     */
    public function classify($content)
    {
        return $this->request(self::CLASSIFY_URL, $content);
    }

    /**
     * 语义联想.
     *
     * @see http://docs.bosonnlp.com/suggest.html
     *
     * @param string $content 文本内容
     * @param int    $top_k   返回结果条数
     *
     * @return array
     */
    public function suggest($content, $top_k = 10)
    {
        $url = self::SUGGEST_URL.$top_k;

        return $this->request($url, $content);
    }

    /**
     * 分词与词性标注.
     *
     * @see  http://docs.bosonnlp.com/tag.html
     *
     * @param string|array $content           文本内容
     * @param int          $space_mode        空格保留选项
     * @param int          $oov_level         新词枚举强度选项
     * @param int          $t2s               繁简转换选项
     * @param int          $special_char_conv 特殊字符转换选项
     *
     * @return array
     */
    public function tag($content, $space_mode = 0, $oov_level = 3, $t2s = 0, $special_char_conv = 0)
    {
        $query_string = [
            'space_mode' => $space_mode,
            'oov_level' => $oov_level,
            't2s' => $t2s,
            'special_char_conv' => $special_char_conv,
        ];

        $url = self::TAG_URL.http_build_query($query_string);

        return $this->request($url, $content);
    }

    public function convertTime($content, $base_time = false)
    {
        $query_string = ['pattern' => $content];
        if ($base_time) {
            $query_string['basetime'] = $base_time;
        }

        $url = self::TIME_URL.http_build_query($query_string);

        return $this->request($url);
    }

    public function summary($title, $content, $word_limit = 0.3, $not_exceed = 0)
    {
        $url = self::SUMMARY_URL;

        $body = [
            'percentage' => $word_limit,
            'not_exceed' => $not_exceed,
            'title' => $title,
            'content' => $content,
        ];

        return $this->request($url, $body);
    }

    /**
     * 文本聚类引擎.
     *
     * @see http://docs.bosonnlp.com/cluster.html
     *
     * @param string|array $content   文本内容
     * @param bool         $task_id   任务id，不传自动生成
     * @param float        $alpha     调节聚类最大cluster大小
     * @param float        $beta      调节聚类平均cluster大小
     * @param int          $wait_time 等待时间，单位秒，传 true 会一直等待任务完成
     *
     * @return array
     */
    public function cluster($content, $task_id = '', $alpha = 0.8, $beta = 0.45, $wait_time = 1800)
    {
        $cluster = $this->createCluster($content, $task_id);

        $cluster->analysis($alpha, $beta);

        $cluster->wait($wait_time);

        return $cluster->result();
    }

    /**
     * 创建一个 Cluster 实例.
     *
     * @param string|array $content 文本内容
     * @param string       $task_id 任务id，不传自动生成
     *
     * @return Cluster
     */
    public function createCluster($content = '', $task_id = '')
    {
        return new Cluster($this->apiToken, $content, $task_id);
    }

    /**
     * 典型意见引擎.
     *
     * @see http://docs.bosonnlp.com/comments.html
     *
     * @param string|array $content   文本内容
     * @param bool         $task_id   任务id，不传自动生成
     * @param float        $alpha     调节聚类最大cluster大小
     * @param float        $beta      调节聚类平均cluster大小
     * @param int          $wait_time 等待时间，单位秒，传 true 会一直等待任务完成
     *
     * @return array
     */
    public function comment($content, $task_id = '', $alpha = 0.8, $beta = 0.45, $wait_time = 1800)
    {
        $comment = $this->createComment($content, $task_id);

        $comment->analysis($alpha, $beta);

        $comment->wait($wait_time);

        return $comment->result();
    }

    /**
     * 创建一个 Comment 实例.
     *
     * @param string|array $content 文本内容
     * @param string       $task_id 任务id，不传自动生成
     *
     * @return Cluster
     */
    public function createComment($content = '', $task_id = '')
    {
        return new Comment($this->apiToken, $content, $task_id);
    }
}
