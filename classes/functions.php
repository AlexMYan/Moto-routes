<?php
function array_unique_key($array, $key) {
    $tmp = $key_array = array();
    $i = 0;

    foreach($array as $val) {
        //тут проверяем ключ, и если его нет в массиве добавляем
        if (!in_array($val[$key], $key_array)) {
            $key_array[$i] = $val[$key];
            $tmp[$i] = $val;
        }else{
            //тут проверяем если такой id есть в массиве, то сравниваем дату, какая дата новее тот элемент идет в массив
            foreach($tmp as $k => $item){
                if($item["ID"]==$val[$key]){
                    $t1=new DateTime($item["DATE_CHANGE"]);
                    $t2=new DateTime($val["DATE_CHANGE"]);
                    if($t2>$t1){
                        $tmp[$k] = $val;
                    }
                }
            }
        }
        $i++;
    }
    return $tmp;
}
// По убыванию:
function cmp_function_desc($a, $b){
    return ($a['ID'] < $b['ID']);
}

