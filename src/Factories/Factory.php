<?php namespace Boox\Forks\JsonApi\Factories;

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

use \Closure;
use \Psr\Log\LoggerInterface;
use \Boox\Forks\JsonApi\Document\Link;
use \Boox\Forks\JsonApi\Document\Error;
use \Boox\Forks\JsonApi\Encoder\Encoder;
use \Boox\Forks\JsonApi\Schema\Container;
use \Boox\Forks\JsonApi\Document\Document;
use \Boox\Forks\JsonApi\Codec\CodecMatcher;
use \Boox\Forks\JsonApi\Encoder\Stack\Stack;
use \Boox\Forks\JsonApi\Encoder\Parser\Parser;
use \Boox\Forks\JsonApi\Schema\IdentitySchema;
use \Boox\Forks\JsonApi\Schema\ResourceObject;
use \Boox\Forks\JsonApi\Encoder\EncoderOptions;
use \Boox\Forks\JsonApi\Http\Headers\MediaType;
use \Boox\Forks\JsonApi\Encoder\Stack\StackFrame;
use \Boox\Forks\JsonApi\Http\Headers\AcceptHeader;
use \Boox\Forks\JsonApi\Schema\RelationshipObject;
use \Boox\Forks\JsonApi\Encoder\Parser\ParserReply;
use \Boox\Forks\JsonApi\Encoder\Parser\ParserManager;
use \Boox\Forks\JsonApi\Http\Headers\AcceptMediaType;
use \Boox\Forks\JsonApi\Http\Headers\HeaderParameters;
use \Boox\Forks\JsonApi\Encoder\Parser\ParserEmptyReply;
use \Boox\Forks\JsonApi\Contracts\Document\LinkInterface;
use \Boox\Forks\JsonApi\Encoder\Parameters\SortParameter;
use \Boox\Forks\JsonApi\Http\Headers\SupportedExtensions;
use \Boox\Forks\JsonApi\Http\Query\QueryParametersParser;
use \Boox\Forks\JsonApi\Encoder\Handlers\ReplyInterpreter;
use \Boox\Forks\JsonApi\Http\Query\RestrictiveQueryChecker;
use \Boox\Forks\JsonApi\Contracts\Schema\ContainerInterface;
use \Boox\Forks\JsonApi\Http\Headers\HeaderParametersParser;
use \Boox\Forks\JsonApi\Contracts\Document\DocumentInterface;
use \Boox\Forks\JsonApi\Contracts\Factories\FactoryInterface;
use \Boox\Forks\JsonApi\Contracts\Codec\CodecMatcherInterface;
use \Boox\Forks\JsonApi\Encoder\Parameters\EncodingParameters;
use \Boox\Forks\JsonApi\Encoder\Parameters\ParametersAnalyzer;
use \Boox\Forks\JsonApi\Http\Headers\RestrictiveHeadersChecker;
use \Boox\Forks\JsonApi\Schema\ResourceIdentifierSchemaAdapter;
use \Boox\Forks\JsonApi\Contracts\Http\Headers\HeaderInterface;
use \Boox\Forks\JsonApi\Contracts\Schema\SchemaProviderInterface;
use \Boox\Forks\JsonApi\Contracts\Http\Headers\MediaTypeInterface;
use \Boox\Forks\JsonApi\Schema\ResourceIdentifierContainerAdapter;
use \Boox\Forks\JsonApi\Contracts\Http\Headers\AcceptHeaderInterface;
use \Boox\Forks\JsonApi\Contracts\Encoder\Stack\StackReadOnlyInterface;
use \Boox\Forks\JsonApi\Contracts\Encoder\Parser\ParserManagerInterface;
use \Boox\Forks\JsonApi\Contracts\Encoder\Stack\StackFrameReadOnlyInterface;
use \Boox\Forks\JsonApi\Contracts\Encoder\Parameters\EncodingParametersInterface;
use \Boox\Forks\JsonApi\Contracts\Encoder\Parameters\ParametersAnalyzerInterface;

