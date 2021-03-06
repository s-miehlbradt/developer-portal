<?php

use ApiAxle\Api\Api as AxleApi;

class Api extends ApiBase
{
    CONST APPROVAL_TYPE_AUTO = 'auto';
    CONST APPROVAL_TYPE_OWNER = 'owner';
    
    CONST ACCESS_TYPE_PUBLIC = 'public';
    CONST ACCESS_TYPE_INTERNAL_ALL = 'internal-all';
    CONST ACCESS_TYPE_INTERNAL_GROUPS = 'internal-groups';
    
    CONST PROTOCOL_HTTP = 'http';
    CONST PROTOCOL_HTTPS = 'https';
    
    CONST STRICT_SSL_TRUE = 1;
    CONST STRICT_SSL_FALSE = 0;
    
    CONST REGEX_ENDPOINT = '/^(?=.{1,255}$)[0-9A-Za-z](?:(?:[0-9A-Za-z]|-){0,61}[0-9A-Za-z])?(?:\.[0-9A-Za-z](?:(?:[0-9A-Za-z]|-){0,61}[0-9A-Za-z])?)*\.?$/';
    CONST REGEX_PATH = '/^\/[a-zA-Z0-9\-\.\/_]{0,}$/';
    
    public function afterSave()
    {
        parent::afterSave();
        
        // If this is a new API...
        if ($this->isNewRecord) {

            // If we are NOT in an environment where we should send email
            // notifications, skip the rest of this.
            if (\Yii::app()->params['mail'] === false) {
                return;
            }

            // If we do NOT have an adminEmail address in the config params,
            // skip the rest of this.
            if ( ! isset(\Yii::app()->params['adminEmail'])) {
                return;
            }

            // Try to send a notification email.
            $mailer = Utils::getMailer();
            $mailer->setView('api-added');
            $mailer->setTo(\Yii::app()->params['adminEmail']);
            $mailer->setSubject(sprintf(
                'An API was added: %s',
                $this->display_name
            ));
            if (isset(\Yii::app()->params['mail']['bcc'])) {
                $mailer->setBcc(\Yii::app()->params['mail']['bcc']);
            }
            $mailer->setData(array(
                'api' => $this,
                'addedByUser' => \Yii::app()->user->user,
            ));

            // If unable to send the email, record the failure.
            if ( ! $mailer->send()) {
                \Yii::log(
                    'Unable to send API-added email: '
                    . $mailer->ErrorInfo,
                    CLogger::LEVEL_WARNING
                );
            }
        }
    }
    
    /**
     * Get the user-friendly description of this Api's approval type.
     * 
     * @return string|null The description of the approval type (if available).
     */
    public function getApprovalTypeDescription()
    {
        // Get the descriptions of the various approval types.
        $approvalTypeDescriptions = self::getApprovalTypes();
        
        // Return the description for this Api's approval type (if set).
        if ($this->approval_type === null) {
            return null;
        } elseif ( ! isset($approvalTypeDescriptions[$this->approval_type])) {
            return 'UNKNOWN APPROVAL TYPE';
        } else {
            return $approvalTypeDescriptions[$this->approval_type];
        }
    }
    
    /**
     * Get the list of approval type contants along with the user-friendly
     * versions of them (for use in drop-down lists, etc.).
     * @return array<string,string> An array where each key is an approval type
     *     constant and each value is the corresponding user-friendly version
     *     of it.
     */
    public static function getApprovalTypes()
    {
        return array(
            self::APPROVAL_TYPE_AUTO => 'Automatically Approved',
            self::APPROVAL_TYPE_OWNER => 'API Owner Approved',
        );
    }
    
    /**
     * Get the user-friendly description of this Api's access type.
     * 
     * @return string|null The description of the access type (if available).
     */
    public function getAccessTypeDescription()
    {
        // Get the descriptions of the various access types.
        $accessTypeDescriptions = self::getAccessTypes();
        
        // Return the description for this Api's access type (if set).
        if ($this->access_type === null) {
            return null;
        } elseif ( ! isset($accessTypeDescriptions[$this->access_type])) {
            return 'UNKNOWN ACCESS TYPE';
        } else {
            return $accessTypeDescriptions[$this->access_type];
        }
    }
    
    public static function getAccessTypes()
    {
        return array(
            self::ACCESS_TYPE_PUBLIC => 'Publicly Available',
            self::ACCESS_TYPE_INTERNAL_ALL => 'All Insite Users',
            self::ACCESS_TYPE_INTERNAL_GROUPS => 'Insite Users of Specific Groups',
        );
    }
    
