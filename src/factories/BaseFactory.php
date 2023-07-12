<?php

namespace pribolshoy\parseroid\factories;

use yii\helpers\Inflector;

class BaseFactory
{
    const INSTANCES_NAMESPACE = '';

    protected string $instances_namespace = '';

    /**
     * @param string $name
     * @param string|null $defaultName
     *
     * @return mixed
     * @throws \Exception
     */
    public function create(string $name, array $config = [], ?string $defaultName = null)
    {
        $className = $this->getClassName($name);

        if (!class_exists($className)) {
            if (!$defaultName) {
                throw new \Exception("Класс $className не существует");
            } else {
                try {
                    return $this->create($defaultName, $config);
                } catch (\Exception $e) {
                    throw new \Exception("Класс $className не существует. " . $e->getMessage());
                }
            }
        }

        $object = new $className($config);
        return $object;
    }

    /**
     * Формирует из переданного значения полное имя класса,
     * включая namespace
     *
     * @param string $name
     *
     * @return string
     * @throws \Exception
     */
    public function getClassName(string $name)
    {
        if (!$this->getInstancesNamespace() && !static::INSTANCES_NAMESPACE)
            throw new \Exception("В фабрике ".static::class." не задан параметр INSTANCES_NAMESPACE");

        if ($this->getInstancesNamespace()) {
            return $this->getInstancesNamespace() . $this->parseClassName($name);
        }
        return static::INSTANCES_NAMESPACE . $this->parseClassName($name);
    }

    /**
     * Преобразует переданное значение в имя класса
     * в стиле PascalCase
     *
     * @param string $name
     *
     * @return string
     */
    public function parseClassName(string $name)
    {
        return ucfirst(Inflector::camelize($name));
    }

    /**
     * @return string
     */
    public function getInstancesNamespace(): string
    {
        return $this->instances_namespace;
    }

    /**
     * @param string $instances_namespace
     * @return $this
     */
    public function setInstancesNamespace(string $instances_namespace): object
    {
        $this->instances_namespace = $instances_namespace;
        return $this;
    }
}