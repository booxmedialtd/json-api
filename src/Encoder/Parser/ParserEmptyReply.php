<?php namespace Boox\Forks\JsonApi\Encoder\Parser;

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

use \Boox\Forks\JsonApi\Factories\Exceptions;
use \Boox\Forks\JsonApi\Contracts\Encoder\Stack\StackReadOnlyInterface;

/**
 * @package Boox\Forks\JsonApi
 */
class ParserEmptyReply extends BaseReply
{
    /**
     * @param int                    $replyType
     * @param StackReadOnlyInterface $stack
     *
     * @SuppressWarnings(PHPMD.StaticAccess)
     */
    public function __construct($replyType, StackReadOnlyInterface $stack)
    {
        $isOk = ($replyType === self::REPLY_TYPE_NULL_RESOURCE_STARTED ||
            $replyType === self::REPLY_TYPE_EMPTY_RESOURCE_STARTED);
        $isOk ?: Exceptions::throwInvalidArgument('replyType', $replyType);

        parent::__construct($replyType, $stack);
    }
}
