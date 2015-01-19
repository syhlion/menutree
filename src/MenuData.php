<?php
/**
 * Created by PhpStorm.
 * User: scott
 * Date: 2015/1/7
 * Time: 上午 9:33
 */

namespace Rde;
use \PDO;

use SebastianBergmann\Exporter\Exception;

class MenuData implements IMenuProvider
{


    private $selfColumn = 'item_id';
    private $parentColumn = 'parent_id';
    private $filter = array();

    private $sqlite;

    /**
     * 選單建構
     * @param PDO $pdo 可選填 不填預設就是src/storage/menudata.sqlite3
     */
    public function __construct(PDO $pdo  = null)
    {
        try {
            if ($pdo == null) {
                $pdo = new PDO('sqlite:' . dirname(__FILE__) . '/storage/menudata.sqlite3');
            }
            $this->sqlite = $pdo;
        } catch(Exception $e) {
            $e->getTrace();
        }

    }
    public function find($callback)
    {
        $result = array();
        foreach ($this->get() as $value) {
            if ($callback($value)) {
                $result[] = $value;
            }
        }

        return $result;
    }

    public function getParentColumn()
    {
        return $this->parentColumn;
    }

    public function getSelfColumn()
    {
        return $this->selfColumn;
    }

    public function get()
    {
        $filters = $this->getfilter();

        $rs = $this->sqlite->query("SELECT * FROM menu");
        $rs->setFetchMode(PDO::FETCH_ASSOC);
        $origin = $rs->fetchAll();
        $newsource = array();

        if (count($filters) != 0) {

            foreach ($origin as $value) {

                if (in_array($value[$this->selfColumn], $filters)) {

                    $newsource[] = $value;
                }
            }

            $source = $newsource;
        } else {
            $source = $origin;
        }

        return $this->unique($source);
    }

    public function getfilter()
    {
        return $this->filter;
    }

    /**
     * 設定過濾資料條件
     * @param array $filter ex array(item_1, item2, ...)
     * @return this
     */
    public function setFilter(Array $filter)
    {
        $this->filter = array_unique($filter);
        return $this;
    }


    public function update(Array $updateData)
    {
        try {

            $sql = "DELETE FROM menu";
            $this->sqlite->exec($sql);
            $sql = "";

            //TODO 因為sqlite 3.7.11前不支援 sql batch寫法 只能以此方法寫入為了能相容mysql & sqlite 採用此方法
            foreach ($updateData as $data) {

                //欄位重置
                $coloum = array();
                $val = array();
                foreach ($data as $key => $value) {

                    $coloum[] = "`".$key."`";
                    $val[] = "'".$value."'";
                }
                $sql .= "INSERT INTO menu (".implode(",", $coloum).") VALUES "."(".implode(",", $val).");";


            }
            $this->sqlite->beginTransaction();
            $this->sqlite->exec($sql);
            $this->sqlite->commit();
            return $this;
        } catch(Exception $e) {
            $this->sqlite->rollBack();
            throw $e;
        }
    }

    //去除重複元素，以免再組treestruct造成無限遞迴
    private function unique($data)
    {
        $seen = array();
        foreach ($data as $key => $val) {
            if (isset($seen[$val[$this->getSelfColumn()]])) {
                unset($data[$key]);
            } else {
                $seen[$val[$this->getSelfColumn()]] = $key;
            }
        }

        unset($seen);

        return $data;
    }


    /**
     * @param $index 要插入的索引位址 (0 base)
     * @param array $insertData
     * @return this
     */
    public function insert($index, Array $insertData)
    {
        $list = $this->get();

        $end = array_slice($list, $index, count($list));
        array_splice($list, $index, count($list), $insertData);
        foreach ($end as $item) {
            array_push($list,$item);
        }
        $this->update($list);

        return $this;

    }
}