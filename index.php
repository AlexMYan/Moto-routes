<html>
<head>
    <title>Карта моих поездок с оценкой покрытия</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" />

    <link rel="stylesheet" href="/css/styles.css">
    <link rel="stylesheet" href="/css/css.css">


    <link href="/css/lightbox.css" rel="stylesheet" type="text/css" />

    <script src="https://api-maps.yandex.ru/2.1/?lang=ru_RU&amp;apikey=3fbf19f4-1be9-4716-a272-fde43aca6efd"
            type="text/javascript"></script>
    <script src="https://yandex.st/jquery/2.2.3/jquery.min.js" type="text/javascript"></script>

    <script type="text/javascript" src="/js/lightbox.js"></script>


    <script src="/script.js" type="text/javascript" ></script>
    <link rel="stylesheet" href="/css/styles.css">
    <link rel="stylesheet" href="/css/css.css">

    <?
    header('Content-Encoding: UTF-8');
    header('Content-Type: text/html; charset=UTF-8');

    require(__DIR__.'/classes/csv.php');
    require(__DIR__.'/classes/functions.php');
    require(__DIR__.'/classes/Extra/Helper.php');




    $year=date("Y");
    if(isset($_REQUEST["years"]) && $_REQUEST["years"]>0 ){
        $year=$_REQUEST["years"];
    }

    try {
        $csv = new CSV("files/2.csv"); //Открываем наш csv

        /**
         * Чтение из CSV  (и вывод на экран в красивом виде)
         */

        $get_csv = $csv->getCSV();

        unset($get_csv[0]);

        $arPoints = [];
        foreach ($get_csv as $values) { //Проходим по строкам
            if(!empty($values) && !empty($values[1])){
                if($year==$values[7] && $values[9] !="Y"){
                    $arPoints[] = [
                        "ID"=> $values[0],
                        "NAME" => $values[1],
                        "POINTS_START" => [$values[2], $values[3]],
                        "POINTS_END" => [$values[4], $values[5]],
                        "STARS"=>$values[6]>10?10:$values[6],
                        "YEAR"=>$values[7],
                        "DATE_CHANGE"=>$values[8]?$values[8]:$values[7],
                    ];
                }
            }
        }


        $pictureCsv = new CSV("files/picturesmy.csv"); //Открываем наш csv

        /**
         * Чтение из CSV  (и вывод на экран в красивом виде)
         */

        $get_picture_csv = $pictureCsv->getCSV();

        unset($get_picture_csv[0]);




    } catch (Exception $e) { //Если csv файл не существует, выводим сообщение
        echo "Ошибка: " . $e->getMessage();
    }

    //сортируем массив по убывания по ключу ID
    uasort($arPoints, 'cmp_function_desc');
    $arPoints= array_unique_key($arPoints,"ID");

    ?>

    <!-- Yandex.Metrika counter -->
    <script type="text/javascript" >
        (function(m,e,t,r,i,k,a){m[i]=m[i]||function(){(m[i].a=m[i].a||[]).push(arguments)};
            m[i].l=1*new Date();k=e.createElement(t),a=e.getElementsByTagName(t)[0],k.async=1,k.src=r,a.parentNode.insertBefore(k,a)})
        (window, document, "script", "https://mc.yandex.ru/metrika/tag.js", "ym");

        ym(89523182, "init", {
            clickmap:true,
            trackLinks:true,
            accurateTrackBounce:true,
            webvisor:true
        });
    </script>
    <noscript><div><img src="https://mc.yandex.ru/watch/89523182" style="position:absolute; left:-9999px;" alt="" /></div></noscript>
    <!-- /Yandex.Metrika counter -->

</head>
<body>

<div class="wrap" id="my_conteiner" >

    <input type="checkbox" id="hmt" class="hidden-menu-ticker">
    <label class="btn-menu menuEvent" for="hmt" id="menuEvent">
        <span class="first"></span>
        <span class="second"></span>
        <span class="third"></span>
    </label>
    <ul class="hidden-menu">yanovicham@gmail.com
      <!--  <li><div id="showPlacemarker"  class='btn placemarker'>Показать метки</div>
