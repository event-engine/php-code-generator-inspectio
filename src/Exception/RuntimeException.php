<?php

/**
 * @see       https://github.com/event-engine/php-code-generator-inspectio for the canonical source repository
 * @copyright https://github.com/event-engine/php-code-generator-inspectio/blob/master/COPYRIGHT.md
 * @license   https://github.com/event-engine/php-code-generator-inspectio/blob/master/LICENSE.md MIT License
 */

declare(strict_types=1);

namespace EventEngine\CodeGenerator\Inspectio\Exception;

use RuntimeException as BaseRuntimeException;

class RuntimeException extends BaseRuntimeException implements InspectioException
{
}
