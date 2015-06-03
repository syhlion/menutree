<?php
/**
 * Created by PhpStorm.
 * User: scott
 * Date: 2015/1/7
 * Time: 下午 1:46
 */

namespace Rde;
use \PDO;

class User implements UserProviderInterface
{

    private $pdo;
    private $usergroup_table;
    private $menugroup_table = "menu_menugroup";
    private $userId;

    /**
     * @param PDO $pdo pdo連線
     * @param $site 站台 ex allctl,sk2,...
     * @param $userId 使用者名稱
     */
    public function __construct(PDO $pdo, $site, $userId)
    {

        //因應帳號兩個來源，一個是ACC一個是本身DB 未來整回ACC時,要作修改
        $this->pdo = $pdo;
        if ($site == "allctl") {
            $this->usergroup_table = "menu_allctlusergroup";
        } else {
            $this->usergroup_table = "menu_usergroup";
        }
        $this->userId = $userId;

    }

    /**
     * @return mixed array
     */
    public function findMenu()
    {
        $sql = "SELECT * FROM {$this->usergroup_table}
                JOIN {$this->menugroup_table}
                ON {$this->menugroup_table}.group_id = {$this->usergroup_table}.group_id
                WHERE user_id = {$this->userId}";
        $item = array();

        $rs = $this->pdo->query($sql);
        $rs->setFetchMode(PDO::FETCH_ASSOC);
        $result_arr = $rs->fetchAll();
        foreach ($result_arr as $data) {
            $tmp =  explode("|", $data['item_list']);
            $item = array_merge($tmp, $item);
        }

        //因為不給null 系統選單會預設全撈
        $item= (count($item) <= 0 ) ? array('null') : $item;
        return $item;
    }

}
