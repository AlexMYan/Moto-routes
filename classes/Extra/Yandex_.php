<?php
/**
 * Как пример
 *
 */
namespace Extra;

class Yandex
{
    private const yandexKey =  "api_key_yandex";

    private const fileMain = "fileMain";

    private const fileCopy = "fileCopy";

    private const fileImages = "fileMain3";

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
    public function getfileMain(){
        return self::fileMain;
    }

    /**
     * Дополнительный файл, сюда пишем перед удалением
     *
     * @return string
     */
    public function getfileCopy(){
        return self::fileCopy;
    }

    /**
     * Файл где храняться картинки
     *
     * @return string
     */
    public function getfileImages(){
        return self::fileImages;
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
