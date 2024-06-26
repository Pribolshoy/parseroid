<?php

namespace pribolshoy\parseroid\parsers;

use pribolshoy\parseroid\exceptions\ParserException;
use pribolshoy\parseroid\helpers\Curl;
use pribolshoy\parseroid\helpers\ResourceTransferInterface;

/**
 * Class UrlParser
 *
 * @package pribolshoy\parseroid
 */
abstract class UrlParser extends BaseParser
{
    /**
     * Часть урла
     *
     * @var string
     */
    protected ?string $path;

    protected string $resourceTransferClass = Curl::class;

    protected ?ResourceTransferInterface $resourceTransfer = null;

    protected bool $activeResourceTransfer = true;

    /**
     * @var string
     */
    protected ?string $url;

    /**
     * Домен из ссылки на сайт
     *
     * @var string
     */
    protected ?string $domain;

    /**
     * Заголовки ответа
     *
     * @var array
     */
    protected ?array $headers = null;

    /**
     * Статус полученный из запроса по ресурсу
     *
     * @var string
     */
    protected ?string $status_code = null;

    /**
     * http/https из ссылки на сайт
     *
     * @var string
     */
    protected ?string $scheme;

    /**
     * Html selector for finding pagination elements on catalog page
     *
     * @var string
     */
    protected string $paginationsSelector = '';

    /**
     * Num of parsing runs.
     * Независимо от того одна и та же страница парсится
     * или разные, не зависит от каталога.
     * Нужно для того чтобы цикл парсинга страницы
     * не был бесконечен.
     *
     * @var int
     */
    protected int $attempts_to_download_resource = 1;

    /**
     * Max attempts of actual resource parsing.
     *
     * @var int
     */
    protected int $max_attempts = 10;

    public function init()
    {
        parent::init();

        if ($this->isResourceTransferActive()) {
            $this->initResourceTransfer();
        }
    }

    /**
     * @return $this
     */
    protected function initResourceTransfer()
    {
        if (is_null($this->resourceTransfer)
            && $this->resourceTransferClass
        ) {
            $this->resourceTransfer = new $this->resourceTransferClass();
        }

        return $this;
    }

    /**
     * @return string
     */
    public function getResourceTransferClass(): string
    {
        return $this->resourceTransferClass;
    }

    /**
     * @param string $resourceTransferClass
     *
     * @return object
     */
    public function setResourceTransferClass(string $resourceTransferClass): object
    {
        $this->resourceTransferClass = $resourceTransferClass;
        return $this;
    }

    /**
     * @return ResourceTransferInterface|null
     */
    public function getResourceTransfer(): ?ResourceTransferInterface
    {
        if (is_null($this->resourceTransfer)) {
            $this->initResourceTransfer();
        }

        return $this->resourceTransfer;
    }

    /**
     * @param ResourceTransferInterface|null $resourceTransfer
     *
     * @return object
     */
    public function setResourceTransfer(?ResourceTransferInterface $resourceTransfer):object
    {
        $this->resourceTransfer = $resourceTransfer;
        return $this;
    }

    /**
     * @return bool
     */
    public function isResourceTransferActive(): bool
    {
        return $this->activeResourceTransfer;
    }

    /**
     * @param bool $active
     *
     * @return object
     */
    public function setResourceTransferActive(bool $active): object
    {
        $this->activeResourceTransfer = $active;
        return $this;
    }

    /**
     * Получить заданный урл
     *
     * @return string|null
     */
    public function getUrl() :?string
    {
        return $this->url ?? null;
    }

    /**
     * Задать урл для парсинга
     *
     * @param  string $url
     * @return static
     */
    public function setUrl(string $url)
    {
        $this->url = $url;
        $this->prepareUrl();
        return $this;
    }

    /**
     * @return array
     */
    public function getHeaders(): ?array
    {
        return $this->headers;
    }

    /**
     * @param  array $headers
     * @return object
     */
    protected function setHeaders(array $headers) :object
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
     * @param  string $status_code
     * @return static
     */
    protected function setStatusCode(string $status_code)
    {
        $this->status_code = $status_code;
        return $this;
    }

    /**
     * @param  int $status_code
     * @return bool
     */
    public function isStatusCode(int $status_code) :bool
    {
        return ((int)$this->getStatusCode() === (int)$status_code);
    }

