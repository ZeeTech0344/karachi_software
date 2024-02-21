<?php

function setScale($get_value){

    if($get_value  == "Inch"){
        return '"';
    }elseif($get_value == "ft"){
        return "'";
    }elseif($get_value == "m"){
        return "m";
    }elseif($get_value == "cm"){
        return "cm";
    }elseif($get_value == "No"){
        return "No";
    }

}


