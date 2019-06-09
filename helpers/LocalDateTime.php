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

    public static function convertFromFull($dateTime, $dateTimeModel, $timeZone = 'Europe/Moscow')
    {
        try {
            $format = strpos($dateTime, ',') ? 'D, d M Y H:i:s O' : 'd M Y H:i:s O';


            $utcDate = \DateTime::createFromFormat(
                $format,
                $dateTime);

            if (!$utcDate instanceof \DateTime) {
                return $dateTimeModel;
            }

            $localDate = $utcDate;
            $localDate->setTimeZone(new \DateTimeZone($timeZone));
            return $localDate->format('Y-m-d H:i:s');
        } catch (\Throwable $e) {
            return $dateTimeModel;
        }



    }


}