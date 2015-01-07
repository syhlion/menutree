<?php
/**
 * Created by PhpStorm.
 * User: scott
 * Date: 2015/1/7
 * Time: 上午 9:35
 */

namespace Rde;
use \PDO;

class MenuTree
{
    //存放TreeProxy物件用
    private $treeProxy = null;

    public function __construct(array $options)
    {
        $site = \Rde\array_get($options, 'site');
        $id = \Rde\array_get($options, 'id');
        $dsn = \Rde\array_get($options, 'dsn');
        $username = \Rde\array_get($options, 'username');
        $password = \Rde\array_get($options, 'password');

        //連線回nsc用
        try {
            $connect = new PDO($dsn, $username, $password);
        } catch (PDOException $e) {
            echo 'Connection failed: ' . $e->getMessage();
        }

        //TODO 因應帳號兩個來源，一個是ACC一個是本身DB 未來整回ACC時,要作修改
        $user = new User($connect, $site);


        $menu = $user->find_user_menu($id);


        $this->treeProxy = new TreeProxy(new MenuData($menu));
    }

    /**
     * 拿樹狀結構
     * @return array
     */
    public function get()
    {
        return $this->treeProxy->getTree();
    }

    /**
     * 權限判斷
     * @param $uri 傳入class 之後的URI (包含class)
     * @return bool
     */
    public function check($uri)
    {

        $callback = function($data) use ($uri){

            //新增去頭尾 "/" 符號
            if (preg_match('/^\/|\/$/', $uri)) {
                $uri = trim($uri, "/");
            }

            //判斷是否符合
            $pattern = "/^".addcslashes($data['url'], "/")."/";
            if (preg_match($pattern, $uri)) {

                return true;
            } else {

                return false;
            }
        };
        $data = $this->systemMenu->find($callback);
        if (count($data) > 0) {

            return true;
        } else {

            return false;
        }

    }
}