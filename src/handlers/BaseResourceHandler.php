<?php

namespace pribolshoy\parseroid\handlers;

use pribolshoy\parseroid\exceptions\ParserException;
use pribolshoy\parseroid\parsers\BaseParser;

/**
 * Class BaseResourceHandler
 *
 * Абстрактный класс для объектов, которые занимаются
 * парсингом товаров из ресурса (html/xml/xls), и последующим
 * сопоставлением с набором установленных товаров
 *
 * @package pribolshoy\parseroid
 */
abstract class BaseResourceHandler
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
     * Конфигурирует данный тип обработчика
     * @throws \Exception
     */
    public function init()
    {
        if ($this->config['resource']) {
            $this->setResource($this->config['resource']);
        }

        if ($this->config['parser_class']) {
            $this->setParserClass($this->config['parser_class']);
        }
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
     * @param string|null $name
     * @param null $default_value
     *
     * @return mixed
     */
    public function getConfig(?string $name = null, $default_value = null)
    {
        if (!is_null($name)) {
            return $this->config[$name] ?? $default_value;
        }

        return $this->config;
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

        $this->afterParserInit();

        return $this;
    }

    /**
     * Any additional logic after creating of parser object.
     *
     * @return $this
     */
    protected function afterParserInit()
    {
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
        $this->initParser();

        return $this->parser->getItems(
            $this->getResource(),
            $this->getConfig('page_offset', 1),
            $this->getConfig('refresh', true)
        );
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
     * @param array|null $params
     * @return mixed
     *
     * @throws \Exception
     */
    public function parserCommand(string $command, ?array $params = [])
    {
        if ($this->parser
            && !method_exists($this->parser, $command)
        ) {
            throw new ParserException("In " . get_class($this->parser) . " don't exists method $command()");
        }

        return $this->parser->{$command}($params);
    }

    /**
     *
     * @param string $parser_class
     * @return $this
     */
    public function setParserClass(string $parser_class) :object
    {
        $this->parser_class = $parser_class;
        return $this;
    }

    /**
     * Установка ресурса
     *
     * @param string $resource
     *
     * @return $this
     */
    public function setResource(string $resource) :object
    {
        $this->resource = $resource;
        return $this;
    }

    /**
     * Получение ресурса
     * @return mixed
     */
    public function getResource()
    {
        return $this->resource;
    }

}