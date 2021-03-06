<?php

return array(
    
    'elements' => array(
        'code' => array(
            'type' => 'text',
            'htmlOptions' => array(
                'hint' => '<i class="icon-warning-sign"></i> Once an API ' .
                          'has been added, its code name cannot be changed, ' .
                          'so be sure you enter it correctly.',
                'placeholder' => 'example-api-name',
                'autofocus' => 'autofocus',
            ),
            'maxlength' => 32,
        ),
        'display_name' => array(
            'type' => 'text',
            'maxlength' => 64,
            'htmlOptions' => array(
                'placeholder' => 'Example API Name',
            ),
        ),
        'brief_description' => array(
            'type' => 'text',
            'maxlength' => 255,
            'htmlOptions' => array(
                'class' => 'input-xxlarge',
                'hint' => '<i class="icon-info-sign"></i> Enter a one-' .
                          'sentence summary of the purpose of this API.',
            ),
        ),
        'owner_id' => array(
            'type' => 'dropdownlist',
            'data' => CMap::mergeArray(
                array('' => '-- none --'),
                CHtml::encodeArray(CHtml::listData(
                    \User::model()->findAllByAttributes(array(
                        'role' => array(\User::ROLE_OWNER, \User::ROLE_ADMIN),
                    )),
                    'user_id',
                    'display_name'
                ))
            ),
            'visible' => \Yii::app()->user->checkAccess('admin'),
        ),
        'endpoint' => array(
            'htmlOptions' => array(
                'placeholder' => 'your-domain.com',
                'class' => 'input-xlarge',
            ),
            'type' => 'text',
        ),
        'default_path' => array(
            'htmlOptions' => array(
                'placeholder' => '/api/path',
                'class' => 'input-xlarge',
            ),
            'type' => 'text',
        ),
        'endpoint_timeout' => array(
            'htmlOptions' => array(
                'placeholder' => '10',
                'class' => 'input-mini',
            ),
            'type' => 'text',
        ),
        'protocol' => array(
            'type' => 'radiobuttonlist_inline',
            'data' => Api::getProtocols()
        ),
        'strict_ssl' => array(
            'type' => 'radiobuttonlist_inline',
            'data' => Api::getStrictSsls()
        ),
        'queries_second' => array(
            'htmlOptions' => array(
                'placeholder' => '3',
                'class' => 'input-mini',
            ),
            'type' => 'text',
        ),
        'queries_day' => array(
            'htmlOptions' => array(
                'placeholder' => '1000',
                'class' => 'input-mini',
            ),
            'type' => 'text',
        ),
        'access_type' => array(
            'type' => 'dropdownlist',
            'data' => array_merge(
                array('' => '-- Select one: --'),
                Api::getAccessTypes()
            ),
        ),
        'access_options' => array(
            'htmlOptions' => array(
                'hint' => '<i class="icon-info-sign"></i> Enter one or more ' .
                          'Insite access groups separated by commas.',
                'class' => 'input-xxlarge',
            ),
            'type' => 'text',
        ),
        'approval_type' => array(
            'type' => 'dropdownlist',
            'data' => array_merge(
                array('' => '-- Select one: --'),
                Api::getApprovalTypes()
            ),
        ),
        'support' => array(
            'htmlOptions' => array(
                'hint' => '<i class="icon-info-sign"></i> Enter a website, ' .
                          'email address, phone number, or some other way ' .
                          'for developers using this API to seek support.',
                'class' => 'input-xxlarge',
            ),
            'type' => 'text',
        ),
        'documentation' => array(
            'htmlOptions' => array(
                'hint' => '<i class="icon-info-sign"></i> Use <a href="' .
                          'http://en.wikipedia.org/wiki/Markdown" target="' .
                          '_blank">Markdown</a> syntax to format your ' .
                          'documentation.',
                'style' => 'height: 25ex; width: 90%;'
            ),
            'type' => 'textarea',
        )
    ),
    
    'buttons' => array(
        'submit' => array(
            'buttonType' => 'submit',
            'icon' => 'ok white',
            'label' => 'Save',
            'type' => 'primary'
        ),
    ),
);
