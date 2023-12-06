<?php

namespace App\Cards;

class CompareRank
{

    /**
     * @param array<int<0,max>,array<int|string>> $hand
     */
    public function isStraight(array $hand): bool
    {
        usort($hand, function ($current, $next) {
            return (int)$current['rank'] - (int)$next['rank'];
        });

        $max = count($hand);
        for ($i = 0; $i < $max - 1; $i++) {
            if ((int)$hand[$i + 1]['rank'] -  (int)$hand[$i]['rank'] != 1) {
                return false;
            }
        }

        return true;
    }

    /**
     * @param array<int<0,max>,array<int|string>> $hand
     */
    public function isRoyalFlush(array $hand, bool $handUnique): bool
    {
        return array_sum(array_column($hand, 'rank')) == 60 && $handUnique;
    }

    function isStraightFlush(bool $isStraight, bool $handUnique): bool
    {
        return $isStraight && $handUnique;
    }

    /**
     * @param array<int> $handCount
     */
    public function isFourOfAKind(array $handCount): bool
    {
        return in_array(4, $handCount);
    }

    /**
     * @param array<int> $handCount
     */
    public function isFullHouse(array $handCount): bool
    {
        return in_array(3, $handCount) && in_array(2, $handCount);
    }

    /**
     * @param array<int> $handCount
     */
    public function isThreeOfAKind(array $handCount): bool
    {
        return in_array(3, $handCount);
    }

    /**
     * @param array<int> $handCount
     */
    public function isTwoPair(array $handCount): bool
    {
        return count(array_keys($handCount, 2)) == 2;
    }

    /**
     * @param array<int> $handCount
     */
    public function isOnePair(array $handCount): bool
    {
        return in_array(2, $handCount);
    }

    /**
     * @param array<int,int|string> $player
     * @param array<int,int|string> $com
     */
    public function compareHighCard(array $player, array $com): string
    {
        if (max($player) > max($com)) {
            return "Spelaren vann";
        } elseif (max($com) > max($player)) {
            return "Datorn vann";
        }

        $max = count($player);
        for ($i = 0; $i < $max; $i++) {
            if ((int)$player[$i] > (int)$com[$i]) {
                return "Spelaren vann";
            } elseif ((int)$com[$i] > (int)$player[$i]) {
                return "Datorn vann";
            }
        }

        return "Oavgjort";
    }

    /**
     * @param array<int> $playerCount
     * @param array<int> $comCount
     */
    public function compareOnePair(array $playerCount, array $comCount): string
    {
        if ((int)array_search(2, $playerCount) > (int)array_search(2, $comCount)) {
            return "Spelaren vann";
        } else if ((int)array_search(2, $comCount) > (int)array_search(2, $playerCount)) {
            return "Datorn vann";
        }

        $playerKick = array_keys(array_filter($playerCount, function($item){
            return $item == 1;
        }));
        $comKick = array_keys(array_filter($comCount, function($item){
            return $item == 1;
        }));

        rsort($playerKick);
        rsort($comKick);

        $max = count($playerKick);
        for ($i = 0; $i < $max; $i++) {
            if ($playerKick[$i] > $comKick[$i]) {
                return "Spelaren vann";
            } else if ($comKick[$i] > $playerKick[$i]) {
                return "Datorn vann";
            }
        }

        return "Oavgjort";
    }

    /**
     * @param array<int> $playerCount
     * @param array<int> $comCount
     */
    public function compareTwoPair(array $playerCount, array $comCount): string
    {
        if ((int)array_sum((array)array_search(2, $playerCount)) > (int)array_sum((array)array_search(2, $comCount))) {
            return "Spelaren vann";
        } elseif ((int)array_sum((array)array_search(2, $comCount)) > (int)array_sum((array)array_search(2, $playerCount))) {
            return "Datorn vann";
        }

        if (array_search(1, $playerCount) > array_search(1, $comCount)) {
            return "Spelaren vann";
        } elseif (array_search(1, $comCount) > array_search(1, $playerCount)) {
            return "Datorn vann";
        }

        return "Oavgjort";
    }

    /**
     * @param array<int> $playerCount
     * @param array<int> $comCount
     */
    public function compareThreeOfAKind(array $playerCount, array $comCount): string
    {
        if (array_search(3, $playerCount) > array_search(3, $comCount)) {
            return "Spelaren vann";
        } elseif (array_search(3, $comCount) > array_search(3, $playerCount)) {
            return "Datorn vann";
        }
        return "Oavgjort";
    }

    /**
     * @param array<int> $playerCount
     * @param array<int> $comCount
     */
    public function compareFullHouse(array $playerCount, array $comCount): string
    {
        if (array_search(3, $playerCount) > array_search(3, $comCount)) {
            return "Spelaren vann";
        } elseif (array_search(3, $comCount) > array_search(3, $playerCount)) {
            return "Datorn vann";
        }

        if (array_search(2, $playerCount) > array_search(2, $comCount)) {
            return "Spelaren vann";
        } elseif (array_search(2, $comCount) > array_search(2, $playerCount)) {
            return "Datorn vann";
        }

        return "Oavgjort";
    }

    /**
     * @param array<int> $playerCount
     * @param array<int> $comCount
     */
    public function compareFourOfAKind(array $playerCount, array $comCount): string
    {
        if (array_search(4, $playerCount) > array_search(4, $comCount)) {
            return "Spelaren vann";
        } elseif (array_search(4, $comCount) > array_search(4, $playerCount)) {
            return "Datorn vann";
        }

        return "Oavgjort";
    }
}
