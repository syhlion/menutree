MenuTree 
========

Installation：
------

``` json
{
    "require": {
        "rde/": "0.1.*@dev"
    }
}
```

* PHP環境需要pdo_sqlite pdo_mysql (預設載體)
* 把db_schema資料夾裡的sql 部屬在database。
* 預設資料載體sqlite (/src/storage/menudata.slite3) 裡面的資料格式 （id, item_id, parnet_id, depth, left, right, url_name, url），其中item_id為不可重複的唯一key，可用字串，如不小心寫入重複key，在MenuData取資料時也會慮掉重複字串。id為一般流水號，其他欄位可填可不填。
* 唯一要注意，如果此比資料為樹狀資料最上層，其parnet_id 要填入字串'null'


Usage：
---------------

``` php
$pdo_mysql = new PDO('mysql:host=127.0.0.1;dbname=NSC_AccountDB','root','xxxx');
$user = new User($pdo_mysql);
$pdo = new PDO('sqlite:' . dirname(__FILE__) . '/dbtest/dbtest.sqlite3');
$menutree = new MenuTree(new MenuData($user->find_menu(), $pdo)); //find_menu() 會用array回傳 每個item_id  ex array(item_id1,itemid2...) 這兩個參數都不是必填的
$tree = $menutree->get(); //可拿到完整的樹狀結構
$bool = $menutree->get("controller/method"); //可判斷此uri是否符合權限 會回傳 true false
```







License
-------

Composer is licensed under the MIT License - see the LICENSE file for details