<?php

namespace pribolshoy\parseroid\factories;

use pribolshoy\parseroid\helpers\StringHelper;

/**
 * Class BaseFactory
 * Factory for dynamic creating class instances
 * in configured namespace.
 *
 * @package pribolshoy\parseroid
 */
class BaseFactory
{
    /**
     * Immutable namespace for actual factory.
     * @var string
     */
    const INSTANCES_NAMESPACE = '';

    /**
     * Mutable namespace for actual factory.
     * @var string
     */
    protected string $instances_namespace = '';

    /**
     * Create class instance by passed classname in
     * configured namespace.
     *
     * @param string $classname
     * @param array $config
     * @param string|null $defaultName
     *
     * @return mixed
     * @throws \Exception
     */
    public function create(string $classname, array $config = [], ?string $defaultName = null)
    {
        $className = $this->getClassName($classname);

        if (!class_exists($className)) {
            if (!$defaultName) {
                throw new \Exception("Class $className not existing");
            } else {
                try {
                    return $this->create($defaultName, $config);
                } catch (\Exception $e) {
                    throw new \Exception("Class $className not existing. " . $e->getMessage());
                }
            }
        }

        return new $className($config);
    }

    /**
     * Формирует из переданного значения полное имя класса,
     * включая namespace
     *
     * @param string $classname
     *
     * @return string
     * @throws \Exception
     */
    public function getClassName(string $classname)
    {
        if (!$this->getInstancesNamespace() && !static::INSTANCES_NAMESPACE)
            throw new \Exception("There is not set property INSTANCES_NAMESPACE in ".static::class." factory");

        if ($this->getInstancesNamespace()) {
            return $this->getInstancesNamespace() . StringHelper::camelize($classname);
        }
        return static::INSTANCES_NAMESPACE . StringHelper::camelize($classname);
    }

    /**
     * Get property instances_namespace
     *
     * @return string
     */
    public function getInstancesNamespace(): string
    {
        return $this->instances_namespace;
    }

    /**
     * Set property instances_namespace
     *
     * @param string $instances_namespace
     *
     * @return $this
     */
    public function setInstancesNamespace(string $instances_namespace): object
    {
        if (substr($instances_namespace, -1) !== '\\') {
            $instances_namespace .= '\\';
        }
        $this->instances_namespace = $instances_namespace;
        return $this;
    }
}