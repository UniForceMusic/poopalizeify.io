<?php

namespace src\Whatsapp;

use Exception;

class WhatsAppTextParser
{
    public const MESSAGE_SPLIT_REGEX = '/([0-9]{2}-[0-9]{2}-[0-9]{4} [0-9]{2}\:[0-9]{2}) - (.[a-z|A-Z|0-9| |]*)\: (.*)/';

    public static function parseTxt(string $filePath): array
    {
        $fileContents = static::getFileContents($filePath);

        $match = preg_match_all(static::MESSAGE_SPLIT_REGEX, $fileContents, $matches, PREG_PATTERN_ORDER);
        if (!$match) {
            throw new Exception('Unable to parse messages');
        }

        $groupedMatches = static::groupMatches($matches);

        return array_map(
            function (array $matches): WhatsAppMessage {
                return WhatsAppMessage::parseFromMatches($matches);
            },
            $groupedMatches
        );
    }

    protected static function getFileContents(string $filePath): string
    {
        if (!file_exists($filePath)) {
            throw new Exception('File does not exist');
        }

        return file_get_contents($filePath);
    }

    protected static function groupMatches(array $matches): array
    {
        /**
         * Preg_match_all groups all the different kind of matches in one array.
         * So in this case you have
         * [
         *    [
         *       "full match 1",
         *       "full match 2"
         *    ],
         *    [
         *       "timestamp match 1",
         *       "timestamp match 2"
         *    ],
         * ]
         * 
         * Which does not match the behaviour expected as shown on regex101.com
         * 
         * To mitigate this, this function groups them in matched groups like this:
         * [
         *    "full match",
         *    "timestamp match",
         *    "sender match",
         *    "message match",
         * ]
         */
        $fullMatches = $matches[0];
        $timestampMatches = $matches[1];
        $senderMatches = $matches[2];
        $messageMatches = $matches[3];

        $groupedMatches = [];

        foreach ($fullMatches as $index => $match) {
            $groupedMatches[] = [
                $match,
                $timestampMatches[$index],
                $senderMatches[$index],
                $messageMatches[$index]
            ];
        }

        return $groupedMatches;
    }
}
