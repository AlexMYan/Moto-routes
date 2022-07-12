(function () {
    'use strict';
    if (!!window.JCMyNewPage)
        return;

    window.JCMyNewPage = function (params) {

        this.container = document.getElementById(params.container);

        this.map = params.map || {}; //объект созданной карты
        this.routes = params.routes || {}; //object routes (маршруты)
        this.pictures = params.pictures || {}; //object img (картинки)
        this.siteUrl = params.siteUrl || ""; //объект созданной карты

        // console.log(params);



        this.init();
    };

    window.JCMyNewPage.prototype = {
        init: function () {


            if (!!this.container) {
                //color Table only show
                this.colorTableInfo = this.container.querySelector('.info');
                if (!!this.colorTableInfo) {
                    this.createAndShowColorTable(this.colorTableInfo);
                }
                //route
                if (Object.keys(this.routes).length > 0) {
                    this.addRoutesToMap(this.routes, this.map);
                }

                //pictures
                if (Object.keys(this.pictures).length > 0) {
                    this.setPicturesToMap(this.pictures, this.map);
                }



                this.container.onclick = (e) => {
                    const target = e.target; // элемент который вызвал событие
                    const obClass = target.getAttribute('class');

                    //  console.log(obClass);
                    //    console.log(target);
                }

                //по клику закрываем все блоки
                this.menuEvent = this.container.querySelector('.menuEvent');
                if (!!this.menuEvent) {
                    this.menuEvent.addEventListener('click', event => {
                        let chbxMenu = this.container.querySelector('input[type=checkbox]'); //checkbox control menu
                        // если было закрыто открыть
                        if (chbxMenu.checked) {
                            let blockli = this.container.querySelectorAll('.blockli');
                            if (blockli.length > 0) {
                                for (let i = 0; i < blockli.length; i++) {
                                     blockli[i].classList.add("hidden");
                                }
                            }
                        }

                    });
                }

                //btn About
                this.openAbout = this.container.querySelector('#openAbout');
                if (!!this.openAbout) {
                    this.openAbout.addEventListener('click', event => {
                        this.openBlock(this.openAbout);

                    });
                }

                //btn history
                this.openHistory = this.container.querySelector('#openHistory');
                if (!!this.openHistory) {
                    this.openHistory.addEventListener('click', event => {
                        this.openBlock(this.openHistory);

                    });
                }

                //btn add route block
                this.openAddForm = this.container.querySelector('#openAddForm');
                if (!!this.openAddForm) {
                    this.openAddForm.addEventListener('click', event => {
                        this.openBlock(this.openAddForm);

                    });
                }

                // Слушаем клик на карте.
                this.map.events.add('click', function (e) {
                    window.JCMyNewPage.prototype.eventClickOnMap(e);
                });
            }
        },

        /**
         * Добавляем на карту метки
         *
         * @param obPictures
         * @param myMap
         */
        setPicturesToMap:function(obPictures,myMap){

            for (var key in obPictures) {
                //pictures
                var arrPictures = obPictures[key][7].split(",");
                if(arrPictures.length>0){
                    let imgs="<div class='img_wrap single-item_"+obPictures[key][0]+"'>";
                    for(let i=0; i<arrPictures.length; i++){
                        imgs+="<a href='"+this.siteUrl+"img/"+arrPictures[i]+"' rel='lightbox-one'>" +
                            "<img  src='"+this.siteUrl+"img/"+arrPictures[i]+"'/></a>";
                    }
                    imgs+="</div>";
                    this.setPictureOnMap(myMap, imgs, [obPictures[key][1],obPictures[key][2]], obPictures[key][3], obPictures[key][0],arrPictures.length);
                }
            }
        },
        /**
         * copy input value
         *
         * @param e
         */
        selectInput: function (id) {
            let input = document.getElementById(id);
            if (input === null) {
            } else {
                input.select();
                input.setSelectionRange(0, 99999);
                navigator.clipboard.writeText(input.value);
            }
        },
        eventClickOnMap: function (e) {
            let coords = e.get('coords'),
                blockCoordinats = document.getElementById('blockCoordinats');

            if (!!blockCoordinats && !!coords) {
                blockCoordinats.innerHTML = "";
                blockCoordinats.classList.remove("hidden");
                let divInfo = document.createElement('div');
                divInfo.innerHTML = " <label class='btn-menu-close' for='blockCoordinats'  onclick='window.JCMyNewPage.prototype.closeBlock(this)'><span class='first'></span><span class='third'></span></label>" +
                    "<div>Координаты по клику:</div><div class='flex-container2'>" +
                    "<div class='item'><input type='text' id='CLICK_LAT' value='" + coords[0].toFixed(6) + "'></div>" +
                    "<div class='item btnCopy'  for='CLICK_LAT' onclick='window.JCMyNewPage.prototype.selectInput(\"CLICK_LAT\")'>Скопировать</div>" +
                    "</div>" +
                    "<div class='flex-container2'>" +
                    "<div class='item'><input type='text' id='CLICK_LONG' value='" + coords[1].toFixed(6) + "'></div>" +
                    "<div class='item'><div class='btnCopy' for='CLICK_LONG'  onclick='window.JCMyNewPage.prototype.selectInput(\"CLICK_LONG\")'>Скопировать</div></div>" +
                    "</div>";

                blockCoordinats.append(divInfo);
            }
            this.showLeftBlock();
        },
        addRoutesToMap: function (objRoutes, myMap) {

            for (var key in objRoutes) {
                let objectName = this.createRoute(
                    myMap,
                    'multiRoute_' + key,
                    objRoutes[key]
                );

                //добавляем на карту
                myMap.geoObjects.add(objectName);
            }
        },

        /**
         * Показать выбранный маршрут
         *
         * @param e
         * @param objRoutes
         */
        eventClickOnRoute: function (e, objRoutes) {

            var target = e.get('target'), //object to click
                activeRoute = target.getActiveRoute(), //choice active route
                container = document.getElementById("my_conteiner"),//wrap
                form = document.getElementById('myform');//wrap

            if (!!container && !!form) {
                form.innerHTML = "";
                form.classList.remove("hidden");
                let divInfo = document.createElement('div');

                divInfo.innerHTML = " <label class='btn-menu-close' for='myform' onclick='window.JCMyNewPage.prototype.closeBlock(this)'><span class='first'></span><span class='third'></span></label><div>ID: " + objRoutes.ID + "</div>" +
                    "<div>Дистанция:" + activeRoute.properties.get("distance").text + "</div>" +
                    "<div>Название: " + objRoutes.NAME + "</div>" +
                    "<div>Дата (обновления): " + objRoutes.DATE_CHANGE + "</div>" +
                    "<input type='hidden' name='ID' value='" + objRoutes.ID + "'>" +
                    "<span>Название:</span>" +
                    "<input type='text' name='NAME' value='" + objRoutes.NAME + "'>" +
                    "<span>Оценка дороги:</span>" +
                    "<input type='number' name='STARS' value='" + objRoutes.STARS + "'>" +
                    "<div  class='btn' for='myform' onclick='window.JCMyNewPage.prototype.changeRoute(this);'>Изменить</div>" +
                    "<div  class='btn delete' onclick='window.JCMyNewPage.prototype.deleteRoute("+objRoutes.ID+");'>Удалить</div>";

                form.append(divInfo);
            }
            this.showLeftBlock();
        },

        showLeftBlock: function () {
            let container = document.getElementById("my_conteiner"),//wrap
                chbxMenu = container.querySelector('input[type=checkbox]'); //checkbox control menu
            // если было закрыто открыть
            if (!chbxMenu.checked) {
                document.getElementById("menuEvent").click();
            }
        },

        /**
         * e - элемент по которому кликнули, в for лежит id блока который нужно скрыть
         *
         * @param e
         */
        closeBlock: function (e) {
            if (!!e) {
                let idBlock = e.getAttribute('for');
                if (idBlock === null) {
                } else {
                    let container = document.getElementById(idBlock);
                    if (!!container) {
                        container.classList.add("hidden");
                    }
                }
            }
        },
        /**
         * e - элемент по которому кликнули, в for лежит id блока который нужно скрыть
         *
         * @param e
         */
        openBlock: function (e) {
            if (!!e) {
                let idBlock = e.getAttribute('for');
                if (idBlock === null) {
                } else {
                    let container = document.getElementById(idBlock);
                    if (!!container) {
                        container.classList.remove("hidden");
                    }
                }
            }
        },

        /**
         * Создадим мультимаршрут.
         *
         * @param myMap
         * @param name
         * @param objRoutes
         * @returns {ymaps.multiRouter.MultiRoute}
         */
        createRoute: function (myMap, name, objRoutes) {
            //  console.log(objRoutes);
            name = new ymaps.multiRouter.MultiRoute({
                    referencePoints: [[objRoutes.POINTS_START[0], objRoutes.POINTS_START[1]], [objRoutes.POINTS_END[0], objRoutes.POINTS_END[1]]],
                    // Параметры маршрутизации.
                    params: {
                        // Ограничение на максимальное количество маршрутов, возвращаемое маршрутизатором.
                        results: 1
                    }
                },
                {
                    // Внешний вид путевых точек.
                    wayPointVisible: false,
                    // Внешний вид линии активного маршрута.
                    routeActiveStrokeWidth: 8,
                    routeActiveStrokeStyle: 'solid',
                    //цвет маршрута
                    routeActiveStrokeColor: this.colorTable(objRoutes.STARS - 1, false),
                    // Внешний вид линий альтернативных маршрутов.
                    //    routeStrokeStyle: 'dot',
                    routeStrokeWidth: 3,
                    //boundsAutoApply: true
                    balloonLayout: "",
                    // Отключаем режим панели для балуна.
                    balloonPanelMaxMapArea: 0
                }
            );
            //событие по клику на маршрут
            name.events.add('click', function (e) {
                window.JCMyNewPage.prototype.eventClickOnRoute(e, objRoutes);
            });

            return name;

        },

        /**
         * Color for show user
         *
         * @param position
         * @param boolLenght
         * @returns {string|number}
         */
        colorTable: function (position, boolLenght) {
            var arColor = [
                "#ef2f00",
                "#f65d01",
                "#f78a00",
                "#f7b702",
                "#f5d601",
                "#d9e500",
                "#9be400",
                "#62df02",
                "#34c117",
                "#0c9b47",
            ];

            if (boolLenght)
                return arColor.length;
            else
                return arColor[position];
        },

        /**
         * Only show
         *
         * @param tag
         */
        createAndShowColorTable: function (tag) {
            let arColorLength = this.colorTable(1, true)
            if (arColorLength > 0) {
                for (let i = 1; i < arColorLength + 1; i++) {
                    let div = document.createElement('div');
                    div.style.background = this.colorTable(i - 1, false);
                    div.textContent = i;
                    tag.append(div);
                }
            }
        },

        /**
         * Изменение маршрута
         *
         * @param e
         */
        changeRoute: function (e) {

            let id = e.getAttribute("for");
            if (id === null) {
                alert("Что то не так!");
            } else {
                let block = document.getElementById(id);
                if (!!block) {
                    let name = prompt("Введите контрольное слово", "");
                    let inputs = block.querySelectorAll('input'),
                        data = {};

                    data["controlW"] = name;
                    data["action"]="change";
                    if (!!inputs) {
                        for (var i = 0; i < inputs.length; i++) {
                            data[inputs[i].name] = inputs[i].value;
                        }
                    }

                    $.ajax({
                        type: 'POST',
                        url: 'ajax.php',
                        dataType: "json",
                        data: data,
                        success: function (result) {
                            if (!!result.error) {
                                alert(result.error);
                            }
                            if (!!result.succes) {
                                alert(result.succes);
                                location.reload(true);
                            }


                        },
                        error: function (request) {
                            console.log("ERROR", request);
                        }
                    });
                }
            }

        },

        /**
         * Удаление маршрута
         *
         * @param id
         */
        deleteRoute:function (id) {
            if(confirm("Удалить маршрут: " + id + " ?")){

                var data={};
                let name = prompt("Введите контрольное слово", "");
                data["controlW"]=name;
                data["ID"]=id;
                data["action"]="delete";

                $.ajax({
                    type: 'POST',
                    url:'ajax.php',
                    dataType : "json",
                    data: data,
                    success:function(result){
                        if(!!result.error){
                            alert(result.error);
                        }
                        if(!!result.succes){
                            alert(result.succes);
                            location.reload(true);
                        }
                    },
                    error: function(request) {
                        console.log("ERROR", request);
                    }
                });
            }
        },

        /**
         * добавление нового маршрута
         */
        addRoute:function(){

            let name = prompt("Введите контрольное слово", "");
            let form = document.getElementById('myformAdd');
            var inputs = form.querySelectorAll('input');
            var data={};
            data["action"]="add";

            data["controlW"]=name;

            if (!!inputs) {
                for (var i = 0; i < inputs.length; i++) {
                    data[inputs[i].name]=inputs[i].value;
                }
            }

            $.ajax({
                type: 'POST',
                url:'ajax.php',
                dataType : "json",
                data: data,
                success:function(result){
                    if(!!result.error){
                        alert(result.error);
                    }
                    if(!!result.succes){
                        alert(result.succes);
                        location.reload(true);
                    }
                },
                error: function(request) {
                    console.log("ERROR", request);
                }
            });
        },

        setPictureOnMap:function (myMap, text, coord, name,id,count){
            myMap.geoObjects
                .add(new ymaps.Placemark( coord, {
                    balloonContentHeader: name,
                    balloonContent: text,
                    iconContent: count //текст внутри балуна
                }, {
                    preset: 'islands#icon',
                    iconColor: '#0095b6',
                }))
        },
    }
})();