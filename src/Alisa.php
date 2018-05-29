<?php
namespace jeyroik\alice;

/**
 * Class Alice
 * 
 * Basic class.
 *
 * @env_param ALISA__SKILLS_PATH
 * @env_param ALISA__DISPATCHERS_PATH
 * 
 * @author jeyroik <jeyroik@gmail.com>
 */
class Alice
{
    /**
     * Skills list.
     * @var array skill_token => skill_alias
     */
    protected $skills = [];
    
    /**
     * Alice request
     */
    protected $request = [];
    
    /**
     * Response to Yandex
     */
    protected $response = [];
    
    /**
     * Run request dispatching
     *
     * @return void
     */
    public static function run()
    {
        try {
            $static = new static();
            $static->response();
        } catch (\Exception $e) {
            // todo rebuild by Monolog
            file_put_contents('alisa_v2_log.txt', PHP_EOL . date('Y-m-d H:i:s') . PHP_EOL . $e->getMessage() . PHP_EOL, FILE_APPEND);
        }
    }
    
    /**
     * @var array $request
     */
    public function __construct($request = [])
    {
        $this->request = $request ?: file_get_contents('php://input');
        
        $skillsPath = getenv('ALISA__SKILLS_PATH') ?: ALISA__ROOT . '/configs/skills.php';
        
        if (is_file($skillsPath)) {
            $this->skills = include $skillsPath;
        }
    }
    
    /**
     * @return $this
     */
    public function response()
    {
        $isRequestValid = $this->validateRequest();
        
        if (!$isRequestValid) {
            return $this->returnResponse();
        }
        
        $dispatchersPath = getenv('ALISA__DISPATCHERS_PATH') ?: ALISA__ROOT . '/configs/skills_dispatchers.php';
        
        if (!is_file($dispatchersPath)) {
            $this->setError('Произошла ошибка сервера: ADPM. Обратитесь к администратору навыка.');
            
            return $this->returnResponse();
        }
        
        $dispatchers = include $dispatchersPath;
        $skill = $this->getSkill();
        
        if (!isset($dispatchers[$skill])) {
            $this->setError('Unknown skill - Неизвестный навык - ' . $skill);
            
            return $this->returnResponse();
        }
        
        $dispatchers = $dispatchers[$skill];
        
        foreach($dispatchers as $dispatcherClass) {
            $dispatcher = new $dispatcherClass($this->request, $this->response, $this);
            if ($dispatcher->isApplicable()) {
                $this->response = $dispatcher->run();
            }
        }
        
        return $this->returnResponse();
    }
    
    /**
     * @return $this
     */
    protected function returnResponse()
    {
        header('Content-Type: application/json');
        echo is_array($this->response) ? json_encode($this->reponse) : $this->response;
        
        return $this;
    }
    
    /**
     * @return string
     */
    protected function getSkill()
    {
        $skillId =  $this->request['session']['skill_id'];
        
        return $this->skills[$skillId] ?? '';
    }
    
    /**
     * @var string $message
     *
     * @return $this
     */
    protected function setError($message)
    {
        $this->response = $this->buildResponse([], $message, '@');
        
        return $this;
    }
    
    /**
     * @return bool
     */
    protected function validateRequest()
    {
        if (empty($this->request)) {
            $this->setError('Отсутствуют данные');
            return false;
        }
        
        if (is_string($this->request)) {
            try {
                $decoded = json_decode($this->request, true);
            } catch (\Exception $e) {
                $this->setError('Incorrect request - Некорректный запрос');
                
                return false;
            }
            
            $this->request = $decoded;
        }
        
        if (!isset($this->request['session'], $this->request['request'])) {
            $this->setError('Missed required sections session, request - Отсутствуют необходимые секции session, request');
            
            return false;
        }
        
        if (!isset($this->request['request']['type'])) {
            $this->setError('Missed request type - Отсутствует тип запроса');
            
            return false;
        }
        
        $type = $this->request['request']['type'];
        
        if (method_exists($this, 'validate' . $type)) {
            return $this->{'validate' . $type}();
        }
        
        $this->setError('Unknown request type - Неизвестный тип запроса');
        
        return false;
    }
    
    /**
     * @return bool
     */
    protected function validateSimpleUtterance()
    {
        if (!isset($this->request['request']['command'])) {
            $this->setError('Отсутствует поле command');
            
            return false;
        }
        
        return true;
    }
    
    /**
     * @return bool
     */
    protected function validateButtonPressed()
    {
        if (!isset($this->request['request']['payload'])) {
            $this->setError('Отсутствует поле payload');
            
            return false;
        }
        
        return true;
    }
    
    /**
     * @var mixed $data
     * @var string $text
     * @var string $tts
     * @var array $buttons
     * @var bool $endSession
     *
     * @return string
     */
    public function buildResponse($data, $text = '', $tts = '', $buttons = [], $endSession = false)
    {
        if (empty($data)) {
            $data = [
                'session' => [
                    'session_id' => '',
                    'message_id' => '',
                    'user_id' => ''
                ]
            ];
        }
        
        $text = $text ?: 'Готово';
        
        if ($tts == '@') {
            $tts = $text;
        }
        
        return json_encode([
            'version' => '1.0',
            'session' => [
                'session_id' => $data['session']['session_id'],
                'message_id' => $data['session']['message_id'],
                'user_id' => $data['session']['user_id']
            ],
            'response' => [
                'text' => $text,
                'tts' =>  $tts,
                'buttons' => $buttons,
                'end_session' => $endSession
            ]
        ]);
    }
}
