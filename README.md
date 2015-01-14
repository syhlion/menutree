MenuTree 
========

[![Build Status](https://travis-ci.org/syhlion/menutree.svg?branch=master)](https://travis-ci.org/syhlion/menutree)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/syhlion/menutree/badges/quality-score.png)](https://scrutinizer-ci.com/g/syhlion/menutree/)


Installation：
------

``` json
{
    "require": {
        "rde/menutree": "0.2.*@dev"
    }
}
```

* PHP環境需要pdo_sqlite pdo_mysql (預設載體)
* 把db_schema資料夾裡的sql 部屬在database。
* 預設資料載體sqlite (/src/storage/menudata.slite3) 裡面的資料格式 （id, item_id, parent_id, depth, left, right, url_name, url），其中item_id為不可重複的唯一key，可用字串，如不小心寫入重複key，在MenuData取資料時也會慮掉重複字串。id為一般流水號，其他欄位可填可不填。
* 唯一要注意，如果此比資料為樹狀資料最上層，其parnet_id 要填入字串'null'
* 也可各自實現IMenuProvider & IUserProvider 來使用自己儲存體


Usage：
---------------

``` php
/*
假設原始資料為:
array(
    array(
    "id" => 1,
    "item_id" => key_1,
    "parent_id" => 'null'
    "depth" => 0,
    "left" => 1,
    "right" => 2,
    "url_name" => test1,
    "url" => contorller1/method1
    ),
    array(
    "id" => 2,
    "item_id" => key_2,
    "parent_id" => key_1,
    "depth" => 1,
    "left" => 3,
    "right" => 4,
    "url_name" => test2,
    "url" => contorller2/method2
    ),
);
*/


//可自行實作使用者選單，只要最後給MenuData的過濾資料格式如下面範例即可
$pdo_mysql = new PDO('mysql:host=127.0.0.1;dbname=account','root','xxxx');
$user = new User($pdo_mysql);

//find_menu() 會用array回傳 每個item_id  ex array(key_1,key_2...)
//建構參數pdo 可選填，沒填預設會讀取src/storage/menudata.sqlite3 裡的資料
$menuOrigin = new MenuData();


$insertData = array(
    array(
        "id" => 1,
        "item_id" => key_3,
        "parent_id" => 'key_1'
        "depth" => 0,
        "left" => 1,
        "right" => 2,
        "url_name" => test3,
        "url" => contorller3/method3
        ),
);
/*
結果為
array(
    array(
    "id" => 1,
    "item_id" => key_1,
    "parent_id" => 'null'
    "depth" => 0,
    "left" => 1,
    "right" => 2,
    "url_name" => test1,
    "url" => contorller1/method1
    ),
    array(
    "id" => 1,
    "item_id" => key_3,
    "parent_id" => 'key_1'
    "depth" => 0,
    "left" => 1,
    "right" => 2,
    "url_name" => test3,
    "url" => contorller3/method3
    ),
    array(
    "id" => 2,
    "item_id" => key_2,
    "parent_id" => key_1,
    "depth" => 1,
    "left" => 3,
    "right" => 4,
    "url_name" => test2,
    "url" => contorller2/method2
    ),
);
*/
$menuOrigin->setFilter($user->find_menu())->insert(1, $insertData)->get(); 


//建構選單樹狀物件
$menutree = new MenuTree($menuOrigin); 

//可拿到完整的樹狀結構
$tree = $menutree->get(); 
/*
最後get回來的資料格式如以下:
array(
    array(
        "deep" => 0,
        "self" => array(
            "id" => 1,
            "item_id" => key_1,
            "parent_id" => 'null'
            "depth" => 0,
            "left" => 1,
            "right" => 2,
            "url_name" => test1,
            "url" => contorller1/method1
        ),
        "children" => array(
            array(
                "deep" => 2,
                "self" => array(
                    "id" => 2,
                    "item_id" => key_2,
                    "parent_id" => key_1,
                    "depth" => 1,
                    "left" => 3,
                    "right" => 4,
                    "url_name" => test2,
                    "url" => contorller2/method2
                ),
                "children" => array(),
            )
        )
    
    )
)
*/
$bool = $menutree->check("controller/method"); //可判斷此uri是否符合權限 會回傳 true false

```





License
-------

Composer is licensed under the MIT License - see the LICENSE file for details