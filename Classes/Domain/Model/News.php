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
 * Last modified: 2021.05.31 at 14:03
 */

declare(strict_types=1);


namespace LaborDigital\T3faExample\Domain\Model;


use TYPO3\CMS\Extbase\Domain\Model\FileReference;
use TYPO3\CMS\Extbase\DomainObject\AbstractEntity;
use TYPO3\CMS\Extbase\Persistence\ObjectStorage;

class News extends AbstractEntity
{
    /**
     * @var string|null
     */
    protected $title;
    
    /**
     * @var string|null
     */
    protected $teaser;
    
    /**
     * @var string|null
     */
    protected $body;
    
    /**
     * @var \TYPO3\CMS\Extbase\Domain\Model\FileReference|null
     */
    protected $bannerImage;
    
    /**
     * @var \DateTime
     */
    protected $date;
    
    /**
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\TYPO3\CMS\Extbase\Domain\Model\Category>
     */
    protected $categories;
    
    /**
     * @return string|null
     */
    public function getTitle(): ?string
    {
        return $this->title;
    }
    
    /**
     * @return string|null
     */
    public function getTeaser(): ?string
    {
        return $this->teaser;
    }
    
    /**
     * @return string|null
     */
    public function getBody(): ?string
    {
        return $this->body;
    }
    
    /**
     * @return \TYPO3\CMS\Extbase\Domain\Model\FileReference|null
     */
    public function getBannerImage(): ?FileReference
    {
        return $this->bannerImage;
    }
    
    /**
     * @return \DateTime
     */
    public function getDate(): ?\DateTime
    {
        return $this->date;
    }
    
    /**
     * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage
     */
    public function getCategories(): ?ObjectStorage
    {
        return $this->categories;
    }
    
}