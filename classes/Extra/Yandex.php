<?php
namespace Extra;

class Yandex
{
    private const yandexKey =  "3fbf19f4-1be9-4716-a272-fde43aca6efd";

    private const file1 = "files/2.csv";

    private const file2 = "files/3.csv";

    private const file3 = "files/picturesmy.csv";

    private const controlWord = "555";

    /**
     * Ключ yandex карты
     *
     * @return string
     */
    public function getYandexKey(){
        return self::yandexKey;
    }

    /**
     * основной файл
     *
     * @return string
     */
    public function getFile1(){
        return self::file1;
    }

    /**
     * Дополнительный файл, сюда пишем перед удалением
     *
     * @return string
     */
    public function getFile2(){
        return self::file2;
    }

    /**
     * Файл где храняться картинки
     *
     * @return string
     */
    public function getFile3(){
        return self::file3;
    }

    /**
     * Типа пароль
     *
     * @return string
     */
    public function getControlWord(){
        return self::controlWord;
    }


}
