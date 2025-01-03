<?php
$routesRegistrator = \Router\Registrator::getInstance();

//INDEX ROUTES
$routesRegistrator->map("GET","[index@index]","/","indexPage");

//PAGE ROUTES
$routesRegistrator->map("GET","[page@signUp]","sign-up","signUpPage");
$routesRegistrator->map("GET","[page@signIn]","sign-in","signInPage");
$routesRegistrator->map("GET","[page@edit]","edit","editPage");

//USER ROUTES
$routesRegistrator->map("POST","[user@createAcc]","create-acc","createAcc");
$routesRegistrator->map("POST","[user@login]","login","login");
$routesRegistrator->map("GET","[user@logout]","logout","logout");
$routesRegistrator->map("POST", "[user@editAcc]", "edit-acc", "editAcc");


//ERROR PAGES ROUTES
$routesRegistrator->map("GET", "[error@error404]", "error/404", "pageNotFoundError");
$routesRegistrator->map("GET", "[error@error500]", "error/500", "serverError");
$routesRegistrator->map("GET", "[error@unhandledError]", "error/503", "unhandledError");