-->
        <li>
            <div id="openAddForm" for="myformAdd" class='btn open'>Новый маршрут</div>
        </li>
        <li>
            <div id="myformAdd" class="formM hidden blockli">
                <label class='btn-menu-close' for='myformAdd'  onclick='window.JCMyNewPage.prototype.closeBlock(this)'>
                    <span class='first'></span>
                    <span class='third'></span>
                </label>
                <div>Название</div>
                <input type='text' name='NAME' value=''>
                <div>Начальная точка</div>
                <div class="flex-container">
                    <div>  <input type='text' name='START_LAT' value=''></div>
                    <div>  <input type='text' name='START_LONG' value=''></div>
                </div>
                <div>Конечная точка</div>
                <div class="flex-container">
                    <div>  <input type='text' name='END_LAT' value=''></div>
                    <div>  <input type='text' name='END_LONG' value=''></div>
                </div>
                <div>Рейтинг</div>
                <input type='number' name='STARS' value=''>
                <div  class='btn' onclick='window.JCMyNewPage.prototype.addRoute();'>Добавить новый маршрут</div>
            </div>
        </li>
        <li><div id="myform" class="formM hidden blockli"></div></li>

        <li>
            <div id="blockCoordinats" class="formM hidden blockli">
            </div>
        </li>
        <li><div id="openAbout" for="about" class='btn'>О проекте</div></li>
        <li>
            <div id="about" class="hidden blockli">
                <label class='btn-menu-close' for='about'  onclick='window.JCMyNewPage.prototype.closeBlock(this)'>
                    <span class='first'></span>
                    <span class='third'></span>
                </label>
                <div>
                    <p>Данный проект был создан после поездки в г.Борисов по трассе M1 (Минск - Борисов) в 2022 году. Это попытка в масштабе одного человека, создать ресурс, который позволит избегать плохих дорог. Преимущественно это касается конечно  мотоциклов, но формально информацию можно учесть и для авто</p>
                    <p>
                        Градация следующая, чем зеленее маршрут, тем легче по нему ехать, тем меньше слежка за дорогой (ямы и прочее) занимает времени.
                    </p>
                    <p>
                        Это вовсе не значит, что дороги отмечены красным, прям совсем плохи и проехать по ним нельзя. Это лишь значит, что нужно быть предельно внимательным к дорожному покрытию, а лучше совсем избегать данный маршрут.
                    </p>
                </div>
            </div>
        </li>
        <li><div id="openHistory" for='history' class='btn'>История</div></li>
        <li>
            <div id="history" class="hidden blockli">
                <label class='btn-menu-close' for='history'  onclick='window.JCMyNewPage.prototype.closeBlock(this)'>
                    <span class='first'></span>
                    <span class='third'></span>
                </label>
                <div>

                    <p>2022.07.11
                    <ul>
                        <li> - Внедрена галерея lightbox. При клике на метку можно в увеличенном варианте посмотреть фотки </li>
                        <li> - Добавлен показ кол-ва фотографий на метке </li>
                    </ul>
                    </p>
                    <p>2022.06.30
                    <ul>
                        <li> - Добавлена возможность просматривать координаты по клику на карте, с возможностью копировать данные в буфер. Нужно для создания маршрута.</li>
                    </ul>
                    </p>
                    <p>2022.06.22
                    <ul>
                        <li> - Добавлена возможность удалять маршрут (пока только администратору)</li>
                    </ul>
                    </p>
                    <p>2022.06.20
                    <ul>
                        <li> - Добавлена возможность добавлять новый маршрут (пока только администратору)</li>
                    </ul>
                    </p>
                    <p>2022.06.17
                    <ul>
                        <li> - Добавлена возможность изменять название маршрута (пока только администратору)</li>
                    </ul>
                    </p>
                    <p>2022.06.15
                        <ul>
                            <li> - Добавлена возможность изменять рейтинг маршрута (пока только администратору)</li>
                        </ul>
                    </p>
                    <p>2022.06.14
                    <ul>
                        <li>- Создание проекта</li>
                    </ul>
                    </p>

                </div>
            </div>
        </li>
        <li>Версия 1.0.1</li>

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

        var maps =   new JCMyNewPage({
            container: 'my_conteiner',
            map:myMap,
            routes:<?=json_encode($arPoints)?>,
            pictures:<?=json_encode($get_picture_csv)?>,
            siteUrl:'https://moto-maps.tmweb.ru/',
        });
    }
    ymaps.ready(init);

</script>

</body>
</html>
