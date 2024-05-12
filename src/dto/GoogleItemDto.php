<?php

namespace pribolshoy\parseroid\dto;

/**
 * Class GoogleItemDto
 *
 * @package pribolshoy\parseroid\dto
 */
final class GoogleItemDto extends \pribolshoy\parseroid\dto\BaseDto
{
    public ?string $title;

    public ?string $description;

    public ?string $url;

    public ?string $icon;

    public function __construct(
        ?string $title,
        ?string $description,
        ?string $url,
        ?string $icon
    ) {
        $this->title = $title;
        $this->description = $description;
        $this->url = $url;
        $this->icon = $icon;
    }

    public function get() :array
    {
        return [
            'title'        => $this->title,
            'description'  => $this->description,
            'url'          => $this->url,
            'icon'         => $this->icon,
        ];
    }

}