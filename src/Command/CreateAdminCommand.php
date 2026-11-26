<?php

declare(strict_types=1);

namespace App\Command;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

#[AsCommand(name: 'app:admin:create', description: 'Створює адміністратора для адмін-панелі')]
final class CreateAdminCommand extends Command
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly UserRepository $users,
        private readonly UserPasswordHasherInterface $passwordHasher,
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $email = $io->ask('Email адміністратора', null, static function (?string $value): string {
            $email = trim((string) $value);
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                throw new \InvalidArgumentException('Введіть коректну email-адресу.');
            }

            return mb_strtolower($email);
        });

        if ($this->users->findOneBy(['email' => $email]) !== null) {
            $io->error('Користувач із такою email-адресою вже існує.');

            return Command::FAILURE;
        }

        $passwordQuestion = new Question('Пароль (введення приховано): ');
        $passwordQuestion->setHidden(true);
        $passwordQuestion->setHiddenFallback(false);
        $password = $io->askQuestion($passwordQuestion);

        if (!is_string($password) || strlen($password) < 12) {
            $io->error('Пароль має містити щонайменше 12 символів.');

            return Command::FAILURE;
        }

        $confirmationQuestion = new Question('Повторіть пароль: ');
        $confirmationQuestion->setHidden(true);
        $confirmationQuestion->setHiddenFallback(false);
        $confirmation = $io->askQuestion($confirmationQuestion);

        if (!is_string($confirmation) || !hash_equals($password, $confirmation)) {
            $io->error('Паролі не збігаються.');

            return Command::FAILURE;
        }

        $user = (new User())
            ->setEmail($email)
            ->setRoles(['ROLE_ADMIN']);
        $user->setPassword($this->passwordHasher->hashPassword($user, $password));

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        $io->success(sprintf('Адміністратора %s створено.', $email));

        return Command::SUCCESS;
    }
}
