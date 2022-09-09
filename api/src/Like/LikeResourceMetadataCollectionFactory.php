<?php

namespace App\Like;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\CollectionOperationInterface;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\HttpOperation;
use ApiPlatform\Metadata\Link;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Resource\Factory\LinkFactoryInterface;
use ApiPlatform\Metadata\Resource\Factory\ResourceMetadataCollectionFactoryInterface;
use ApiPlatform\Metadata\Resource\ResourceMetadataCollection;
use ApiPlatform\Operation\PathSegmentNameGeneratorInterface;
use App\Controller\IsLiked;
use App\Controller\Like;
use App\Controller\Unlike;

class LikeResourceMetadataCollectionFactory implements ResourceMetadataCollectionFactoryInterface
{
    public function __construct(
        private readonly ResourceMetadataCollectionFactoryInterface $decorated,
        private readonly PathSegmentNameGeneratorInterface          $pathSegmentNameGenerator,
        private readonly LinkFactoryInterface                       $linkFactory,
    )
    {
    }

    public function create(string $resourceClass): ResourceMetadataCollection
    {
        $metadataCollection = $this->decorated->create($resourceClass);

        if (!in_array(LikableInterface::class, class_implements($resourceClass), true)) {
            return $metadataCollection;
        }

        foreach ($metadataCollection as $apiResource) {
            $this->addLikeOperation($apiResource);
            $this->addUnlikeOperation($apiResource);
            $this->addIslIkedOperation($apiResource);
        }

        return $metadataCollection;
    }

    public function addLikeOperation(ApiResource $apiResource): void
    {
        $operations = $apiResource->getOperations();

        $likeOperation = new Post(
            formats: ['json'],
            inputFormats: ['json' => 'application/json'],
            stateless: true,
            openapiContext: [
                'summary' => 'Like a ' . $apiResource->getShortName(),
                'parameters' => [
                    [
                        'name' => 'id',
                        'in' => 'path',
                        'required' => true,
                        'schema' => [
                            'type' => 'integer',
                        ],
                    ],
                ],
            ],
            shortName: $apiResource->getShortName(),
            class: $apiResource->getClass(),
        );

        $uriTemplate = $this->getUriTemplate($apiResource, 'like');
        $operationName = $this->getOperationName($apiResource, $likeOperation, 'like');

        $uriVariables = $this->getUriVariables($likeOperation);

        $likeOperation = $likeOperation->withUriTemplate($uriTemplate);
        $likeOperation = $likeOperation->withUriVariables($uriVariables);
        $likeOperation = $likeOperation->withName($operationName);

        $operations->add($likeOperation->getName(), $likeOperation);
    }

    public function addUnlikeOperation(ApiResource $apiResource): void
    {
        $operations = $apiResource->getOperations();

        $unlikeOperation = new Post(
            formats: ['json'],
            inputFormats: ['json' => 'application/json'],
            stateless: true,
            openapiContext: [
                'summary' => 'Unlike a ' . $apiResource->getShortName(),
                'parameters' => [
                    [
                        'name' => 'id',
                        'in' => 'path',
                        'required' => true,
                        'schema' => [
                            'type' => 'integer',
                        ],
                    ],
                ],
            ],
            shortName: $apiResource->getShortName(),
            class: $apiResource->getClass(),
        );

        $uriTemplate = $this->getUriTemplate($apiResource, 'unlike');
        $operationName = $this->getOperationName($apiResource, $unlikeOperation, 'unlike');

        $uriVariables = $this->getUriVariables($unlikeOperation);

        $unlikeOperation = $unlikeOperation->withUriTemplate($uriTemplate);
        $unlikeOperation = $unlikeOperation->withUriVariables($uriVariables);
        $unlikeOperation = $unlikeOperation->withName($operationName);

        $operations->add($unlikeOperation->getName(), $unlikeOperation);
    }

    public function addIslIkedOperation(ApiResource $apiResource): void
    {
        $operations = $apiResource->getOperations();

        $isLikedOperation = new Get(
            formats: ['json'],
            inputFormats: ['json' => 'application/json'],
            stateless: true,
            openapiContext: [
                'summary' => 'Unlike a ' . $apiResource->getShortName(),
                'parameters' => [
                    [
                        'name' => 'id',
                        'in' => 'path',
                        'required' => true,
                        'schema' => [
                            'type' => 'integer',
                        ],
                    ],
                ],
            ],
            shortName: $apiResource->getShortName(),
            class: $apiResource->getClass()
        );

        $uriTemplate = $this->getUriTemplate($apiResource, 'is_liked');
        $operationName = $this->getOperationName($apiResource, $isLikedOperation, 'is_liked');

        $uriVariables = $this->getUriVariables($isLikedOperation);

        $isLikedOperation = $isLikedOperation->withUriTemplate($uriTemplate);
        $isLikedOperation = $isLikedOperation->withUriVariables($uriVariables);
        $isLikedOperation = $isLikedOperation->withName($operationName);

        $operations->add($isLikedOperation->getName(), $isLikedOperation);
    }

    private function getUriTemplate(ApiResource $apiResource, string $operationUriName): string
    {
        return sprintf(
            '/%s/{id}/%s.{_format}',
            $this->pathSegmentNameGenerator->getSegmentName($apiResource->getShortName()),
            $operationUriName
        );
    }

    private function getOperationName(ApiResource $apiResource, HttpOperation $likeOperation, string $name): string
    {
        return sprintf(
            '_api_%s_%s_%s%s',
            strtolower($apiResource->getShortName()),
            $name,
            strtolower($likeOperation->getMethod()),
            $likeOperation instanceof CollectionOperationInterface ? '_collection' : ''
        );
    }

    private function getUriVariables(HttpOperation $unlikeOperation): array
    {
        $links = $this->linkFactory->createLinksFromIdentifiers($unlikeOperation);

        return array_combine(array_map(static fn(Link $link) => $link->getParameterName(), $links), $links);
    }
}
