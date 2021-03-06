<?php

class ApiController extends Controller
{
    public $layout = '//layouts/one-column-with-title';
    
    public function actionActiveKeys($code)
    {
        // Make sure the specified API exists.
        $api = \Api::model()->findByAttributes(array('code' => $code));
        
        // Get a reference to the current website user's User model.
        $user = \Yii::app()->user->user;
        
        // Prevent information about it from being seen by user's without
        // permission to see the specified API.
        if (( ! $api) || ( ! $api->isVisibleToUser($user))) {
            throw new CHttpException(
                404,
                'Either there is no "' . $code . '" API or you do not have '
                . 'permission to view it.'
            );
        }
        
        // Prevent this list of keys from being seen by anyone who is not
        // authorized to see them.
        if (( ! ($user instanceof User)) || ( ! $user->canSeeKeysForApi($api))) {
            throw new CHttpException(
                403,
                'You do not have permission to see its list of active keys for '
                . 'the "' . $api->display_name . '" API.'
            );
        }
        
        // Get the list of active keys for that API.
        $activeKeys = new CActiveDataProvider('Key', array(
            'criteria' => array(
                'condition' => 'api_id = :api_id',
                'params' => array(
                    ':api_id' => $api->api_id,
                ),
            ),
        ));
        
        // Show the page.
        $this->render('activeKeys', array(
            'activeKeys' => $activeKeys,
            'api' => $api,
        ));
    }
    
    public function actionAddContactUs()
    {
        // Render the page.
        $this->render('addContactUs', array(
            'contactEmail' => \Yii::app()->params['adminEmail'],
        ));
    }
    
    public function actionAdd()
    {
        // Get the current user's model.
        /* @var $user User */
        $user = \Yii::app()->user->user;
        
        // Set up to add an API.
        /* @var $api Api */
        $api = new Api;
        
        // Record the current user as the owner.
        $api->owner_id = $user->user_id;

        // Get the form object.
        $form = new YbHorizForm('application.views.forms.apiForm', $api);

        // If the form was submitted...
        if ($form->submitted('yt0')) {

            // If the user making this change is NOT an admin...
            if ($user->role !== \User::ROLE_ADMIN) {

                // Make sure they are still set as the owner.
                $api->owner_id = $user->user_id;
            }
            
            // If the data passes validation...
            if ($form->validate()) {

                // Attempt to save the changes to the API (skipping validation,
                // since the data has already been validated). If successful...
                if ($api->save(false)) {

                    // Record that in the log.
                    Yii::log('API created: code "' . $api->code . '", ID ' . 
                             $api->api_id,
                            CLogger::LEVEL_INFO,
                            __CLASS__ . '.' . __FUNCTION__);

                    // Send the user to the details page for the new Api.
                    $this->redirect(array(
                        '/api/details/',
                        'code' => $api->code
                    ));
                }
                // Otherwise...
                else {

                    // Record that in the log.
                    Yii::log(
                        'API creation FAILED: code "' . $api->code . '"',
                        CLogger::LEVEL_ERROR,
                        __CLASS__ . '.' . __FUNCTION__
                    );

                    // Tell the user.
                    Yii::app()->user->setFlash(
                        'error',
                        '<strong>Error!</strong> We were unable to create that '
                        . 'API: <pre>' . print_r($api->getErrors(), true)
                        . '</pre>'
                    );
                }
            }
        }

        // If we reach this point, render the page.
        $this->render('add', array('form' => $form));
    }

