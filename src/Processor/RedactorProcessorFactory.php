<?php

declare(strict_types=1);

namespace Sirix\Monolog\Processor;

use Sirix\Monolog\FactoryInterface;
use Sirix\Monolog\Redaction\Enum\ObjectViewModeEnum;
use Sirix\Monolog\Redaction\RedactorProcessor;

use function array_key_exists;
use function is_array;
use function is_bool;
use function is_callable;
use function is_int;
use function is_string;

class RedactorProcessorFactory implements FactoryInterface
{
    /**
     * @param array<string, mixed> $options
     */
    public function __invoke(array $options): RedactorProcessor
    {
        $rules = $options['rules'] ?? [];
        $useDefaultRules = $options['useDefaultRules'] ?? true;

        if (! is_array($rules)) {
            $rules = [];
        }

        $processor = new RedactorProcessor($rules, (bool) $useDefaultRules);

        if (isset($options['replacement']) && is_string($options['replacement'])) {
            $processor->setReplacement($options['replacement']);
        }

        if (isset($options['template']) && is_string($options['template'])) {
            $processor->setTemplate($options['template']);
        }

        if (isset($options['processObjects']) && is_bool($options['processObjects'])) {
            $processor->setProcessObjects($options['processObjects']);
        }

        if (array_key_exists('lengthLimit', $options)) {
            $lengthLimit = $options['lengthLimit'];
            if (null === $lengthLimit || is_int($lengthLimit)) {
                $processor->setLengthLimit($lengthLimit);
            }
        }

        if (isset($options['objectViewMode']) && $options['objectViewMode'] instanceof ObjectViewModeEnum) {
            $processor->setObjectViewMode($options['objectViewMode']);
        }

        if (array_key_exists('maxDepth', $options)) {
            $maxDepth = $options['maxDepth'];
            if (null === $maxDepth || is_int($maxDepth)) {
                $processor->setMaxDepth($maxDepth);
            }
        }

        if (array_key_exists('maxItemsPerContainer', $options)) {
            $maxItemsPerContainer = $options['maxItemsPerContainer'];
            if (null === $maxItemsPerContainer || is_int($maxItemsPerContainer)) {
                $processor->setMaxItemsPerContainer($maxItemsPerContainer);
            }
        }

        if (array_key_exists('maxTotalNodes', $options)) {
            $maxTotalNodes = $options['maxTotalNodes'];
            if (null === $maxTotalNodes || is_int($maxTotalNodes)) {
                $processor->setMaxTotalNodes($maxTotalNodes);
            }
        }

        if (array_key_exists('onLimitExceededCallback', $options)) {
            $callback = $options['onLimitExceededCallback'];
            if (null === $callback || is_callable($callback)) {
                $processor->setOnLimitExceededCallback($callback);
            }
        }

        if (array_key_exists('overflowPlaceholder', $options)) {
            $processor->setOverflowPlaceholder($options['overflowPlaceholder']);
        }

        return $processor;
    }
}
