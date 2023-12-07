<?php

use src\Whatsapp\WhatsAppMessage;

function arrayUnique(array $array): array
{
    return array_values(array_unique($array));
}

function getAllSenders(array $whatsAppMessages): array
{
    $senders = [];

    foreach ($whatsAppMessages as $whatsAppMessage) {
        /** @var WhatsAppMessage $message */
        $senders[] = $whatsAppMessage->sender;
    }

    return arrayUnique($senders);
}

function getAllDates(array $whatsAppMessages): array
{
    $dates = [];

    foreach ($whatsAppMessages as $whatsAppMessage) {
        /** @var WhatsAppMessage $message */
        $dates[] = $whatsAppMessage->getDate();
    }

    return arrayUnique($dates);
}

function getAmountOfShits(string $sender, array $messages): int
{
    $shitCounter = 0;

    foreach ($messages as $message) {
        /** @var WhatsAppMessage $message */
        if ($message->matchesSender($sender) && $message->containsCheck()) {
            $shitCounter++;
        }
    }

    return $shitCounter;
}

function getActiveDaysInChat(string $sender, array $dates, array $messages): int
{
    $daysActive = 0;

    foreach ($dates as $date) {
        foreach ($messages as $message) {
            /** @var WhatsAppMessage $message */
            if ($message->matchesSender($sender) && $message->matchesDate($date)) {
                $daysActive++;
                break;
            }
        }
    }

    return $daysActive;
}

function getMostShitsInOneDay(string $sender, array $dates, array $messages): array
{
    $mostShitsDate = '';
    $mostShitsNumber = 0;

    foreach ($dates as $date) {
        $shits = 0;

        foreach ($messages as $message) {
            /** @var WhatsAppMessage $message */
            if ($message->matchesSender($sender) && $message->matchesDate($date) && $message->containsCheck()) {
                $shits++;
            }
        }

        if ($shits > $mostShitsNumber) {
            $mostShitsDate = $date;
            $mostShitsNumber = $shits;
        }
    }

    return [str_replace('-', ' ', $mostShitsDate), $mostShitsNumber];
}

function getLongestShitStreak(string $sender, array $dates, array $messages): array
{
    $datesWithShitBool = [];

    foreach ($dates as $date) {
        $datesWithShitBool[$date] = false;

        foreach ($messages as $message) {
            /** @var WhatsAppMessage $message */
            if ($message->matchesSender($sender) && $message->matchesDate($date) && $message->containsCheck()) {
                $datesWithShitBool[$date] = true;
                break;
            }
        }
    }

    $beginDate = '';
    $endDate = '';
    $currentStreak = 0;

    $highestBeginDate = '';
    $highestEndDate = '';
    $highestStreakNumber = $currentStreak;

    $firstLoop = true;

    foreach ($datesWithShitBool as $date => $hasShit) {
        if ($firstLoop && $hasShit) {
            $currentStreak = 0;
            $beginDate = $date;
            $firstLoop = false;
        }

        if ($hasShit) {
            $currentStreak++;
            $endDate = $date;
        } else {
            $firstLoop = true;
        }

        if ($currentStreak > $highestStreakNumber) {
            $highestStreakNumber = $currentStreak;
            $highestBeginDate = $beginDate;
            $highestEndDate = $endDate;
        }
    }

    return [
        $highestBeginDate,
        $highestEndDate,
        $highestStreakNumber
    ];
}

function printLn(mixed $content): void
{
    if (is_array($content) || is_object($content)) {
        echo json_encode($content);
        echo PHP_EOL;
        return;
    }

    echo (string) $content;
    echo PHP_EOL;
}

function printFLn(string $template, ...$args): void
{
    echo sprintf($template, ...$args);
    echo PHP_EOL;
}
