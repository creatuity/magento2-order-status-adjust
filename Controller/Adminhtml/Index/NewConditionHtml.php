<?php

declare(strict_types=1);

namespace Creatuity\OrderStatusAdjust\Controller\Adminhtml\Index;

use Creatuity\OrderStatusAdjust\Api\Data\RuleInterfaceFactory;
use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Rule\Model\Condition\AbstractCondition;

class NewConditionHtml extends Action
{
    public function __construct(
        Context $context,
        private readonly RuleInterfaceFactory $ruleFactory
    ) {
        parent::__construct($context);
    }

    public function execute(): void
    {
        $formName = $this->getRequest()->getParam('form_namespace');
        $id = (int)$this->getRequest()->getParam('id');
        $typeArr = explode('|', str_replace('-', '/', $this->getRequest()->getParam('type')));
        $type = $typeArr[0];

        $model = $this->_objectManager->create(
            $type
        )->setId(
            $id
        )->setType(
            $type
        )->setRule(
            $this->ruleFactory->create()
        )->setPrefix(
            'conditions'
        );

        if (!empty($typeArr[1])) {
            $model->setAttribute($typeArr[1]);
        }

        if ($model instanceof AbstractCondition) {
            $model->setJsFormObject($this->getRequest()->getParam('form'));
            $model->setFormName($formName);
            $this->setJsFormObject($model);
            $html = $model->asHtmlRecursive();
        } else {
            $html = '';
        }

        $this->getResponse()->setBody($html);
    }

    private function setJsFormObject(AbstractCondition $model): void
    {
        $requestJsFormName = $this->getRequest()->getParam('form');
        $actualJsFormName = $this->getJsFormObjectName($model->getFormName());
        if ($requestJsFormName === $actualJsFormName) {
            $model->setJsFormObject($actualJsFormName);
        }
    }

    private function getJsFormObjectName(string $formName): string
    {
        return $formName . 'rule_conditions_fieldset_';
    }

    protected function _isAllowed(): bool
    {
        return $this->_authorization->isAllowed('Magento_Backend::admin');
    }
}
