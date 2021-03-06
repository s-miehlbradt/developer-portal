<?php

class SiteControllerTest extends ControllerTestCase {
    
    public function setUp()
    {
        // Define what controller is expected to handle all of the tests in this
        // class.
        $this->expectedController = 'SiteController';
    }
    
    public function testRoutesIndex()
    {
        // Make sure the given URL paths are routed as expected.
        $this->checkUrlRoute('', '');
        $this->checkUrlRoute('/', '');
        $this->checkUrlRoute('/index.php', '');
        $this->checkUrlRoute('/index.php/', '');
        $this->checkUrlRoute('/index.php/site', '');
        $this->checkUrlRoute('/index.php/site/', '');
        $this->checkUrlRoute('/index.php/site/index', 'index');
        $this->checkUrlRoute('/index.php/site/index/', 'index');
    }
    
    
    
    
    
    // TODO: Set up test(s) for the error route (if necessary/possible).
    
    
    
    
}