    /**
     * @return $this
     */
    public function setStatusCodeByHeaders()
    {
        if ($headers = $this->getHeaders()) {
            $this->setStatusCode(explode(' ', $headers[0])[1]);
        }
        return $this;
    }

    /**
     * @param  string $value
     * @return false|int
     */
    public function isUrl(string $value) :bool
    {
        return (bool)preg_match('#^http(s|)://.+.\..+$#', $value);
    }

    /**
     * Получить заданный урл, очистив его от поддомена.
     *
     * @param string|null $path
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
     * @param  string $url
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
        $result = '';

        if ($headers = $this->getHeaders()) {
            foreach ($headers as $header) {
                if (stripos($header, "location") !== false) {
                    $result = $header;
                }
            }
        }

        return $result;
    }

    /**
     * Получение адреса страницы из заголовков,
     * если таковой заголовок имеется
     *
     * @return string
     */
    public function getUrlFromLocation() :string
    {
        $result = '';

        if ($location = $this->getLocation()) {
            $result = trim(preg_replace('#^(.*)(location:)(.+)$#i', '$3', $location));
        }

        return $result;
    }

    /**
     * @return int
     */
    public function incrParseAttempts()
    {
        return ++$this->attempts_to_download_resource;
    }

    /**
     * Получить счетчик спарсеных страниц
     */
    public function getDownloadAttempts()
    {
        return $this->attempts_to_download_resource;
    }

    /**
     * Сбросить счетчик спарсеных страниц
     */
    public function resetParseAttempts()
    {
        $this->attempts_to_download_resource = 1;
        return $this;
    }

    /**
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
        return $this->getDownloadAttempts() > $this->getMaxAttempts();
    }

    /**
     * @param string $resource
     *
     * @return bool
     * @throws ParserException
     */
    public function initDocument(string $resource): bool
    {
        if (($result = parent::initDocument($resource))
            && $this->isResourceTransferActive()
            && $this->isUrl($resource)
        ) {
            $document = $this->setUrl($resource)
                ->resetParseAttempts()
                ->download();

            if ($document) {
                $this->setDocument($document);
            } else {
                $result = false;
            }
        }

        return $result;
    }

    /**
     * @param string|null $url
     *
     * @return bool|string строку с html кодом или false
     * @throws ParserException
     */
    public function download(?string $url = null)
    {
        if (!$url) {
            $url = $this->getUrl();
        }

        if (!$url) {
            throw new ParserException('Не задан обязательный параметр url!');
        }

        if ($this->maxAttemtsExceeded()) {
            return false;
        }

        // Если ошибка при парсинге, то 505 означает ошибку, отсутствие страницы
        if (!$document = $this->getResourceTransfer()->get($url)) {
            $this->setStatusCode('505');
            return false;
        }

        $this->incrParseAttempts();

        // Вычленяем headers
        if (!$headers = $this->getResourceTransfer()->getHeaders($document)) {
            return false;
        }

        $this->setHeaders($headers)
            ->setStatusCodeByHeaders();

        if ($this->isStatusCode(200)) {
            return $document;
        }

        if ($this->isStatusCode(404)) {
            return false;
        }

        // если в заголовках есть location
        if (!$url = $this->getUrlFromLocation()) {
            return false;
        }

        $this->setUrl($url);

        // если при этом домена прошлого урла нет, то предполагаем что
        // в location был только путь, без домена
        if (stripos($this->getUrl(), $this->domain) === false) {
            $this->setUrl("{$this->scheme}://{$this->domain}{$this->getUrl()}");
        }

        return $document = $this->download($this->getUrl());
    }

    /**
     * Return summary of parsing
     *
     * @return array
     */
    public function getSummary(): array
    {
        return [
            'url'           => $this->getUrl(),
            'pure_url'      => $this->getPureUrl(),
            'url_from_location'=> $this->getUrlFromLocation(),
            'status_code'   => $this->getStatusCode(),
            'headers'       => $this->headers,
            'max_attempts'      => $this->getMaxAttempts(),
            'parse_attempts'    => $this->getDownloadAttempts(),
            'attempts_to_download_resource' => $this->attempts_to_download_resource,
        ];
    }
}