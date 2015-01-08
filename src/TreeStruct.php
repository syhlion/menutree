<?php
/**
 * Created by PhpStorm.
 * User: scott
 * Date: 2015/1/7
 * Time: 上午 9:15
 */

namespace Rde;


class TreeStruct
{

    /**
     * 拿樹狀結構
     * @param $data 一個可以迭代陣列
     * @param $selfColumn 迭代陣列裡,自己唯一ID欄位名稱
     * @param $parentColumn 迭代陣列裡,找尋父欄位ID欄位名稱
     * @return array
     */
    public static function get($data, $selfColumn, $parentColumn)
    {
        $memberList = array();
        foreach ($data as $value) {
            if ($value[$parentColumn] == 'null') {
                $node = array();
                $deep = 0;
                $node['deep'] = $deep;
                $node['self'] = $value;
                $node['children'] = static::create($data, $selfColumn, $parentColumn, $value[$selfColumn], $deep);
                $memberList[]=$node;
            }
        }

        return $memberList;
    }

    // 創建樹狀資料
    private static function create($data, $selfColumn, $parentColumn, $parentId, $deep)
    {
        $memberList = array();

        //深度累加
        $deep = $deep + 1;
        foreach ($data as $value) {
            if ($parentId == $value[$parentColumn]) {
                $node = array();
                $node['deep'] = $deep;
                $node['self'] = $value;
                $node['children'] = static::create($data, $selfColumn, $parentColumn, $value[$selfColumn], $deep);
                $memberList[]=$node;
            }

        }

        return $memberList;
    }

}
