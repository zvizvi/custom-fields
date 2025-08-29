<?php

declare(strict_types=1);

namespace Relaticle\CustomFields\Filament\Integration\Components\Forms;

use Closure;
use Filament\Forms\Components\Field;
use Relaticle\CustomFields\Filament\Integration\Base\AbstractFormComponent;
use Relaticle\CustomFields\Models\CustomField;
use Relaticle\CustomFields\Services\ValidationService;
use Relaticle\CustomFields\Services\Visibility\CoreVisibilityLogicService;
use Relaticle\CustomFields\Services\Visibility\FrontendVisibilityService;

/**
 * Simple adapter that allows Closures to benefit from AbstractFormComponent's
 * full feature set (validation, visibility, state management, etc.) while
 * maintaining user configuration priority.
 *
 * This adapter extends AbstractFormComponent and simply implements the create()
 * method to call the user's Closure, then lets AbstractFormComponent handle
 * all the complex configuration logic.
 */
final readonly class ClosureFormAdapter extends AbstractFormComponent
{
    public function __construct(
        private Closure $closure,
        ValidationService $validationService,
        CoreVisibilityLogicService $coreVisibilityLogic,
        FrontendVisibilityService $frontendVisibilityService
    ) {
        parent::__construct($validationService, $coreVisibilityLogic, $frontendVisibilityService);
    }

    /**
     * Implementation of AbstractFormComponent's create() method.
     * Simply calls the user's Closure to create the field.
     */
    public function create(CustomField $customField): Field
    {
        return ($this->closure)($customField);
    }
}
