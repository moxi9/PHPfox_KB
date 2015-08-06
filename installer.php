<?php

$installer = new Core\App\Installer();

$installer->onInstall(function() use ($installer) {
	// do stuffs
	$installer->db->query('');
});