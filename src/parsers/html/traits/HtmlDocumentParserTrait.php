<?php

namespace pribolshoy\parseroid\parsers\html\traits;

use pribolshoy\parseroid\helpers\CurlHelper;

/**
 * Трейт предоставляющий метод для парсинга html
 * документов через curl
 *
 * @package pribolshoy\parseroid\services
 */
trait HtmlDocumentParserTrait
{
    use HtmlParserTrait {
        getSummary as getOrigSummary;
    }

    /**
     * Количество спарсеных страниц.
     * Независимо от того одна и та же страница парсится
     * или разные, не зависит от каталога.
     * Нужно для того чтобы цикл парсинга страницы
     * не был бесконечен.
     * @var int
     */
    protected int $parsed_page_count = 1;

    /**
     * Максимальное количество попыток парсинга
     * одного ресурса за цикл
     * @var int
     */
    protected int $max_attempts = 10;

    /**
     * Увеличить количество спарсеных страниц.
     */
    public function incrParseAttempts()
    {
        return ++$this->parsed_page_count;
    }

    /**
     * Получить счетчик спарсеных страниц
     */
    public function getParseAttempts()
    {
        return $this->parsed_page_count;
    }

    /**
     * Сбросить счетчик спарсеных страниц
     */
    public function resetParseAttempts()
    {
        $this->parsed_page_count = 1;
        return $this;
    }

    /**
     * Установка максимального количества спарсеных
     * страниц каталога за раз
     *
     * @param int $num
     *
     * @return $this
     */
    public function setMaxAttempts(int $num)
    {
        $this->max_attempts = $num;
        return $this;
    }

    /**
     * @return int
     */
    public function getMaxAttempts() :int
    {
        return $this->max_attempts;
    }

    /**
     * Истекло ли максимальное количетсво попыток
     * парсинга одного ресурса.
     *
     * @return bool
     */
    public function maxAttemtsExceeded() :bool
    {
        return $this->getParseAttempts() > $this->getMaxAttempts();
    }


    /**
     * @param string|null $url
     *
     * @return bool|string строку с html кодом или false
     */
    public function parse(?string $url = null)
    {
        if (!$url) $url = $this->getUrl();
        if (!$url) throw new \RuntimeException('Не задан обязательный параметр url!');

        if ($this->maxAttemtsExceeded()) return false;

        // Если ошибка при парсинге, то 505 означает ошибку, отсутствие страницы
        if (!$this->document = CurlHelper::curl($url)) {
            $this->setStatusCode('505');
            return false;
        }

        $this->incrParseAttempts();

        // Вычленяем headers
        if (!$headers = CurlHelper::getHeaders($this->document)) return false;

        $this->setHeaders($headers)->setStatusCodeByHeaders();

        if ($this->isStatusCode(200)) return $this->document;
        if ($this->isStatusCode(404)) return false;

        // если в заголовках есть location
        if (!$url = $this->getUrlFromLocation()) return false;

        $this->setUrl($url);

        // если при этом домена прошлого урла нет, то предполагаем что
        // в location был только путь, без домена
        if (stripos($this->getUrl(), $this->domain) === false) {
            $this->setUrl("{$this->scheme}://{$this->domain}{$this->getUrl()}");
        }
        return $this->document = $this->parse($this->getUrl());
    }
}
