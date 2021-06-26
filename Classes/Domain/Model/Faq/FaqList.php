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
 * Last modified: 2021.06.25 at 21:19
 */

declare(strict_types=1);


namespace LaborDigital\T3faExample\Domain\Model\Faq;


use LaborDigital\T3ba\ExtBase\Domain\Model\BetterEntity;
use TYPO3\CMS\Extbase\Persistence\ObjectStorage;

class FaqList extends BetterEntity
{
    /**
     * @var string|null
     */
    protected $title;
    
    /**
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\LaborDigital\T3faExample\Domain\Model\Faq\FaqItem>
     */
    protected $items;
    
    /**
     * @return string|null
     */
    public function getTitle(): ?string
    {
        return $this->title;
    }
    
    /**
     * @param   string|null  $title
     *
     * @return FaqList
     */
    public function setTitle(?string $title): FaqList
    {
        $this->title = $title;
        
        return $this;
    }
    
    /**
     * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage
     */
    public function getItems(): ObjectStorage
    {
        return $this->items;
    }
    
    /**
     * @param   \TYPO3\CMS\Extbase\Persistence\ObjectStorage  $items
     *
     * @return FaqList
     */
    public function setItems(ObjectStorage $items): FaqList
    {
        $this->items = $items;
        
        return $this;
    }
}