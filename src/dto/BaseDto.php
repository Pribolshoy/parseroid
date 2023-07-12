<?php

namespace pribolshoy\parseroid\dto;

/**
 * Class BaseDto
 *
 * Базовый класс DTO.
 *
 * @package pribolshoy\parseroid\dto
 */
abstract class BaseDto
{
    public function __construct()
    {

    }

    /**
     * Возаращает содержимое объекта в виде массива
     *
     * @return array
     */
    abstract public function get() :array;

    /**
     * Возвращает массив-коллекцию одного свойства-массива
     *
     * @param string $objectValueName
     *
     * @return array
     */
    protected function collection(string $objectValueName) :array
    {
        $result = [];
        
        if (property_exists($this, $objectValueName)
            && is_array($this->{$objectValueName})
        ) {
            foreach ($this->{$objectValueName} as $item) {
                if ($item instanceof BaseDto) {
                    /** @var BaseDto $item */
                    $result[] = $item->get();
                } else if (is_array($item)) {
                    $result[] = $item;
                }
            }
        }

        return $result;
    }

}