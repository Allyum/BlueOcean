<?php

namespace App\Controller;

use App\Business\TokenBusiness;
use App\Entity\Token;
use App\Entity\User;
use App\Form\Type\UserType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\String\Slugger\SluggerInterface;

class UserController extends AbstractController
{
    #[Route('/register', name: 'user_register')]
    public function create(
        Request                     $request,
        EntityManagerInterface      $entityManager,
        UserPasswordHasherInterface $hasher,
        TokenBusiness            $tokenBusiness,
        MailerInterface             $mailer
    ): Response
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user)->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user->setPassword($hasher->hashPassword($user, $user->getPlainPassword()));

            $token = $tokenBusiness->createToken($user);
            $user->addToken($token);
            $entityManager->persist($user);
            $entityManager->flush();

            $email = new Email();

            $email
                ->from('jean.marius@dyosis.com')
                ->to($user->getEmail())
                ->text($this->renderBlockView('Mail/validate-email.html.twig', 'text', ['token' => $token]))
                ->html($this->renderBlockView('Mail/validate-email.html.twig', 'html', ['token' => $token]))
                ->subject($this->renderBlockView('Mail/validate-email.html.twig', 'subject', ['token' => $token]));
            $mailer->send($email);

            return $this->redirectToRoute('app_login');
        }

        return $this->render('User/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/login', name: 'app_login')]
    public function login(AuthenticationUtils $utils): Response
    {
        return $this->render('User/login.html.twig', [
            'last_username' => $utils->getLastUsername(),
            'error' => $utils->getLastAuthenticationError(),
        ]);
    }

    #[Route('/validate/{value}', name: 'app_validate')]
    public function confirmEmail(Token $token, EntityManagerInterface $entityManager): Response
    {
        if ($token->getExpiresAt() < new \DateTime()) {
            throw $this->createNotFoundException();
        }

        $user = $token->getUser();
        $user->setEnabled(true);

        $entityManager->persist($user);
        $entityManager->remove($token);
        $entityManager->flush();

        return $this->redirectToRoute('app_login');
    }

    #[IsGranted('ROLE_USER')]
    #[Route('/profile', name: 'app_profile')]
    public function profile(Request $request, EntityManagerInterface $entityManager, SluggerInterface $slugger): Response
    {
        $user = $this->getUser();

        if ($request->isMethod('POST') && $request->files->has('profile_picture')) {
            $uploadedFile = $request->files->get('profile_picture');

            if ($uploadedFile) {
                $originalFilename = pathinfo($uploadedFile->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $fileName = $safeFilename . '-' . uniqid() . '.' . $uploadedFile->guessExtension();

                try {
                    $uploadDir = $this->getParameter('kernel.project_dir') . '/public/uploads/profiles';
                    if (!is_dir($uploadDir)) {
                        mkdir($uploadDir, 0777, true);
                    }

                    $uploadedFile->move($uploadDir, $fileName);

                    if ($user->getProfilePicture()) {
                        $oldFile = $uploadDir . '/' . basename($user->getProfilePicture());
                        if (file_exists($oldFile)) {
                            unlink($oldFile);
                        }
                    }

                    $user->setProfilePicture('/uploads/profiles/' . $fileName);
                    $entityManager->flush();
                } catch (FileException $e) {
                }
            }

            return $this->redirectToRoute('app_profile');
        }

        return $this->render('User/Profile.html.twig', [
            'user' => $user,
        ]);
    }
}
