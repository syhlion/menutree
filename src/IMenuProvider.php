<?php
/**
 * Created by PhpStorm.
 * User: scott
 * Date: 2015/1/7
 * Time: 上午 9:19
 */

namespace Rde;


interface IMenuProvider
{
    public function get();

    public function update(Array $updateData);

    public function getSelfColumn();

    public function getParentColumn();

    public function find($callback);

}