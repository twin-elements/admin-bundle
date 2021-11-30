<?php

namespace TwinElements\AdminBundle\Model;

use TwinElements\AdminBundle\Helper\Breadcrumbs;
use TwinElements\AdminBundle\Helper\CrudLoggerMessage;
use TwinElements\AdminBundle\Service\AdminTranslator;
use TwinElements\Components\Flashes\Flashes;

trait CrudControllerTrait
{
    protected $breadcrumbs;
    /**
     * @var Flashes $flashes
     */
    protected $flashes;
    protected $crudLogger;
    protected $adminTranslator;

    /**
     * @param $breadcrumbs
     */
    public function __construct(
        Breadcrumbs $breadcrumbs,
        Flashes $flashes,
        CrudLoggerMessage $crudLogger,
        AdminTranslator $translator
    )
    {
        $this->breadcrumbs = $breadcrumbs;
        $this->flashes = $flashes;
        $this->crudLogger = $crudLogger;
        $this->adminTranslator = $translator;
    }
}
