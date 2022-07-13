<?php
/**
 * Как пример
 *
 */
namespace Extra;

class Yandex
{
    private const yandexKey =  "api_key_yandex";

    private const file1 = "file1";

    private const file2 = "file2";

    private const file3 = "file13";

    private const controlWord = "2343423048-";

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
