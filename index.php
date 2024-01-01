<?php

// php -S localhost:3000 index.php

include_once 'vendor/autoload.php';

use src\Whatsapp\WhatsAppTextParser;

?>
<html>

<head>
    <style>
        table,
        th,
        td {
            border: 1px solid black;
        }
    </style>
</head>

<body>
    <form action="/" method="POST">
        <label for="message_contents">Message export:</label><br>
        <textarea name="message_contents" name="message_contents" placeholder="Place whatsapp message export here"></textarea><br>
        <input type="submit" value="Calculate">
    </form>
    <?php
    if (isset($_POST['message_contents']) && !empty($_POST['message_contents'])) {
    ?>
        <table>
            <thead>
                <th>Name</th>
                <th>Amount of shits</th>
                <th>Active days in chat</th>
                <th>Average shits per day</th>
                <th>Most shits per day</th>
                <th>Longest shit streak</th>
            </thead>
            <tbody>
            <?php
            $messages = WhatsAppTextParser::parseString($_POST['message_contents']);

            $senders = getAllSenders($messages);
            $dates = getAllDates($messages);

            foreach ($senders as $sender) {
                $shitCounter = getAmountOfShits($sender, $messages);

                // For people who do not poop
                if ($shitCounter == 0) {
                    td($sender);
                    td('This person does not poop');
                    td();
                    td();
                    td();
                    td();
                    endTag('tr');
                    continue;
                }

                $daysActive = getActiveDaysInChat($sender, $dates, $messages);
                [$mostShitsDate, $mostShitsNumber] = getMostShitsInOneDay($sender, $dates, $messages);
                [$streakBeginDate, $streakEndDate, $streakLength] = getLongestShitStreak($sender, $dates, $messages);

                startTag('tr');
                td($sender);

                // Days active
                td($shitCounter);

                // Active days in chat
                td(
                    sprintf(
                        '%d/%d days',
                        $daysActive,
                        count($dates)
                    )
                );

                // Average shits per day
                td(round(
                    ($shitCounter / $daysActive),
                    2
                ));

                // Most shits per day
                td(
                    sprintf(
                        'date: %s --> %s',
                        $mostShitsDate,
                        $mostShitsNumber
                    )
                );

                // Longest streak
                td(
                    sprintf(
                        '%s until %s --> %s',
                        $streakBeginDate,
                        $streakEndDate,
                        $streakLength,
                    )
                );

                endTag('tr');
            }
        }
            ?>
            </tbody>
</body>

</html>
