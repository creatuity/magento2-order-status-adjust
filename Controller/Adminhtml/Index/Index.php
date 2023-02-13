<?php

declare(strict_types=1);

namespace Creatuity\OrderStatusAdjust\Controller\Adminhtml\Index;

class Index extends Rule
{
    public function execute(): void
    {
        $this->initAction()->_addBreadcrumb(__('Order Status Adjust Rules'), __('Order Status Adjust Rules'));
        $this->_view->getPage()->getConfig()->getTitle()->prepend(__('Order Status Adjust Rules'));
        $this->_view->renderLayout('root');
    }
}