    public function actionDelete($code)
    {
        // Get the current user's model.
        /* @var $user User */
        $user = \Yii::app()->user->user;
        
        // Get the API by the code name.
        /* @var $api Api */
        $api = Api::model()->findByAttributes(array('code' => $code));
        
        // If that is NOT an Api that the User has permission to manage, say so.
        if ( ! $user->hasAdminPrivilegesForApi($api)) {
            throw new CHttpException(
                403,
                'That is not an API that you have permission to manage.'
            );
        }
        
        // If the form has been submitted (POSTed)...
        if (Yii::app()->request->isPostRequest) {
            
            try {
                
                // Try to delete the API. If successful...
                if ($api->delete()) {
                    
                    // Record that in the log.
                    Yii::log(
                        'API deleted: ID ' . $api->api_id,
                        CLogger::LEVEL_INFO,
                        __CLASS__ . '.' . __FUNCTION__
                    );

                    // Tell the user.
                    Yii::app()->user->setFlash(
                        'success',
                        '<strong>Success!</strong> API deleted.'
                    );

                    // Send the user back to the list of APIs.
                    $this->redirect(array('/api/'));
                }
                // Otherwise...
                else {

                    // Record that in the log.
                    Yii::log(
                        'API deletion FAILED: ID ' . $api->api_id,
                        CLogger::LEVEL_ERROR,
                        __CLASS__ . '.' . __FUNCTION__
                    );

                    // Tell the user.
                    Yii::app()->user->setFlash(
                        'error',
                        '<strong>Error!</strong> Unable to delete that API: '
                        . '<pre>' . print_r($api->getErrors(), true) . '</pre>'
                    );
                }
            }
            catch (CDbException $ex) {
                
                // Record that in the log.
                Yii::log(
                    'API deletion FAILED: ID ' . $api->api_id . ', '
                    . 'CDbException thrown',
                    CLogger::LEVEL_ERROR,
                    __CLASS__ . '.' . __FUNCTION__
                );

                // Tell the user.
                Yii::app()->user->setFlash(
                    'error',
                    '<strong>Error!</strong> Unable to delete that API. It is '
                    . 'possible that the Keys and/or Key Requests were not '
                    . 'successfully deleted, preventing the API from being '
                    . 'deleted.'
                );
                
                Yii::trace($ex->getMessage());
            }
        }
        
        // Get the list of all Keys to this API.
        $criteria = new CDbCriteria;
        $criteria->compare('api_id', $api->api_id);
        $keyList = new CActiveDataProvider('Key',
            array('criteria' => $criteria)
        );
        
        // Show the page.
        $this->render('delete', array(
            'api'  => $api,
            'keyList' => $keyList,
        ));
    }

    public function actionDetails($code)
    {
        // Get the current user's model.
        /* @var $user User */
        $user = \Yii::app()->user->user;
        
        // Get the API by the code name.
        /* @var $api Api */
        $api = Api::model()->findByAttributes(array('code' => $code));
        
        // If no such Api was found 
        //    OR
        // if the Api isn't visible to the current user... say so.
        if (($api === null) || ( ! $api->isVisibleToUser($user))) {
            throw new CHttpException(
                404,
                'Either there is no "' . $code . '" API or you do not have '
                . 'permission to view it.'
            );
        }
        
        // Get the list of action links that should be shown.
        $actionLinks = LinksManager::getApiDetailsActionLinksForUser(
            $api,
            $user
        );
        
        // Render the page.
        $this->render('details', array(
            'actionLinks' => $actionLinks,
            'api' => $api,
        ));
    }

    public function actionDocsEdit($code)
    {
        // Get the current user's model.
        /* @var $user User */
        $user = \Yii::app()->user->user;
        
        // Get the API by the code name.
        /* @var $api Api */
        $api = Api::model()->findByAttributes(array('code' => $code));
        
        // If that is NOT an Api that the User has permission to manage, say so.
        if ( ! $user->hasAdminPrivilegesForApi($api)) {
            throw new CHttpException(
                403,
                'That is not an API that you have permission to manage.'
            );
        }
        
        // Make a note of what the Api's current owner_id is (in case we need to
        // prevent changes to it).
        $apiOwnerId = $api->owner_id;
        
        // Get the form object.
        $form = new YbHorizForm('application.views.forms.apiDocsForm', $api);
        
        // If the form was submitted...
        if ($form->submitted('yt0')) {

            // If the user making this change is NOT an admin...
            if ($user->role !== \User::ROLE_ADMIN) {

                // Make sure they didn't change the owner_id.
                $api->owner_id = $apiOwnerId;
            }
            
            // If the data passes validation...
            if ($form->validate()) {

                // Attempt to save the changes to the API (skipping validation,
                // since the data has already been validated). If successful...
                if ($api->save(false)) {

                    // Record that in the log.
                    Yii::log(
                        'API docs updated: ID ' . $api->api_id,
                        CLogger::LEVEL_INFO,
                        __CLASS__ . '.' . __FUNCTION__
                    );

                    // Send the user back to the appropriate page.
                    $this->redirect(array('/api/details/',
                        'code' => $api->code,
                    ));
                }
                // Otherwise...
                else {

                    // Record that in the log.
                    Yii::log(
                        'API docs update FAILED: ID ' . $api->api_id,
                        CLogger::LEVEL_ERROR,
                        __CLASS__ . '.' . __FUNCTION__
                    );

                    // Tell the user.
                    Yii::app()->user->setFlash(
                        'error',
                        '<strong>Error!</strong> We were unable to save your '
                        . 'changes to the API documentation: <pre>'
                        . print_r($api->getErrors(), true) . '</pre>'
                    );
                }
            }
        }
        
        // If we reach this point, render the page.
        $this->render('docsEdit', array(
            'form' => $form,
        ));
    }

