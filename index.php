<?php

// Kickstart the framework
$f3=require('lib/base.php');

$f3->set('DEBUG',1);
if ((float)PCRE_VERSION<7.9)
	trigger_error('PCRE version is out of date');

$db = new DB\SQL(
	'mysql:host=localhost; port=3306; dbname=amely', 'root', '');

// Load configuration
$f3->config('config.ini');

$f3->route('GET /',
	function($f3) {
		$f3->set('content','welcome.htm');
		echo View::instance()->render('layout.htm');
	}
);

$f3->route('GET /login',
	function($f3) {
		$f3->set('content','login.htm');
		echo View::instance()->render('layout.htm');
	}
);

$f3->route('POST /register',
	function($f3) use ($db) {
		if ($_POST['pass_text'] != $_POST['cpass_text']) {
			$f3->set('isNotPassConf', true);
		}
		else {
			$newUser = new DB\SQL\Mapper($db, 'users');
			$newUser->login = $_POST['login_text'];
			$newUser->password = $_POST['pass_text'];
			$newUser->save();
			$f3->set('isNotPassConf', false);
			$f3->reroute('/login');
		};	
		$f3->set('content','login.htm');
		echo View::instance()->render('layout.htm');
	}
);

$f3->route('POST /login',
	function($f3) use ($db) {
			$userMapper = new \DB\SQL\Mapper($db, 'users');
			$auth = new \Auth($userMapper, array('id' =>
				'login', 'pw' => 'password'));
			$login_result = $auth->login($f3->get('POST.login_aut'), 
				$f3->get('POST.pass_aut'));
			if ($login_result) {
				$f3->set('SESSION.login_aut',
				$f3->get('POST.login_aut'));
				$f3->reroute('/');
			}
			else {
				$f3->set('isError', true);
				$f3->set('content','login.htm');
				echo View::instance()->render('layout.htm');
			}
		}		
);

$f3->route('GET /logout',
	function ($f3) {
		$f3->set('SESSION.login_aut', 0);
		$f3->reroute('/login');
	}
);

$f3->route('GET /menu',
	function ($f3) {
		$f3->set('content','menu.htm');
		echo View::instance()->render('layout.htm');
	}
);

$f3->route('GET /kuhny',
	function ($f3) {
		$f3->set('content','kuhny.htm');
		echo View::instance()->render('layout.htm');
	}
);

$f3->route('GET /wine',
	function ($f3) {
		$f3->set('content','wine.htm');
		echo View::instance()->render('layout.htm');
	}
);

$f3->route('GET /bar',
	function ($f3) {
		$f3->set('content','bar.htm');
		echo View::instance()->render('layout.htm');
	}
);

$f3->route('GET /coct',
	function ($f3) {
		$f3->set('content','coctail.htm');
		echo View::instance()->render('layout.htm');
	}
);

$f3->route('GET /contacts',
	function ($f3) {
		$f3->set('content','contacts.htm');
		echo View::instance()->render('layout.htm');
	}
);

$f3->route('POST /contacts',
		function($f3,$params) use ($db) {
		$newUser = new DB\SQL\Mapper($db,'users');		
		$newUser->name = $f3->get('POST.name_user');
		$newUser->mail = $f3->get('POST.mail_user');
		$newUser->tel = $f3->get('POST.tel_user');
		$newUser->comment = $f3->get('POST.comments_user');
		
		$newUser->save();
		$f3->reroute('/contacts');
	}
);

$f3->route('POST /bron',
		function($f3,$params) use ($db) {
		$newUser = new DB\SQL\Mapper($db,'bron');		
		$newUser->name = $f3->get('POST.name_bron');
		$newUser->tel = $f3->get('POST.tel_bron');
		$newUser->mail = $f3->get('POST.mail_bron');
		$newUser->date_bron = $f3->get('POST.date_bron');
		$newUser->kolvo = $f3->get('POST.kol_bron');
		$newUser->time_from = $f3->get('POST.time_from_bron');
		$newUser->time_to = $f3->get('POST.time_to_bron');
		$newUser->comment = $f3->get('POST.comment_bron');
		
		$newUser->save();
		$f3->reroute('/layout');
	}
);

$f3->route('GET /layout',
	function ($f3) {
		$f3->set('content','welcome.htm');
		echo View::instance()->render('layout.htm');
	}
);

$f3->run();
