<?php

namespace App\EventListener;

use App\Entity\Log;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Event\AfterEntityPersistedEvent;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use App\Entity\Book;

#[AsEventListener(event: AfterEntityPersistedEvent::class, method: 'onAfterEntityPersisted')]
class BookPersistListener
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function onAfterEntityPersisted(AfterEntityPersistedEvent $event): void
    {
        $entity = $event->getEntityInstance();

        if ($entity instanceof Book) {
            $this->logBookCreation($entity);
        }
    }

    private function logBookCreation(Book $book): void
    {
        $message = sprintf(
            'Created book "%s", author:  "%s", year: "%s", ISBN: "%s"',
            $book->getTitle(),
            $book->getAuthor(),
            $book->getYear(),
            $book->getIsbn()
        );

        $log = new Log(
            title: 'bookPersist',
            message: $message,
            entity_type: Book::class,
            entity_id: $book->getId()
        );

        $this->entityManager->persist($log);
        $this->entityManager->flush();
    }
}
