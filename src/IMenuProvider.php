<?php
/**
 * Created by PhpStorm.
 * User: scott
 * Date: 2015/1/7
 * Time: 上午 9:19
 */

namespace Rde;


interface IMenuProvider
{

    /**
     * 拿回過濾完的資料
     * @return mixed
     */
    public function get();


    /**
     * 更新資料（會整個資料刷新而不是插入單筆）
     * @param array $updateData
     */
    public function update(Array $updateData);

    /**
     * 回傳自己欄位名稱
     * @return mixed
     */
    public function getSelfColumn();

    /**
     * 回傳父親欄位名稱
     * @return mixed
     */
    public function getParentColumn();

    /**
     * 尋找資料
     * @param $callback
     */
    public function find($callback);

    /**
     * 設定過濾資料條件
     * @param array $filter ex array(item_1, item2, ...)
     */
    public function setFilter(Array $filter);

}