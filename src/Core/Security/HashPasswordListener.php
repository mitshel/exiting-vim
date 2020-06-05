<?php
/**
 * @data 05.06.2020
 * @package Портал ТФОМС
 * @subpackage Модуль - ОТчеты
 * @author <yurinskiy@krasmed.ru>
 */

namespace Core\Security;

use Core\Entity\User;
use DateTime;
use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Security\Core\Encoder\MessageDigestPasswordEncoder;


class HashPasswordListener implements EventSubscriber
{
    private ContainerInterface $container;

    private MessageDigestPasswordEncoder $passwordEncoder;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
        $this->passwordEncoder = new MessageDigestPasswordEncoder(
            $this->container->getParameter('encode.algorithm'),
            $this->container->getParameter('encode.use_base64'),
            $this->container->getParameter('encode.iterations')
        );
    }

    public function prePersist(LifecycleEventArgs $args): void
    {
        $entity = $args->getEntity();
        if (! $entity instanceof User) {
            return;
        }

        $this->encodePassword($entity);
    }

    public function preUpdate(LifecycleEventArgs $args): void
    {
        $entity = $args->getEntity();
        if (! $entity instanceof User) {
            return;
        }

        $this->encodePassword($entity);
        // necessary to force the update to see the change
        $em = $args->getEntityManager();
        $meta = $em->getClassMetadata(get_class($entity));
        $em->getUnitOfWork()->recomputeSingleEntityChangeSet($meta, $entity);
    }

    /**
     * {@inheritdoc}
     */
    public function getSubscribedEvents()
    {
        return ['prePersist', 'preUpdate'];
    }

    private function encodePassword(User $entity): void
    {
        $plainPassword = $entity->getPlainPassword();
        if ($plainPassword === null) {
            return;
        }

        $salt = (string) (new DateTime('NOW'))->getTimestamp();

        $encoded = $this->passwordEncoder->encodePassword(
            $plainPassword,
            $salt
        );

        $entity->setPassword($encoded);
        $entity->setSalt($salt);
    }

}