<?php

/**
 * Copyright 2018
 * mongo-php-library的Collection扩展
 * 推荐：php使用的扩展是mongodb，地址：https://pecl.php.net/package/mongodb
 * 要求：library 使用 https://github.com/mongodb/mongo-php-library
 * 使用：直接在扩展Collection类中，添加 use \MongoDB\MongoDBCollectionTrait;
 */

namespace MongoDB;

trait MongoDBCollectionTrait
{
    public static $update_set = ['$set', '$push', '$pushAll', '$pop', '$pull'];

    // 特殊字段设置，表是否启用一些通配字段
    public $status = NULL; // 状态
    public $add_time = NULL; // 添加时间戳
    public $update_time = NULL; // 更新时间戳

    /**
     * 创建，成功后返回本条document的id
     * @param $info
     * @return mixed
     */
    public function create(array $info)
    {
        if (empty($info)) {
            throw \MPHP\MException('DB ERROR.');
        }

		// id是否自增长
        $this->_id_auto && $info['_id'] = $this->lastId();

        // 其他公用字段
        !isset($info['status']) && $this->status && $info['status'] = 1;
        !isset($info['add_time']) && $this->add_time && $info['add_time'] = time();
        !isset($info['update_time']) && $this->update_time && $info['update_time'] = time();

        if ($this->_id_auto) {
            return $info['_id'];
        }

        return $this->insertOne($info)->getInsertedId();
    }

    /**
     * 更新
     * @param $filter
     * @param $update
     * @param array $options
     * @return int
     * @example update(['_id' => 1], ['age' => 18])
     */
    public function update($filter, array $update, array $options = ['multi' => TRUE]) : int
    {
        if (array_intersect(array_keys($update), self::$update_set)) {
            // 支持 update_time
            if ($this->add_time && !isset($update['$set']['update_time'])) {
                // 补充 add_time 字段，只在执行单纯的创建一个记录的时候补充，UPDATE时不更新,REPLACE&INSERT时更新
                $update['$set']['update_time'] = time();
            }

            // 其实就 1 个字段
            $update_data = $update;

        } else {
            // 支持 update_time
            if ($this->add_time && !isset($update['update_time'])) {
                // 补充 add_time 字段，只在执行单纯的创建一个记录的时候补充，UPDATE时不更新,REPLACE&INSERT时更新
                $update['update_time'] = time();
            }

            $update_data = ['$set' => $update];
        }

        return $this->updateMany($this->generateIdFilter($filter), $update_data, $options)->getMatchedCount();
    }

    /**
     * 获取单条
     * @param $filter
     * @param array $options
     * @return array
     * @example read(['_id' => 1], ['add_time' => -1]);
     */
    public function read($filter, array $sort = [], array $options = []) : array
    {
        $r = $this->findOne($this->generateIdFilter($filter), $this->generateSortOptions($sort, $options));
        if ($r) return $r->getArrayCopy();
        return [];
    }

    /**
     * 获取总数
     * @param array $filter
     * @param array $options
     * @return int
     */
    public function getTotal($filter = [], array $options = []) : int
    {
        return $this->count($filter, $options);
    }

    /**
     * 获取列表
     * @param array $filter 条件
     * @param array $options 附加条件
     * @param int $page 页码
     * @param int $per_page
     * @param array $sort
     * @return array
     * @example
     */
    public function getList($filter = [], int $page = 1, int $per_page = 100, array $sort = [], array $options = []) : array
    {
        $options = $this->generateLimitOptions($page, $per_page, $options);
        $options = $this->generateSortOptions($sort, $options);

        return $this->find($this->generateIdFilter($filter), $options)->toArray();
    }

    /**
     * 获取统计数组
     * @param $pipeline
     * @param array $options
     * @return mixed
     */
    public function getAggregate($pipeline, array $options = []) : array
    {
        return $this->aggregate($pipeline, $options)->toArray();
    }

