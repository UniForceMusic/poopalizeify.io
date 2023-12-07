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
