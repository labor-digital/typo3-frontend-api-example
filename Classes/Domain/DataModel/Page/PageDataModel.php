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
 * Last modified: 2021.06.04 at 16:36
 */

declare(strict_types=1);


namespace LaborDigital\T3faExample\Domain\DataModel\Page;

use LaborDigital\T3fa\Domain\DataModel\Page\DefaultPageDataModel;
use TYPO3\CMS\Extbase\Persistence\ObjectStorage;

// As you can see, we extend the build in page data model to inherit all basic fields and just
// extend upon them. Of course you can create a completely custom model by yourself if you want.
// This model will be mapped on the "pages" table and will have all configured "slide" fields applied when
// the data is resolved.
class PageDataModel extends DefaultPageDataModel
{
    /**
     * We want to map the "media" field to be included in our page data.
     * So we map the field like you would in any other extbase model
     *
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\TYPO3\CMS\Extbase\Domain\Model\FileReference>
     */
    protected $media;
    
    /**
     * The "getter" is important, because it is used by the transformer to retrieve your mapped data
     *
     * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage
     */
    public function getMedia(): ?ObjectStorage
    {
        return $this->media;
    }
}