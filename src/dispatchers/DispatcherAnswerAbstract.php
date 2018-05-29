<?php
namespace jeyroik\alice\dispatchers;

/**
 * Class DispatcherAnswerAbstract
 * 
 * @author jeyroik <jeyroik@gmail.com>
 */
class DispatcherAnswerAbstract
{
    protected $request = [];
    protected $response = [];
    
    /**
     * @var jeyroik\alice\Alice
     */
    protected $alice = null;
    
    /**
     * @var array $request
     * @var array $response
     * @var jeyroik\alice\Alice $alice
     */
    public function __construct($request, $response, $alice)
    {
        $this->request = $request;
        $this->response = $response;
        $this->alice = $alice;
    }
    
    /**
     * @return bool
     */
    protected function isButtonPressed()
    {
        return $this->request['request']['type'] == 'ButtonPressed';
    }
}
