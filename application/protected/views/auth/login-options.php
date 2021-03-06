<?php
/* @var $this AuthController */
/* @var $loginOptions array */

// Set the page title.
$this->pageTitle = 'Login Options';

echo '<h2>' . \CHtml::encode($this->pageTitle) . '</h2> ';

foreach ($loginOptions as $displayName => $loginUrl) {
    $htmlEncodedDisplayName = \CHtml::encode($displayName);
    echo sprintf(
        '<a href="%s" class="btn">Log in using %s</a> ',
        $loginUrl,
        $htmlEncodedDisplayName
    );
}
