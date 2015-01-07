<?php
/**
 * Created by PhpStorm.
 * User: scott
 * Date: 2015/1/7
 * Time: 下午 1:43
 */

namespace Rde\MenuTree;


interface IUserProvider
{
    /**
     * @param $user_key
     * @return mixed array
     */
    public function find_user_menu($user_key);

    /**
     * @param $group_key
     * @param $menu_list string  ex GAMELIST|FF_GROUP
     * @return mixed
     */
    public function edit_group($group_key, $menu_list);

    /**
     * @param $user_key
     * @param array $group_key
     * @return mixed
     */
    public function edit_user_group($user_key, Array $group_key);
}