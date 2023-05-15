<?php

declare(strict_types=1);

namespace Creatuity\OrderStatusAdjust\Block\Adminhtml;

use Creatuity\OrderStatusAdjust\Model\RuleFactory;
use Magento\Backend\Block\Template\Context;
use Magento\Backend\Block\Widget\Form\Generic;
use Magento\Backend\Block\Widget\Form\Renderer\Fieldset;
use Magento\Backend\Block\Widget\Tab\TabInterface;
use Magento\Framework\Data\FormFactory;
use Magento\Framework\Phrase;
use Magento\Framework\Registry;
use Magento\Rule\Block\Conditions;
use Magento\Rule\Model\Condition\AbstractCondition;

class Condition extends Generic implements TabInterface
{
    protected $_nameInLayout = 'conditions';

    public function __construct(
        Context $context,
        Registry $registry,
        FormFactory $formFactory,
        private readonly Conditions $conditions,
        private readonly Fieldset $rendererFieldset,
        private readonly RuleFactory $ruleFactory,
        array $data = []
    ) {
        parent::__construct($context, $registry, $formFactory, $data);
    }

    public function getTabClass(): ?string
    {
        return null;
    }

    public function getTabUrl(): ?string
    {
        return null;
    }

    public function isAjaxLoaded(): bool
    {
        return false;
    }

    public function getTabLabel(): Phrase
    {
        return __('Conditions');
    }

    public function getTabTitle(): Phrase
    {
        return __('Conditions');
    }

    public function canShowTab(): bool
    {
        return true;
    }

    public function isHidden(): bool
    {
        return false;
    }

    protected function _prepareForm(): self
    {
        $model = $this->_coreRegistry->registry('current_rule');
        $form = $this->addTabToForm($model);
        $this->setForm($form);

        return parent::_prepareForm();
    }

    protected function addTabToForm(
        $model,
        $fieldsetId = 'conditions_serialized_field',
        $formName = 'order_status_adjust_form'
    ) {
        if (!$model) {
            $id = $this->getRequest()->getParam('rule_id');
            $model = $this->ruleFactory->create();
            $model->load($id);
        }

        $conditionsFieldSetId = $model->getConditionsFieldSetId($formName);
        $newChildUrl = $this->getUrl(
            'order_status_adjust/index/newConditionHtml/form/' . $conditionsFieldSetId,
            ['form_namespace' => $formName]
        );

        $form = $this->_formFactory->create();
        $form->setHtmlIdPrefix('rule_');
        $renderer = $this->rendererFieldset->setTemplate(
            'Magento_CatalogRule::promo/fieldset.phtml'
        )->setNewChildUrl(
            $newChildUrl
        )->setFieldSetId(
            $conditionsFieldSetId
        )->setNameInLayout('');

        $fieldset = $form->addFieldset(
            $fieldsetId,
            [
                'legend' => __(
                    'Apply the rule only if the following conditions are met (leave blank for all products).'
                ),
            ]
        )->setRenderer(
            $renderer
        );
        $fieldset->addField(
            'conditions',
            'text',
            [
                'name' => 'conditions',
                'label' => __('Conditions'),
                'title' => __('Conditions'),
                'required' => true,
                'data-form-part' => $formName,
            ]
        )->setRule(
            $model
        )->setRenderer(
            $this->conditions
        );
        $form->setValues($model->getData());
        $this->setConditionFormName($model->getConditions(), $formName);

        return $form;
    }

    private function setConditionFormName(AbstractCondition $conditions, string $formName): void
    {
        $conditions->setFormName($formName);
        if ($conditions->getConditions() && is_array($conditions->getConditions())) {
            foreach ($conditions->getConditions() as $condition) {
                $this->setConditionFormName($condition, $formName);
            }
        }
    }
}