    public static function getProtocols()
    {
        return array(
            self::PROTOCOL_HTTP => 'HTTP',
            self::PROTOCOL_HTTPS => 'HTTPS'
        );
    }
    
    public static function getStrictSsls()
    {
        return array(
            self::STRICT_SSL_TRUE => 'True',
            self::STRICT_SSL_FALSE => 'False',
        );
    }
    
    /**
     * @return array<string,string> Customized attribute labels (name => label),
     *     overriding any that did not autogenerate to the desired value in the
     *     base class.
     */
    public function attributeLabels() {
        $labels = parent::attributeLabels();
        $newLabels = array_merge($labels, array(
            'access_options' => 'Groups',
            'access_type' => 'Visibility',
            'queries_second' => 'Queries per Second',
            'queries_day' => 'Queries per Day',
            'protocol' => 'Endpoint Protocol',
            'strict_ssl' => 'Endpoint Strict SSL',
            'endpoint_timeout' => 'Endpoint Timeout',
            'support' => 'For Support',
        ));
        
        return $newLabels;
    }
    
    /**
     * Generate the HTML for the active key count badge.
     * 
     * @param string|null $hoverTitle (Optional:) Text to show on hover (if
     *     any).
     * @param string|null $linkTarget (Optional:) If desired, the URL to make
     *     this badge a hyperlink to.
     * @return string The resulting HTML.
     */
    public function getActiveKeyCountBadgeHtml(
        $hoverTitle = null,
        $linkTarget = null
    ) {
        // Get the active key count (to avoid retrieving it multiple times).
        $count = $this->keyCount;
        
        // Generate and return the HTML for a badge, highlighting it if the
        // pending key count is non-zero.
        return self::generateBadgeHtml(
            $count,
            ($count ? 'badge-info' : null),
            $hoverTitle,
            $linkTarget
        );
    }
    
    /**
     * Get the apiProxyDomain config data. Throws an exception if it has not
     * been set.
     * 
     * NOTE: This is primarily defined in order to make mocking it in unit tests
     *       easier, which is also why it is NOT a static function (which would
     *       prevent mocking).
     * 
     * @return string The API proxy domain.
     * @throws Exception
     */
    public function getApiProxyDomain()
    {
        if (isset(\Yii::app()->params['apiProxyDomain'])) {
            return \Yii::app()->params['apiProxyDomain'];
        } else {
            throw new Exception(
                'The API proxy domain has not been defined in the config data.',
                1420751158
            );
        }
    }
    
    /**
     * Get the apiProxyProtocol config data, returning a default value if that
     * has not been set.
     * 
     * NOTE: This is primarily defined in order to make mocking it in unit tests
     *       easier, which is also why it is NOT a static function (which would
     *       prevent mocking).
     * 
     * @return string The API proxy protocol.
     * @throws Exception
     */
    public function getApiProxyProtocol()
    {
        if (isset(\Yii::app()->params['apiProxyProtocol'])) {
            return \Yii::app()->params['apiProxyProtocol'];
        } else {
            return 'https';
        }
    }
    
    /**
     * Generate the HTML for the pending key count badge.
     * 
     * @param string|null $hoverTitle (Optional:) Text to show on hover (if
     *     any).
     * @return string The resulting HTML.
     */
    public function getPendingKeyCountBadgeHtml(
        $hoverTitle = null,
        $linkTarget = null
    ) {
        // Get the pending key count (to avoid retrieving it multiple times).
        $count = $this->pendingKeyCount;
        
        // Generate and return the HTML for a badge, highlighting it if the
        // pending key count is non-zero.
        return self::generateBadgeHtml(
            $count,
            ($count ? 'badge-important' : null),
            $hoverTitle,
            $linkTarget
        );
    }
    
    /**
     * Get the public URL for this API.
     * 
     * @return string The (absolute) URL.
     */
    public function getPublicUrl()
    {
        return sprintf(
            '%s://%s%s/',
            $this->getApiProxyProtocol(),
            $this->code,
            $this->getApiProxyDomain()
        );
    }
    
    /**
     * Get a list containing the email address (if available) of each user that
     * has a key to this Api.
     * 
     * @return array The list of email addresses (without duplicates).
     */
    public function getEmailAddressesOfUsersWithActiveKeys()
    {
        $emailAddresses = array();
        foreach ($this->keys as $key) {
            if ($key->user && $key->user->email) {
                if ( ! in_array($key->user->email, $emailAddresses)) {
                    $emailAddresses[] = $key->user->email;
                }
            }
        }
        return $emailAddresses;
    }
    