    public function actionEdit($code)
    {
        // Get the current user's model.
        /* @var $user User */
        $user = \Yii::app()->user->user;
        
        // Get the API by the code name.
        /* @var $api Api */
        $api = Api::model()->findByAttributes(array('code' => $code));
        
        // If that is NOT an Api that the User has permission to manage, say so.
        if ( ! $user->hasAdminPrivilegesForApi($api)) {
            throw new CHttpException(
                403,
                'That is not an API that you have permission to manage.'
            );
        }
        
        // Make a note of what the Api's current owner_id is (in case we need to
        // prevent changes to it).
        $apiOwnerId = $api->owner_id;
        
        // Get the form object.
        $form = new YbHorizForm('application.views.forms.apiForm', $api);
        
        // If the form was submitted...
        if ($form->submitted('yt0')) {
            
            // If the user making this change is NOT an admin...
            if ($user->role !== \User::ROLE_ADMIN) {

                // Make sure they didn't change the owner_id.
                $api->owner_id = $apiOwnerId;
            }
            
            // If the data passes validation...
            if ($form->validate()) {

                // Attempt to save the changes to the API (skipping validation,
                // since the data has already been validated). If successful...
                if ($api->save(false)) {

                    // Record that in the log.
                    Yii::log(
                        'API updated: ID ' . $api->api_id,
                        CLogger::LEVEL_INFO,
                        __CLASS__ . '.' . __FUNCTION__
                    );

                    // Send the user back to the API details page.
                    $this->redirect(array('/api/details/', 'code' => $api->code));
                }
                // Otherwise...
                else {

                    // Record that in the log.
                    Yii::log(
                        'API update FAILED: ID ' . $api->api_id,
                        CLogger::LEVEL_ERROR,
                        __CLASS__ . '.' . __FUNCTION__
                    );

                    // Tell the user.
                    Yii::app()->user->setFlash(
                        'error',
                        '<strong>Error!</strong> We were unable to save your '
                        . 'changes to the API: <pre>'
                        . print_r($api->getErrors(), true) . '</pre>'
                    );
                }
            }
        }
        
        // If we reach this point, render the page.
        $this->render('edit', array(
            'form' => $form,
        ));
    }
    
    public function actionIndex()
    {
        // Get the current user's model.
        $user = \Yii::app()->user->user;
        
        // If the user is an admin, get the list of all APIs.
        if ($user->role === User::ROLE_ADMIN) {
            $apiList = new CActiveDataProvider('Api',array(
                'criteria' => array(
                    'with' => array('keyCount', 'pendingKeyCount'),
                ),
            ));
        } else {
            
            // Otherwise, get the list of APIs that should be visible to the
            // current user.
            $visibleApis = array();
            $allApis = Api::model()->findAll();
            foreach ($allApis as $api) {
                if ($api->isVisibleToUser($user)) {
                    $visibleApis[] = $api;
                }
            }
            $apiList = new CArrayDataProvider($visibleApis, array(
                'keyField' => 'api_id',
                'sort' => array(
                    'attributes' => array(
                        'display_name',
                    ),
                ),
            ));
        }
        
        // Render the page.
        $this->render('index', array(
            'apiList' => $apiList,
            'user' => $user,
        ));
    }

