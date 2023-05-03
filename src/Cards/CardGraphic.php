<?php

namespace App\Cards;

class CardGraphic extends Card
{
    private const cards = [
        "heart-ace" => "🂱",
        "heart-2" => "🂲",
        "heart-3" => "🂳",
        "heart-4" => "🂴",
        "heart-5" => "🂵",
        "heart-6" => "🂶",
        "heart-7" => "🂷",
        "heart-8" => "🂸",
        "heart-9" => "🂹",
        "heart-10" => "🂺",
        "heart-jack" => "🂻",
        "heart-queen" => "🂽",
        "heart-king" => "🂾",
        "spade-ace" => "🂡",
        "spade-2" => "🂢",
        "spade-3" => "🂣",
        "spade-4" => "🂤",
        "spade-5" => "🂥",
        "spade-6" => "🂦",
        "spade-7" => "🂧",
        "spade-8" => "🂨",
        "spade-9" => "🂩",
        "spade-10" => "🂪",
        "spade-jack" => "🂫",
        "spade-queen" => "🂭",
        "spade-king" => "🂮",
        "diamond-ace" => "🃁",
        "diamond-2" => "🃂",
        "diamond-3" => "🃃",
        "diamond-4" => "🃄",
        "diamond-5" => "🃅",
        "diamond-6" => "🃆",
        "diamond-7" => "🃇",
        "diamond-8" => "🃈",
        "diamond-9" => "🃉",
        "diamond-10" => "🃊",
        "diamond-jack" => "🃋",
        "diamond-queen" => "🃍",
        "diamond-king" => "🃎",
        "club-ace" => "🃑",
        "club-2" => "🃒",
        "club-3" => "🃓",
        "club-4" => "🃔",
        "club-5" => "🃕",
        "club-6" => "🃖",
        "club-7" => "🃗",
        "club-8" => "🃘",
        "club-9" => "🃙",
        "club-10" => "🃚",
        "club-jack" => "🃛",
        "club-queen" => "🃝",
        "club-king" => "🃞",
    ];

    public function __construct(
        string $suite,
        string $rank
    ) {
        parent::__construct($suite, $rank);
    }

    public function getCardRep(): string
    {
        return self::cards[$this->suite . "-" . $this->rank];
    }
}
