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
 * Class Cluster.
 *
 * @see http://docs.bosonnlp.com/cluster.html
 */
class Cluster extends BaseApi
{
    private $taskId;

    /**
     * Cluster constructor.
     *
     * @param $api_token
     * @param string|array $content 文本内容
     * @param string       $task_id 任务id，为空自动生成
     */
    public function __construct($api_token, $content = '', $task_id = '')
    {
        parent::__construct($api_token);

        if ($task_id) {
            $this->taskId = $task_id;
        } else {
            $this->taskId = $this->generateId();
        }

        if ($content) {
            $this->push($content);
        }
    }

    /**
     * 上传数据.
     *
     * @param string|array $content 文本内容
     *
     * @return array
     */
    public function push($content)
    {
        $url = sprintf(self::CLUSTER_PUSH_URL, $this->taskId);
        $body = [];

        foreach ((array) $content as $item) {
            $body[] = [
                '_id' => isset($item['_id']) ? $item['_id'] : $this->generateId(),
                'text' => isset($item['text']) ? $item['text'] : $item,
            ];
        }

        return $this->request($url, $body);
    }

    /**
     * 调用分析.
     *
     * @param float $alpha 调节聚类最大cluster大小
     * @param float $beta  调节聚类平均cluster大小
     *
     * @return array
     */
    public function analysis($alpha = 0.8, $beta = 0.45)
    {
        $query_string = [
            'alpha' => $alpha,
            'beta' => $beta,
        ];

        $url = sprintf(self::CLUSTER_ANALYSIS_URL, $this->taskId).http_build_query($query_string);

        return $this->request($url, [], [], 'GET');
    }

    /**
     * 等待任务完成.
     *
     * @param bool $wait_time 等待时间，单位秒，传 true 会一直等待任务完成
     */
    public function wait($wait_time = true)
    {
        if (is_numeric($wait_time)) {
            sleep($wait_time);
        } else {
            while (true) {
                $result = $this->status();
                if (isset($result['status']) && 'DONE' == $result['status']) {
                    break;
                }

                sleep(1);
            }
        }
    }

    /**
     * 查看任务状态
     *
     * @return array
     */
    public function status()
    {
        $url = sprintf(self::CLUSTER_STATUS_URL, $this->taskId);

        return $this->request($url, [], [], 'GET');
    }

    /**
     * 获取结果.
     *
     * @return array
     */
    public function result()
    {
        $url = sprintf(self::CLUSTER_RESULT_URL, $this->taskId);

        return $this->request($url, [], [], 'GET');
    }

    /**
     * 清除分析结果.
     *
     * @return array
     */
    public function clear()
    {
        $url = sprintf(self::CLUSTER_CLEAR_URL, $this->taskId);

        return $this->request($url, [], [], 'GET');
    }
}
