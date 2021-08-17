<?php

namespace TwinElements\AdminBundle\Menu;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Cache;

/**
 * Class MenuItem
 * @package App\AdminBundle\Menu
 * @Cache(expires="tomorrow")
 */
class MenuItem
{
    /**
     * @var string
     */
    private $name;
    /**
     * @var string
     */
    private $route;

    /**
     * @var array
     */
    private $routeParams = [];

    /**
     * @var string
     */
    private $role = null;

    /**
     * @var int
     */
    private $priority;

    private function __construct($name, $route, array $routeParams = array(), $priority = 20, $role = null)
    {
        $this->name = (string)$name;
        $this->route = (string)$route;
        $this->routeParams = $routeParams;
        $this->role = (string)$role;
        $this->priority = (int) $priority;
    }

    public static function newInstance($name, $route, array $routeParams = array(), $priority = 20, $role = null)
    {
        return new self($name, $route, $routeParams, $priority, $role);
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return mixed
     */
    public function getRoute()
    {
        return $this->route;
    }

    /**
     * @param mixed $route
     */
    public function setRoute($route)
    {
        $this->route = $route;
    }

    /**
     * @return mixed
     */
    public function getRouteParams()
    {
        return $this->routeParams;
    }

    /**
     * @param mixed $routeParams
     */
    public function setRouteParams($routeParams)
    {
        $this->routeParams = $routeParams;
    }

    /**
     * @return mixed
     */
    public function getRole()
    {
        return $this->role;
    }

    /**
     * @param mixed $role
     */
    public function setRole($role)
    {
        $this->role = $role;
    }

    /**
     * @return int
     */
    public function getPriority(): int
    {
        return $this->priority;
    }

    /**
     * @param int $priority
     */
    public function setPriority(int $priority): void
    {
        $this->priority = $priority;
    }


}
