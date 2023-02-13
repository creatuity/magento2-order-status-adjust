<?php

declare(strict_types=1);

namespace Creatuity\OrderStatusAdjust\Controller\Adminhtml\Index;

use Creatuity\OrderStatusAdjust\Model\RuleFactory;
use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\App\Response\Http\FileFactory;
use Magento\Framework\Registry;
use Magento\Framework\Stdlib\DateTime\Filter\Date;
use Psr\Log\LoggerInterface;

abstract class Rule extends Action
{
    public function __construct(
        protected readonly Context $context,
        protected readonly Registry $coreRegistry,
        protected readonly FileFactory $fileFactory,
        protected readonly Date $dateFilter,
        protected readonly RuleFactory $ruleFactory,
        protected readonly LoggerInterface $logger
    ) {
        parent::__construct($context);
    }

    protected function initRule(): void
    {
        $rule = $this->ruleFactory->create();
        $this->coreRegistry->register(
            'current_rule',
            $rule
        );
        $id = (int)$this->getRequest()->getParam('id');

        if (!$id && $this->getRequest()->getParam('rule_id')) {
            $id = (int)$this->getRequest()->getParam('rule_id');
        }

        if ($id) {
            $this->coreRegistry->registry('current_rule')->load($id);
        }
    }

    protected function initAction(): Rule
    {
        $this->_view->loadLayout();
//        $this->_setActiveMenu('Creatuity_OrderStatusAdjust::order_status_adjust"')
//            ->_addBreadcrumb(__('Order Status Adjust Rules'), __('Order Status Adjust Rules'));

        return $this;
    }

    protected function _isAllowed(): bool
    {
        return $this->_authorization->isAllowed('Magento_Backend::admin');
    }
}
