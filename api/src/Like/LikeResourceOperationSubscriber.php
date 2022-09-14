<?php

namespace App\Like;

use ApiPlatform\Symfony\EventListener\EventPriorities;
use ApiPlatform\Util\RequestAttributesExtractor;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Security\Core\Security;

class LikeResourceOperationSubscriber implements EventSubscriberInterface
{
    public function __construct(
        private readonly Security               $security,
        private readonly LikeHandler            $likeHandler,
        private readonly EntityManagerInterface $entityManager,
    )
    {
    }

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::REQUEST => ['onKernelRequest', EventPriorities::POST_READ],
        ];
    }

    public function onKernelRequest(RequestEvent $event): void
    {
        $attributes = RequestAttributesExtractor::extractAttributes($event->getRequest());
        $user = $this->security->getUser();

        if (!array_key_exists('resource_class', $attributes)
            || !in_array(LikableInterface::class, class_implements($attributes['resource_class']), true)) {
            return;
        }

        if (preg_match('/_api_\w+_is_liked_get/', $attributes['operation_name'])) {
            $isLiked = $this->likeHandler->isLiked($attributes['previous_data'], $user);
            $event->setResponse(new Response($isLiked));
        }

        if (preg_match('/_api_\w+_like_post/', $attributes['operation_name'])) {
            $likable = $event->getRequest()->attributes->get('data');
            $this->likeHandler->like($likable, $user);
            $this->entityManager->flush();
            $event->setResponse(new Response(null, Response::HTTP_CREATED));
        }

        if (preg_match('/_api_\w+_like_delete/', $attributes['operation_name'])) {
            $likable = $event->getRequest()->attributes->get('data');
            $this->likeHandler->unlike($likable, $user);
            $this->entityManager->flush();
            $event->setResponse(new Response(null, Response::HTTP_NO_CONTENT));
        }
    }
}
