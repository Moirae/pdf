<?php
$capabilities = array('local/pdf:download' => array(
	'captype' => 'read',
	'contextlevel' => CONTEXT_SYSTEM,
	'archetypes' => array(
		'teacher' => CAP_ALLOW,
		'student' => CAP_ALLOW,
		)
	)
);