/**
 * @package Boox\Forks\JsonApi
 *
 * @SuppressWarnings(PHPMD.TooManyMethods)
 * @SuppressWarnings(PHPMD.TooManyPublicMethods)
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class Factory implements FactoryInterface
{
    /**
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->logger = new ProxyLogger();
    }

    /**
     * @inheritdoc
     */
    public function setLogger(LoggerInterface $logger)
    {
        $this->logger->setLogger($logger);
    }

    /**
     * @inheritdoc
     */
    public function createEncoder(ContainerInterface $container, EncoderOptions $encoderOptions = null)
    {
        $encoder = new Encoder($this, $container, $encoderOptions);

        $encoder->setLogger($this->logger);

        return $encoder;
    }

    /**
     * @inheritdoc
     */
    public function createDocument()
    {
        $document = new Document();

        $document->setLogger($this->logger);

        return $document;
    }

    /**
     * @inheritdoc
     */
    public function createError(
        $idx = null,
        LinkInterface $aboutLink = null,
        $status = null,
        $code = null,
        $title = null,
        $detail = null,
        $source = null,
        array $meta = null
    ) {
        return new Error($idx, $aboutLink, $status, $code, $title, $detail, $source, $meta);
    }
    /**
     * @inheritdoc
     */
    public function createReply($replyType, StackReadOnlyInterface $stack)
    {
        return new ParserReply($replyType, $stack);
    }

    /**
     * @inheritdoc
     */
    public function createEmptyReply(
        $replyType,
        StackReadOnlyInterface $stack
    ) {
        return new ParserEmptyReply($replyType, $stack);
    }

    /**
     * @inheritdoc
     */
    public function createParser(ContainerInterface $container, ParserManagerInterface $manager)
    {
        $parser = new Parser($this, $this, $this, $container, $manager);

        $parser->setLogger($this->logger);

        return $parser;
    }

    /**
     * @inheritdoc
     */
    public function createManager(ParametersAnalyzerInterface $parameterAnalyzer)
    {
        $manager = new ParserManager($parameterAnalyzer);

        $manager->setLogger($this->logger);

        return $manager;
    }

    /**
     * @inheritdoc
     */
    public function createFrame(StackFrameReadOnlyInterface $previous = null)
    {
        return new StackFrame($previous);
    }

    /**
     * @inheritdoc
     */
    public function createStack()
    {
        return new Stack($this);
    }

    /**
     * @inheritdoc
     */
    public function createReplyInterpreter(DocumentInterface $document, ParametersAnalyzerInterface $parameterAnalyzer)
    {
        $interpreter = new ReplyInterpreter($document, $parameterAnalyzer);

        $interpreter->setLogger($this->logger);

        return $interpreter;
    }

    /**
     * @inheritdoc
     */
    public function createParametersAnalyzer(EncodingParametersInterface $parameters, ContainerInterface $container)
    {
        $analyzer = new ParametersAnalyzer($parameters, $container);

        $analyzer->setLogger($this->logger);

        return $analyzer;
    }

    /**
     * @inheritdoc
     */
    public function createMediaType($type, $subType, $parameters = null)
    {
        return new MediaType($type, $subType, $parameters);
    }

    /**
     * @inheritdoc
     */
    public function createQueryParameters(
        $includePaths = null,
        array $fieldSets = null,
        $sortParameters = null,
        array $pagingParameters = null,
        array $filteringParameters = null,
        array $unrecognizedParams = null
    ) {
        return new EncodingParameters(
            $includePaths,
            $fieldSets,
            $sortParameters,
            $pagingParameters,
            $filteringParameters,
            $unrecognizedParams
        );
    }

    /**
     * @inheritdoc
     */
    public function createHeaderParameters($method, AcceptHeaderInterface $accept, HeaderInterface $contentType)
    {
        return new HeaderParameters($method, $accept, $contentType);
    }

    /**
     * @inheritdoc
     */
    public function createNoContentHeaderParameters($method, AcceptHeaderInterface $accept)
    {
        return new HeaderParameters($method, $accept, null);
    }

    /**
     * @inheritdoc
     */
    public function createQueryParametersParser()
    {
        $parser = new QueryParametersParser($this);

        $parser->setLogger($this->logger);

        return $parser;
    }

    /**
     * @inheritdoc
     */
    public function createHeaderParametersParser()
    {
        $parser = new HeaderParametersParser($this);

        $parser->setLogger($this->logger);

        return $parser;
    }

    /**
     * @inheritdoc
     */
    public function createSortParam($sortField, $isAscending)
    {
        return new SortParameter($sortField, $isAscending);
    }

    /**
     * @inheritdoc
     */
    public function createSupportedExtensions($extensions = MediaTypeInterface::NO_EXT)
    {
        return new SupportedExtensions($extensions);
    }

    /**
     * @inheritdoc
     */
    public function createAcceptMediaType(
        $position,
        $type,
        $subType,
        $parameters = null,
        $quality = 1.0,
        $extensions = null
    ) {
        return new AcceptMediaType($position, $type, $subType, $parameters, $quality, $extensions);
    }

    /**
     * @inheritdoc
     */
    public function createAcceptHeader($unsortedMediaTypes)
    {
        return new AcceptHeader($unsortedMediaTypes);
    }

    /**
     * @inheritdoc
     */
    public function createHeadersChecker(CodecMatcherInterface $codecMatcher)
    {
        return new RestrictiveHeadersChecker($codecMatcher);
    }

    /**
     * @inheritdoc
     *
     * @SuppressWarnings(PHPMD.BooleanArgumentFlag)
     */
    public function createQueryChecker(
        $allowUnrecognized = true,
        array $includePaths = null,
        array $fieldSetTypes = null,
        array $sortParameters = null,
        array $pagingParameters = null,
        array $filteringParameters = null
    ) {
        return new RestrictiveQueryChecker(
            $allowUnrecognized,
            $includePaths,
            $fieldSetTypes,
            $sortParameters,
            $pagingParameters,
            $filteringParameters
        );
    }

    /**
     * @inheritdoc
     */
    public function createContainer(array $providers = [])
    {
        $container = new Container($this, $providers);

        $container->setLogger($this->logger);

        return $container;
    }

    /**
     * @inheritdoc
     */
    public function createResourceObject(
        SchemaProviderInterface $schema,
        $resource,
        $isInArray,
        $attributeKeysFilter = null
    ) {
        return new ResourceObject($schema, $resource, $isInArray, $attributeKeysFilter);
    }

    /**
     * @inheritdoc
     */
    public function createRelationshipObject($name, $data, $links, $meta, $isShowData, $isRoot)
    {
        return new RelationshipObject($name, $data, $links, $meta, $isShowData, $isRoot);
    }

    /**
     * @inheritdoc
     *
     * @SuppressWarnings(PHPMD.BooleanArgumentFlag)
     */
    public function createLink($subHref, $meta = null, $treatAsHref = false)
    {
        return new Link($subHref, $meta, $treatAsHref);
    }

    /**
     * @inheritdoc
     */
    public function createResourceIdentifierSchemaAdapter(SchemaProviderInterface $schema)
    {
        return new ResourceIdentifierSchemaAdapter($this, $schema);
    }

    /**
     * @inheritdoc
     */
    public function createResourceIdentifierContainerAdapter(ContainerInterface $container)
    {
        return new ResourceIdentifierContainerAdapter($this, $container);
    }

    /**
     * @inheritdoc
     */
    public function createIdentitySchema(ContainerInterface $container, $classType, Closure $identityClosure)
    {
        return new IdentitySchema($this, $container, $classType, $identityClosure);
    }

    /**
     * @inheritdoc
     */
    public function createCodecMatcher()
    {
        return new CodecMatcher();
    }
}
