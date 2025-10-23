<?php

declare(strict_types=1);

namespace Sirix\Monolog\Processor;

use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Sirix\Monolog\ContainerAwareInterface;
use Sirix\Monolog\ContainerTrait;
use Sirix\Monolog\FactoryInterface;
use Sirix\Redaction\Bridge\Monolog\RedactorProcessor;
use Sirix\Redaction\Enum\ObjectViewModeEnum;
use Sirix\Redaction\Redactor;
use Sirix\Redaction\RedactorInterface;

use function array_key_exists;
use function is_callable;
use function is_int;
use function is_string;

class RedactorProcessorFactory implements FactoryInterface, ContainerAwareInterface
{
    use ContainerTrait;

    /**
     * @param array<string, mixed> $options
     *
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(array $options): RedactorProcessor
    {
        $redactor = $this->getContainer()->has(RedactorInterface::class)
            ? $this->getContainer()->get(RedactorInterface::class)
            : $this->createRedactor($options);

        return new RedactorProcessor($redactor);
    }

    private function createRedactor(array $options): RedactorInterface
    {
        $rules = $options['rules'] ?? [];
        $useDefaultRules = $options['useDefaultRules'] ?? true;

        $redactor = new Redactor($rules, (bool) $useDefaultRules);

        if (isset($options['replacement']) && is_string($options['replacement'])) {
            $redactor->setReplacement($options['replacement']);
        }

        if (isset($options['template']) && is_string($options['template'])) {
            $redactor->setTemplate($options['template']);
        }

        if (array_key_exists('lengthLimit', $options)) {
            $lengthLimit = $options['lengthLimit'];
            if (null === $lengthLimit || is_int($lengthLimit)) {
                $redactor->setLengthLimit($lengthLimit);
            }
        }

        if (isset($options['objectViewMode']) && $options['objectViewMode'] instanceof ObjectViewModeEnum) {
            $redactor->setObjectViewMode($options['objectViewMode']);
        }

        if (array_key_exists('maxDepth', $options)) {
            $maxDepth = $options['maxDepth'];
            if (null === $maxDepth || is_int($maxDepth)) {
                $redactor->setMaxDepth($maxDepth);
            }
        }

        if (array_key_exists('maxItemsPerContainer', $options)) {
            $maxItemsPerContainer = $options['maxItemsPerContainer'];
            if (null === $maxItemsPerContainer || is_int($maxItemsPerContainer)) {
                $redactor->setMaxItemsPerContainer($maxItemsPerContainer);
            }
        }

        if (array_key_exists('maxTotalNodes', $options)) {
            $maxTotalNodes = $options['maxTotalNodes'];
            if (null === $maxTotalNodes || is_int($maxTotalNodes)) {
                $redactor->setMaxTotalNodes($maxTotalNodes);
            }
        }

        if (array_key_exists('onLimitExceededCallback', $options)) {
            $callback = $options['onLimitExceededCallback'];
            if (null === $callback || is_callable($callback)) {
                $redactor->setOnLimitExceededCallback($callback);
            }
        }

        if (array_key_exists('overflowPlaceholder', $options)) {
            $redactor->setOverflowPlaceholder($options['overflowPlaceholder']);
        }

        return $redactor;
    }
}
