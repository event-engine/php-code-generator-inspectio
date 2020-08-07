<?php

/**
 * @see       https://github.com/event-engine/php-code-generator-inspectio for the canonical source repository
 * @copyright https://github.com/event-engine/php-code-generator-inspectio/blob/master/COPYRIGHT.md
 * @license   https://github.com/event-engine/php-code-generator-inspectio/blob/master/LICENSE.md MIT License
 */

declare(strict_types=1);

namespace EventEngine\CodeGenerator\Inspectio;

use EventEngine\CodeGenerator\Inspectio\Transformator\MxGraphToGraphMl;
use OpenCodeModeling\CodeGenerator;

final class WorkflowConfigFactory
{
    public const SLOT_XML_FILE = 'inspectio-file_xml';
    public const SLOT_XSL_FILE = 'inspectio-file_xsl';

    public const SLOT_DOM_XML = 'inspectio-xml';
    public const SLOT_DOM_XSL = 'inspectio-xsl';

    public const SLOT_GRAPHML_SCHEMA_FILE = 'inspectio-graphml_schema_filename';
    public const SLOT_GRAPHML_XML = 'inspectio-graphml_xml';

    /**
     * Configures the workflow for converting Inspectio mxGraph XML to GraphML XML.
     *
     * @param CodeGenerator\Workflow\WorkflowContext $workflowContext
     * @param string $inputSlotXmlFilename XML file to load
     * @param string $inputSlotXslFilename XSL file to load
     * @param string $outputSlotGraphMlXml Transformed GraphML XML
     * @return CodeGenerator\Config\Component
     */
    public static function mxGraphToGraphMlConfig(
        CodeGenerator\Workflow\WorkflowContext $workflowContext,
        string $inputSlotXmlFilename,
        string $inputSlotXslFilename,
        string $outputSlotGraphMlXml
    ): CodeGenerator\Config\Component {
        if (! $workflowContext->has(self::SLOT_GRAPHML_SCHEMA_FILE)) {
            $workflowContext->put(self::SLOT_GRAPHML_SCHEMA_FILE, 'http://graphml.graphdrawing.org/xmlns/1.1/graphml.xsd');
        }

        return new CodeGenerator\Config\ArrayConfig(
            CodeGenerator\Transformator\FileToDomDocument::workflowComponentDescription($inputSlotXmlFilename, self::SLOT_DOM_XML),
            CodeGenerator\Transformator\FileToDomDocument::workflowComponentDescription($inputSlotXslFilename, self::SLOT_DOM_XSL),
            MxGraphToGraphMl::workflowComponentDescription(self::SLOT_DOM_XSL, self::SLOT_DOM_XML, self::SLOT_GRAPHML_SCHEMA_FILE, $outputSlotGraphMlXml),
        );
    }
}
