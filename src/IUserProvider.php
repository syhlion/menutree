<?php
/**
 * Created by PhpStorm.
 * User: scott
 * Date: 2015/1/7
 * Time: 下午 1:43
 */

namespace Rde;


interface IUserProvider
{
    /**
     * 拿回使用者選單key
     * @param $user_key
     * @return mixed array ex array(item_1, item_2, ....)
     */
    public function find_menu();


}