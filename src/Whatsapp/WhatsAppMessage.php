<?php

namespace src\Whatsapp;

use DateTime;

class WhatsAppMessage
{
    public const WHATSAPP_TIMESTAMP_FORMAT = 'd-m-Y H:i'; // 23-11-2023 22:53;
    public const DATE_ONLY_FORMAT = 'Y-m-d';
    public const CHECK_MARK = '✅';

    public DateTime $sentAt;
    public string $sender;
    public string $message;

    public static function parseFromMatches(array $matches): static
    {
        // $matches[0] = '23-11-2023 22:53 - Firstname Lastname: Message content';

        return new static(
            $matches[1], // 23-11-2023 22:53
            $matches[2], // Firstname Lastname
            $matches[3], // Message content
        );
    }

    public function __construct(string $sentAt, string $sender, string $message)
    {
        $this->sentAt = $this->parseSentAt($sentAt);
        $this->sender = $sender;
        $this->message = $message;
    }

    public function getDate(): string
    {
        return $this->sentAt->format($this::DATE_ONLY_FORMAT);
    }

    public function containsCheck(): bool
    {
        return str_contains($this->message, $this::CHECK_MARK);
    }

    public function matchesDate(string $yyyymmddString): bool
    {
        $dateString = $this->getDate();

        return ($dateString == $yyyymmddString);
    }

    public function matchesSender(string $sender): bool
    {
        return ($this->sender == $sender);
    }

    protected function parseSentAt(string $sentAt): DateTime
    {
        return DateTime::createFromFormat(
            $this::WHATSAPP_TIMESTAMP_FORMAT,
            $sentAt
        );
    }
}
