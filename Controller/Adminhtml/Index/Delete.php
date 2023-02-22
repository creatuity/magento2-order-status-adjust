<?php

declare(strict_types=1);

namespace Creatuity\OrderStatusAdjust\Controller\Adminhtml\Index;

use Creatuity\OrderStatusAdjust\Model\Rule;
use Exception;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Exception\LocalizedException;

class Delete extends \Creatuity\OrderStatusAdjust\Controller\Adminhtml\Index\Rule
{
    public function execute(): ResponseInterface
    {
        $id = $this->getRequest()->getParam('rule_id');
        if ($id) {
            try {
                /** @var Rule $model */
                $model = $this->ruleFactory->create();
                $model->load($id);
                $model->delete();
                $this->messageManager->addSuccessMessage(__('You deleted the rule.'));

                return $this->_redirect('order_status_adjust/*/');
            } catch (LocalizedException $e) {
                $this->messageManager->addErrorMessage($e->getMessage());
            } catch (Exception $e) {
                $this->messageManager->addErrorMessage(
                    __('We can\'t delete the rule right now. Please review the log and try again.')
                );
                $this->logger->critical($e);

                return $this->_redirect('order_status_adjust/*/edit', ['id' => $this->getRequest()->getParam('id')]);
            }
        }

        $this->messageManager->addErrorMessage(__('We can\'t find a rule to delete.'));

        return $this->_redirect('order_status_adjust/*/');
    }
}
