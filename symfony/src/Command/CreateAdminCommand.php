<?php

namespace App\Command;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

#[AsCommand(
    name: 'app:create-admin',
    description: 'create a new admin user',
)]
class CreateAdminCommand extends Command
{
    public function __construct(
        private UserRepository $userRepository,
        private UserPasswordHasherInterface $passwordHasher,
        private EntityManagerInterface $entityManager,
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $adminEmail = 'admin@bracelify.com';
        $admin = $this->userRepository->findOneByEmail($adminEmail);

        if ($admin) {
            $io->warning('Admin user already exists.');
            if (!$io->confirm('Do you want to update the password for the existing admin user?', false)) {
                return Command::SUCCESS;
            }
        }
        $adminpassword = $io->askHidden('Enter password for the admin user: ');
        $confirmPassword = $io->askHidden('Confirm password: ');
        if ($adminpassword !== $confirmPassword) {
            $io->error('Passwords do not match. Aborting.');

            return Command::FAILURE;
        }

        $admin = $admin ?? new User();
        $admin->setEmail($adminEmail);
        $admin->setFirstname('Admin');
        $admin->setLastname('User');
        $admin->setAdress('123 Admin St, Admin City');
        $admin->setPhone('1234567890');
        $admin->setRoles(['ROLE_ADMIN']);
        $hashedPassword = $this->passwordHasher->hashPassword($admin, $adminpassword);

        $admin->setPassword($hashedPassword);
        $this->entityManager->persist($admin);
        $this->entityManager->flush();

        $io->success('You have successfully created an admin user.');

        return Command::SUCCESS;
    }
}
