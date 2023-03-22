<?php

declare(strict_types=1);

namespace Creatuity\OrderStatusAdjust\Controller\Adminhtml\Index;

use Magento\Backend\App\Action;

class Index extends Action
{
    public function execute(): void
    {
        $this->_view->loadLayout();

        $this->_addBreadcrumb(__('Order Status Adjust Rules'), __('Order Status Adjust Rules'));

        $this->_view->getPage()->getConfig()->getTitle()->prepend(__('Order Status Adjust Rules'));
        $this->_view->renderLayout('root');
    }

    protected function _isAllowed(): bool
    {
        return $this->_authorization->isAllowed('Magento_Backend::admin');
    }
}
