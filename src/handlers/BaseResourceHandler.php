<?php

namespace pribolshoy\parseroid\handlers;

use pribolshoy\parseroid\parsers\BaseParser;

/**
 * Class Handler
 *
 * Абстрактный класс для объектов, которые занимаются
 * парсингом товаров из ресурса (html/xml/xls), и последующим
 * сопоставлением с набором установленных товаров
 *
 * @package pribolshoy\parseroid\handlers
 */
abstract class BaseResourceHandler extends \yii\base\Model
{
    /**
     * Парсер который разбирает информацию из ресура
     * и подает в стандартизированном виде
     * @var BaseParser $parser Object парсер,
     */
    protected ?BaseParser $parser = null;

    protected string $parser_class = '';

    /**
     * Ресурс из которого будет браться информация
     * (каталог сайта, xml файл)
     * @var mixed
     */
    protected string $resource;

    /**
     * Массив с переданными данными при создании
     * @var array
     */
    protected array $config = [];

    public function __construct($config = [])
    {
        $this->config = $config;
        $this->init();
    }

    /**
     * Добавить параметр в конфиг
     *
     * @param string $name
     * @param $value
     *
     * @return $this
     */
    public function addConfig(string $name, $value)
    {
        $this->config[$name] = $value;
        return $this;
    }

    /**
     * Конфигурирует данный тип обработчика
     * @throws \Exception
     */
    public function init()
    {
        if ($this->config['resource']) {
            $this->setResource($this->config['resource']);
        }
    }

    /**
     * Инициализация и настройка парсера обработчика.
     * Может переопределяться.
     *
     * @return mixed
     */
    protected function initParser()
    {
        $class = $this->parser_class;
        $this->parser = new $class();
        return $this;
    }

    /**
     * Метод - мост
     * Обертка выборки элемента через парсер
     *
     * @return mixed
     */
    public function getItem()
    {
        // инициализация происходит здесь, чтобы можно было добавлять конфиги
        $this->initParser();
        return $this->parser->getItem($this->getResource());
    }

    /**
     * Метод - мост
     * Получить элементы парсинга из ресурса через парсер
     *
     * @return mixed
     */
    public function getItems() :array
    {
        // инициализация происходит здесь, чтобы можно было добавлять конфиги
        $this->initParser();
        return $this->parser->getItems($this->getResource(), $this->config['page_offset'] ?? 1, $this->config['refresh'] ?? true);
    }

    /**
     * Возвращает флаг того, закончени ли парсинг.
     * По умолчанию всегда true.
     *
     * @return bool
     */
    public function isParsingFinished() :bool
    {
        return true;
    }

    /**
     * Исполнение команды парсера
     *
     * @param string $command
     * @return mixed
     */
    public function parserCommand(string $command, ?array $params = [])
    {
        if ($this->parser) {
            if (!method_exists($this->parser, $command)) {
                throw new \RuntimeException("В парсере " . get_class($this->parser) . "не существует метода $command()");
            }
        }

        return $this->parser->{$command}($params);
    }

    /**
     * Установка ресурса
     *
     * @param string $resource
     *
     * @return mixed
     */
    public function setResource(string $resource) {
        return $this->resource = $resource;
    }

    /**
     * Получение ресурса
     * @return mixed
     */
    public function getResource() {
        return $this->resource;
    }

}