    /**
     * 获取去重后的某个字段值列表
     * @param $distinctField
     * @param array $filter
     * @param int $page
     * @param int $per_page
     * @param array $sort
     * @param array $options
     * @return mixed[]
     */
    public function getFields($distinctField, array $filter = [], int $page = 1, int $per_page = 100, array $sort = [], array $options = []) : array
    {
        $filter = $this->generateIdFilter($filter);
        $options = $this->generateLimitOptions($page, $per_page, $options);
        $options = $this->generateSortOptions($sort, $options);

        return $this->distinct($distinctField, $filter, $options);
    }

    /**
     * 按指定字段取数据
     * @param $fields
     * @param array $filter
     * @param int $page
     * @param int $per_page
     * @param array $sort
     * @param array $options
     * @return array
     */
    public function getFieldsList($fields, array $filter = [], int $page = 1, int $per_page = 100, array $sort = [], array $options = []) : array
    {
        $fields = (array) $fields;
        $filter = $this->generateIdFilter($filter);

        $options = $this->generateLimitOptions($page, $per_page, $options);
        $options = $this->generateSortOptions($sort, $options);

        $options['projection'] = array_fill_keys($fields, 1);
        if (!isset($options['projection']['_id'])) {
            $options['projection']['_id'] = 0;
        }

        return $this->find($filter, $options)->toArray();
    }

    /**
     * 删除
     * @param $filter
     * @param array $options
     */
    public function delete($filter, array $options = []) : int
    {
        return $this->deleteMany($this->generateIdFilter($filter), $options)->getDeletedCount();
    }

    /**
     * @param $filter
     * @param array $increatment_info ['count' => 1]
     * @return int 返回写入的结果
     */
    public function increatment($filter, array $increatment_info) : int
    {
        list ($k, $v) = each($increatment_info);

        $res = $this->findOneAndUpdate(
        		$this->generateIdFilter($filter), 
        		['$inc' => $increatment_info], 
        		['upsert' => true, 'projection' => [$k => 1, '_id' => 0]]
        	)->getArrayCopy();
        return $v + $res[$k];
    }

    /**
     * 拼装分页参数
     * @param $page
     * @param $per_page
     * @param array $options
     * @return array
     */
    private function generateLimitOptions(int $page, int $per_page, array $options = [])
    {
        // 不分页
        if ($per_page < 1) {
            return $options;
        }

        $page = max($page, 1);
        $options['limit'] = max($per_page, 1);
        $options['skip'] = (1 == $page) ? 0 : ($page-1) * $options['limit'];

        return $options;
    }

    /**
     * 拼装排序参数
     * @param $sort
     * @param array $options
     * @return array
     */
    private function generateSortOptions(array $sort, array $options = [])
    {
        if ($sort) {
            $options['sort'] = $sort;
        }

        return $options;
    }

    /**
     * 拼装带有_id的filter
     * @param array $filter
     * @return array|string
     */
    private function generateIdFilter($filter)
    {
        if (!$filter) {
            return $filter;
        }
        // 如果纯字符串或者ObjectID，当_id处理
        if (is_string($filter) || $filter instanceof \MongoDB\BSON\ObjectID) {
            return ['_id' => $this->str2objectid($filter)];
        }
        if (isset($filter[0])) {
            return ['_id' => ['$in' => $this->str2objectid($filter)]];
        }
        if (isset($filter['_id'])) {
            if (is_array($filter['_id'])) {
                // 数组
                if (isset($filter['_id']['$in'])) {
                    $filter['_id']['$in'] = $this->str2objectid($filter['_id']['$in']);
                } else {
                    $filter['_id'] = ['$in' => $this->str2objectid($filter['_id'])];
                }
            } else {
                // 字串
                $filter['_id'] = $this->str2objectid($filter['_id']);
            }
        }

        return $filter;
    }

    /**
     * 普通串转换成mongodb的objectid
     * @param $str
     * @return \MongoDB\BSON\ObjectID
     */
    private function str2objectid($arr)
    {
        if (empty($arr) || is_numeric($arr) || $arr instanceof \MongoDB\BSON\ObjectID) {
            return $arr;
        }

        if (is_array($arr)) {
            return array_map(['self', 'str2objectid'], $arr);
        }

        return new \MongoDB\BSON\ObjectID($arr);
    }

}
