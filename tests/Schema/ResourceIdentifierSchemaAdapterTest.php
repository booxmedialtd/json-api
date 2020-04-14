<?php namespace Boox\Forks\Tests\JsonApi\Schema;

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

use \Mockery;
use \Boox\Forks\Tests\JsonApi\BaseTestCase;
use \Boox\Forks\JsonApi\Contracts\Schema\ContainerInterface;
use \Boox\Forks\JsonApi\Contracts\Factories\FactoryInterface;
use \Boox\Forks\JsonApi\Schema\ResourceIdentifierSchemaAdapter;
use \Boox\Forks\JsonApi\Contracts\Schema\SchemaProviderInterface;

/**
 * @package Boox\Forks\Tests\JsonApi
 */
class ResourceIdentifierSchemaAdapterTest extends BaseTestCase
{
    public function testAdapter()
    {
        $factory = Mockery::mock(FactoryInterface::class);
        $schema  = Mockery::mock(SchemaProviderInterface::class);

        /** @noinspection PhpMethodParametersCountMismatchInspection */
        $schema->shouldReceive('getSelfSubLink')->once()->withAnyArgs()->andReturn('/sublink');

        /** @noinspection PhpMethodParametersCountMismatchInspection */
        $schema->shouldReceive('getSelfSubUrl')->once()->withAnyArgs()->andReturn('/suburl/');

        /** @noinspection PhpMethodParametersCountMismatchInspection */
        $schema->shouldReceive('getLinkageMeta')->once()->withAnyArgs()->andReturn(['some' => 'meta']);

        /** @noinspection PhpMethodParametersCountMismatchInspection */
        $schema->shouldReceive('getPrimaryMeta')->once()->withAnyArgs()->andReturn(['some' => 'meta']);

        /** @noinspection PhpMethodParametersCountMismatchInspection */
        $schema->shouldReceive('getRelationshipSelfLink')->once()->withAnyArgs()->andReturn('whatever');

        /** @noinspection PhpMethodParametersCountMismatchInspection */
        $schema->shouldReceive('getRelationshipRelatedLink')->once()->withAnyArgs()->andReturn('whatever');

        /** @var FactoryInterface $factory */
        /** @var ContainerInterface $container */
        /** @var SchemaProviderInterface $schema */

        $adapter = new ResourceIdentifierSchemaAdapter($factory, $schema);

        $resource = (object)['whatever'];
        $adapter->getSelfSubLink($resource);
        $adapter->getSelfSubUrl();
        $this->assertEmpty($adapter->getResourceLinks($resource));
        $this->assertEmpty($adapter->getIncludedResourceLinks($resource));
        $this->assertEmpty($adapter->getAttributes($resource));
        $this->assertEmpty($adapter->getIncludePaths());
        $this->assertNotEmpty($adapter->getPrimaryMeta($resource));
        $this->assertFalse($adapter->isShowAttributesInIncluded());
        $this->assertFalse($adapter->isShowRelationshipsInIncluded());
        $this->assertNull($adapter->getInclusionMeta($resource));
        $this->assertNotNull($adapter->getRelationshipObjectIterator($resource, true, []));
        $this->assertNull($adapter->getRelationshipsPrimaryMeta($resource));
        $this->assertNull($adapter->getRelationshipsInclusionMeta($resource));
        $this->assertNotNull($adapter->getRelationshipSelfLink($resource, 'relationship'));
        $this->assertNotNull($adapter->getRelationshipRelatedLink($resource, 'relationship'));
        $adapter->getLinkageMeta($resource);
    }
}
