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
     * @param $user_key
     * @return mixed array
     */
    public function find_menu();


}