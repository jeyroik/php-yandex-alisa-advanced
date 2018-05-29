<?php
namespace jeyroik\alice\dispatchers\skill_alias;

use jeyroik\alice\dispatchers\DispatcherAnswerAbstract;

/**
 * Class DispatcherAnswerTest
 *
 * Example of a simple dispatcher.
 *
 * @author jeyroik <jeyroik@gmail.com>
 */
class DispatcherAnswerTest extends DispatcherAnswerAbstract
{
    protected $isCommand = false;
    protected $isButton = false;

    public function isApplicable()
    {
        $text = $this->request['request']['command'];
        $textToCheck = trim(mb_strtolower($text));
        
        /**
         * // Раскомментируйте этот кусок кода, чтобы использовать стандартную логику сравнения исходного текста.
         * // Uncomment this code to use default comparing logic.
         * 
         * // Замените "тест" на текст, который должен был ввести пользователь, чтобы текущий обработчик сработал.
         * // Replace "тест" to text, which user should be typed or said.
         * $this->isCommand = !$this->isButtonPressed() && ($textToCheck == 'тест');
         * 
         * // Тут ничего менять не надо.
         * // Do not change here anything.
         * if ($this->isButtonPressed()) {
         *   if ($this->request['request']['payload'] != '[]') {
         *       $payload = json_decode($this->request['request']['payload'], true);
         *       if (isset($payload['command'])) {
         *           $this->isButton = true;
         *       }
         *   }
         * }
         * // Не забудьте удалить нижний return true, когда раскомментируете этот кусок кода.
         * // Do not forget to delete "return true" below.
         * return $this->isCommand || $this->isButton;
         */
        
        return true;
    }
    
    public function run()
    {
        return $this->alisa->buildResponse(
            $this->request, 
            /**
             * Вставьте сюда текст, который вернётся пользователю в ответ.
             * Place here a text for returning to user as answer.
             */
            'Приветствую! Я тестовый обработчик, пожалуйста, не используйте меня в продакшене.',
            
            /**
             * Это tts - подсказки для Алисы по произношению текста. Оставьте поле пустым, если не умеет этим пользоваться.
             * This is tts - tips for Alice for text pronounce. Left this field empty if do not know how to use it.
             */
            'Приветствую - я - тестовый обработчик - пожалуйста - не используйте меня в прод+акшене'
        );
    }
}
