<?php

namespace App\Like;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\CollectionOperationInterface;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\HttpOperation;
use ApiPlatform\Metadata\Link;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Resource\Factory\LinkFactoryInterface;
use ApiPlatform\Metadata\Resource\Factory\ResourceMetadataCollectionFactoryInterface;
use ApiPlatform\Metadata\Resource\ResourceMetadataCollection;
use ApiPlatform\Operation\PathSegmentNameGeneratorInterface;
use Symfony\Component\DependencyInjection\Attribute\AsDecorator;

#[AsDecorator(decorates: 'api_platform.metadata.resource.metadata_collection_factory', priority: 100)]
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
        $uriResourceName = $this->pathSegmentNameGenerator->getSegmentName($apiResource->getShortName());

        $likeOperation = new Post(
            uriTemplate: '/'.$uriResourceName.'/{id}/like.{_format}',
            formats: ['json'],
            inputFormats: ['json' => 'application/json'],
            shortName: $apiResource->getShortName(),
            class: $apiResource->getClass(),
            name: '_api_'.$uriResourceName.'_like_post',
        );

        $uriVariables = $this->getUriVariables($likeOperation);
        $likeOperation = $likeOperation->withUriVariables($uriVariables);

        $operations->add($likeOperation->getName(), $likeOperation);
    }

    public function addUnlikeOperation(ApiResource $apiResource): void
    {
        $operations = $apiResource->getOperations();
        $uriResourceName = $this->pathSegmentNameGenerator->getSegmentName($apiResource->getShortName());

        $unlikeOperation = new Delete(
            uriTemplate: '/'.$uriResourceName.'/{id}/like.{_format}',
            shortName: $apiResource->getShortName(),
            class: $apiResource->getClass(),
            name: '_api_'.$uriResourceName.'_like_delete',
        );

        $uriVariables = $this->getUriVariables($unlikeOperation);
        $unlikeOperation = $unlikeOperation->withUriVariables($uriVariables);

        $operations->add($unlikeOperation->getName(), $unlikeOperation);
    }

    public function addIslIkedOperation(ApiResource $apiResource): void
    {
        $operations = $apiResource->getOperations();
        $uriResourceName = $this->pathSegmentNameGenerator->getSegmentName($apiResource->getShortName());

        $isLikedOperation = new Get(
            uriTemplate: '/'.$uriResourceName.'/{id}/is_liked.{_format}',
            shortName: $apiResource->getShortName(),
            class: $apiResource->getClass(),
            name: '_api_'.$uriResourceName.'_is_liked_get',
        );

        $uriVariables = $this->getUriVariables($isLikedOperation);
        $isLikedOperation = $isLikedOperation->withUriVariables($uriVariables);

        $operations->add($isLikedOperation->getName(), $isLikedOperation);
    }

    private function getUriVariables(HttpOperation $unlikeOperation): array
    {
        $uriVariables = [];
        $links = $this->linkFactory->createLinksFromIdentifiers($unlikeOperation);

        foreach ($links as $link) {
            $uriVariables[$link->getParameterName()] = $link;
        }

        return $uriVariables;
    }
}
