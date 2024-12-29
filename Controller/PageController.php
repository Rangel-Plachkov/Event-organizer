<?php

namespace Controller;

class PageController extends AbstractController
{
    public function signUp(){
        require_once 'View/templates/signUpForm.html';
    }
//    public function signIn(){
//        require_once 'View/templates/signInForm.html';
//    }
}