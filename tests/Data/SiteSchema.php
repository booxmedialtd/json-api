<?php namespace Boox\Forks\Tests\JsonApi\Data;

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

use \Boox\Forks\JsonApi\Contracts\Document\LinkInterface;
use \Boox\Forks\JsonApi\Contracts\Schema\SchemaFactoryInterface;

/**
 * @package Boox\Forks\Tests\JsonApi
 */
class SiteSchema extends DevSchemaProvider
{
    /**
     * @inheritdoc
     */
    protected $resourceType = 'sites';

    /**
     * @param SchemaFactoryInterface $factory
     */
    public function __construct(SchemaFactoryInterface $factory)
    {
        parent::__construct($factory);

        $this->setIncludePaths([
            Site::LINK_POSTS,
            Site::LINK_POSTS . '.' . Post::LINK_AUTHOR,
            Site::LINK_POSTS . '.' . Post::LINK_COMMENTS,
        ]);
    }

    /**
     * @inheritdoc
     */
    public function getId($site)
    {
        return $site->{Site::ATTRIBUTE_ID};
    }

    /**
     * @inheritdoc
     */
    public function getAttributes($site)
    {
        assert('$site instanceof '.Site::class);

        return [
            Site::ATTRIBUTE_NAME => $site->{Site::ATTRIBUTE_NAME},
        ];
    }

    /**
     * @inheritdoc
     */
    public function getRelationships($site, $isPrimary, array $includeRelationships)
    {
        assert('$site instanceof '.Site::class);

        if (($isPrimary && $this->isIsLinksInPrimary()) || (!$isPrimary && $this->isIsLinksInIncluded())) {
            $selfLink = $this->getRelationshipSelfLink($site, Site::LINK_POSTS);
            $links    = [
                Site::LINK_POSTS => [self::LINKS => [LinkInterface::SELF => $selfLink], self::SHOW_DATA => false],
            ];
        } else {
            $links = [
                Site::LINK_POSTS => [self::DATA => $site->{Site::LINK_POSTS}],
            ];
        }

        // NOTE: The line(s) below for testing purposes only. Not for production.
        $this->fixLinks($site, $links);

        return $links;
    }
}
