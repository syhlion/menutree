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
    private $userMenu = array();

    private $sqlite;

    /**
     * 選單建構
     * @param array $userMenu 可選填 不填會返回全部選單
     * @param PDO $pdo 可選填 不填預設就是src/storage/menudata.sqlite3
     */
    public function __construct(Array $userMenu = array(), PDO $pdo  = null)
    {
        try {
            if ($pdo == null) {
                $pdo = new PDO('sqlite:' . dirname(__FILE__) . '/storage/menudata.sqlite3');
            }
            $this->sqlite = $pdo;
            $this->userMenu = $userMenu;
        } catch(Exception $e) {

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



    public function update(Array $updateData)
    {
        try {
            $sql = "DELETE FROM menu";
            $this->sqlite->query($sql);
            foreach ($updateData as $data) {

                $values[] = "('".implode("','", $data)."')";

            }

            $sql = "INSERT INTO menu (`id`, `item_id`, `parent_id`, `depth`, `left`, `right`, `url_name`, `url`) VALUES ".implode(",", $values);
            $this->sqlite->query($sql);

        } catch(Exception $e) {

            throw $e;
        }
    }

    public function setFilter(Array $filter)
    {
        $this->userMenu = array_unique($filter);

    }

    //把建構子的條件組成所需的array
    private function getfilter()
    {
        if (count($this->userMenu) === 0) return array();
        $filter = array_unique($this->userMenu);

        return $filter;
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


}