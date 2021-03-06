<?php

class KeyController extends Controller
{
    public $layout = '//layouts/one-column-with-title';
    
    public function actionAll()
    {
        // Get the list of all active Keys.
        $keysDataProvider = new CActiveDataProvider('Key');
        
        // Render the page.
        $this->render('all', array(
            'keysDataProvider' => $keysDataProvider,
        ));
    }
    
    public function actionDelete($id)
    {
        // Get a reference to the current website user's User model.
        /* @var $user User */
        $user = \Yii::app()->user->user;
        
        // Try to retrieve the specified Key's data.
        /* @var $key Key */
        $key = \Key::model()->findByPk($id);
        
        // If this is not a Key that the current User is allowed to
        // delete/revoke, say so.
        if ( ! $user->canRevokeKey($key)) {
            throw new CHttpException(
                403,
                'That is not a Key that you have permission to delete.'
            );
        }
        
        // If the form has been submitted (POSTed)...
        if (Yii::app()->request->isPostRequest) {
            
            // Revoke the key, paying attention to the results.
            $revokeResults = Key::revokeKey($key->key_id);
            
            // If we were unable to delete that Key...
            if ( ! $revokeResults[0]) {
                
                // Record that in the log.
                Yii::log(
                    'Key deletion/revokation FAILED: ID ' . $key->key_id,
                    CLogger::LEVEL_ERROR,
                    __CLASS__ . '.' . __FUNCTION__
                );

                // Tell the user.
                Yii::app()->user->setFlash(
                    'error',
                    '<strong>Error!</strong> Unable to delete key: <pre>'
                    . $revokeResults[1] . '</pre>'
                );
            }
            // Otherwise...
            else {
                
                // Record that in the log.
                Yii::log(
                    'Key deleted/revoked: ID ' . $key->key_id,
                    CLogger::LEVEL_INFO,
                    __CLASS__ . '.' . __FUNCTION__
                );
                
                // If the user deleting the key is the owner of the key...
                if ($key->isOwnedBy($user)) {
                    
                    // Try to also delete the key request (because we don't need
                    // any record of the key being revoked, since it wasn't
                    // revoked, it was deleted).
                    if ($key->keyRequest) {
                        $key->keyRequest->delete();
                    }
                }

                // Tell the user.
                Yii::app()->user->setFlash(
                    'success',
                    '<strong>Success!</strong> Key deleted.'
                );
            }
            
            // Send the user back to the list of Keys.
            $this->redirect(array('/key/'));
        }
        
        // Show the page.
        $this->render('delete', array(
            'key' => $key,
        ));
    }

    public function actionDetails($id)
    {
        // Get a reference to the current website user's User model.
        $user = \Yii::app()->user->user;
        
        // Try to retrieve the specified Key's data.
        $key = \Key::model()->findByAttributes(array('key_id' => $id));
        
        // Prevent access by users without permission to see this key.
        if (( ! $key) || ( ! $key->isVisibleToUser($user))) {
            throw new CHttpException(
                404,
                'Either there is no key with an ID of ' . $id . ' or you do '
                . 'not have permission to view it.'
            );
        }
        
        // Render the page.
        $this->render('details', array('key' => $key));
    }

    public function actionIndex()
    {
        // Get a reference to the current website user's User model.
        $user = \Yii::app()->user->user;
        
        // If the user is an admin, redirect them to the list of all keys.
        // Otherwise redirect them to the list of their keys.
        if ($user->role === \User::ROLE_ADMIN) {
            $this->redirect(array('/key/all'));
        } else {
            $this->redirect(array('/key/mine'));
        }
    }

    public function actionReset($id)
    {
        // Get a reference to the current website user's User model.
        /* @var $user User */
        $user = \Yii::app()->user->user;
        
        // Try to retrieve the specified Key's data.
        /* @var $key Key */
        $key = \Key::model()->findByPk($id);
        
        // If this is not a Key that the current User is allowed to reset, say
        // so.
        if ( ! $user->canResetKey($key)) {
            throw new CHttpException(
                403,
                'That is not a Key that you have permission to reset.'
            );
        }

        // If the form has been submitted (POSTed)...
        if (Yii::app()->request->isPostRequest) {
            
            // Reset the key, paying attention to the results.
            $resetResults = Key::resetKey($key->key_id);
            
            // If we were unable to reset that Key...
            if ( ! $resetResults[0]) {
                
                // Record that in the log.
                Yii::log(
                    'Key reset FAILED: ID ' . $key->key_id,
                    CLogger::LEVEL_ERROR,
                    __CLASS__ . '.' . __FUNCTION__
                );

                // Tell the user.
                Yii::app()->user->setFlash(
                    'error',
                    '<strong>Error!</strong> Unable to reset key: <pre>'
                    . $resetResults[1] . '</pre>'
                );
            }
            // Otherwise...
            else {
                
                // Record that in the log.
                Yii::log(
                    'Key reset: ID ' . $key->key_id,
                    CLogger::LEVEL_INFO,
                    __CLASS__ . '.' . __FUNCTION__
                );

                // Tell the user.
                Yii::app()->user->setFlash(
                    'success',
                    '<strong>Success!</strong> Key reset.'
                );
            }
            
            // Send the user back to the Key details.
            $this->redirect(array(
                '/key/details/',
                'id' => $key->key_id
            ));
        }
        
        // Show the page.
        $this->render('reset', array(
            'key' => $key,
        ));
    }
    
    public function actionMine()
    {
        // Get the current user's model.
        $user = \Yii::app()->user->user;
        
        // Get the list of the user's (active) keys (as a data provider for the
        // view).
        $activeKeysDataProvider = new CArrayDataProvider(
            $user->keys,
            array(
                'keyField' => 'key_id',
            )
        );
        
        // Get the list of the user's non-active key requests (as a data
        // provider for the view).
        $allKeyRequests = $user->keyRequests;
        $nonActiveKeyRequests = array();
        foreach ($allKeyRequests as $keyRequest) {
            if ($keyRequest->status !== \KeyRequest::STATUS_APPROVED) {
                $nonActiveKeyRequests[] = $keyRequest;
            }
        }
        $nonActiveKeyRequestsDataProvider = new CArrayDataProvider(
            $nonActiveKeyRequests,
            array(
                'keyField' => 'key_request_id',
            )
        );

        // Render the page.
        $this->render('mine', array(
            'activeKeysDataProvider' => $activeKeysDataProvider,
            'nonActiveKeyRequestsDataProvider' => $nonActiveKeyRequestsDataProvider,
        ));
    }
}
