<?php

declare(strict_types=1);

namespace Speakeasy\Serializer\Handler;

use Speakeasy\Serializer\Exception\RuntimeException;
use Speakeasy\Serializer\GraphNavigatorInterface;
use Speakeasy\Serializer\SerializationContext;
use Speakeasy\Serializer\Visitor\DeserializationVisitorInterface;
use Speakeasy\Serializer\Visitor\SerializationVisitorInterface;
use Speakeasy\Serializer\XmlSerializationVisitor;

final class DateHandler implements SubscribingHandlerInterface
{
    /**
     * @var string
     */
    private $defaultSerializationFormat;

    /**
     * @var array<string>
     */
    private $defaultDeserializationFormats;

    /**
     * @var \DateTimeZone
     */
    private $defaultTimezone;

    /**
     * @var bool
     */
    private $xmlCData;

    /**
     * {@inheritdoc}
     */
    public static function getSubscribingMethods()
    {
        $methods = [];
        $types = [\DateTime::class, \DateTimeImmutable::class, \DateInterval::class];

        foreach (['json', 'xml'] as $format) {
            foreach ($types as $type) {
                $methods[] = [
                    'type' => $type,
                    'direction' => GraphNavigatorInterface::DIRECTION_DESERIALIZATION,
                    'format' => $format,
                ];
                $methods[] = [
                    'type' => $type,
                    'format' => $format,
                    'direction' => GraphNavigatorInterface::DIRECTION_SERIALIZATION,
                    'method' => 'serialize' . $type,
                ];
            }

            $methods[] = [
                'type' => \DateTimeInterface::class,
                'direction' => GraphNavigatorInterface::DIRECTION_DESERIALIZATION,
                'format' => $format,
                'method' => 'deserializeDateTimeFrom' . ucfirst($format),
            ];

            $methods[] = [
                'type' => \DateTimeInterface::class,
                'direction' => GraphNavigatorInterface::DIRECTION_SERIALIZATION,
                'format' => $format,
                'method' => 'serializeDateTimeInterface',
            ];
        }

        return $methods;
    }

    /**
     * @param array<string> $defaultDeserializationFormats
     */
    public function __construct(
        string $defaultFormat = \DateTime::ATOM,
        string $defaultTimezone = 'UTC',
        bool $xmlCData = true,
        array $defaultDeserializationFormats = []
    ) {
        $this->defaultSerializationFormat = $defaultFormat;
        $this->defaultDeserializationFormats = [] === $defaultDeserializationFormats ? [$defaultFormat] : $defaultDeserializationFormats;
        $this->defaultTimezone = new \DateTimeZone($defaultTimezone);
        $this->xmlCData = $xmlCData;
    }

    /**
     * @return \DOMCdataSection|\DOMText|mixed
     */
    public function serializeDateTimeInterface(
        SerializationVisitorInterface $visitor,
        \DateTimeInterface $date,
        array $type,
        SerializationContext $context
    ) {
        if ($visitor instanceof XmlSerializationVisitor && false === $this->xmlCData) {
            return $visitor->visitSimpleString($date->format($this->getSerializationFormat($type)), $type);
        }

        $format = $this->getSerializationFormat($type);
        if ('U' === $format) {
            return $visitor->visitInteger((int) $date->format($format), $type);
        }

        return $visitor->visitString($date->format($this->getSerializationFormat($type)), $type);
    }

    /**
     * @param array $type
     *
     * @return \DOMCdataSection|\DOMText|mixed
     */
    public function serializeDateTime(SerializationVisitorInterface $visitor, \DateTime $date, array $type, SerializationContext $context)
    {
        return $this->serializeDateTimeInterface($visitor, $date, $type, $context);
    }

    /**
     * @param array $type
     *
     * @return \DOMCdataSection|\DOMText|mixed
     */
    public function serializeDateTimeImmutable(
        SerializationVisitorInterface $visitor,
        \DateTimeImmutable $date,
        array $type,
        SerializationContext $context
    ) {
        return $this->serializeDateTimeInterface($visitor, $date, $type, $context);
    }

    /**
     * @param array $type
     *
     * @return \DOMCdataSection|\DOMText|mixed
     */
    public function serializeDateInterval(SerializationVisitorInterface $visitor, \DateInterval $date, array $type, SerializationContext $context)
    {
        $iso8601DateIntervalString = $this->format($date);

        if ($visitor instanceof XmlSerializationVisitor && false === $this->xmlCData) {
            return $visitor->visitSimpleString($iso8601DateIntervalString, $type);
        }

        return $visitor->visitString($iso8601DateIntervalString, $type);
    }

    /**
     * @param mixed $data
     */
    private function isDataXmlNull($data): bool
    {
        $attributes = $data->attributes('xsi', true);

        return isset($attributes['nil'][0]) && 'true' === (string) $attributes['nil'][0];
    }