    /**
     * Render or process request to test an API
     */
    public function actionPlayground()
    {
        $req = Yii::app()->request;

        /**
         * Initialize variables in case this is a GET request or in case there
         * are errors during POST processing
         */
        $download = false;
        $method = false;
        $reqPath = false;
        $responseBody = $responseHeaders = $requestUrl = $apiRequest = $apiRequestBody = $responseSyntax = false;
        $params = array(
            array(
                'type' => null,
                'name' => null,
                'value' => null,
            )
        );
        
        // Record the key_id that the user provided (if any).
        $keyId = $req->getParam('key_id', false);
        
        if($req->isPostRequest){
            // Override remaining variables with what was submitted.
            $method = $req->getParam('method','GET');
            $reqPath = $req->getParam('path','');
            $params = $req->getParam('param',false);
            $download = $req->getParam('download',false);
            
            // Create copy of $reqPath for manipulation
            $path = $reqPath;
            
            /**
             * Get Key object and make sure this user owns the key
             */
            $key = Key::model()->findByAttributes(array('key_id' => $keyId));
            if($key && $key->user_id == Yii::app()->user->getId()){
                // Create a single dimension parameter array from parameters
                // submitted divided by form and header parameters
                $paramsForm = $paramsHeader = array();
                if($params && is_array($params)){
                    foreach($params as $param){
                        if(isset($param['name']) && isset($param['value']) 
                                && $param['name'] != '' && $param['value'] != ''
                                && !is_null($param['name']) && !is_null($param['value'])){

                            // Determine if parameter is supposed to be form based or header
                            if(isset($param['type']) && $param['type'] == 'form'){
                                $paramsForm[$param['name']] = $param['value'];
                            } elseif(isset($param['type']) && $param['type'] == 'header'){
                                $paramsHeader[$param['name']] = $param['value'];
                            }
                        }
                    }
                }
                
                /**
                 * Figure out proxy domain to form url
                 */
                $proxyProtocol = parse_url(Yii::app()->params['apiaxle']['endpoint'], PHP_URL_SCHEME);
                $proxyDomain = parse_url(Yii::app()->params['apiaxle']['endpoint'], PHP_URL_HOST);
                $proxyDomain = str_replace('apiaxle.', '', $proxyDomain);
                
                // Build url from components
                $url = $proxyProtocol.'://'.$key->api->code.'.'.$proxyDomain.$path;

                /**
                 * Calculate signature for ApiAxle call
                 */
                $apiKey = $key->value;
                $apiSig = \CalcApiSig\HmacSigner::CalcApiSig($apiKey, $key->secret);

                $paramsQuery = array(
                    'api_key' => $apiKey,
                    'api_sig' => $apiSig,
                );

                /**
                 * If GET request, merge paramsForm into paramsQuery
                 */
                if ($method == 'GET') {
                    $paramsQuery = CMap::mergeArray($paramsQuery, $paramsForm);
                    $paramsForm = null;
                } elseif($method == 'POST' || $method == 'PUT') {
                    $apiRequestBody = PHP_EOL . PHP_EOL;
                    foreach ($paramsForm as $name => $value) {
                        $apiRequestBody .= $name . '=' . $value . PHP_EOL;
                    }
                }

                /**
                 * Create Guzzle client for making API call
                 */
                $client = new Guzzle\Http\Client();
                $request = $client->createRequest($method,$url,$paramsHeader,$paramsForm,array(
                    'query' => $paramsQuery,
                    'exceptions' => false,
                    'verify' => Yii::app()->params['apiaxle']['ssl_verifypeer'],
                ));

                $apiRequest = $request->getRawHeaders();
                $requestUrl = $request->getUrl();
                $response = $request->send();

                $responseHeaders = $response->getRawHeaders();
                $responseBody = $response->getBody(true);

                if($response->getContentType() == 'applicaton/json'){
                    $responseSyntax = 'javascript';
                } else {
                    $responseSyntax = 'markup';
                }

            } else {
                // Display an error
                Yii::app()->user->setFlash('error','Invalid API selected');
            }
            
        }
        
        /**
         * Get list of APIs that user has a key for
         */
        $apiOptions = Key::model()->findAllByAttributes(array('user_id' => Yii::app()->user->getId()));
        
        if(!$download){
            /**
             * Attempt to pretty print
             */
            if(isset($response) && substr_count($response->getContentType(), 'xml') > 0){
                $dom = new DOMDocument('1.0');
                $dom->preserveWhiteSpace = false;
                $dom->formatOutput = true;
                if($dom->loadXML($responseBody)){
                    $asString = $dom->saveXML();
                    if($asString){
                        $responseBody = $asString;
                    }
                }
            } elseif (isset($response) && substr_count($response->getContentType(), 'json') > 0) {
                $responseBody = Utils::pretty_json($responseBody);
            }

            $this->render('playground',array(
                'key_id' => $keyId,
                'method' => $method,
                'apiOptions' => $apiOptions,
                'params' => $params,
                'path' => $reqPath,
                'responseBody' => $responseBody,
                'responseHeaders' => $responseHeaders,
                'requestUrl' => $requestUrl,
                'apiRequest' => $apiRequest,
                'apiRequestBody' => $apiRequestBody,
                'responseSyntax' => $responseSyntax,
            ));
        } else {
            /**
             * We expect results to be either JSON, XML, or CSV. So we test if they
             * can be parsed as JSON and set headers appropriately. 
             */
            if(isset($response) && substr_count($response->getContentType(), 'json') > 0){
                header('Content-disposition: attachment; filename=results.json');
                header('Content-type: application/json');
            } elseif(isset($response) && substr_count($response->getContentType(), 'xml') > 0) {
                header('Content-disposition: attachment; filename=results.xml');
                header('Content-type: application/xml');
            } else {
                header('Content-disposition: attachment; filename=results.csv');
                header('Content-type: text/csv');
            }
            echo $responseBody;
        }
    }
    
