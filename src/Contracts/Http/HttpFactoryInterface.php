<?php namespace Boox\Forks\JsonApi\Contracts\Http;

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

use \Boox\Forks\JsonApi\Contracts\Schema\ContainerInterface;
use \Boox\Forks\JsonApi\Contracts\Codec\CodecMatcherInterface;
use \Boox\Forks\JsonApi\Contracts\Http\Headers\HeaderInterface;
use \Boox\Forks\JsonApi\Contracts\Http\Headers\MediaTypeInterface;
use \Boox\Forks\JsonApi\Contracts\Http\Query\QueryCheckerInterface;
use \Boox\Forks\JsonApi\Contracts\Http\Headers\AcceptHeaderInterface;
use \Boox\Forks\JsonApi\Contracts\Http\Headers\HeadersCheckerInterface;
use \Boox\Forks\JsonApi\Contracts\Http\Headers\AcceptMediaTypeInterface;
use \Boox\Forks\JsonApi\Contracts\Http\Headers\HeaderParametersInterface;
use \Boox\Forks\JsonApi\Contracts\Encoder\Parameters\SortParameterInterface;
use \Boox\Forks\JsonApi\Contracts\Http\Query\QueryParametersParserInterface;
use \Boox\Forks\JsonApi\Contracts\Http\Headers\SupportedExtensionsInterface;
use \Boox\Forks\JsonApi\Contracts\Http\Headers\HeaderParametersParserInterface;
use \Boox\Forks\JsonApi\Contracts\Encoder\Parameters\EncodingParametersInterface;
use \Boox\Forks\JsonApi\Contracts\Encoder\Parameters\ParametersAnalyzerInterface;

/**
 * @package Boox\Forks\JsonApi
 */
interface HttpFactoryInterface
{
    /**
     * Create parameter analyzer.
     *
     * @param EncodingParametersInterface $parameters
     * @param ContainerInterface          $container
     *
     * @return ParametersAnalyzerInterface
     */
    public function createParametersAnalyzer(EncodingParametersInterface $parameters, ContainerInterface $container);

    /**
     * Create media type.
     *
     * @param string                    $type
     * @param string                    $subType
     * @param array<string,string>|null $parameters
     *
     * @return MediaTypeInterface
     */
    public function createMediaType($type, $subType, $parameters = null);

    /**
     * Create parameters.
     *
     * @param string[]|null                 $includePaths
     * @param array|null                    $fieldSets
     * @param SortParameterInterface[]|null $sortParameters
     * @param array|null                    $pagingParameters
     * @param array|null                    $filteringParameters
     * @param array|null                    $unrecognizedParams
     *
     * @return EncodingParametersInterface
     */
    public function createQueryParameters(
        $includePaths = null,
        array $fieldSets = null,
        $sortParameters = null,
        array $pagingParameters = null,
        array $filteringParameters = null,
        array $unrecognizedParams = null
    );

    /**
     * @param string                $method
     * @param AcceptHeaderInterface $accept
     * @param HeaderInterface       $contentType
     *
     * @return HeaderParametersInterface
     */
    public function createHeaderParameters($method, AcceptHeaderInterface $accept, HeaderInterface $contentType);

    /**
     * @param string                $method
     * @param AcceptHeaderInterface $accept
     *
     * @return HeaderParametersInterface
     */
    public function createNoContentHeaderParameters($method, AcceptHeaderInterface $accept);

    /**
     * Create parameters parser.
     *
     * @return QueryParametersParserInterface
     */
    public function createQueryParametersParser();

    /**
     * Create parameters parser.
     *
     * @return HeaderParametersParserInterface
     */
    public function createHeaderParametersParser();

    /**
     * Create sort parameter.
     *
     * @param string $sortField
     * @param bool   $isAscending
     *
     * @return SortParameterInterface
     */
    public function createSortParam($sortField, $isAscending);

    /**
     * Create supported extensions.
     *
     * @param string $extensions
     *
     * @return SupportedExtensionsInterface
     */
    public function createSupportedExtensions($extensions = MediaTypeInterface::NO_EXT);

    /**
     * Create media type for Accept HTTP header.
     *
     * @param int                       $position
     * @param string                    $type
     * @param string                    $subType
     * @param array<string,string>|null $parameters
     * @param float                     $quality
     * @param array<string,string>|null $extensions
     *
     * @return AcceptMediaTypeInterface
     */
    public function createAcceptMediaType(
        $position,
        $type,
        $subType,
        $parameters = null,
        $quality = 1.0,
        $extensions = null
    );

    /**
     * Create Accept HTTP header.
     *
     * @param AcceptMediaTypeInterface[] $unsortedMediaTypes
     *
     * @return AcceptHeaderInterface
     */
    public function createAcceptHeader($unsortedMediaTypes);

    /**
     * Create header parameters checker.
     *
     * @param CodecMatcherInterface $codecMatcher
     *
     * @return HeadersCheckerInterface
     */
    public function createHeadersChecker(CodecMatcherInterface $codecMatcher);

    /**
     * Create query parameters checker.
     *
     * @param bool|false $allowUnrecognized
     * @param array|null $includePaths
     * @param array|null $fieldSetTypes
     * @param array|null $sortParameters
     * @param array|null $pagingParameters
     * @param array|null $filteringParameters
     *
     * @return QueryCheckerInterface
     *
     * @SuppressWarnings(PHPMD.BooleanArgumentFlag)
     */
    public function createQueryChecker(
        $allowUnrecognized = false,
        array $includePaths = null,
        array $fieldSetTypes = null,
        array $sortParameters = null,
        array $pagingParameters = null,
        array $filteringParameters = null
    );
}
