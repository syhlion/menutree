<?php
/**
 * Created by PhpStorm.
 * User: scott
 * Date: 2015/1/7
 * Time: 上午 9:18
 */

namespace Rde\MenuTree;


class TreeProxy
{

    private $treeData;

    private $sourceObject;


    public function __construct(IMenuProvider $source)
    {
        $this->sourceObject = $source;
        $this->treeData = TreeStruct::get($source->get(), $source->getSelfColumn(), $source->getParentColumn());
    }

    //取得樹狀結構
    public function getTree()
    {
        return $this->treeData;
    }

    //搜尋樹狀裡的元素
    public function find($callback)
    {
        return $this->sourceObject->find($callback);
    }


}
