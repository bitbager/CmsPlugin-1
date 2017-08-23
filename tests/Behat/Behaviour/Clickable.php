<?php

/**
 * This file was created by the developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * another great project.
 * You can find more information about us on https://bitbag.shop and write us
 * an email on kontakt@bitbag.pl.
 */

namespace Tests\BitBag\CmsPlugin\Behat\Behaviour;

use Sylius\Behat\Behaviour\DocumentAccessor;

/**
 * @author Mikołaj Król <mikolaj.krol@bitbag.pl>
 */
trait Clickable
{
    use DocumentAccessor;

    /**
     * @param string $buttonName
     */
    public function clickButton($buttonName)
    {
        $this->getDocument()->pressButton($buttonName);
    }
}