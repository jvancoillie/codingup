<?php echo "<?php\n"; ?>

namespace <?php echo $namespace; ?>;

use App\Challenge\AbstractChallengeResolver;

/**
 * Class ChallengeResolver.
 *
 * @see <?php echo $challengeLink; ?><?php echo "\n"; ?>
 */
class ChallengeResolver extends AbstractChallengeResolver
{
    public function main()
    {
        $ans = 0;

        $data = $this->getInput()->getArrayData();

        return $ans;
    }
}
