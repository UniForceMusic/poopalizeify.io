<?php

include_once 'vendor/autoload.php';

use src\Whatsapp\WhatsAppTextParser;

$messages = WhatsAppTextParser::parseTxt('input.txt');

$senders = getAllSenders($messages);
$dates = getAllDates($messages);

foreach ($senders as $sender) {
    $shitCounter = getAmountOfShits($sender, $messages);
    $daysActive = getActiveDaysInChat($sender, $dates, $messages);
    [$mostShitsDate, $mostShitsNumber] = getMostShitsInOneDay($sender, $dates, $messages);
    [$streakBeginDate, $streakEndDate, $streakLength] = getLongestShitStreak($sender, $dates, $messages);

    printFLn('%s:', $sender);
    printFLn(
        ' - shit %dx since creating the group chat',
        $shitCounter
    );
    printFLn(
        ' - has been active in the group chat for %d/%d days',
        $daysActive,
        count($dates)
    );
    printFLn(
        ' - this means this person shit an average of %s per day',
        round(
            ($shitCounter / $daysActive),
            2
        )
    );
    printFLn(
        ' - the most brutal date for this person was %s with %d shits',
        $mostShitsDate,
        $mostShitsNumber
    );
    printFLn(
        ' - the longest shit streak of this person was %d days from %s until %s',
        $streakLength,
        $streakBeginDate,
        $streakEndDate
    );
    printLn('');
}
