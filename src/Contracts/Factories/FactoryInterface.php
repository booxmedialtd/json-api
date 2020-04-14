<?php namespace Boox\Forks\JsonApi\Contracts\Factories;

/**
 * Copyright 2015-2017 info@neomerx.com
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

use \Psr\Log\LoggerAwareInterface as PSR3;

use \Boox\Forks\JsonApi\Encoder\EncoderOptions;
use \Boox\Forks\JsonApi\Contracts\Encoder\EncoderInterface;
use \Boox\Forks\JsonApi\Contracts\Schema\ContainerInterface;
use \Boox\Forks\JsonApi\Contracts\Codec\CodecMatcherInterface;

use \Boox\Forks\JsonApi\Contracts\Http\HttpFactoryInterface as HttpFI;
use \Boox\Forks\JsonApi\Contracts\Schema\SchemaFactoryInterface as SchFI;
use \Boox\Forks\JsonApi\Contracts\Document\DocumentFactoryInterface as DFI;
use \Boox\Forks\JsonApi\Contracts\Encoder\Stack\StackFactoryInterface as StkFI;
use \Boox\Forks\JsonApi\Contracts\Encoder\Parser\ParserFactoryInterface as PrsFI;
use \Boox\Forks\JsonApi\Contracts\Encoder\Handlers\HandlerFactoryInterface as HFI;

/**
 * @package Boox\Forks\JsonApi
 */
interface FactoryInterface extends DFI, PrsFI, StkFI, HFI, HttpFI, SchFI, PSR3
{
    /**
     * Create encoder.
     *
     * @param ContainerInterface  $container
     * @param EncoderOptions|null $encoderOptions
     *
     * @return EncoderInterface
     */
    public function createEncoder(ContainerInterface $container, EncoderOptions $encoderOptions = null);

    /**
     * Create codec matcher.
     *
     * @return CodecMatcherInterface
     */
    public function createCodecMatcher();
}
