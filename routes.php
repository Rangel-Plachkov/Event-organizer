<?php

$routesRegistrator = \Router\Registrator::getInstance();

//INDEX ROUTES
$routesRegistrator->map("GET", "[index@index]", "/", "indexPage");

//PAGE ROUTES
$routesRegistrator->map("GET", "[page@signUp]", "sign-up", "signUpPage");
$routesRegistrator->map("GET", "[page@signIn]", "sign-in", "signInPage");
$routesRegistrator->map("GET", "[page@edit]", "edit", "editPage");
$routesRegistrator->map("GET", "[page@createEvent]", "create-event", "create_event-page");
$routesRegistrator->map("GET", "[page@listEvents]", "event-list", "create_event-page");
// $routesRegistrator->map("GET", "[page@eventDashboard]", "event-dashboard", "event-dashboard-page");


//USER ROUTES
$routesRegistrator->map("POST", "[user@createAcc]", "create-acc", "createAcc");
$routesRegistrator->map("POST", "[user@login]", "login", "login");
$routesRegistrator->map("GET", "[user@logout]", "logout", "logout");
$routesRegistrator->map("POST", "[user@editAcc]", "edit-acc", "editAcc");
$routesRegistrator->map("GET", "[user@deleteAcc]", "delete-acc", "deleteAcc");

//EVENT ROUTES
$routesRegistrator->map("POST", "[event@createEvent]", "create-event-op", "createEvent");
$routesRegistrator->map("POST", "[eventDashboard@showEventDashboard]", "event-dashboard", "event-dashboard-page");
$routesRegistrator->map("POST", "[eventDashboard@addOrganization]", "add-organization-op", "add-ogranization-operation");

//GIFT ROUTES
$routesRegistrator->map("GET", "[giftVoting@showGiftPoll]", "gift-poll", "gift-poll-page");
$routesRegistrator->map("POST", "[giftVoting@addGift]", "add-gift", "add-gift-op");
$routesRegistrator->map("POST", "[giftVoting@voteForGift]", "vote-gift", "vote-gift-op");
$routesRegistrator->map("POST", "[giftVoting@endPoll]", "end-poll", "end-poll-op");
$routesRegistrator->map("POST", "[giftVoting@createPoll]", "create-poll", "create-poll-op");


//COMMENT ROUTES
$routesRegistrator->map("POST", "[eventDashboard@createComment]", "create-comment-op", "createComment");


//ERROR PAGES ROUTES
$routesRegistrator->map("GET", "[error@error404]", "error/404", "pageNotFoundError");
$routesRegistrator->map("GET", "[error@error500]", "error/500", "serverError");
$routesRegistrator->map("GET", "[error@unhandledError]", "error/503", "unhandledError");
