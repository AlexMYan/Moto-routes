<?php
namespace Extra;

class Helper
{
    /**
     * Метод убирает повторяющиеся ключи
     *
     * При сохранении (удалении, изменении) объекта на карте создается новая запись
     * что бы выбрать только одну применяется данный метод
     *
     * @param $array
     * @param $key
     * @return array
     */
    public function arrayUniqueKey($array, $key) {
        $tmp = $key_array = array();
        $i = 0;

        foreach($array as $val) {
            //тут проверяем ключ, и если его нет в массиве добавляем
            if (!in_array($val[$key], $key_array)) {
                $key_array[$i] = $val[$key];
                $tmp[$i] = $val;
            }
            $i++;
        }
        return $tmp;
    }

    /**
     * Метод убирает повторяющиеся ключи по двум полям одно из которых дата
     *
     * @param $array
     * @param $key
     * @return array
     */
    public function arrayUniqueKeyAndDate($array, $key, $keyDate) {
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
                        $t1=new DateTime($item[$keyDate]);
                        $t2=new DateTime($val[$keyDate]);
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

}
