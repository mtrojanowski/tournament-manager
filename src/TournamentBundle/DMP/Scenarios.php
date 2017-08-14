<?php
namespace TournamentBundle\DMP;

use function MongoDB\server_supports_feature;

class Scenarios
{
    const HOLD = 'Hold the Ground';
    const BREAK = 'Breakthrough';
    const CAPTURE = 'Capture the Flags';
    const SECURE = 'Secure Target';

    const SCENARIOS = [
        1 => [
            'Pairing 1' => self::HOLD,
            'Pairing 2' => self::HOLD,
            'Pairing 3' => self::BREAK,
            'Pairing 4' => self::BREAK,
            'Pairing 5' => self::CAPTURE,
        ],
        2 => [
            'Pairing 1' => self::SECURE,
            'Pairing 2' => self::SECURE,
            'Pairing 3' => self::HOLD,
            'Pairing 4' => self::HOLD,
            'Pairing 5' => self::BREAK,
        ],
        3 => [
            'Pairing 1' => self::CAPTURE,
            'Pairing 2' => self::CAPTURE,
            'Pairing 3' => self::SECURE,
            'Pairing 4' => self::SECURE,
            'Pairing 5' => self::HOLD,
        ],
        4 => [
            'Pairing 1' => self::BREAK,
            'Pairing 2' => self::BREAK,
            'Pairing 3' => self::CAPTURE,
            'Pairing 4' => self::CAPTURE,
            'Pairing 5' => self::SECURE,
        ],
        5 => [
            'Pairing 1' => self::HOLD,
            'Pairing 2' => self::HOLD,
            'Pairing 3' => self::BREAK,
            'Pairing 4' => self::BREAK,
            'Pairing 5' => self::CAPTURE,
        ],
        ];
}
