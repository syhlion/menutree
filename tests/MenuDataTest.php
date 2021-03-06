<?php
/**
 * Created by PhpStorm.
 * User: scott
 * Date: 2015/1/9
 * Time: 下午 2:43
 */
class MenuDataTest extends PHPUnit_Framework_TestCase
{
    protected  $pdo;

    private $menuData;


    //初始化db
    //TODO php5.4 預載的sqlite 3.7.7.1 無法sql batch寫法  所以分成多筆寫入
    protected function setUp()
    {
        $this->pdo = new PDO('sqlite:' . dirname(__FILE__) . '/dbtest/menudbtest.sqlite3');
        $sql = "DELETE FROM menu";
        $this->pdo->query($sql);
        $sql = "INSERT INTO menu (id, item_id, parent_id, depth, left, right, url_name, url) VALUES (1,1,'null',0,0,0,'test/test1','test1');
                INSERT INTO menu (id, item_id, parent_id, depth, left, right, url_name, url) VALUES (1,2,1,0,0,0,'test/test2','test2');
                INSERT INTO menu (id, item_id, parent_id, depth, left, right, url_name, url) VALUES (1,3,2,0,0,0,'test/test3','test3');
                INSERT INTO menu (id, item_id, parent_id, depth, left, right, url_name, url) VALUES (1,3,2,0,0,0,'test/test3','test3');
                INSERT INTO menu (id, item_id, parent_id, depth, left, right, url_name, url) VALUES (1,4,3,0,0,0,'test/test4','test4');
                INSERT INTO menu (id, item_id, parent_id, depth, left, right, url_name, url) VALUES (1,5,4,0,0,0,'test/test5','test5');
                INSERT INTO menu (id, item_id, parent_id, depth, left, right, url_name, url) VALUES (1,6,5,0,0,0,'test/test6','test6');
                INSERT INTO menu (id, item_id, parent_id, depth, left, right, url_name, url) VALUES (1,7,2,0,0,0,'test/test7','test7');
                ";
        $this->pdo->beginTransaction();
        $this->pdo->exec($sql);
        $this->pdo->commit();
        $this->menuData = new \Rde\MenuData($this->pdo);
        $this->menuData->setFilter(array(2,7));
    }


    public function testFind()
    {
        $callback = function($item){
            if($item['url'] === 'test7') {
                return true;
            }
        };
        $result = $this->menuData->find($callback);
        $this->assertEquals($result[0]['url_name'], 'test/test7');

        $callback2 = function($item){
            if($item['url_name'] === 'test/test2') {
                return true;
            }
        };
        $result2 = $this->menuData->find($callback2);
        $this->assertEquals($result2[0]['url'], 'test2');


    }

    public function testGet()
    {
        $data = $this->menuData->get();
        $success = array(
            array(
                "id" => 1,
                "item_id" => 2,
                "parent_id" => 1,
                "depth" => 0,
                "left" => 0,
                "right" => 0,
                "url_name" => 'test/test2',
                "url" => 'test2',
            ),
            array(
                "id" => 1,
                "item_id" => 7,
                "parent_id" => 2,
                "depth" => 0,
                "left" => 0,
                "right" => 0,
                "url_name" => 'test/test7',
                "url" => 'test7',
            ),
        );
        $this->assertEquals($success, $data);
    }

    public function testInsert()
    {
        $success = array(
            array(
                "id" => 1,
                "item_id" => 2,
                "parent_id" => 1,
                "depth" => 0,
                "left" => 0,
                "right" => 0,
                "url_name" => 'test/test2',
                "url" => 'test2',
            ),
            array(
                "id" => 1,
                "item_id" => 9,
                "parent_id" => 2,
                "depth" => 0,
                "left" => 0,
                "right" => 0,
                "url_name" => 'test/test9',
                "url" => 'test9',
            ),
            array(
                "id" => 1,
                "item_id" => 7,
                "parent_id" => 2,
                "depth" => 0,
                "left" => 0,
                "right" => 0,
                "url_name" => 'test/test7',
                "url" => 'test7',
            ),
        );
        $insertData = array(
            array(
            "item_id" => 9,
            "parent_id" => 2,
            "depth" => 0,
            "left" => 0,
            "right" => 0,
            "url_name" => 'test/test9',
            "url" => 'test9',
            "id" => 1
            )
        );

        $this->assertEquals($success, $this->menuData->setFilter(array("2", "9", "7"))->insert(1, $insertData)->get());
    }

    public function testGetSelfColumn()
    {
        $this->assertEquals($this->menuData->getSelfColumn(), 'item_id');

    }

    public function testGetParentColumn()
    {
        $this->assertEquals($this->menuData->getParentColumn(), 'parent_id');
    }

    public function testUpdate()
    {
        $update = array(
            array(
                "id" => 1,
                "item_id" => 8,
                "parent_id" => 2,
                "depth" => 0,
                "left" => 0,
                "right" => 0,
                "url_name" => 'test/test8',
                "url" => 'test8',
            ),
        );
        $this->menuData->update($update);

        $this->menuData->setFilter(array(8));

        $callback = function($item){
            if($item['url_name'] === 'test/test8') {
                return true;
            }
        };
        $result = $this->menuData->find($callback);
        $this->assertEquals($result[0]['url'], 'test8');
    }


    protected function tearDown()
    {
        $sql = "DELETE FROM menu";
        $this->pdo->query($sql);
        $this->pdo = null;
    }




}
