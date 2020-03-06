<?php
declare(strict_types=1);

namespace EonX\EasyAsync\Helpers;

use EonX\EasyAsync\Interfaces\DateTimeGeneratorInterface;
use Nette\Utils\Strings;

final class PropertyHelper
{
    public static function getSetterName(string $property): string
    {
        return \sprintf('set%s', \str_replace('_', '', Strings::capitalize($property)));
    }

    /**
     * @param object $object
     * @param mixed[] $data
     * @param mixed[] $properties
     *
     * @throws \EonX\EasyAsync\Exceptions\UnableToGenerateDateTimeException
     */
    public static function setDatetimeProperties(
        $object,
        array $data,
        array $properties,
        DateTimeGeneratorInterface $datetime
    ): void {
        foreach ($properties as $property) {
            if (empty($data[$property] ?? null) === false) {
                $setter = static::getSetterName($property);
                $object->{$setter}($datetime->fromString($data[$property]));
            }
        }
    }

    /**
     * @param object $object
     * @param mixed[] $data
     * @param mixed[] $properties
     */
    public static function setIntProperties($object, array $data, array $properties): void
    {
        foreach ($properties as $property) {
            $setter = static::getSetterName($property);
            $object->{$setter}((int)($data[$property] ?? 0));
        }
    }

    /**
     * @param object $object
     * @param mixed[] $data
     * @param mixed[] $properties
     *
     * @throws \Nette\Utils\JsonException
     */
    public static function setJsonProperties($object, array $data, array $properties): void
    {
        foreach ($properties as $property) {
            $setter = static::getSetterName($property);
            $object->{$setter}(JsonHelper::decode($data[$property] ?? null));
        }
    }

    /**
     * @param object $object
     * @param mixed[] $data
     * @param mixed[] $properties
     */
    public static function setOptionalProperties($object, array $data, array $properties): void
    {
        foreach ($properties as $property) {
            if (isset($data[$property])) {
                $setter = static::getSetterName($property);
                $object->{$setter}($data[$property]);
            }
        }
    }
}
