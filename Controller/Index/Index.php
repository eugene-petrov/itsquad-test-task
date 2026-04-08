<?php
/**
 * Aeqet Pokedex — frontend controller
 */

declare(strict_types=1);

namespace Aeqet\Pokedex\Controller\Index;

use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\View\Result\Page;
use Magento\Framework\View\Result\PageFactory;

class Index implements HttpGetActionInterface
{
    /**
     * @param PageFactory $pageFactory
     */
    public function __construct(
        private readonly PageFactory $pageFactory
    ) {
    }

    /**
     * Render the Pokédex search page.
     *
     * @return Page
     */
    public function execute(): Page
    {
        return $this->pageFactory->create();
    }
}
