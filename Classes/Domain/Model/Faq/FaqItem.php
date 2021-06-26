<?php
/*
 * Copyright 2021 LABOR.digital
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *     http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 *
 * Last modified: 2021.06.25 at 20:47
 */

declare(strict_types=1);


namespace LaborDigital\T3faExample\Domain\Model\Faq;


use LaborDigital\T3ba\ExtBase\Domain\Model\BetterEntity;

class FaqItem extends BetterEntity
{
    /**
     * @var string|null
     */
    protected $question;
    
    /**
     * @var string|null
     */
    protected $answer;
    
    /**
     * @return string|null
     */
    public function getQuestion(): ?string
    {
        return $this->question;
    }
    
    /**
     * @param   string|null  $question
     *
     * @return FaqItem
     */
    public function setQuestion(?string $question): FaqItem
    {
        $this->question = $question;
        
        return $this;
    }
    
    /**
     * @return string|null
     */
    public function getAnswer(): ?string
    {
        return $this->answer;
    }
    
    /**
     * @param   string|null  $answer
     *
     * @return FaqItem
     */
    public function setAnswer(?string $answer): FaqItem
    {
        $this->answer = $answer;
        
        return $this;
    }
}