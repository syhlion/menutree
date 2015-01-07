<?php
/**
 * Created by PhpStorm.
 * User: scott
 * Date: 2015/1/7
 * Time: 下午 1:46
 */

namespace Rde;
use \PDO;

class User implements IUserProvider
{


    private $site;
    private $connect;
    private $usergroup_table;
    private $menugroup_table = "menu_menugroup";

    public function __construct(PDO $pdo, $site)
    {
        $this->connect = $pdo;
        //$this->site = $site;
        if ($site == "allctl") {
            $this->usergroup_table = "menu_allctlusergroup";
        } else {
            $this->usergroup_table = "menu_usergroup";
        }

    }

    /**
     * @param $group_key
     * @param $menu_list string  ex GAMELIST|FF_GROUP
     * @return mixed
     */
    public function edit_group($group_key, $menu_list)
    {
        // TODO: Implement edit_group() method.
    }

    /**
     * @param $user_key
     * @param array $group_key
     * @return mixed
     */
    public function edit_user_group($user_key, Array $group_key)
    {
        // TODO: Implement edit_user_group() method.
    }

    /**
     * @param $user_key
     * @return mixed array
     */
    public function find_user_menu($user_key)
    {
        $sql = "SELECT * FROM {$this->usergroup_table}
                JOIN {$this->menugroup_table}
                ON {$this->menugroup_table}.group_id = {$this->usergroup_table}.group_id
                WHERE user_id = {$user_key}";
        $menu = array();

        $rs = $this->connect->query($sql);
        $rs->setFetchMode(PDO::FETCH_ASSOC);
        $result_arr = $rs->fetchAll();

        foreach ($result_arr as $data) {
            $menu[] = $data['item_list'];
        }

        //因為不給null 系統選單會預設全撈
        $menu = (count($menu) <= 0 ) ? array('null') : $menu;

        return $menu;
    }

}