    /**
     * Get the internal endpoint for this Api.
     * 
     * @return string The (absolute) URL.
     */
    public function getInternalApiEndpoint()
    {
        return sprintf(
            '%s://%s%s',
            $this->protocol,
            $this->endpoint,
            ($this->default_path ?: '/')
        );
    }
    
    /**
     * Get the public URL for this API as an HTML string that adds an HTML
     * element around the Api's code, with the given CSS class.
     * 
     * @param string $cssClass The CSS class to use for the Api's code. Defaults
     *     to 'bold'.
     * @return string HTML of the (absolute) URL.
     */
    public function getStyledPublicUrlHtml($cssClass = 'bold')
    {
        return sprintf(
            '%s://<span class="%s">%s</span>%s/',
            CHtml::encode($this->getApiProxyProtocol()),
            CHtml::encode($cssClass),
            CHtml::encode($this->code),
            CHtml::encode($this->getApiProxyDomain())
        );
    }
    
    /**
     * Get usage data for this Api.
     * 
     * @param string $granularity The time interval (e.g. - 'second', 'minute',
     *     'hour', 'day') by which the data should be grouped.
     * @param boolean $includeCurrentInterval (Optional:) Whether to include the
     *     current time interval, even though we only have incomplete data for
     *     it. Defaults to true.
     * @return array A hash with timestamps (in $granularity intervals) as keys,
     *     and arrays of http_response_code(or error_name) => num_hits as
     *     values.  
     *     EXAMPLE: 
     *     <pre>
     *     array(
     *         1416340920 => array(200 => 2),
     *         1416340980 => array(200 => 4),
     *         1416341520 => array(200 => 1),
     *     )
     *     </pre>
     */
    public function getUsage(
        $granularity = 'minute',
        $includeCurrentInterval = true
    ) {
        // Get the ApiAxle Api object for this Api model.
        $axleApi = new AxleApi(Yii::app()->params['apiaxle'], $this->code);
        
        // Get the starting timestamp for the data we care about.
        $timeStart = \UsageStats::getTimeStart(
            $granularity,
            $includeCurrentInterval
        );
        
        // Retrieve the stats from ApiAxle.
        $axleStats = $axleApi->getStats($timeStart, false, $granularity, 'false');
        
        // Reformat the data for easier use.
        $dataByCategory = array();
        foreach ($axleStats as $category => $categoryStats) {
            $tempCategoryData = array();
            foreach ($categoryStats as $responseCode => $timeData) {
                if (count($timeData) <= 0) {
                    continue;
                }
                $tempResponseCodeData = array();
                foreach ($timeData as $timestamp => $numHits) {
                    $tempResponseCodeData[$timestamp] = $numHits;
                }
                if (count($tempResponseCodeData) > 0) {
                    $tempCategoryData[$responseCode] = $tempResponseCodeData;
                }
            }
            $dataByCategory[$category] = $tempCategoryData;
        }
        
        // Sum the cached and uncached hits, then sum that with the errors.
        $successfulUsage = UsageStats::combineUsageCategoryArrays(
            $dataByCategory['uncached'],
            $dataByCategory['cached']
        );
        $usage = UsageStats::combineUsageCategoryArrays(
            $successfulUsage,
            $dataByCategory['error']
        );
        
        // Return the resulting data.
        return $usage;
    }
    
    public function rules() {
        $rules = parent::rules();
        $newRules = array_merge($rules, array(
            array('code', 'unique'),
            array('code', 'match',
                'allowEmpty' => false,
                'pattern' => '/^([a-z0-9]{1}[a-z0-9\-]{1,}[a-z0-9]{1})$/',
                'message' => 'The API code must only be (lowercase) letters ' .
                'and numbers. It may contain hyphens, but not ' .
                'at the beginning or end.'),
            array('owner_id', 'validateOwnerId'),
            array('endpoint', 'match',
                'allowEmpty' => false,
                'pattern' => self::REGEX_ENDPOINT,
                'message' => 'Endpoint must be the domain only, no protocol or '
                . 'path should be included. (ex: sub.domain.com)',
            ),
            array('default_path', 'match',
                'allowEmpty' => true,
                'pattern' => self::REGEX_PATH,
                'message' => 'Default Path must begin with a / and should not include '
                . 'any query string parameters. (ex: /example/path)',
            ),
            array('endpoint', 'isUniqueEndpointDefaultPathCombo'),
            array('updated', 'default',
                'value' => new CDbExpression('NOW()'),
                'setOnEmpty' => false, 'on' => 'update'),
            array('created,updated', 'default',
                'value' => new CDbExpression('NOW()'),
                'setOnEmpty' => true, 'on' => 'insert'),
            array('code', 'unsafe', 'on' => 'update'),
            array('access_options', 'default', 'setOnEmpty' => true,
                'value' => NULL),
            array('protocol', 'default',
                'value' => 'http',
                'setOnEmpty' => true, 'on' => 'insert'),
            array('strict_ssl', 'default',
                'value' => 1,
                'setOnEmpty' => true, 'on' => 'insert'),
            array('endpoint_timeout', 'numerical',
                'integerOnly' => true, 'min' => 2, 'max' => 900),
            array('queries_second, queries_day', 'numerical',
                'integerOnly' => true, 'min' => 1, 'max' => 1000000000),
        ));

        return $newRules;
    }
    
