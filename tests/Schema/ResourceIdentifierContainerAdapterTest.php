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
use \Boox\Forks\JsonApi\Contracts\Schema\SchemaProviderInterface;
use \Boox\Forks\JsonApi\Schema\ResourceIdentifierContainerAdapter;

/**
 * @package Boox\Forks\Tests\JsonApi
 */
class ResourceIdentifierContainerAdapterTest extends BaseTestCase
{
    public function testAdapter()
    {
        $factory   = Mockery::mock(FactoryInterface::class);
        $container = Mockery::mock(ContainerInterface::class);
        $schema    = Mockery::mock(SchemaProviderInterface::class);

        /** @noinspection PhpMethodParametersCountMismatchInspection */
        $container->shouldReceive('getSchema')->once()->withAnyArgs()->andReturn($schema);
        /** @noinspection PhpMethodParametersCountMismatchInspection */
        $container->shouldReceive('getSchemaByType')->once()->withAnyArgs()->andReturn($schema);
        /** @noinspection PhpMethodParametersCountMismatchInspection */
        $container->shouldReceive('getSchemaByResourceType')->once()->withAnyArgs()->andReturn($schema);
        /** @noinspection PhpMethodParametersCountMismatchInspection */
        $factory->shouldReceive('createResourceIdentifierSchemaAdapter')->times(3)->withAnyArgs()->andReturnUndefined();

        /** @var FactoryInterface $factory */
        /** @var ContainerInterface $container */

        $adapter = new ResourceIdentifierContainerAdapter($factory, $container);

        $resource = (object)['whatever'];
        $this->assertNotNull($adapter->getSchema($resource));
        $this->assertNotNull($adapter->getSchemaByType($resource));
        $this->assertNotNull($adapter->getSchemaByResourceType('does not matter'));
    }
}
