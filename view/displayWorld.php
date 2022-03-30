<?php

use parser\SandBoxParser;

// index.php?path=displayWorld

$world = SandBoxParser::getWorldFromFilePath(BASE_PATH . 'tmp/SANDBOX_0_0_0_.sbs');
?>

<?php foreach ($world->structures as $structure) { ?>
	<hr/>
	<?php echo htmlentities($structure->displayName) . '<br/>'; ?>
	<?php echo htmlentities($structure->totalArmor) . ' armors<br/>'; ?>
	<?php echo htmlentities($structure->isStation) . '<br/>'; ?>
	<?php echo htmlentities($structure->id) . '<br/>'; ?>
	<?php echo htmlentities($structure->gridSizeEnum) . '<br/>'; ?>
	<?php echo htmlentities($structure->position->x) . '<br/>'; ?>
	<hr/>
<?php } ?>