    public function relations()
    {
        return array_merge(parent::relations(), array(
            'keyCount' => array(self::STAT, 'Key', 'api_id'),
            'pendingKeyCount' => array(
                self::STAT,
                'KeyRequest',
                'api_id',
                'condition' => 'status = :status',
                'params' => array(':status' => \KeyRequest::STATUS_PENDING),
            ),
        ));
        
    }
    
    public function beforeSave()
    {
        parent::beforeSave();
        
        global $ENABLE_AXLE;
        if(isset($ENABLE_AXLE) && !$ENABLE_AXLE){
            return true;
        }
        
        /**
         * Before saving an Api object, we need to provision it on ApiAxle,
         * or update it of course.
         * 
         * If the call to ApiAxle failes, the save will not go through
         */
        
        $apiData = array(
            'endPoint' => $this->endpoint,
            'defaultPath' => $this->default_path ?: '/',
            'protocol' => $this->protocol,
            'strictSSL' => $this->strict_ssl ? true : false,
            'endPointTimeout' => !is_null($this->endpoint_timeout) 
                    ? (int)$this->endpoint_timeout : 2,
        );
        
        $axleApi = new AxleApi(Yii::app()->params['apiaxle']);
        if($this->getIsNewRecord()){
            try {
                $axleApi->create($this->code,$apiData);
                return true;
            } catch (\Exception $e) {
                $this->addError('code','Failed to create new API on the proxy: '.$e->getMessage());
                return false;
            }
        } else {
            try {
                $axleApi->get($this->code);
                $axleApi->update($apiData);
                return true;
            } catch (\Exception $e) {
                $this->addError('code','Failed to update API on the proxy: '.$e->getMessage());
                return false;
            }
        }
    }
    
    public function beforeDelete()
    {
        parent::beforeDelete();

        // first delete keys and key_requests
        Key::model()->deleteAllByAttributes(array(
            'api_id' => $this->api_id,
        ));
        KeyRequest::model()->deleteAllByAttributes(array(
            'api_id' => $this->api_id,
        ));
        
        global $ENABLE_AXLE;
        if(isset($ENABLE_AXLE) && !$ENABLE_AXLE){
            return true;
        }
        
        $axleApi = new AxleApi(Yii::app()->params['apiaxle']);
        try{
            $axleApi->delete($this->code);
            return true;
        } catch (\Exception $e) {
            $this->addError('code',$e->getMessage());
            return false;
        }
    }
    
    /**
     * Generate HTML for a badge (such as for a key count).
     * 
     * @param number $badgeValue The value to show in the badge.
     * @param string|null $extraCssClass (Optional:) Any additional CSS
     *     class(es) to include on the HTML element.
     * @param string|null $hoverTitle (Optional:) Text to show on hover (if
     *     any).
     * @param string|null $linkTarget (Optional:) If desired, the URL to make
     *     this badge a hyperlink to.
     * @return string The resulting HTML.
     */
    public static function generateBadgeHtml(
        $badgeValue,
        $extraCssClass = null,
        $hoverTitle = null,
        $linkTarget = null
    ) {
        // Assemble the title attribute HTML string (if applicable).
        if ($hoverTitle === null) {
            $titleAttrHtml = '';
        } else {
            $titleAttrHtml = ' title="' . CHtml::encode($hoverTitle) . '"';
        }
           
        // Assemble the HTML for the badge itself.
        $badgeHtml = sprintf(
            '<span class="badge%s"%s>%s</span>',
            ($extraCssClass !== null ? ' ' . $extraCssClass : ''),
            $titleAttrHtml,
            CHtml::encode($badgeValue)
        );
        
        // If given a URL to link to...
        if ($linkTarget !== null) {
            
            // Wrap the badge in a link tag before returning it.
            return sprintf(
                '<a href="%s">%s</a>',
                $linkTarget,
                $badgeHtml
            );
        } else {
            
            // Otherwise just return the badge HTML.
            return $badgeHtml;
        }
    }
    
