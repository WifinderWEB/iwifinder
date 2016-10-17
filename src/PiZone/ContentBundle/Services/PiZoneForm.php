<?php

namespace PiZone\ContentBundle\Services;

class PiZoneForm{
    public function formDataToArray($form){
        $result = array();
        foreach($form as $key => $one){
            $vars = $one->vars;

            $result[$key] = array(
                'name' => isset($vars['name']) ? $vars['name']: null,
                'full_name' => isset($vars['full_name']) ? $vars['full_name'] : null,
                'id' => isset($vars['id']) ? $vars['id'] : null,
                'label' => isset($vars['label']) ? $vars['label'] : null,
                'required' => isset($vars['required']) ? $vars['required'] : null,
                'value' => isset($vars['value']) ? $vars['value'] : null,
                'valid' => isset($vars['valid']) ? $vars['valid'] : null,
                'errors' => $this->getErrors($vars),
                'attr' => isset($vars['attr']) ? $vars['attr'] : null,
                'type' => $this->getType($vars),
                'compound' => isset($vars['compound']) ? $vars['compound'] : null,
            );
            if($result[$key]['type'] == 'checkbox')
                $result[$key]['checked'] = $vars['checked'];
            if($result[$key]['type'] == 'choice') {
                $result[$key] = $result[$key] + $this->getChoices($vars);
                $result[$key]['value'] = isset($vars['value']) ? intval($vars['value']) : null;
            }
            if(isset($vars['compound']))
                $result[$key]['form'] = $this->formDataToArray($vars['form']);
        }

        return $result;
    }

    private function getErrors($vars){
        $errors = array();
        if(isset($vars['valid']) && !$vars['valid']) {
            foreach ($vars['errors'] as $error) {
                $errors[] = $error->getMessage();
            }
        }

        return $errors;
    }

    private function getType($vars){
        if(isset($vars['block_prefixes'])){
            if($vars['block_prefixes'][1] == 'text' && $vars['block_prefixes'][2] == 'textarea' )
                return $vars['block_prefixes'][2];
            else
                return $vars['block_prefixes'][1];
        }
        return null;
    }

    private function getChoices($vars){
        $result = array(
            'multiple' => $vars['multiple'],
            'expanded' => $vars['expanded'],
            'preferred_choices' => $vars['preferred_choices'],
            'placeholder' => $vars['placeholder'],
            'choice_translation_domain' => $vars['choice_translation_domain'],
//            'is_selected' => $vars['is_selected'],
            'placeholder_in_choices' => $vars['placeholder_in_choices'],
            'empty_value' => $vars['empty_value'],
            'empty_value_in_choices' => $vars['empty_value_in_choices']
        );
        foreach($vars['choices'] as $choice){
            $result['choices'][] = array(
                'label' => $choice->label,
                'value' => intval($choice->value),
                'attr' => $choice->value
            );
        }


        return $result;
    }
}