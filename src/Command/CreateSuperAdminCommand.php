<?php

namespace TwinElements\AdminBundle\Command;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use TwinElements\AdminBundle\Entity\AdminUser;

class CreateSuperAdminCommand extends Command
{
    protected static $defaultName = 'te:admin:create_super_admin';

    /**
     * @var EntityManagerInterface $em
     */
    private $em;

    /**
     * @var UserPasswordEncoderInterface $encoder
     */
    private $encoder;

    /**
     * @param EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em, UserPasswordEncoderInterface $encoder)
    {
        $this->em = $em;
        $this->encoder = $encoder;

        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setDescription('Create new Super Admin')
            ->setDefinition([
                new InputArgument('username', InputArgument::REQUIRED, 'User name'),
                new InputArgument('email', InputArgument::REQUIRED, 'User email'),
                new InputArgument('password', InputArgument::REQUIRED, 'User password')
            ]);
    }

    protected function interact(InputInterface $input, OutputInterface $output)
    {
        $questions = [];

        if (!$input->getArgument('username')) {
            $question = new Question('Please choose a username:');
            $question->setValidator(function ($username) {
                if (empty($username)) {
                    throw new \Exception('Username can not be empty');
                }

                return $username;
            });
            $questions['username'] = $question;
        }

        if (!$input->getArgument('email')) {
            $question = new Question('Please choose an email:');
            $question->setValidator(function ($email) {
                if (empty($email)) {
                    throw new \Exception('Email can not be empty');
                }

                return $email;
            });
            $questions['email'] = $question;
        }

        if (!$input->getArgument('password')) {
            $question = new Question('Please choose a password:');
            $question->setValidator(function ($password) {
                if (empty($password)) {
                    throw new \Exception('Password can not be empty');
                }

                return $password;
            });
            $question->setHidden(true);
            $questions['password'] = $question;
        }

        foreach ($questions as $name => $question) {
            $answer = $this->getHelper('question')->ask($input, $output, $question);
            $input->setArgument($name, $answer);
        }
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        try {
            $username = $input->getArgument('username');
            $email = $input->getArgument('email');
            $password = $input->getArgument('password');

            $user = new AdminUser();
            $user->setEmail($email);
            $user->setUsername($username);
            $user->setEnabled(true);
            $password = $this->encoder->encodePassword($user, $password);
            $user->setPassword($password);
            $user->setRoles(['ROLE_SUPER_ADMIN']);

            $this->em->persist($user);
            $this->em->flush();

            $output->writeln(sprintf('Created user <comment>%s</comment>', $username));

            return 0;

        } catch (\Exception $exception) {
            $output->writeln($exception->getMessage());
            return 1;
        }
    }
}
