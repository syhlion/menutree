<?php
/**
 * Created by PhpStorm.
 * User: scott
 * Date: 2015/1/7
 * Time: 下午 4:48
 */

class TreeStructTest extends PHPUnit_Framework_TestCase
{

    public function testGet()
    {
        $i = 0;
        foreach($this->originTreeData() as $d) {

            $tree = \Rde\TreeStruct::get($d, "id", "parent_id");
            $this->assertEquals($tree,$this->successTreeData()[$i]);
            $i++;

        }


    }

    private function successTreeData()
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

    //原始資料提供
    private function originTreeData()
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
}
