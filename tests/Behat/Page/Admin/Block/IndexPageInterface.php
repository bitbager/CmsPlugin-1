<?php

/**
 * This file was created by the developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * another great project.
 * You can find more information about us on https://bitbag.shop and write us
 * an email on kontakt@bitbag.pl.
 */

namespace Tests\BitBag\CmsPlugin\Behat\Page\Admin\Block;

use Sylius\Behat\Page\Admin\Crud\IndexPageInterface as BaseIndexPageInterface;

/**
 * @author Mikołaj Król <mikolaj.krol@bitbag.pl>
 */
interface IndexPageInterface extends BaseIndexPageInterface
{
    /**
     * @param int $number
     * @param string $type
     *
     * @return bool
     */
    public function containsBlocksWithType($number, $type);

    /**
     * @param string $code
     */
    public function removeBlock($code);

    /**
     * @param array ...$blockTypes
     *
     * @throws
     */
    public function shouldContainBlockTypes(...$blockTypes);
}