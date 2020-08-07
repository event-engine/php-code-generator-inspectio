<?php

/**
 * @see       https://github.com/event-engine/php-code-generator-inspectio for the canonical source repository
 * @copyright https://github.com/event-engine/php-code-generator-inspectio/blob/master/COPYRIGHT.md
 * @license   https://github.com/event-engine/php-code-generator-inspectio/blob/master/LICENSE.md MIT License
 */

declare(strict_types=1);

namespace EventEngine\CodeGenerator\Inspectio\Console;

use EventEngine\CodeGenerator\Inspectio\WorkflowConfigFactory;
use OpenCodeModeling\CodeGenerator\Console\WorkflowContext;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

final class XmlGenerateAllCommand extends Command
{
    protected function configure(): void
    {
        $this
            ->setName('inspectio:xml:generate-all')
            ->setDescription('Converts InspectIO mxGraph XML to GraphML XML and generates Event Engine code')
            ->addArgument('file_xml', InputArgument::REQUIRED, 'Source xml file')
            ->addArgument('file_xsl', InputArgument::OPTIONAL, 'Source xsl file');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $fileXml = $input->getArgument(WorkflowConfigFactory::SLOT_XML_FILE);
        $fileXsl = $input->getArgument(WorkflowConfigFactory::SLOT_XSL_FILE) ?: \dirname(__DIR__) . '/xsl/graphmx.xsl';

        /** @var \OpenCodeModeling\CodeGenerator\Workflow\WorkflowContext $workflowContext */
        $workflowContext = $this->getHelper(WorkflowContext::class)->context();
        $workflowContext->put(WorkflowConfigFactory::SLOT_XML_FILE, $fileXml);
        $workflowContext->put(WorkflowConfigFactory::SLOT_XSL_FILE, $fileXsl);

        $command = $this->getApplication()->find('ocmcg:workflow:run');
        $command->run(new ArrayInput([]), $output);

        return 0;
    }
}
