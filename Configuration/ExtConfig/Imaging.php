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
 * Last modified: 2021.06.24 at 18:58
 */

declare(strict_types=1);


namespace LaborDigital\T3faExample\Configuration\ExtConfig;


use LaborDigital\T3ba\ExtConfig\ExtConfigContext;
use LaborDigital\T3fa\ExtConfigHandler\Imaging\ConfigureImagingApiInterface;
use LaborDigital\T3fa\ExtConfigHandler\Imaging\ImagingConfigurator;

class Imaging implements ConfigureImagingApiInterface
{
    /**
     * @inheritDoc
     */
    public static function configureImagingApi(ImagingConfigurator $imagingConfigurator, ExtConfigContext $context): void
    {
        // The imaging endpoint is a built in provider for web-optimized images served by the T3FA extension.
        // By default TYPO3 does the image processing when the HTML of a page is rendered.
        // This allows you to create cropped or resized images on the fly based on the requirements of your template.
        
        // In an API based approach, where you work with data and not necessarily a page template
        // it becomes quite a hassle to modify images based on your needs. Therefore imaging steps in
        // and automagically replaces all image-file references that are processed through the resource
        // transformation with a request on the imaging api endpoint.
        
        // The image urls look somewhat like this: https://api.host/imaging-api/filename.md5Hash.r3.jpg
        // Contained in the url is all the information needed for the api to determine the image and serve it to a webbrowser.
        // Imaging will automatically redirect the request using the 301 status to the actually, processed image that
        // looks like something you know in TYPO3: https://api.host/fileadmin/_processed_/9/1/filename.jpg.webp
        
        // As you can see, imaging will automatically detect if the browser supports webp and redirect the request
        // to a webp resource if possible.
        
        // Additionally you can apply processing information to any image served through the imaging api
        // To avoid attacks on open apis you have to configure all processing information that are allowed
        // in "definition" sets. After you have done that, you can require any image using that definition key like so:
        // .../imaging-api/filename.md5Hash.r3.jpg?definition=definitionKey
        
        // TYPO3 will then do the image processing on the fly when the resource was requested and redirect
        // the user to the processed file afterwards.
        
        // Imaging also provides built in support for "retina" images. When your frontend detects, that
        // the current devices supports retina images you can use the "x2" flag which will double every
        // processing definition by two. Meaning a "width" of 200 gets a 400 and so on:
        // .../imaging-api/filename.md5Hash.r3.jpg?x2=true
        
        // Furthermore the TYPO3 core supports "cropping" the image based on the information stored
        // in file reference objects. You can either select the "crop" variant to use in your definition
        // and even change the variant by requesting it directly .../imaging-api/filename.md5Hash.r3.jpg?crop=variantName
        
        // If you, for whatever reason don't want to use the imaging endpoint, you can either disable it
        // using the "disable" option, or by setting the "T3FA_IMAGING_DISABLED" environment variable
        // $imagingConfigurator->disable();
        
        // You can simply provide the image definitions through this configurator,
        // the syntax is the same that you know from the image view helper in a fluid template
        $imagingConfigurator->registerDefinition('small', ['width' => '250c'])
                            ->registerDefinition('square', ['width' => '400c', 'height' => '400c']);
        
        // Because the imaging endpoint runs so early in the TYPO3 lifecycle to prevent as much overhead
        // as possible, not everything can be configured through the configurator.
        // There are some environment variables to configure those aspects, tho:
        
        // - T3FA_IMAGING_REDIRECT_INFO_STORAGE_PATH
        // By default the imaging endpoint stores a cache of a file request and the matching, resolved redirect
        // url to provide faster lookup times. The data is stored under /var/t3fa_imaging by default.
        // You can provide another path to where the cache is stored using this variable.
        
        // - T3FA_IMAGING_BASE_URL
        // If an image redirect is resolved, the imaging endpoint will try to redirect the user to the
        // same domain that was used to generate the request. So https://foo.bar/imaging-api/request.jpg
        // becomes  https://foo.bar/fileadmin/processed/.../request.jpg
        // This variable allows you to change the https://foo.bar part to something of your needs.
    }
    
}