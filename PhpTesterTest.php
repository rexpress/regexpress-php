<?php
require("PhpTester.php");

$tester = new PhpTester;
$test_strings = array("Hello Php Test", "Hello\nRegex!\nTest", "010-1234-5678");

$config = array(
    "test_type"=>"matchall",
    "regex"=>"/([A-Za-z]+)/"
);
$result = $tester->test_regex(json_decode(json_encode($config)), $test_strings);
echo(json_encode($result));
echo "\n\n";

$config = array(
    "test_type"=>"grep",
    "regex"=>"/!/"
);
$result = $tester->test_regex(json_decode(json_encode($config)), $test_strings);
echo(json_encode($result));
echo "\n\n";

$config = array(
    "test_type"=>"replace",
    "regex"=>"/([A-Z])/",
    "replace"=>"$1_"
);
$result = $tester->test_regex(json_decode(json_encode($config)), $test_strings);
echo(json_encode($result));
echo "\n\n";

$config = array(
    "test_type"=>"split",
    "regex"=>"/-/"
);
$result = $tester->test_regex(json_decode(json_encode($config)), $test_strings);
echo(json_encode($result));
echo "\n\n";
?>