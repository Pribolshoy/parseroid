<?php

namespace pribolshoy\parseroid\parsers\html\traits;

/**
 * Class HtmlParserTrait
 * Трейт парсера, включающий в себя необходимые
 * методы и свойства для работы html парсера.
 *
 *
 * @package pribolshoy\parseroid\parsers
 */
trait HtmlParserTrait
{
    /**
     * Ссылка на страницу парсинга
     * которая парсится в данный момент
     * @var string
     */
    protected ?string $url;

    /**
     * Домен из ссылки на сайт
     * @var string
     */
    protected ?string $domain;

    /**
     * Заголовки ответа
     * @var array
     */
    protected ?array $headers;

    /**
     * Статус полученный из запроса по ресурсу
     * @var string
     */
    protected ?string $status_code = null;

    /**
     * http/https из ссылки на сайт
     * @var string
     */
    protected ?string $scheme;

    /**
     * Часть урла
     * @var string
     */
    protected ?string $path;

    /**
     * Содержимое загруженного ресурса
     * @var
     */
    protected $document;

    /**
     * Получить заданный урл
     *
     * @return string|null
     */
    public function getUrl() :string
    {
        return $this->url ?? null;
    }

    /**
     * Задать урл для парсинга
     *
     * @param string $url
     * @return static
     */
    public function setUrl(string $url)
    {
        $this->url = $url;
        $this->prepareUrl();
        return $this;
    }

    /**
     * @param array $headers
     */
    public function setHeaders(array $headers) :object
    {
        $this->headers = $headers;
        return $this;
    }

    /**
     * Получить код статуса http запроса
     *
     * @return string|null
     */
    public function getStatusCode()
    {
        return $this->status_code ?? null;
    }

    /**
     * Задать код статуса http запроса
     *
     * @param string $status_code
     * @return static
     */
    public function setStatusCode(string $status_code)
    {
        $this->status_code = $status_code;
        return $this;
    }

    public function isStatusCode(int $status_code) :bool
    {
        return ((int)$this->status_code === (int)$status_code);
    }

    public function setStatusCodeByHeaders()
    {
        if ($this->headers) {
            $this->setStatusCode(explode(' ', $this->headers[0])[1]);
        }
        return $this;
    }

    /**
     * Получить заданный урл, очистив его от поддомена.
     *
     * @return string|null
     */
    public function getPureUrl(?string $path = null) :string
    {
        if ($this->domain) {
            $domainParts = explode('.', $this->domain);

            if (count($domainParts) >= 2) {
                list($second, $first) = array_reverse($domainParts);
                $pureUrl = $this->scheme . '://' . "$first.$second" . ($path ? $path : $this->path);
            }
        }
        return $pureUrl ?? null;
    }

    /**
     * Разбор строки адреса на составляющие
     *
     * @param string $url
     * @return static
     */
    public function prepareUrl(?string $url = null)
    {
        if ($url_parts = parse_url($url ?? $this->getUrl())) {
            $this->domain = $url_parts['host'];
            $this->scheme = $url_parts['scheme'];
            $this->path = $url_parts['path'];
        }
        return $this;
    }

    /**
     * @return string
     */
    public function getLocation() :string
    {
        foreach ($this->headers as $header) {
            if (stripos($header, "location") !== false) {
                print "$header \n";
                return $header;
            }
        }

        return '';
    }

    /**
     * Получение адреса страницы из заголовков,
     * если таковой заголовок имеется
     *
     * @return string
     */
    public function getUrlFromLocation() :string
    {
        if ($location = $this->getLocation()) {
            return trim(preg_replace('#^(.*)(location:)(.+)$#i', '$3', $location));
        }

        return '';
    }

    /**
     * Является ли данная страница страницей каталога.
     * Реализуется в каждом конкретном парсере сайта
     * самостоятельно.
     *
     * @return bool
     *
     * @throws \Exception
     */
    public function isPageCatalog() :bool
    {
        return false;
    }


    /**
     * Получить сводную информацию и работе парсера
     *
     * @return array
     */
    public function getSummary() :array
    {
        return [
            'url'           => $this->getUrl(),
            'pure_url'      => $this->getPureUrl(),
            'url_from_location'=> $this->getUrlFromLocation(),
            'status_code'   => $this->getStatusCode(),
            'headers'       => $this->headers,
        ];
    }
}