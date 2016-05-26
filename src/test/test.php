<?php
	include('../config.php');
	include('../print_r.php');
	require('Curl.php');
	require('Dogpatch.php');
	use Dogpatch\Dogpatch;

	$HOST='http://'.HOSTNAME.'/';
	$ENTITY=$HOST.'entities/';

	function printFailure($text) {
		echo "<div style=\"color: #f00\">".$text."</div>";
	}

	function printOK($text, $verbose=false) {
		if (!$verbose && !array_key_exists('verbose', $_GET)) return;
		$backtrace = debug_backtrace();
		echo "<div style=\"color: #0f0\"><b>".$backtrace[0]['line'].":</b> ".$text."</div>";
	}

	$dogpatch=new Dogpatch();
	$logins = ['asdf', 'fdsa', 'pnd'];
	$passwords = ['adsf', 'fdsa', 'pnd'];
	for ($i=0; $i<=3; $i++) {
		try {
			if ($i<3) {
				$login = $logins[$i];
				$password = $passwords[$i];
	
				$dogpatch
				->post($HOST.'login.php?redir=1', ['login'=>$login, 'password'=>$password])
				->assertStatusCode(200);
				printOK("Logged in");
			} else {
				$dogpatch
				->get($HOST.'index.php')
				->assertStatusCode(401);
				printOK("Redirected to login page");
			}

			$alienlgn = $i===2 ? $logins[1] : $logins[2];
			$alienpw = $i===2 ? $passwords[1] : $passwords[2];

			$listener1 = array(
				'Name' => 'test123',
				'Surname' => 'test123',
				'Patronymic' => 'test123',
				'UGroup' => 'test123',
				'Phone' => '+8378123',
				'Email' => 'test@test.ua'
			);

			$dogpatch
			->post($HOST.'entities/listener/create.php', $listener1)
			->assertStatusCode($i<2?200:403);
			printOK("Created test listener");

			$dogpatch
			->get($HOST.'entities/listener/read.php')
			->assertStatusCode($i<3?200:403)
			->assertBodyContains($listener1, 'idListener', true, ($i>=2));
			printOK("Read listeners");

			$idListener = $dogpatch->getId();

			$listener2 = array(
				'idListener' => $idListener,
				'Name' => 'test321',
				'Surname' => 'test321',
				'Patronymic' => 'test321',
				'UGroup' => 'test321',
				'Phone' => '+8378321',
				'Email' => 'test@test.md'
			);
	
			$dogpatch
			->post($HOST.'entities/listener/update.php', $listener2)
			->assertStatusCode($i<2?200:403);
			printOK("Updated test listener");
	
			$dogpatch
			->get($HOST.'entities/listener/read.php')
			->assertStatusCode($i<3?200:403)
			->assertBodyContains($listener2, 'idListener', true, ($i>=2));
			printOK("Read listeners");
	
			$teacher1 = array(
				'Name' => 'test123',
				'Surname' => 'test123',
				'Patronymic' => 'test123',
				'Phone' => '+8378123',
				'Email' => 'test@test.ua',
				'degree' => 2
			);
	
			$dogpatch
			->post($HOST.'entities/teacher/create.php', $teacher1)
			->assertStatusCode($i<2?200:403);
			printOK("Created test teacher");
	
			$dogpatch
			->get($HOST.'entities/teacher/read.php')
			->assertStatusCode($i<3?200:403)
			->assertBodyContains($teacher1, 'idTeacher', true, ($i>=2));
			printOK("Read teachers");
	
			$idTeacher = $dogpatch->getId();
			
			$teacher2 = array(
				'idTeacher' => $idTeacher,
				'Name' => 'test321',
				'Surname' => 'test321',
				'Patronymic' => 'test321',
				'Phone' => '+8378321',
				'Email' => 'test@test.md',
				'degree' => 1
			);
	
			$dogpatch
			->post($HOST.'entities/teacher/update.php', $teacher2)
			->assertStatusCode($i<2?200:403);
			printOK("Updated test teacher");

			$dogpatch
			->get($HOST.'entities/teacher/read.php')
			->assertStatusCode($i<3?200:403)
			->assertBodyContains($teacher2, 'idTeacher', true, ($i>=2));
			printOK("Read teachers");

			$course1 = array(
				'Name' => 'test123',
				'Description' => 'test123',
				'idTeacher' => $idTeacher,
				'price' => 200,
				'state' => 0,
				'spec' => 0
			);

			$dogpatch
			->post($HOST.'entities/course/create.php', $course1)
			->assertStatusCode($i<2?200:403);
			printOK("Created test course");

			$dogpatch
			->get($HOST.'entities/course/read.php')
			->assertStatusCode($i<3?200:403)
			->assertBodyContains($course1, 'idCourse', true, ($i>=2));
			printOK("Read courses");

			$idCourse = $dogpatch->getId();

			$lesson1 = array(
				'date' => '2023-03-21',
				'type' => 0,
				'idCourse' => $idCourse
			);
			$lesson2 = array(
				'date' => '2023-03-23',
				'type' => 1,
				'idCourse' => $idCourse
			);

			$dogpatch
			->post($HOST.'entities/lesson/create.php', $lesson1)
			->assertStatusCode($i<2?200:403)
			->post($HOST.'entities/lesson/create.php', $lesson2)
			->assertStatusCode($i<2?200:403);
			printOK("Created test lessons");

			$idLesson1 = $dogpatch
			->get($HOST.'entities/lesson/read.php?idCourse='.$idCourse)
			->assertStatusCode($i<3?200:403)
			->assertBodyContains($lesson1, 'idLesson', true, ($i>=2))
			->getId();
			$idLesson2 = $dogpatch
			->assertBodyContains($lesson2, 'idLesson', true, ($i>=2))
			->getId();
			printOK("Read lessons");

			$course1['DateBegin'] = $lesson1['date'];
			$course1['DateEnd'] = $lesson2['date'];
			$course1['hours'] = 2;

			$dogpatch
			->get($HOST.'entities/course/read.php')
			->assertStatusCode($i<3?200:403)
			->assertBodyContains($course1, 'idCourse', true, ($i>=2));
			printOK("Read courses");

			$course2 = array(
				'idCourse' => $idCourse,
				'Name' => 'test321',
				'Description' => 'test321',
				'idTeacher' => $idTeacher,
				'price' => 300,
				'state' => 1,
				'spec' => 1
			);

			$dogpatch
			->post($HOST.'entities/course/update.php', $course2)
			->assertStatusCode($i<2?200:403);
			printOK("Updated test course");

			$course2['DateBegin'] = $course1['DateBegin'];
			$course2['DateEnd'] = $course1['DateEnd'];
			$course2['hours'] = $course1['hours'];

			$dogpatch
			->get($HOST.'entities/course/read.php')
			->assertStatusCode($i<3?200:403)
			->assertBodyContains($course2, 'idCourse', true, ($i>=2));
			printOK("Read courses");

			$lesson2 = array(
				'idLesson' => $idLesson2,
				'date' => '2023-03-22',
				'time' => '12:00:00',
				'type' => 0
			);

			$dogpatch
			->post($HOST.'entities/lesson/update.php', $lesson2)
			->assertStatusCode($i<2?200:403);
			printOK("Updated test lesson");

			$dogpatch
			->get($HOST.'entities/lesson/read.php?idCourse='.$idCourse)
			->assertStatusCode($i<3?200:403)
			->assertBodyContains($lesson2, 'idLesson', true, ($i>=2));
			printOK("Read lessons");

			$dogpatch
			->post($HOST.'entities/courses_of_listener/create.php', array('id' => $idListener, 'ids' => $idCourse))
			->assertStatusCode($i<2?200:403);
			printOK("Created payment");

			$dogpatch
			->get($HOST.'entities/courses_of_listener/read.php?id='.$idListener)
			->assertStatusCode($i<3?200:403)
			->assertBodyContains(array('Course' => $idCourse), null, true, ($i>=2));
			printOK("Read payment");
	
			$dogpatch
			->post($HOST.'entities/courses_of_listener/update.php?id='.$idListener, array('payment' => 50, 'idCourse' => $idCourse))
			->assertStatusCode($i<2?200:403);
			printOK("Update payment");
	
			$dogpatch
			->get($HOST.'entities/courses_of_listener/read.php?id='.$idListener)
			->assertStatusCode($i<3?200:403)
			->assertBodyContains(array('Course' => $idCourse, 'havePaid' => 50), null, true, ($i>=2));
			printOK("Read payment");
	
			$dogpatch
			->post($HOST.'entities/courses_of_listener/update.php?id='.$idListener.'&full=1', array('payment' => 300, 'idCourse' => $idCourse))
			->assertStatusCode($i<2?200:403);
			printOK("Update payment");
	
			$dogpatch
			->get($HOST.'entities/courses_of_listener/read.php?id='.$idListener)
			->assertStatusCode($i<3?200:403)
			->assertBodyContains(array('Course' => $idCourse, 'havePaid' => 300), null, true, ($i>=2));
			printOK("Read payment");
	
			$dogpatch
			->post($HOST.'entities/listeners_of_course/create.php', array('id' => $idCourse, 'ids' => $idListener))
			->assertStatusCode($i<2?200:403);
			printOK("Created payment");
	
			$dogpatch
			->get($HOST.'entities/listeners_of_course/read.php?id='.$idCourse)
			->assertStatusCode($i<3?200:403)
			->assertBodyContains(array('Listener' => $idListener), null, true, ($i>=2));
			printOK("Read payment");
	
			$dogpatch
			->post($HOST.'entities/listeners_of_course/update.php?id='.$idCourse, array('payment' => 50, 'idListener' => $idListener))
			->assertStatusCode($i<2?200:403);
			printOK("Update payment");
	
			$dogpatch
			->get($HOST.'entities/listeners_of_course/read.php?id='.$idCourse)
			->assertStatusCode($i<3?200:403)
			->assertBodyContains(array('Listener' => $idListener, 'havePaid' => 50), null, true, ($i>=2));
			printOK("Read payment");
	
			$dogpatch
			->post($HOST.'entities/listeners_of_course/update.php?id='.$idCourse.'&full=1', array('payment' => 300, 'idListener' => $idListener))
			->assertStatusCode($i<2?200:403);
			printOK("Update payment");
	
			$dogpatch
			->get($HOST.'entities/listeners_of_course/read.php?id='.$idCourse)
			->assertStatusCode($i<3?200:403)
			->assertBodyContains(array('Listener' => $idListener, 'havePaid' => 300), null, true, ($i>=2));
			printOK("Read payment");
	
			$dogpatch
			->post($HOST.'entities/user/update.php', array('login' => $login, 'password' => $password.'a'))
			->assertStatusCode($i<3?200:403);
			printOK("Changed own password");

			$dogpatch
			->get($HOST.'entities/user/read.php')
			->assertStatusCode($i<3?200:403)
			->assertBodyContains(array('login' => $login), null, true, ($i==3));
			printOK("Read user data");

			$dogpatch
			->post($HOST.'entities/user/update.php', array('login' => $login, 'password' => $password))
			->assertStatusCode($i<3?200:403);
			printOK("Restored own password");
			
			$dogpatch
			->get($HOST.'entities/user/read.php')
			->assertStatusCode($i<3?200:403)
			->assertBodyContains(array('login' => $login), null, true, ($i==3));
			printOK("Read user data");

			$dogpatch
			->post($HOST.'entities/user/update.php', array('login' => $alienlgn, 'password' => $alienpw.'a'))
			->assertStatusCode($i===0?200:403);
			printOK("Changed alien password");

			$dogpatch
			->get($HOST.'entities/user/read.php')
			->assertStatusCode($i<3?200:403)
			->assertBodyContains(array('login' => $alienlgn), null, true, ($i!==0));
			printOK("Read user data");

			$dogpatch
			->post($HOST.'entities/user/update.php', array('login' => $alienlgn, 'password' => $alienpw))
			->assertStatusCode($i===0?200:403);
			printOK("Restored alien password");
			
			$dogpatch
			->get($HOST.'entities/user/read.php')
			->assertStatusCode($i<3?200:403)
			->assertBodyContains(array('login' => $alienlgn), null, true, ($i!==0));
			printOK("Read user data");

			$settings1 = array(
				'name' => 'personal',
				'title' => 'Зарплата персонала',
				'value' => 1.3
			);
			$settings2 = $settings1;
			$settings2['value'] = 1.25;

			$dogpatch
			->post($HOST.'entities/settings/update.php', $settings1)
			->assertStatusCode($i===0?200:403);
			printOK("Updated settings");

			$dogpatch
			->get($HOST.'entities/settings/read.php')
			->assertStatusCode($i===0?200:403)
			->assertBodyContains($settings1, null, true, ($i>0));
			printOK("Read settings");

			$dogpatch
			->post($HOST.'entities/settings/update.php', $settings2)
			->assertStatusCode($i===0?200:403);
			printOK("Restored settings");

			$dogpatch
			->get($HOST.'entities/settings/read.php')
			->assertStatusCode($i===0?200:403)
			->assertBodyContains($settings2, null, true, ($i>0));
			printOK("Read settings");

			$dogpatch
			->get($HOST.'login.php?action=logout')
			->assertStatusCode(401);
			printOK("Logged out");
			printOK("<b>".(array_key_exists($i, $logins) ? $logins[$i] : "anonymous").": All test passed successfully!</b>", true);
			if ($i>=2) continue;
		} catch(Exception $e) {
			printFailure(nl2br(htmlspecialchars($e->getMessage())));
		}

		try {
			$dogpatch
			->post($HOST.'login.php?redir=1', ['login'=>$logins[0], 'password'=>$passwords[0]])
			->assertStatusCode(200);
			printOK("Logged in");
		
			$dogpatch
			->post($HOST.'entities/lesson/destroy.php', array('idLesson' => $idLesson1))
			->assertStatusCode(200);
			$dogpatch
			->post($HOST.'entities/lesson/destroy.php', array('idLesson' => $idLesson2))
			->assertStatusCode(200);
			printOK("Deleted test lessons");

			$dogpatch
			->post($HOST.'entities/course/destroy.php', array('idCourse' => $idCourse))
			->assertStatusCode(200);
			printOK("Deleted test course");
			
			$dogpatch
			->post($HOST.'entities/teacher/destroy.php', array('idTeacher' => $idTeacher))
			->assertStatusCode(200);
			printOK("Deleted test teacher");
		
			$dogpatch
			->post($HOST.'entities/listener/destroy.php', array('idListener' => $idListener))
			->assertStatusCode(200);
			printOK("Deleted test listener");
		} catch(Exception $e) {
			printFailure(nl2br(htmlspecialchars($e->getMessage())));
		}
	}

	$dogpatch->close();
?>
