<?php
/**
 * Created by PhpStorm.
 * User: scott
 * Date: 2015/1/7
 * Time: 上午 9:35
 */

namespace Rde;
use \PDO;

class MenuTree
{

    //存放實作IMenuProvider的物件
    private $menuProvider;

    //存放組合完畢的樹狀結構
    private $treeData;

    public function __construct(IMenuProvider $menuProvider)
    {
        $this->menuProvider = $menuProvider;
        $this->treeData = static::getTree($this->menuProvider->get(), $this->menuProvider->getSelfColumn(), $this->menuProvider->getParentColumn());
    }

    /**
     * 拿樹狀結構
     * @return array
     */
    public function get()
    {
        return $this->treeData;
    }

    /**
     * 權限判斷
     * @param $uri 傳入class 之後的URI (包含class)
     * @return bool
     */
    public function check($uri)
    {

        $callback = function($data) use ($uri){

            //新增去頭尾 "/" 符號
            if (preg_match('/^\/|\/$/', $uri)) {
                $uri = trim($uri, "/");
            }

            //判斷是否符合
            $pattern = "/^".addcslashes($data['url'], "/")."/";
            if (preg_match($pattern, $uri)) {

                return true;
            } else {

                return false;
            }
        };
        $data = $this->menuProvider->find($callback);
        if (count($data) > 0) {

            return true;
        } else {

            return false;
        }

    }

    /**
     * 拿樹狀結構
     * @param $data 一個可以迭代陣列
     * @param $selfColumn 迭代陣列裡,自己唯一ID欄位名稱
     * @param $parentColumn 迭代陣列裡,找尋父欄位ID欄位名稱
     * @return array
     */
    private static function getTree($data, $selfColumn, $parentColumn)
    {
        $memberList = array();
        foreach ($data as $value) {
            if ($value[$parentColumn] == 'null') {
                $node = array();
                $deep = 0;
                $node['deep'] = $deep;
                $node['self'] = $value;
                $node['children'] = static::createTree($data, $selfColumn, $parentColumn, $value[$selfColumn], $deep);
                $memberList[]=$node;
            }
        }

        return $memberList;
    }

    // 創建樹狀資料
    private static function createTree($data, $selfColumn, $parentColumn, $parentId, $deep)
    {
        $memberList = array();

        //深度累加
        $deep = $deep + 1;
        foreach ($data as $value) {
            if ($parentId == $value[$parentColumn]) {
                $node = array();
                $node['deep'] = $deep;
                $node['self'] = $value;
                $node['children'] = static::createTree($data, $selfColumn, $parentColumn, $value[$selfColumn], $deep);
                $memberList[]=$node;
            }

        }

        return $memberList;
    }

}