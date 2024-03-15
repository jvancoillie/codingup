<?php

namespace App\Challenge\Defis\Fourchelangue;

use App\Challenge\AbstractChallengeResolver;

/**
 * Class ChallengeResolver.
 *
 * @see https://pydefis.callicode.fr/defis/Fourchelangue/txt
 */
class ChallengeResolver extends AbstractChallengeResolver
{
    public const MAPPING = [
    'HFH' => 'A',
    'FFH' => 'B',
    'SHS' => 'C',
    'SHH' => 'D',
    'SSH' => 'E',
    'FHF' => 'F',
    'FSS' => 'G',
    'HFF' => 'H',
    'HHH' => 'IJ',
    'SFS' => 'K',
    'FFS' => 'L',
    'FHS' => 'M',
    'SSF' => 'N',
    'FHH' => 'O',
    'HHF' => 'P',
    'SFF' => 'Q',
    'FSF' => 'R',
    'FSH' => 'S',
    'HHS' => 'T',
    'FFF' => 'UV',
    'SSS' => 'W',
    'HFS' => 'X',
    'SHF' => 'Y',
    'SFH' => 'Z',
    ];

    public function main(): string
    {
        $text = '';
        $data = $this->getInput()->getData();

        while (strlen($data) > 0) {
            if (str_starts_with($data, 'HS')) {
                $data = substr($data, 2);
                $text .= ' ';
                continue;
            }

            $syllabe = substr($data, 0, 3);
            $text .= self::MAPPING[$syllabe];
            $data = substr($data, 3);
        }

        return $text;
    }
}
