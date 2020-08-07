<?php

/**
 * @see       https://github.com/event-engine/php-code-generator-inspectio for the canonical source repository
 * @copyright https://github.com/event-engine/php-code-generator-inspectio/blob/master/COPYRIGHT.md
 * @license   https://github.com/event-engine/php-code-generator-inspectio/blob/master/LICENSE.md MIT License
 */

declare(strict_types=1);

namespace EventEngine\CodeGenerator\Inspectio\Transformator;

use DOMDocument;
use EventEngine\CodeGenerator\Inspectio\Transformator\Exception\XmlSchemaValidationError;
use OpenCodeModeling\CodeGenerator\Workflow;
use XSLTProcessor;

final class MxGraphToGraphMl
{
    public function __invoke(DOMDocument $xsl, DOMDocument $xml, string $schema): string
    {
        $xslt = new XSLTProcessor();
        $xslt->importStylesheet($xsl);

        $graphMlXml = $xslt->transformToXML($xml);
        $this->validateXml($graphMlXml, $schema);

        return $graphMlXml;
    }

    public static function workflowComponentDescription(
        string $inputXsl,
        string $inputXml,
        string $inputSchema,
        string $output
    ): Workflow\DescriptionWithInputSlot {
        return new Workflow\ComponentDescriptionWithSlot(
            new self(),
            $output,
            $inputXsl,
            $inputXml,
            $inputSchema
        );
    }

    private function validateXml(string $xml, string $schemaFilename): void
    {
        $previousValue = \libxml_use_internal_errors(true);

        // xml reader is used because others does not work correctly with imports
        $xmlReader = new \XMLReader();
        $xmlReader->xml($xml);
        $xmlReader->setSchema($schemaFilename);

        while ($xmlReader->read()) {
            if (! $xmlReader->isValid()) {
                throw XmlSchemaValidationError::withErrors(...\libxml_get_errors());
            }
        }
        \libxml_use_internal_errors($previousValue);
    }
}
