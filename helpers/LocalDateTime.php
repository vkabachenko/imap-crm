<?php


namespace app\helpers;


class LocalDateTime
{
    public static function convertFromUtc($dateTime, $timeZone = 'Europe/Moscow')
    {
        $utcDate = \DateTime::createFromFormat(
            'Y-m-d H:i:s',
            $dateTime,
            new \DateTimeZone('UTC'));

        $localDate = $utcDate;
        $localDate->setTimeZone(new \DateTimeZone($timeZone));

        return $localDate->format('Y-m-d H:i:s');
    }
}