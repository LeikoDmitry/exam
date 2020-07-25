<?php

declare(strict_types=1);

namespace App\Controller;

use App\Core\TemplateRenderInterface;
use App\Form\LoginForm;
use App\Form\PayoutForm;
use App\Service\Account as AccountService;
use RuntimeException;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use function compact;

class AccountController
{
    private TemplateRenderInterface $templateRender;
    private AccountService $accountService;
    public const USER_SESSION_KEY      = 'user_id';
    public const USER_SESSION_AMOUNT   = 'amount';
    public const USER_SESSION_EMAIL    = 'email';
    public const USER_SESSION_PASSWORD = 'password';

    public function __construct(TemplateRenderInterface $templateRender)
    {
        $this->templateRender = $templateRender;
        $this->accountService = new AccountService();
    }

    public function index(Request $request): Response
    {
        $user = '';

        if ($request->isMethod('POST')) {

            return new RedirectResponse('/');
        }

        if ($request->hasSession() && ($session = $request->getSession())) {
            $userId = $session->get(static::USER_SESSION_KEY, 0);

            try {
                $user = $this->accountService->getUserAccountById($userId);
            } catch (RuntimeException $exception) {
                $user = '';
            }
        }

        $context = $this->templateRender->getRender()->render('index.twig', compact('user'));

        return new Response($context);
    }

    public function login(Request $request): Response
    {
        if ($request->isMethod('POST')) {
            $form = new LoginForm();
            $form->setData($request->request);

            if ($form->isValid()) {
                $data = $form->getData();
                $user = $this->accountService->getUserByEmail($data['email']);
                $request->getSession()->set(static::USER_SESSION_KEY, $user->getId());

                return new RedirectResponse('/');
            }
            //TODO show the flash message

            return new RedirectResponse('/login');
        }

        $context = $this->templateRender->getRender()->render('login.twig');

        return new Response($context);
    }

    public function logout(Request $request): Response
    {
        $session = $request->getSession();
        $session->clear();
        $session->migrate();
        //TODO show the flash message

        return new RedirectResponse('/login');
    }

    public function payout(Request $request): Response
    {
        if (! $request->isMethod('POST')) {

            return new RedirectResponse('/');
        }

        $session = $request->getSession();
        $userId  = $session->get(static::USER_SESSION_KEY, false);

        if (! $request->hasSession() || ! $userId) {

            return new RedirectResponse('/');
        }

        $form = new PayoutForm();
        $form->setData($request->request);

        if (! $form->isValid()) {

            return new RedirectResponse('/');
        }

        $data = $form->getData();
        $this->accountService->updateUser($data);

        return new RedirectResponse('/');
    }
}
