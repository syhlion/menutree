<?php
/**
 * Created by PhpStorm.
 * User: scott
 * Date: 2015/1/12
 * Time: 下午 3:27
 */

class UserTest extends PHPUnit_Framework_TestCase
{
    private $pdo;

    //TODO 改成用sqlite 測試（原始設定環境是以mysql為主）
    //TODO 跟MenuDataTest一樣，需要找方法解決每測試一次，就會有版本異動。
    public function setUp()
    {
        $this->pdo = new PDO('sqlite:' . dirname(__FILE__) . '/dbtest/userdbtest.sqlite3');
        $sql = "DELETE FROM menu_allctlusergroup";
        $this->pdo->query($sql);
        $sql = "DELETE FROM menu_menugroup";
        $this->pdo->query($sql);
        $sql = "DELETE FROM menu_usergroup";
        $this->pdo->query($sql);

        $sql = "INSERT INTO menu_menugroup (group_name, item_list) VALUES
                ('test', 'test1|test2|test3')";
        $this->pdo->query($sql);
        $groupId = $this->pdo->lastInsertId();

        $sql = "INSERT INTO menu_allctlusergroup (user_id,group_id) VALUES
                (1,$groupId)";
        $this->pdo->query($sql);

        $sql = "INSERT INTO menu_usergroup (user_id,group_id) VALUES
                (1,$groupId)";
        $this->pdo->query($sql);
    }

    public function testFindMenu()
    {
        $user1 = new \Rde\User($this->pdo,'allctl',1);
        $user2 = new \Rde\User($this->pdo,'ctl',1);
        $this->assertEquals($user1->findMenu(),array("test1","test2","test3"));
        $this->assertEquals($user2->findMenu(),array("test1","test2","test3"));
    }

    public function tearDown()
    {
        $sql = "DELETE FROM menu_allctlusergroup";
        $this->pdo->query($sql);
        $sql = "DELETE FROM menu_menugroup";
        $this->pdo->query($sql);
        $sql = "DELETE FROM menu_usergroup";
        $this->pdo->query($sql);
        $this->pdo = null;
    }

}
