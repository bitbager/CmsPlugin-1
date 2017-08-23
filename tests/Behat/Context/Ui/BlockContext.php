<?php

/**
 * This file was created by the developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * another great project.
 * You can find more information about us on https://bitbag.shop and write us
 * an email on kontakt@bitbag.pl.
 */

namespace Tests\BitBag\CmsPlugin\Behat\Context\Ui;

use Behat\Behat\Context\Context;
use BitBag\CmsPlugin\Entity\BlockInterface;
use BitBag\CmsPlugin\Repository\BlockRepositoryInterface;
use Sylius\Behat\NotificationType;
use Sylius\Behat\Page\SymfonyPageInterface;
use Sylius\Behat\Service\NotificationCheckerInterface;
use Sylius\Behat\Service\Resolver\CurrentPageResolverInterface;
use Sylius\Behat\Service\SharedStorageInterface;
use Tests\BitBag\CmsPlugin\Behat\Behaviour\Block;
use Tests\BitBag\CmsPlugin\Behat\Behaviour\Clickable;
use Tests\BitBag\CmsPlugin\Behat\Page\Admin\Block\CreatePageInterface;
use Tests\BitBag\CmsPlugin\Behat\Page\Admin\Block\IndexPageInterface;
use Tests\BitBag\CmsPlugin\Behat\Page\Admin\Block\UpdatePageInterface;
use Webmozart\Assert\Assert;

/**
 * @author Mikołaj Król <mikolaj.krol@bitbag.pl>
 */
final class BlockContext implements Context
{
    /**
     * @var IndexPageInterface|Clickable
     */
    private $indexPage;

    /**
     * @var CreatePageInterface
     */
    private $createPage;

    /**
     * @var UpdatePageInterface
     */
    private $updatePage;

    /**
     * @var SharedStorageInterface
     */
    private $sharedStorage;

    /**
     * @param IndexPageInterface $indexPage
     * @param CreatePageInterface $createPage
     * @param UpdatePageInterface $updatePage
     * @param CurrentPageResolverInterface $currentPageResolver
     * @param NotificationCheckerInterface $notificationChecker
     * @param BlockRepositoryInterface $blockRepository
     * @param SharedStorageInterface $sharedStorage
     */
    public function __construct(
        IndexPageInterface $indexPage,
        CreatePageInterface $createPage,
        UpdatePageInterface $updatePage,
        CurrentPageResolverInterface $currentPageResolver,
        NotificationCheckerInterface $notificationChecker,
        BlockRepositoryInterface $blockRepository,
        SharedStorageInterface $sharedStorage
    )
    {
        $this->createPage = $createPage;
        $this->updatePage = $updatePage;
        $this->indexPage = $indexPage;
        $this->currentPageResolver = $currentPageResolver;
        $this->notificationChecker = $notificationChecker;
        $this->blockRepository = $blockRepository;
        $this->sharedStorage = $sharedStorage;
    }

    /**
     * @var CurrentPageResolverInterface
     */
    private $currentPageResolver;

    /**
     * @var NotificationCheckerInterface
     */
    private $notificationChecker;

    /**
     * @var BlockRepositoryInterface
     */
    private $blockRepository;

    /**
     * @When I go to the cms blocks page
     */
    public function iGoToTheCmsBlocksPage()
    {
        $this->indexPage->open();
    }

    /**
     * @When I go to the create image block page
     */
    public function iGoToTheCreateImageBlockPage()
    {
        $this->createPage->open(['type' => BlockInterface::IMAGE_BLOCK_TYPE]);
    }

    /**
     * @When I go to the create text block page
     */
    public function iGoToTheCreateTextBlockPage()
    {
        $this->createPage->open(['type' => BlockInterface::TEXT_BLOCK_TYPE]);
    }

    /**
     * @When I go to the update :code block page
     */
    public function iGoToTheUpdateBlockPage($code)
    {
        $this->updatePage->open(['code' => $code]);
    }

    /**
     * @When I fill the code with :code
     */
    public function iFillTheCodeWith($code)
    {
        $this->resolveCurrentPage()->fillCode($code);
    }

    /**
     * @When I upload the :image image
     */
    public function iUploadTheImage($image)
    {
        $this->resolveCurrentPage()->uploadImage($image);
    }

    /**
     * @When I fill the content with :content
     */
    public function iFillTheContentWith($content)
    {
        $this->resolveCurrentPage()->fillContent($content);
    }

    /**
     * @When I click :name button
     */
    public function iClickButton($name)
    {
        $this->indexPage->clickButton($name);
    }

    /**
     * @When I remove this image block
     */
    public function iRemoveThisImageBlock()
    {
        /** @var BlockInterface $block */
        $block = $this->sharedStorage->get('block');
        $code = $block->getCode();

        $this->indexPage->removeBlock($code);
    }

    /**
     * @Then no dynamic content blocks should appear in the store
     */
    public function noDynamicContentBlocksShouldAppearInTheStore()
    {

    }

    /**
     * @Then I should be able to select between :firstType and :secondType block types
     */
    public function iShouldBeAbleToSelectBetweenAndBlockTypes($firstType, $secondType)
    {
        $this->indexPage->containsBlocksWithType($firstType, $secondType);
    }

    /**
     * @When I add it
     */
    public function iAddIt()
    {
        $this->createPage->add();
    }

    /**
     * @When I update it
     */
    public function iUpdateIt()
    {
        $this->updatePage->update();
    }

    /**
     * @Then I should see :number dynamic content blocks with :type type
     */
    public function iShouldSeeDynamicContentBlocksWithType($number, $type)
    {
        $this->indexPage->containsBlocksWithType($number, $type);
    }

    /**
     * @Then I should be notified that this block was removed
     */
    public function iShouldBeNotifiedThatThisBlockWasRemoved()
    {
        $this->notificationChecker->checkNotification("Block has been removed.", NotificationType::success());
    }

    /**
     * @Then I should be notified that new image block was created
     * @Then I should be notified that new text block was created
     */
    public function iShouldBeNotifiedThatNewImageBlockWasCreated()
    {
        $this->notificationChecker->checkNotification("Block has been created.", NotificationType::success());
    }

    /**
     * @Then I should be notified that the block was successfully updated
     */
    public function iShouldBeNotifiedThatTheBlockWasSuccessfullyUpdated()
    {
        $this->notificationChecker->checkNotification("Block has been updated.", NotificationType::success());
    }

    /**
     * @Then block with :type type and :content content should be in the store
     */
    public function blockWithTypeAndContentShouldBeInTheStore($type, $content)
    {
        $block = $this->blockRepository->findOneByTypeAndContent($type, $content);

        Assert::isInstanceOf($block, BlockInterface::class);
    }

    /**
     * @return CreatePageInterface|UpdatePageInterface|Block|SymfonyPageInterface
     */
    private function resolveCurrentPage()
    {
        return $this->currentPageResolver->getCurrentPageWithForm([$this->createPage, $this->updatePage]);
    }
}
