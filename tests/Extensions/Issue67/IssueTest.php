<?php namespace Boox\Forks\Tests\JsonApi\Extensions\Issue67;

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

use \Boox\Forks\Tests\JsonApi\BaseTestCase;

/**
 * @package Boox\Forks\Tests\JsonApi
 */
class IssueTest extends BaseTestCase
{
    /**
     * Test encoder will create instances of child classes.
     *
     * @see https://github.com/neomerx/json-api/issues/67
     */
    public function testEnheritedEncoder()
    {
        $childEncoder = CustomEncoder::instance();
        $this->assertEquals(CustomEncoder::class, get_class($childEncoder));
    }
}
