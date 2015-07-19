<?php

namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Component\Security\Core\SecurityContextInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use AppBundle\Form\UserRegisterType as UserForm;
use AppBundle\Entity\User;


class ShopController extends Controller
{
    /**
     * @Route("/index")
     * @Template()
     */
    public function indexAction()
    {
        $request = $this->get('request');
        
        $em = $this->getDoctrine()->getManager();
        $users = $em->getRepository('AppBundle:User')->findAll();
        $session = $this->get('session');
        
        //Если пользователь аутотифицирован как anonymous, выводим шаблон без имени пользователя 
        if (false === $this->get('security.context')->isGranted('IS_AUTHENTICATED_FULLY')) {
            
                return $this->render('AppBundle:Shop:index.html.twig', array(
                'auth' => false,
                'users' => $users,
            ));
        }
        else {//для аутотифицированного пользователя, выводим шаблон с именем пользователя
              //(имя получаем как 'last_username' => $this->get('request')->getSession()->get(SecurityContext::LAST_USERNAME)
            
            return $this->render('AppBundle:Shop:index.html.twig', array(
                'auth' => true,
                'username' => $request->getSession()->get(SecurityContextInterface::LAST_USERNAME),
                'users' => $users,   
            ));
        }
    }
   
    /**
     * @Route("/login", name="_security_login" )
     * @Template("AppBundle:Shop:login.html.twig")
     */
    public function loginAction()
    { 
        if ($this->getUser())
            return $this->redirectToRoute('app_welcom');

        $authenticationUtils = $this->get('security.authentication_utils');
        $error = $authenticationUtils->getLastAuthenticationError();
        $lastUsername = $authenticationUtils->getLastUsername();

        //return array('last_username' => $lastUsername, 'error' => $error);
		return $this->render('AppBundle:Shop:login.html.twig', array(
             //имя пользователя,аутентифицированного в текущем сеансе
            'last_username' => $lastUsername,
            'error' => $error,
        ));
        
    }

    /**
     * @Route("/login_check", name="_security_check")
     */
    public function securityCheckAction()
    {
        // The security layer will intercept this request
    }

    /**
     * @Route("logout", name="_security_logout"  )
     */
    public function logoutAction()
    {
        // The security layer will intercept this request
    }
   
    //На этот экшен будут отправляться данные из формы регистрации 
	/**
     * @Route("/registration", name="app_registration")
     * @Template()
     */
    public function registrationAction(Request $request)
    {
        $user = new User();
        $form = $this->createForm(new UserForm(), $user);
	
            $form->handleRequest($request);
			
            if ($form->isValid())
            {
                $encoder = $this->container->get('security.password_encoder');
                // encode password + salt
                $password = $encoder->encodePassword($user, $user->getPassword(), $user->getSalt());
                $user->setPassword($password);

                $em = $this->getDoctrine()->getEntityManager();
                $em->persist($user);
                $em->flush();
				
				$this->get('session')->getFlashBag()->add('notice', 'Add user success!');
                return $this->redirectToRoute('app_welcom');
            }
    }
	
	//А здесь будет выводиться сама форма регистрации
    /**
     * @Route("/register", name="app_register")
     * @Template()
     */
    public function registerAction()
    {
		$user = new User();
        $form = $this->createForm(new UserForm(), $user);
		
        return $this->render('AppBundle:Shop:register.html.twig', array(
		
                'registration_form' => $form->createView()
        ));
    }
    
}
