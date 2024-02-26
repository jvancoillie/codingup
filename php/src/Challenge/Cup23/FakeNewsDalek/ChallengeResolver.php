<?php

namespace App\Challenge\Cup23\FakeNewsDalek;

use App\Challenge\AbstractChallengeResolver;

/**
 * Class ChallengeResolver.
 *
 * @see https://pydefis.callicode.fr/defis/C23_FakeNewsDalek/txt
 */
class ChallengeResolver extends AbstractChallengeResolver
{
    public function main()
    {
        $filename = __DIR__.'/input/message_dalek.mp3';

        $getID3 = new \getID3();
        $fileInfo = $getID3->analyze($filename);

        $pos = array_map(fn ($e) => hexdec($e), str_split($fileInfo['tags']['id3v2']['comment'][0], 3));

        $fp = fopen($filename, 'r');

        $ans = '';

        foreach ($pos as $p) {
            fseek($fp, $p);
            $ans .= fgets($fp, 2);
        }

        return $ans;
    }
}