    public function actionPendingKeys($code)
    {
        // Make sure the specified API exists.
        $api = \Api::model()->findByAttributes(array('code' => $code));
        
        // Get a reference to the current website user's User model.
        $user = \Yii::app()->user->user;
        
        // Prevent information about it from being seen by user's without
        // permission to see the specified API.
        if (( ! $api) || ( ! $api->isVisibleToUser($user))) {
            throw new CHttpException(
                404,
                'Either there is no "' . $code . '" API or you do not have '
                . 'permission to view it.'
            );
        }
        
        // Prevent this list of keys from being seen by anyone who is not
        // authorized to see them.
        if (( ! ($user instanceof User)) || ( ! $user->canSeeKeysForApi($api))) {
            throw new CHttpException(
                403,
                'You do not have permission to see the list of pending keys '
                . 'for the "' . $api->display_name . '" API.'
            );
        }
        
        // Get the list of pending key requests for that API.
        $pendingKeyRequests = new CActiveDataProvider('KeyRequest', array(
            'criteria' => array(
                'condition' => 'api.code = :code AND status = :status',
                'join' => 'LEFT JOIN api ON t.api_id = api.api_id',
                'params' => array(
                    ':code' => $code,
                    ':status' => \KeyRequest::STATUS_PENDING,
                ),
            ),
        ));
        
        // Show the page.
        $this->render('pendingKeys', array(
            'pendingKeyRequests' => $pendingKeyRequests,
            'api' => $api,
        ));
    }
    
