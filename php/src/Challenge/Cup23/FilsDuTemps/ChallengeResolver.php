<?php

namespace App\Challenge\Cup23\FilsDuTemps;

use App\Challenge\AbstractChallengeResolver;
use App\Utils\Image;
use Symfony\Component\HttpClient\HttpClient;

/**
 * Class ChallengeResolver.
 *
 * @see https://pydefis.callicode.fr/defis/C23_FilsDuTemps/txt
 */
class ChallengeResolver extends AbstractChallengeResolver
{
    public const DIRECTIONS = [[0, 1], [0, -1], [1, 0], [-1, 0]];

    public function main()
    {
        $client = HttpClient::create();

        $response = $client->request(
            'GET',
            'https://pydefis.callicode.fr/defis/C23_FilsDuTemps/get/kami/2f2ac',
        );

        $content = explode("\n", $response->getContent());
        $json = json_decode($content[0], true);

        $data = base64_decode(
            $json['image']
        );

        $source = __DIR__.'/input/fil.png';

        file_put_contents($source, $data);

        $image = Image::createFromPNG($source);
        $graph = [];

        for ($x = 0; $x < $image->getWidth(); ++$x) {
            for ($y = 0; $y < $image->getHeight(); ++$y) {
                $colorat = $image->getColorat($x, $y);
                if (0 !== $colorat) {
                    continue;
                }
                $graph["$x-$y"] = [];
                foreach (self::DIRECTIONS as [$dx, $dy]) {
                    $nx = $x + $dx;
                    $ny = $y + $dy;
                    if ($nx < 0 || $nx > $image->getWidth() || $ny < 0 || $ny > $image->getHeight()) {
                        continue;
                    }
                    if (0 !== $image->getColorat($nx, $ny)) {
                        continue;
                    }
                    $graph["$x-$y"][] = "$nx-$ny";
                }
            }
        }

        $visited = [];
        $paths = [];
        foreach ($graph as $node => $neighbors) {
            if (in_array($node, $visited)) {
                continue;
            }

            $queue = new \SplQueue();

            // Enqueue the path
            $queue->enqueue($node);
            $visited[] = $node;
            $path = [];
            while ($queue->count() > 0) {
                $toCheck = $queue->dequeue();
                $path[] = $toCheck;
                foreach ($graph[$toCheck] as $neighbor) {
                    if (in_array($neighbor, $visited)) {
                        continue;
                    }
                    $visited[] = $neighbor;
                    $queue->enqueue($neighbor);
                }
            }

            $paths[] = $path;
        }

        $max = max(array_map(fn ($path) => count($path), $paths));

        $data = [
            'signature' => $json['signature'],
            'n' => count($paths),
            'taille' => $max,
        ];

        $response = $client->request(
            'POST',
            'https://pydefis.callicode.fr/defis/C23_FilsDuTemps/post/kami/2f2ac',
            [
                'json' => $data,
            ]
        );

        return $response->getContent();
    }
}
