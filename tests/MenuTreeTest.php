<?php

class FakeData
{

    //未組成數壯結構的原始資料
    public static function getOriginData()
    {
        return array(
            array(
                array(
                    "id" => 1,
                    "parent_id" => 'null',
                ),
                array(
                    "id" => 2,
                    "parent_id" => 1,
                ),
                array(
                    "id" => 3,
                    "parent_id" => 2,
                ),
                array(
                    "id" => 4,
                    "parent_id" => 3,
                ),
            ),
            array(
                array(
                    "id" => 1,
                    "parent_id" => 'null',
                ),
                array(
                    "id" => 2,
                    "parent_id" => 'null',
                ),
                array(
                    "id" => 3,
                    "parent_id" => 2,
                ),
                array(
                    "id" => 4,
                    "parent_id" => 3,
                ),
            ),
            array(
                array(
                    "id" => 1,
                    "parent_id" => 'null',
                ),
                array(
                    "id" => 2,
                    "parent_id" => 'null',
                ),
                array(
                    "id" => 3,
                    "parent_id" => 'null',
                ),
                array(
                    "id" => 4,
                    "parent_id" => 3,
                ),
            ),
            array(
                array(
                    "id" => 1,
                    "parent_id" => 'null',
                ),
                array(
                    "id" => 2,
                    "parent_id" => 1,
                ),
                array(
                    "id" => 3,
                    "parent_id" => 1,
                ),
                array(
                    "id" => 4,
                    "parent_id" => 3,
                ),
            ),
        );
    }

    //組完後預期的資料格式
    public static function getSuccessData()
    {
        return array(
            array(
                array(
                    "deep" => 0,
                    "self" => array(
                        "id" => 1,
                        "parent_id" => 'null',
                    ),
                    "children" => array(
                        array(
                            "deep" => 1,
                            "self" => array(
                                "id" => 2,
                                "parent_id" => 1,
                            ),
                            "children" => array(
                                array(
                                    "deep" => 2,
                                    "self" => array(
                                        "id" => 3,
                                        "parent_id" => 2,
                                    ),
                                    "children" =>array(
                                        array(
                                            "deep" => 3,
                                            "self" => array(
                                                "id" => 4,
                                                "parent_id" => 3,
                                            ),
                                            "children" => array()
                                        ),
                                    )
                                ),
                            )
                        ),
                    )
                ),

            ),
            array(
                array(
                    "deep" => 0,
                    "self" => array(
                        "id" => 1,
                        "parent_id" => 'null',
                    ),
                    "children" => array()

                ),
                array(
                    "deep" => 0,
                    "self" => array(
                        "id" => 2,
                        "parent_id" => 'null',
                    ),
                    "children" => array(
                        array(
                            "deep" => 1,
                            "self" => array(
                                "id" => 3,
                                "parent_id" => 2,
                            ),
                            "children" => array(
                                array(
                                    "deep" => 2,
                                    "self" => array(
                                        "id" => 4,
                                        "parent_id" => 3,
                                    ),
                                    "children" => array()

                                ),
                            )

                        ),
                    )
                ),
            ),
            array(
                array(
                    "deep" => 0,
                    "self" => array(
                        "id" => 1,
                        "parent_id" => 'null',
                    ),
                    "children" => array()
                ),
                array(
                    "deep" => 0,
                    "self" => array(
                        "id" => 2,
                        "parent_id" => 'null',
                    ),
                    "children" => array()
                ),

                array(
                    "deep" => 0,
                    "self" => array(
                        "id" => 3,
                        "parent_id" => 'null',
                    ),
                    "children" => array(
                        array(
                            "deep" => 1,
                            "self" => array(
                                "id" => 4,
                                "parent_id" => 3,
                            ),
                            "children" => array()
                        ),
                    )
                ),




            ),
            array(
                array(
                    "deep" => 0,
                    "self" => array(
                        "id" => 1,
                        "parent_id" => 'null',
                    ),
                    "children" => array(
                        array(
                            "deep" => 1,
                            "self" => array(
                                "id" => 2,
                                "parent_id" => 1,
                            ),
                            "children" => array()
                        ),
                        array(
                            "deep" => 1,
                            "self" => array(
                                "id" => 3,
                                "parent_id" => 1,
                            ),
                            "children" => array(
                                array(
                                    "deep" => 2,
                                    "self" => array(
                                        "id" => 4,
                                        "parent_id" => 3,
                                    ),
                                    "children" => array()
                                ),
                            )
                        ),
                    )
                ),

            ),
        );
    }


}

/**
 * Created by PhpStorm.
 * User: scott
 * Date: 2015/1/7
 * Time: 下午 4:48
 */

class MenuTreeTest extends PHPUnit_Framework_TestCase
{
    public function testGet()
    {
        foreach(FakeData::getOriginData() as $key => $data) {
            $stub = $this->getMockBuilder("Rde\\MenuData")->disableOriginalConstructor()->getMock();
            //$stub->method("find")->will(array())->willReturn();
            $stub->method("getParentColumn")->willReturn('parent_id');
            $stub->method("getSelfColumn")->willReturn('id');
            $stub->method("get")->willReturn($data);

            $menuTree = new \Rde\MenuTree($stub);
            $this->assertEquals($menuTree->get(), FakeData::getSuccessData()[$key]);
        }




    }
}
