<?php


class PhpTester
{
    function test_regex($config, $test_strings) {
        $result = array(
            "result" => array(
                "resultList" => array()
            )
        );

        try {
            if(!isset($config["test_type"])) {
                throw new Exception("test_type parameter doesn't exists.");
            }

            $test_type = $config["test_type"];
            $regex = $config["regex"];

            if(isset($config["PCRE_CASELESS"]) && $config["PCRE_CASELESS"] == true) {
                $regex += "i";
            }
            if(isset($config["PCRE_MULTILINE"]) && $config["PCRE_MULTILINE"] == true) {
                $regex += "m";
            }
            if(isset($config["PCRE_DOTALL"]) && $config["PCRE_DOTALL"] == true) {
                $regex += "s";
            }
            if(isset($config["PCRE_EXTENDED"]) && $config["PCRE_EXTENDED"] == true) {
                $regex += "x";
            }
            if(isset($config["PCRE_ANCHORED"]) && $config["PCRE_ANCHORED"] == true) {
                $regex += "A";
            }
            if(isset($config["PCRE_DOLLAR_ENDONLY"]) && $config["PCRE_DOLLAR_ENDONLY"] == true) {
                $regex += "D";
            }
            if(isset($config["S"]) && $config["S"] == true) {
                $regex += "c";
            }
            if(isset($config["PCRE_UNGREEDY"]) && $config["PCRE_UNGREEDY"] == true) {
                $regex += "U";
            }
            if(isset($config["PCRE_EXTRA"]) && $config["PCRE_EXTRA"] == true) {
                $regex += "X";
            }
            if(isset($config["PCRE_INFO_JCHANGED"]) && $config["PCRE_INFO_JCHANGED"] == true) {
                $regex += "J";
            }
            if(isset($config["PCRE_UTF8"]) && $config["PCRE_UTF8"] == true) {
                $regex += "u";
            }

            if($test_type == "matchall") {
                $result["type"] = "GROUP";
                $result["columns"] = array();
                $first = true;
                foreach($test_strings as $test_string) {
                    preg_match_all($regex, $test_string, $matches, PREG_SET_ORDER);
                    $groups_list=array("list"=>array());
                    foreach($matches as $groups) {
                        array_push($groups_list["list"], $groups);
                        if($first) {
                            for($i = 0; $i < sizeof($groups); $i++) {
                                array_push($result["columns"], "Group #$i");
                            }
                            $first = false;
                        }
                    }
                    if(sizeof($matches) > 0) {
                        array_push($result["result"]["resultList"], $groups_list);
                    } else {
                        array_push($result["result"]["resultList"], null);
                    }
                }
            } else if($test_type == "grep") {
                $result["type"] = "STRING";
                $flags = 0;
                if(isset($config["PREG_GREP_INVERT"]) && $config["PREG_GREP_INVERT"] == true) {
                    $flags = PREG_GREP_INVERT;
                }
                $array = preg_grep($regex, $test_strings, $flags);
                foreach($array as $match) {
                    array_push($result["result"]["resultList"], $match);
                }
            } else if($test_type == "replace") {
                $result["type"] = "STRING";
                $replacement = $config["replace"];
                foreach($test_strings as $test_string) {
                    array_push($result["result"]["resultList"], preg_replace($regex, $replacement, $test_string));
                }
            } else if($test_type == "split") {
                $result["type"] = "GROUP";
                $result["columns"] = array("Split");
                foreach($test_strings as $test_string) {
                    $rows = array("list"=> array());
                    $split = preg_split($regex, $test_string);
                    foreach($split as $string) {
                        array_push($rows["list"], array($string));
                    }
                    array_push($result["result"]["resultList"], $rows);
                }
            }
        } catch (Exception $e) {
            $result["result"] = null;
            $result["exception"] = $e->getMessage();
        } finally {
            return $result;
        }
    }
}

if(preg_match("/PhpTester\\.php$/", $_SERVER["argv"][0])) {
    $tester = new PhpTester;
    $config = json_decode($_SERVER["argv"][1]);
    $test_strings = json_decode($_SERVER["argv"][2]);
    $result = $tester->test_regex($config, $test_strings);
    echo "##START_RESULT##\n";
    echo json_encode($result)."\n";
    echo "##END_RESULT##\n";
}
?>