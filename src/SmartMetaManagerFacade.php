<?php

namespace Towoju5\SmartMetaManager;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Towoju5\SmartMetaManager\Skeleton\SkeletonClass
 */
class SmartMetaManagerFacade extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'smart-meta-manager';
    }
}
