<?php

declare(strict_types=1);

namespace FastPix\Sdk\Serializer\Annotation;

/**
 * @Annotation
 * @Target({"PROPERTY","METHOD","ANNOTATION"})
 */
#[\Attribute(\Attribute::TARGET_METHOD | \Attribute::TARGET_PROPERTY)]
final class Groups implements SerializerAttribute
{
    use AnnotationUtilsTrait;

    /** @var array<string> @Required */
    public $groups = [];

    // NOSONAR php:S1172 - constructor parameters are consumed via get_defined_vars() to map attribute arguments to properties.
    public function __construct(array $values = [], array $groups = [])
    {
        $vars = get_defined_vars();
        /*
            if someone wants to set as Groups(['value' => '...']) this check will miserably fail (only one group with 'value' as only key).
            That is because doctrine annotations uses for @Groups("abc") the same values content (buy validation will fail since groups has to be an array).
            All the other cases should work as expected.
            The alternative here is to use the explicit syntax  Groups(groups=['value' => '...'])
        */
        if (!empty($values) && ((!isset($values['value']) && !isset($values['groups'])) || count($values) > 1) && empty($groups)) {
            $vars['groups'] = $values;
            $vars['values'] = [];
        }

        $this->loadAnnotationParameters($vars);
    }
}
