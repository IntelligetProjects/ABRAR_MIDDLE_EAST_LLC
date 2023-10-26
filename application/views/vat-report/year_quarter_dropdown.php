<?php
$qs = array(
    array("id" => "", "text" => "- " . lang("quarter") . " -"),
    array("id" => 1, "text" => lang("First quarter")),
    array("id" => 2, "text" => lang("Second quarter")),
    array("id" => 3, "text" => lang("Third quarter")),
    array("id" => 4, "text" => lang("Fourth quarter"))
);
echo json_encode($qs);
?>