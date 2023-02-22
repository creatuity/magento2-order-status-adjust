<?php

declare(strict_types=1);

namespace Creatuity\OrderStatusAdjust\Controller\Adminhtml\Index;

use Magento\Rule\Model\Condition\AbstractCondition;

class NewConditionHtml extends Rule
{
    public function execute(): void
    {
        $formName = $this->getRequest()->getParam('form_namespace');
        $id = $this->getRequest()->getParam('id');
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
        if ($requestJsFormName === $actualJsFormName) { //new
            $model->setJsFormObject($actualJsFormName);
        }
    }

    private function getJsFormObjectName(string $formName): string
    {
        return $formName . 'rule_conditions_fieldset_';
    }
}
