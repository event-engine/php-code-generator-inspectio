<?php

/**
 * @see       https://github.com/event-engine/php-code-generator-inspectio for the canonical source repository
 * @copyright https://github.com/event-engine/php-code-generator-inspectio/blob/master/COPYRIGHT.md
 * @license   https://github.com/event-engine/php-code-generator-inspectio/blob/master/LICENSE.md MIT License
 */

declare(strict_types=1);

namespace EventEngine\CodeGenerator\Inspectio\Transformator\Exception;

use LibXMLError;
use RuntimeException;

final class XmlSchemaValidationError extends RuntimeException
{
    /**
     * @var LibXMLError[]
     */
    private $errors;

    public static function withErrors(LibXMLError ...$errors): self
    {
        $self = new self('XML schema validation error occurred.');
        $self->errors = $errors;

        return $self;
    }

    /**
     * @return LibXMLError[]
     */
    public function errors(): array
    {
        return $this->errors;
    }
}
