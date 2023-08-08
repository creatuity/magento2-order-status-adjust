<?php

declare(strict_types=1);

namespace Creatuity\OrderStatusAdjust\Controller\Adminhtml\Index;

use Creatuity\OrderStatusAdjust\Api\RuleRepositoryInterface;
use Exception;
use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\App\ResponseInterface;
use Psr\Log\LoggerInterface;

class Delete extends Action
{
    public function __construct(
        Context $context,
        private readonly RuleRepositoryInterface $ruleRepository,
        private readonly LoggerInterface $logger
    ) {
        parent::__construct($context);
    }

    public function execute(): ResponseInterface
    {
        $id = $this->getRequest()->getParam('rule_id');
        if (!$id) {
            $this->messageManager->addErrorMessage(__('We can\'t find a rule to delete.'));
            return $this->_redirect('order_status_adjust/*/');
        }

        try {
            $rule = $this->ruleRepository->getById((int)$id);
            $this->ruleRepository->delete($rule);

            $this->messageManager->addSuccessMessage(__('You deleted the rule.'));

            return $this->_redirect('order_status_adjust/*/');
        } catch (Exception $e) {
            $this->messageManager->addErrorMessage(
                __('We can\'t delete the rule right now.')
            );
            $this->logger->critical($e);

            return $this->_redirect('order_status_adjust/*/edit', ['id' => $this->getRequest()->getParam('id')]);
        }
    }

    protected function _isAllowed(): bool
    {
        return $this->_authorization->isAllowed('Magento_Backend::admin');
    }
}