    /**
     * @param mixed $data
     * @param array $type
     */
    public function deserializeDateTimeFromXml(DeserializationVisitorInterface $visitor, $data, array $type): ?\DateTimeInterface
    {
        if ($this->isDataXmlNull($data)) {
            return null;
        }

        return $this->parseDateTime($data, $type);
    }

    /**
     * @param mixed $data
     * @param array $type
     */
    public function deserializeDateTimeImmutableFromXml(DeserializationVisitorInterface $visitor, $data, array $type): ?\DateTimeInterface
    {
        if ($this->isDataXmlNull($data)) {
            return null;
        }

        return $this->parseDateTime($data, $type, true);
    }

    /**
     * @param mixed $data
     * @param array $type
     */
    public function deserializeDateIntervalFromXml(DeserializationVisitorInterface $visitor, $data, array $type): ?\DateInterval
    {
        if ($this->isDataXmlNull($data)) {
            return null;
        }

        return $this->parseDateInterval((string) $data);
    }

    /**
     * @param mixed $data
     * @param array $type
     */
    public function deserializeDateTimeFromJson(DeserializationVisitorInterface $visitor, $data, array $type): ?\DateTimeInterface
    {
        if (null === $data) {
            return null;
        }

        return $this->parseDateTime($data, $type);
    }

    /**
     * @param mixed $data
     * @param array $type
     */
    public function deserializeDateTimeImmutableFromJson(DeserializationVisitorInterface $visitor, $data, array $type): ?\DateTimeInterface
    {
        if (null === $data) {
            return null;
        }

        return $this->parseDateTime($data, $type, true);
    }

    /**
     * @param mixed $data
     * @param array $type
     */
    public function deserializeDateIntervalFromJson(DeserializationVisitorInterface $visitor, $data, array $type): ?\DateInterval
    {
        if (null === $data) {
            return null;
        }

        return $this->parseDateInterval($data);
    }

    /**
     * @param mixed $data
     * @param array $type
     */
    private function parseDateTime($data, array $type, bool $immutable = false): \DateTimeInterface
    {
        $timezone = !empty($type['params'][1]) ? new \DateTimeZone($type['params'][1]) : $this->defaultTimezone;
        $formats = $this->getDeserializationFormats($type);

        $formatTried = [];
        foreach ($formats as $format) {
            if ($immutable) {
                $datetime = \DateTimeImmutable::createFromFormat($format, (string) $data, $timezone);
            } else {
                $datetime = \DateTime::createFromFormat($format, (string) $data, $timezone);
            }

            if (false !== $datetime) {
                if ('U' === $format) {
                    $datetime = $datetime->setTimezone($timezone);
                }

                return $datetime;
            }

            $formatTried[] = $format;
        }

        throw new RuntimeException(sprintf(
            'Invalid datetime "%s", expected one of the format %s.',
            $data,
            '"' . implode('", "', $formatTried) . '"',
        ));
    }

    private function parseDateInterval(string $data): \DateInterval
    {
        $dateInterval = null;
        try {
            $f = 0.0;
            if (preg_match('~\.\d+~', $data, $match)) {
                $data = str_replace($match[0], '', $data);
                $f = (float) $match[0];
            }

            $dateInterval = new \DateInterval($data);
            $dateInterval->f = $f;
        } catch (\Throwable $e) {
            throw new RuntimeException(sprintf('Invalid dateinterval "%s", expected ISO 8601 format', $data), 0, $e);
        }

        return $dateInterval;
    }

    private function getDeserializationFormats(array $type): array
    {
        if (isset($type['params'][2])) {
            return is_array($type['params'][2]) ? $type['params'][2] : [$type['params'][2]];
        }

        if (isset($type['params'][0])) {
            return [$type['params'][0]];
        }

        return $this->defaultDeserializationFormats;
    }

    private function getSerializationFormat(array $type): string
    {
        return $type['params'][0] ?? $this->defaultSerializationFormat;
    }

    public function format(\DateInterval $dateInterval): string
    {
        $format = 'P';

        if (0 < $dateInterval->y) {
            $format .= $dateInterval->y . 'Y';
        }

        if (0 < $dateInterval->m) {
            $format .= $dateInterval->m . 'M';
        }

        if (0 < $dateInterval->d) {
            $format .= $dateInterval->d . 'D';
        }

        if (0 < $dateInterval->h || 0 < $dateInterval->i || 0 < $dateInterval->s) {
            $format .= 'T';
        }

        if (0 < $dateInterval->h) {
            $format .= $dateInterval->h . 'H';
        }

        if (0 < $dateInterval->i) {
            $format .= $dateInterval->i . 'M';
        }

        if (0 < $dateInterval->s) {
            $format .= $dateInterval->s . 'S';
        }

        if ('P' === $format) {
            $format = 'P0DT0S';
        }

        return $format;
    }
}
