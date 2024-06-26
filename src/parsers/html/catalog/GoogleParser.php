<?php

namespace pribolshoy\parseroid\parsers\html\catalog;

use DOMElement;
use pribolshoy\parseroid\dto\BaseDto;
use pribolshoy\parseroid\dto\GoogleItemDto;
use pribolshoy\parseroid\exceptions\ParserException;
use pribolshoy\parseroid\parsers\html\HtmlCatalogParser;

class GoogleParser extends HtmlCatalogParser
{
    protected string $paginationsSelector = 'div[role="navigation"] a.fl';

    public function run()
    {
        $result = [];

        if (!$this->getUrl()) {
            throw new ParserException("Url is not set");
        }

        // следим чтоб актуальная страинца каталога не превышала максимальной
        for ($i = $this->getActualPageNum(); $i <= $this->getMaxPageNum(); $i++) {
            if ($this->getDownloadedResourcesCount() > $this->getPageLimit()) {
                break;
            }

            // задаем актуальную страницу каталога
            $this->setActualPageNum($i);

            if (stripos($this->getUrl(), '?') !== false) {
                $url = $this->getUrl() . '&start=' . $this->getActualPageNum() . '0';
            } else {
                $url = $this->getUrl() . '?start=' . $this->getActualPageNum() . '0';
            }

            if ($this->getMaxAttempts() >= 10) { $this->resetParseAttempts();
            }

            if (!$this->initDocument($url)) {
                continue;
            }

            /**
 * @var \phpQueryObject 
*/
            $phpQueryDoc = $this->getConvertedDocument($this->getDocument());

            if (count($items = $phpQueryDoc->find('#search .MjjYud')) ) {
                foreach ($items as $cont) {
                    if (!$item = $this->parseItem($cont)) {
                        continue;
                    }
                    $result[] = $item;
                }
            }

            // засчитываем еще одну спарсенную страницу каталога
            $this->incrDownloadedResourcesCount();
        }

        return $result;
    }

    /**
     * @param  DOMElement $item
     * @return array|BaseDto
     */
    public function parseItem(DOMElement $item) :array
    {
        $pqItem = pq($item); // Это аналог $ в jQuery

        $url = mb_strtolower(trim(pq($pqItem->find('a'))->attr('href')));
        $title = trim(pq($pqItem->find('a h3'))->text());
        $description = trim(pq($pqItem->find('div[data-sncf="1"]'))->text());
        $icon = trim(pq($pqItem->find('img'))->attr('src'));

        $dto = new GoogleItemDto($title, $description, $url, $icon);

        return $dto->get();
    }

}