    public function actionRequestKey($code)
    {
        // Get the API by the code name.
        /* @var $api Api */
        $api = Api::model()->findByAttributes(array('code' => $code));

        // Get the current user's model.
        /* @var $user User */
        $user = \Yii::app()->user->user;
        
        // If the user already has an active key to this API, show its details
        // instead.
        if ($user->hasActiveKeyToApi($api)) {
            \Yii::app()->user->setFlash(
                'info',
                sprintf(
                    '<strong>Note:</strong> You already have the following key '
                    . 'to the %s API.',
                    $api->display_name
                )
            );
            $key = \Key::model()->findByAttributes(array(
                'api_id' => $api->api_id,
                'user_id' => $user->user_id,
            ));
            $this->redirect(array('/key/details/', 'id' => $key->key_id));
        }

        // If the user already has a pending key request for this API, show its
        // details instead.
        if ($user->hasPendingKeyRequestForApi($api)) {
            \Yii::app()->user->setFlash(
                'info',
                sprintf(
                    '<strong>Note:</strong> You already have the following '
                    . 'pending key request for the %s API.',
                    $api->display_name
                )
            );
            $keyRequest = \KeyRequest::model()->findByAttributes(array(
                'api_id' => $api->api_id,
                'user_id' => $user->user_id,
                'status' => \KeyRequest::STATUS_PENDING,
            ));
            $this->redirect(array(
                '/key-request/details/',
                'id' => $keyRequest->key_request_id,
            ));
        }

        // Create a new KeyRequest object.
        $model = new KeyRequest;
        
        // If the form has been submitted...
        if (isset($_POST['KeyRequest'])) {
            
            // Record the submitted form data into the new KeyRequest object.
            $model->attributes = $_POST['KeyRequest'];
            
            // Also record the extra data it needs.
            $model->user_id = $user->user_id;
            $model->api_id = $api->api_id;
            $model->status = KeyRequest::STATUS_PENDING;
            
            // If the form submission was valid...
            if ($model->validate()) {
                
                // If this API is set to auto-approve key requests...
                if ($api->approval_type == API::APPROVAL_TYPE_AUTO) {
                    
                    // Record a few more details into the Key Request object.
                    $model->status = KeyRequest::STATUS_APPROVED;
                    $model->processed_by = $user->user_id;
                    
                    // Try to save this new Key Request to the database.
                    if ( ! $model->save()) {
                        throw new \Exception(
                            sprintf(
                                'Unable to create a key request. (Error: %s).',
                                var_export($model->getErrors(), true)
                            ),
                            1418849904
                        );
                    }
                    
                    // Attempt to create the requested Key.
                    $createResults = Key::createKey(
                        $api->api_id,
                        $user->user_id,
                        $model->key_request_id
                    );
                    
                    // If we FAILED to create the new Key...
                    if ( ! $createResults[0]) {
                        
                        // Record that in the log.
                        Yii::log(
                            'Key request auto-approval FAILED: User ID '
                            . $user->user_id . ', API ID ' . $api->api_id,
                            CLogger::LEVEL_ERROR,
                            __CLASS__ . '.' . __FUNCTION__
                        );

                        // Tell the user.
                        Yii::app()->user->setFlash(
                            'error',
                            '<strong>Error!</strong> Unable to create key: '
                            . '<br />' .  $createResults[1]
                        );
                    }
                    // Otherwise...
                    else {
                        
                        // Record that in the log.
                        Yii::log(
                            'Key request auto-approved: User ID '
                            . $user->user_id . ', API ID ' . $api->api_id
                            . ', Key ID ' . $createResults[1]->key_id,
                            CLogger::LEVEL_INFO,
                            __CLASS__ . '.' . __FUNCTION__
                        );

                        // Tell the user.
                        Yii::app()->user->setFlash(
                            'success',
                            '<strong>Success!</strong> Key created.'
                        );
                    }
                }
                // Otherwise (i.e. - this API is NOT set to auto-approve)...
                else {
                    
                    // Save the new Key Request to the database.
                    $model->save();

                    // Record that in the log.
                    Yii::log(
                        'Key requested: User ID ' . $user->user_id . ', API ID '
                        . $api->api_id,
                        CLogger::LEVEL_INFO,
                        __CLASS__ . '.' . __FUNCTION__
                    );
                    
                    // Tell the user the status.
                    Yii::app()->user->setFlash(
                        'success',
                        '<strong>Success!</strong> Key requested.'
                    );
                    
                    // NOTE: The KeyRequest model's beforeSave method should
                    //       have sent an email to the API Owner (if set) about
                    //       the pending key request.
                }

                // Send the user back to the API details page.
                $this->redirect(array(
                    '/api/details/',
                    'code' => $api->code,
                ));
            }
        }

        // If we reach this point, show the Request Key page.
        $this->render('requestKey', array(
            'api' => $api,
            'model' => $model,
        ));
    }
    
    public function actionUsage($code)
    {
        // Get the current user's model.
        /* @var $user User */
        $user = \Yii::app()->user->user;
        
        // Get the API by the code name.
        /* @var $api Api */
        $api = Api::model()->findByAttributes(array('code' => $code));
        
        // If that is NOT an Api that the User has permission to manage, say so.
        if ( ! $user->hasAdminPrivilegesForApi($api)) {
            throw new CHttpException(
                403,
                'That is not an API that you have permission to manage.'
            );
        }
        
        // Show the page.
        $this->render('usage', array(
            'api' => $api,
        ));
    }
}
