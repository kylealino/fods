<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

$routes->get('/', 'Home::index');
$routes->add('mylogin-auth', 'MyLogIn::auth');
$routes->add('mylogout', 'MyLogIn::logout');
$routes->get('mydashboard', 'MyDashboard::index',['filter' => 'myauthuser']);

//User Management - User module
$routes->get('myua', 'MyUserManagement::index',['filter' => 'myauthuser']);
$routes->post('myua', 'MyUserManagement::index',['filter' => 'myauthuser']);

//Data-entity - Autocomplete data hovers
$routes->post('mydata-entity', 'MyDataEntity::index',['filter' => 'myauthuser']);
$routes->get('mydata-entity', 'MyDataEntity::index',['filter' => 'myauthuser']);

//Maintenance - Payee module
$routes->get('mypayee', 'MyPayee::index',['filter' => 'myauthuser']);
$routes->post('mypayee', 'MyPayee::index',['filter' => 'myauthuser']);

//Maintenance - Projects module
$routes->get('myproject', 'MyProject::index',['filter' => 'myauthuser']);
$routes->post('myproject', 'MyProject::index',['filter' => 'myauthuser']);

//Maintenance - UACS module
$routes->get('myuacs', 'MyUACS::index',['filter' => 'myauthuser']);
$routes->post('myuacs', 'MyUACS::index',['filter' => 'myauthuser']);

//Budget - Budget Allotment module
$routes->get('mybudgetallotment', 'MyBudgetAllotment::index',['filter' => 'myauthuser']);
$routes->post('mybudgetallotment', 'MyBudgetAllotment::index',['filter' => 'myauthuser']);

$routes->get('mybudgetapproval', 'MyBudgetApproval::index',['filter' => 'myauthuser']);
$routes->post('mybudgetapproval', 'MyBudgetApproval::index',['filter' => 'myauthuser']);

//File uploading
$routes->post('uploadFile', 'Upload::index',['filter' => 'myauthuser']);