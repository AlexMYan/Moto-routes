<?php

if(isset($_SERVER['HTTP_X_REQUESTED_WITH']) && !empty($_SERVER['HTTP_X_REQUESTED_WITH']) &&strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {

    header('Content-Encoding: UTF-8');
    header('Content-Type: text/html; charset=UTF-8');

    //подключаем класс для работы с csv
    require(__DIR__.'/classes/csv.php');
    require(__DIR__.'/classes/Extra/Yandex.php');

    $obYandex = new \Extra\Yandex();

    //файл в которм храняться координаты маршрутов
    $pathFileRoadCoordinat=__DIR__."/".$obYandex::getfileMain();
    //копия делается каждый раз перед записью в 2
    $pathFileRoadCoordinatCopy=__DIR__.'/'.$obYandex::getfileCopy();

    $checkWords=$obYandex::getControlWord();

    if($_POST["controlW"]==$checkWords){
        //изменение маршрута
        if(isset($_POST["ID"]) && $_POST["ID"]>0 && $_POST["STARS"]>0  && !empty($_POST["NAME"]) && $_POST["action"]=="change"){
            try {
                $csv2 = new CSV($pathFileRoadCoordinat); //Открываем наш csv

                $get_csv2=$csv2->getCSV();

                $arrSet=[];
                foreach ($get_csv2 as &$item){
                    if($item[0]==$_POST["ID"]){
                        $item[6]=$_POST["STARS"];
                        $item[1]=$_POST["NAME"];
                        $item[7]=date("Y");
                        $item[8]=date("Y-m-d H:i:s");

                        $arrSet=$item;
                    }
                }
                $file = new SplFileObject($pathFileRoadCoordinat, 'a');
                $file->fputcsv($arrSet,";");

                $res["succes"]="Данные изменены";

                echo  json_encode($res);

            } catch (Exception $e) { //Если csv файл не существует, выводим сообщение
                echo "Ошибка: " . $e->getMessage();
            }
        }

        //добавления нового маршрута
        if(!empty($_POST["NAME"]) && !empty($_POST["START_LONG"])  && !empty($_POST["START_LAT"]) && !empty($_POST["END_LAT"]) && !empty($_POST["END_LONG"]) && !empty($_POST["STARS"]) && $_POST["action"]=="add"){

            try {
                $csv2 = new CSV($pathFileRoadCoordinat); //Открываем наш csv

                $get_csv2=$csv2->getCSV();

                $arrSet=[];
                $arrSet=[
                    0=>count($get_csv2)+1,
                    1=>$_POST["NAME"],
                    2=>$_POST["START_LAT"],
                    3=>$_POST["START_LONG"],
                    4=>$_POST["END_LAT"],
                    5=>$_POST["END_LONG"],
                    6=>$_POST["STARS"],
                    7=>date("Y"),
                    8=>date("Y-m-d H:i:s"),
                ];

                $file = new SplFileObject($pathFileRoadCoordinat, 'a');
                $file->fputcsv($arrSet,";");

                $res["succes"]="Данные добавлены";

                echo  json_encode($res);


            } catch (Exception $e) { //Если csv файл не существует, выводим сообщение
                $res["error"]="Ошибка: " . $e->getMessage();

                echo  json_encode($res);
            }
        }
        //удаление нового маршрута
       if($_POST["ID"]>0 && $_POST["action"]=="delete"){

            try {
                $csv2 = new CSV($pathFileRoadCoordinat); //Открываем наш csv
                $get_csv2=$csv2->getCSV();

                foreach ($get_csv2 as &$item){
                    if($item[0]==$_POST["ID"]){
                        $item[7]=date("Y");
                        $item[8]=date("Y-m-d H:i:s");
                        $item[9]="Y";
                    }
                }
                $csv3 = new CSV($pathFileRoadCoordinatCopy); //Открываем наш csv
                $get_csv3=$csv3->getCSV();
                $csv3->setCSVMod($get_csv2); // на всякий
                file_put_contents($pathFileRoadCoordinat, "");
                $csv2->setCSVMod($get_csv2);

                $res["succes"]="Маршрут ".$_POST['ID'] ." удален";

                echo  json_encode($res);

            } catch (Exception $e) { //Если csv файл не существует, выводим сообщение
                $res["error"]="Ошибка: " . $e->getMessage();

                echo  json_encode($res);
            }
        }

       if(!$res["succes"]){
           $res["error"]="Ошибка: Не хватает каких то данных";
           echo  json_encode($res);
       }

    }else{
        $res["error"]="Ошибка: контрольное слово";
        echo  json_encode($res);
    }

    exit;
}
//Если это не ajax запрос
$res["error"]='Это не ajax запрос!';

echo  json_encode($res);




