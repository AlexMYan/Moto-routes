<?
require(__DIR__ . '/classes/Extra/Helper.php');
require(__DIR__ . '/classes/Extra/Yandex.php');

$obYandex = new \Extra\Yandex();
$obHelper = new \Extra\Helper();

$siteUrl =" https://".$_SERVER["HTTP_HOST"]."/";
?>

<html>
<head>
    <title>Карта моих поездок с оценкой покрытия</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no"/>

    <link  href="/css/styles.css" rel="stylesheet">

    <link href="/css/lightbox.css" rel="stylesheet" type="text/css"/>

    <script src="https://api-maps.yandex.ru/2.1/?lang=ru_RU&amp;apikey=<?= $obYandex::getYandexKey() ?>"
            type="text/javascript"></script>
    <script src="https://yandex.st/jquery/2.2.3/jquery.min.js" type="text/javascript"></script>
    <script src="/js/lightbox.js" type="text/javascript"></script>
    <script src="/script.js" type="text/javascript"></script>


    <?
    require(__DIR__ . '/classes/csv.php');
    require(__DIR__ . '/classes/functions.php');

    $year = date("Y");
    if (isset($_REQUEST["years"]) && $_REQUEST["years"] > 0) {
        $year = $_REQUEST["years"];
    }

    try {
        $csv = new CSV($obYandex::getfileMain()); //Открываем наш csv

        /**
         * Чтение из CSV  (и вывод на экран в красивом виде)
         */

        $get_csv = $csv->getCSV();

        unset($get_csv[0]);

        $arPoints = [];
        foreach ($get_csv as $values) { //Проходим по строкам
            if (!empty($values) && !empty($values[1])) {
                if ($year == $values[7] && $values[9] != "Y") {
                    $arPoints[] = [
                        "ID" => $values[0],
                        "NAME" => $values[1],
                        "POINTS_START" => [$values[2], $values[3]],
                        "POINTS_END" => [$values[4], $values[5]],
                        "STARS" => $values[6] > 10 ? 10 : $values[6],
                        "YEAR" => $values[7],
                        "DATE_CHANGE" => $values[8] ? $values[8] : $values[7],
                    ];
                }
            }
        }

        $pictureCsv = new CSV($obYandex::getfileImages()); //Открываем наш csv

        /**
         * Чтение из CSV  (и вывод на экран в красивом виде)
         */

        $get_picture_csv = $pictureCsv->getCSV();

        unset($get_picture_csv[0]);


    } catch (Exception $e) { //Если csv файл не существует, выводим сообщение
        echo "Ошибка: " . $e->getMessage();

        die();
    }

    //сортируем массив по убывания по ключу ID
    uasort($arPoints, 'cmp_function_desc');
    $arPoints = $obHelper::arrayUniqueKeyAndDate($arPoints, "ID", "DATE_CHANGE"); ?>

    <!-- Yandex.Metrika counter -->
    <? require(__DIR__ . '/include/yandex_metrika.php'); ?>
    <!-- /Yandex.Metrika counter -->

</head>
<body>
<div class="wrap" id="my_conteiner">

    <input type="checkbox" id="hmt" class="hidden-menu-ticker">
    <label class="btn-menu menuEvent" for="hmt" id="menuEvent">
        <span class="first"></span>
        <span class="second"></span>
        <span class="third"></span>
    </label>
    <ul id="hidden-menu" class="hidden-menu">
        <!--  <li><div id="showPlacemarker"  class='btn placemarker'>Показать метки</div>
  -->
        <li class="parentWrap">yanovicham@gmail.com</li>
        <li class="parentWrap">
            <div id="openAddForm" for="myformAdd" class='btn open'>Новый маршрут</div>
        </li>
        <li class="parentWrap">
            <div id="myformAdd" class="formM hidden blockli">
                <label class='btn-menu-close' for='myformAdd' onclick='window.JCMyNewPage.prototype.closeBlock(this)'>
                    <span class='first'></span>
                    <span class='third'></span>
                </label>
                <div>Название</div>
                <input type='text' name='NAME' value=''>
                <div>Начальная точка</div>
                <div class="flex-container">
                    <div><input type='text' name='START_LAT' value=''></div>
                    <div><input type='text' name='START_LONG' value=''></div>
                </div>
                <div>Конечная точка</div>
                <div class="flex-container">
                    <div><input type='text' name='END_LAT' value=''></div>
                    <div><input type='text' name='END_LONG' value=''></div>
                </div>
                <div>Рейтинг</div>
                <input type='number' name='STARS' value=''>
                <div class='btn' onclick='window.JCMyNewPage.prototype.addRoute();'>Добавить новый маршрут</div>
            </div>
        </li>
        <li class="parentWrap">
            <div id="myform" class="formM hidden blockli"></div>
        </li class="parentWrap">

        <li class="parentWrap">
            <div id="blockCoordinats" class="formM hidden blockli">
            </div>
        </li>
        <li class="parentWrap">
            <div id="openAbout" for="about" class='btn'>О проекте</div>
        </li>
        <li class="parentWrap">
            <div id="about" class="hidden blockli">
                <label class='btn-menu-close' for='about' onclick='window.JCMyNewPage.prototype.closeBlock(this)'>
                    <span class='first'></span>
                    <span class='third'></span>
                </label>
                <div>
                    <? require(__DIR__ . '/include/about.php'); ?>
                </div>
            </div>
        </li>
        <li class="parentWrap">
            <div id="openHistory" for='history' class='btn'>История</div>
        </li>
        <li class="parentWrap">
            <div id="history" class="hidden blockli">
                <label class='btn-menu-close' for='history' onclick='window.JCMyNewPage.prototype.closeBlock(this)'>
                    <span class='first'></span>
                    <span class='third'></span>
                </label>
                <? require(__DIR__ . '/include/dev_history.php'); ?>
            </div>
        </li>
        <li class="parentWrap">Версия 1.0.1</li>
    </ul>

    <header>
        <h1>Карта поездок</h1>
    </header>
    <div id="map"></div>
    <div id="colorTable" class="info"></div>
</div>


<script>
    function init() {
        //создаем карту
        var myMap = new ymaps.Map('map', {
            center: [53.906717, 27.545352],
            zoom: 8,

        });

        var maps = new JCMyNewPage({
            container: 'my_conteiner',
            map: myMap,
            routes: <?=json_encode($arPoints)?>,
            pictures: <?=json_encode($get_picture_csv)?>,
            siteUrl: '<?=$siteUrl?>',
        });
    }

    ymaps.ready(init);

</script>


</body>
</html>
