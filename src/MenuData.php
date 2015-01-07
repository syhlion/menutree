<?php
/**
 * Created by PhpStorm.
 * User: scott
 * Date: 2015/1/7
 * Time: 上午 9:33
 */

namespace Rde;


use SebastianBergmann\Exporter\Exception;

class MenuData implements IMenuProvider
{

    private $source;

    private $selfColumn = 'item_id';
    private $parentColumn = 'parent_id';
    private $datafields = array('id' => '', 'item_id' => '', 'parent_id'=> '', 'depth'=> '', 'left' => '', 'right' => '', 'url_name' =>'', 'url' );
    private $userMenu = array();

    private $sqlite;
    public function __construct(Array $userMenu = array())
    {
        try {
            $this->sqlite = new PDO('sqlite:'.__DIR___.'/menudata.db3');
            $this->userMenu = $userMenu;
        } catch(Exception $e) {

        }

    }
    public function find($callback)
    {
        $result = array();
        foreach ($this->source as $value) {
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
        //$origin = Config::get('systemmenu');

        $rs = $this->sqlite->query("SELECT * FROM menu");
        $rs->setFetchMode(PDO::FETCH_ASSOC);
        $origin = $rs->fetchAll();

        if (count($filters) != 0) {
            $newsource = array();
            foreach ($origin as $value) {
                if (in_array($value[$this->selfColumn], $filters)) {
                    $newsource[] = $value;
                }
            }

            $this->source = $newsource;
        } else {
            $this->source = $origin;
        }

        return $this->unique($this->source);
    }



    public function update(Array $updateData)
    {
        try {

            foreach ($updateData as $data) {
                $values[] = "(".implode(",", $data).")";
            }
            $sql = "INSERT INTO menu (`id`, `item_id`, `parent_id`, `depth`, `left`, `right`, `url_name`, `url`) VALUES (".implode(",", $values).")";
            $this->sqlite->query($sql);

            return true;
        } catch(Excetpion $e) {

            throw $e;
        }
    }

    //把建構子的條件組成所需的array
    private function getfilter()
    {
        if (count($this->userMenu) === 0) return array();
        $menu = implode("|", $this->userMenu);
        $menu = explode("|", $menu);
        $filter = array_unique($menu);

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