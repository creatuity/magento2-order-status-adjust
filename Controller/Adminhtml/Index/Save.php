<?php

declare(strict_types=1);

namespace Creatuity\OrderStatusAdjust\Controller\Adminhtml\Index;

use Creatuity\OrderStatusAdjust\Model\Handler\LoadHandler;
use Creatuity\OrderStatusAdjust\Model\Handler\SaveHandler;
use Creatuity\OrderStatusAdjust\Model\Handler\ValidationHandler;
use Exception;
use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Stdlib\DateTime\Filter\Date;
use Psr\Log\LoggerInterface;

class Save extends Action
{
    public function __construct(
        Context $context,
        private readonly Date $dateFilter,
        private readonly LoggerInterface $logger,
        private readonly SaveHandler $saveHandler,
        private readonly ValidationHandler $validationHandler,
        private readonly LoadHandler $loadHandler
    ) {
        parent::__construct($context);
    }

    public function execute(): ResponseInterface
    {
        $id = $this->getRequest()->getParam('rule_id');
        if ($id !== null) {
            $id = (int)$id;
        }

        $data = $this->getRequest()->getPostValue();

        if (empty($data)) {
            return $this->_redirect('order_status_adjust/*/');
        }

        $inputFilter = $this->getInputFilter($data);
        $data = $inputFilter->getUnescaped();

        $rule = $this->loadHandler->execute($id);

        try {
            $validationErrors = $this->validationHandler->execute($rule, $data);
            if (!empty($validationErrors)) {
                foreach ($validationErrors as $error) {
                    $this->messageManager->addErrorMessage($error);
                }
                $this->_session->setPageData($data);

                return $this->_redirect('order_status_adjust/*/edit', ['rule_id' => $rule->getId()]);
            }

            $this->saveHandler->execute(
                $rule,
                $data
            );

            $this->_session->setPageData($rule->getData());

            $this->messageManager->addSuccessMessage(__('You saved the rule.'));
            $this->_session->setPageData(false);

            if ($this->getRequest()->getParam('back')) {
                return $this->_redirect('order_status_adjust/*/edit', ['rule_id' => $rule->getId()]);
            }

            return $this->_redirect('order_status_adjust/*/');
        } catch (Exception $e) {
            $this->messageManager->addErrorMessage(
                __('Something went wrong while saving the rule data. Please review the error log.')
            );
            $this->logger->critical($e);
            $data = !empty($data) ? $data : [];
            $this->_session->setPageData($data);

            $id = (int)$this->getRequest()->getParam('rule_id');
            if (!empty($id)) {
                return $this->_redirect('order_status_adjust/*/edit', ['rule_id' => $id]);
            }

            return $this->_redirect('order_status_adjust/*/new');
        }
    }

    private function getInputFilter(array $data): object
    {
        if (class_exists('Zend_Filter_Input')) {
            $inputFilter = new \Zend_Filter_Input(
                ['date_from' => $this->dateFilter, 'date_to' => $this->dateFilter],
                [],
                $data
            );
        } else {
            $inputFilter = new \Magento\Framework\Filter\FilterInput(
                ['date_from' => $this->dateFilter, 'date_to' => $this->dateFilter],
                [],
                $data
            );
        }

        return $inputFilter;
    }

    protected function _isAllowed(): bool
    {
        return $this->_authorization->isAllowed('Magento_Backend::admin');
    }
}
