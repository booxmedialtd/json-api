<?php namespace Boox\Forks\Tests\JsonApi\Extensions\Issue47;

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
use \Boox\Forks\JsonApi\Encoder\Parameters\EncodingParameters;

/**
 * @package Boox\Forks\Tests\JsonApi
 */
class IssueTest extends BaseTestCase
{
    /**
     * Test filter nested attributes.
     */
    public function testFilterNestedAttributes()
    {
        $user = new User('12287', 'vivalacrowe', ['email' => 'hello@vivalacrowe.com', 'name' => 'Rob']);

        $actual = CustomEncoder::instance([
            User::class => UserSchema::class,
        ])->encodeData($user, new EncodingParameters(
            null,
            [
                'users' => ['private.email'],
            ]
        ));

        $expected = <<<EOL
        {
            "data" : {
                "type" : "users",
                "id"   : "12287",
                "attributes" : {
                    "private" : {
                        "email" : "hello@vivalacrowe.com"
                    }
                },
                "links" : {
                    "self" : "/users/12287"
                }
            }
        }
EOL;
        // remove formatting from 'expected'
        $expected = json_encode(json_decode($expected));

        $this->assertEquals($expected, $actual);
    }
}
