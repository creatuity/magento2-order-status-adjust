<?php

declare(strict_types=1);

namespace Creatuity\OrderStatusAdjust\Controller\Adminhtml\Index;

use Creatuity\OrderStatusAdjust\Model\Rule;
use Magento\Framework\DataObject;

class Save extends \Creatuity\OrderStatusAdjust\Controller\Adminhtml\Index\Rule
{
    public function execute(): void
    {
        if (!$this->getRequest()->getPostValue()) {
            $this->_redirect('order_status_adjust/*/');
        }

        try {
            /** @var $model Rule */
            $model = $this->ruleFactory->create();
            $this->_eventManager->dispatch(
                'adminhtml_controller_order_status_adjust_rule_prepare_save',
                ['request' => $this->getRequest()]
            );
            $data = $this->getRequest()->getPostValue();
            $inputFilter = new \Zend_Filter_Input(
                ['from_date' => $this->dateFilter, 'to_date' => $this->dateFilter],
                [],
                $data
            );
            $data = $inputFilter->getUnescaped();
            $id = $this->getRequest()->getParam('rule_id');
            if ($id) {
                $model->load($id);
            }

            $validateResult = $model->validateData(new DataObject($data));
            if ($validateResult !== true) {
                foreach ($validateResult as $errorMessage) {
                    $this->messageManager->addErrorMessage($errorMessage);
                }
                $this->_session->setPageData($data);
                $this->_redirect('order_status_adjust/*/edit', ['rule_id' => $model->getId()]);
                return;
            }

            $data = $this->prepareData($data);
            $model->loadPost($data);

            $this->_session->setPageData($model->getData());

            $model->save();
            $this->messageManager->addSuccessMessage(__('You saved the rule.'));
            $this->_session->setPageData(false);
            if ($this->getRequest()->getParam('back')) {
                $this->_redirect('order_status_adjust/*/edit', ['rule_id' => $model->getId()]);
                return;
            }
            $this->_redirect('order_status_adjust/*/');
            return;
        } catch (\Magento\Framework\Exception\LocalizedException $e) {
            $this->messageManager->addErrorMessage($e->getMessage());
            $id = (int)$this->getRequest()->getParam('rule_id');
            if (!empty($id)) {
                $this->_redirect('order_status_adjust/*/edit', ['rule_id' => $id]);
            } else {
                $this->_redirect('order_status_adjust/*/new');
            }
            return;
        } catch (\Exception $e) {
            $this->messageManager->addErrorMessage(
                __('Something went wrong while saving the rule data. Please review the error log.')
            );
            $this->logger->critical($e);
            $data = !empty($data) ? $data : [];
            $this->_session->setPageData($data);
            $this->_redirect('order_status_adjust/*/edit', ['id' => $this->getRequest()->getParam('rule_id')]);
            return;
        }
    }

    private function prepareData(array $data): array
    {
        if (isset($data['rule']['conditions'])) {
            $data['conditions'] = $data['rule']['conditions'];
        }

        unset($data['rule']);

        return $data;
    }
}