    /**
     * Confirm that this Api's endpoint and default_path are a unique
     * combination.
     * 
     * @param string $attribute The name of the attribute to be validated.
     * @param array $params Options specified in the validation rule.
     */
    public function isUniqueEndpointDefaultPathCombo($attribute, $params)
    {
        // Get the list of all Apis that have this endpoint and default_path.
        $apis = \Api::model()->findAllByAttributes(array(
            'endpoint' => $this->endpoint,
            'default_path' => $this->default_path,
        ));
        
        // If any of those are NOT this Api...
        foreach ($apis as $api) {
            if ($api->api_id !== $this->api_id) {
                
                // Then the validation fails.
                $this->addError(
                    $attribute,
                    'The given endpoint and default path are already in use by '
                    . 'the "' . $api->display_name . '" API.'
                );
                
                // Also add an error on the default_path attribute (to highlight
                // it as having an error), but don't put any additional message.
                $this->addError('default_path', '');
                
                // Go ahead and break out of the loop (since we found an error).
                break;
            }
        }
    }
    
    /**
     * Indicate whether this API should be visible to the given User. Note that
     * this is a User model, not a Yii CWebUser.
     * 
     * @param User $user The User (model) whose permissions need to be checked.
     * @return boolean Whether the API should be visible to that User.
     */
    public function isVisibleToUser($user)
    {
        // If the user is a guest...
        if ( ! ($user instanceof \User)) {
            
            // They can't see any APIs.
            return false;
        }
        
        
        // ** Otherwise, determine access based on the API Access Type, etc: **
        
        // If the API is public, then it's visible to the user.
        if ($this->access_type === self::ACCESS_TYPE_PUBLIC) {
            return true;
        }
        // OR, if the API can be see by anyone in Insite...
        elseif ($this->access_type == self::ACCESS_TYPE_INTERNAL_ALL) {
            
            // ... then the user can see it only if they're in Insite.
            return $user->isInAccessGroup(
                \Yii::app()->params['allInsiteUsersGroup']
            );
        }
        // OR, if the API can only be seen by people in specific access
        // groups...
        elseif ($this->access_type == self::ACCESS_TYPE_INTERNAL_GROUPS) {
            
            // The user can only see if it they are in at least one of the
            // specified access groups.
            if (isset($this->access_options) && !is_null($this->access_options)) {
                
                // Get the list of groups allowed to see this API.
                $allowedGroups = explode(',', $this->access_options);
                foreach ($allowedGroups as $group) {
                    
                    // Clean up / format the group name.
                    $group = strtoupper(trim($group));
                    
                    // If that groups is one of the groups that the user is
                    // part of, then they can see this API.
                    if ($user->isInAccessGroup($group)) {
                        return true;
                    }
                }
            }
            
            // If we reach this point, then the user was NOT in any of the
            // groups allowed to see this API.
            return false;
        }
        
        // If we reach this point, we have come across a situation that we
        // are not yet set up to handle.
        throw new \Exception(
            'Unable to determine whether a User should be allowed to see a '
            . 'particular API because we do not know how to handle an API '
            . 'access_type of "' . $this->access_type . '".',
            1417718496
        );
    }
    
    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return Api the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }
	
    /**
     * @param string $attribute the name of the attribute to be validated
     * @param array $params options specified in the validation rule
     */
    public function validateEndpoint($attribute, $params) {

            // If the endpoint DOES seem to contain a protocal...
            if(!preg_match(self::REGEX_ENDPOINT, $this->endpoint)){
                    // Indicate that it is NOT valid.
                    $this->addError('endpoint', 'API Endpoint should only be '
                            . 'the host name and should not include protocol '
                            . 'or path.');
            }
    }
	
    /**
     * Validate that the specified owner_id is an acceptable value.
     * 
     * @param string $attribute The name of the attribute to be validated.
     * @param array $params The options specified in the validation rule.
     */
    public function validateOwnerId($attribute, $params)
    {
        // If they selected anyone...
        if (($this->owner_id !== null) && ($this->owner_id !== '')) {

            // Try to get the User model for the specified owner.
            $owner = $this->owner;
            if ( ! ($owner instanceof \User)) {
                $this->addError(
                    $attribute,
                    'Please pick a real user to be the owner.'
                );
            } else {

                // Make sure they selected someone with owner privileges.
                if ( ! $owner->hasOwnerPrivileges()) {
                    $this->addError(
                        $attribute,
                        'You may only specify users with API Owner privileges '
                        . 'as the owner.'
                    );
                }
            }
        }
    }
}
