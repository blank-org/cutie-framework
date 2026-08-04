<?php
	require_once '../API/API.php';
	require_once '../API/ComponentDetails.php';
	require_once '../API/IncludeSVG.php';
	require_once '../API/Config.php';
	require_once 'Fragment/Item.php';

	$config = loadConfig();

	if( isset($_GET['mode']) && ($_GET['mode'] === "publish") )
		$bPublish = TRUE;
	else
		$bPublish = FALSE;

	if( isset($_GET['full']) && ($_GET['full'] === "true") )
		$bFull = TRUE;
	else
		$bFull = FALSE;

	$lang = getLanguage();
	$component = loadComponents();

	$id = substr(getOrigCall(), 0, -5); //stripping extension part
	$file = getComponentPath($id);
	$desc = getComponentDesc($id);
	$date = getFileDate($file);
	$prev_article_id = getPrevArticleId($id);
	$next_article_id = getNextArticleId($id);
	$prev_article = ($prev_article_id == '') ? null : array(
		'id' => $prev_article_id,
		'label' => getComponentLabel($prev_article_id),
		'url' => getComponentURL($prev_article_id)
	);
	$next_article = ($next_article_id == '') ? null : array(
		'id' => $next_article_id,
		'label' => getComponentLabel($next_article_id),
		'url' => getComponentURL($next_article_id)
	);
	
	header('Content-type: application/json; charset=utf-8');

	ob_start();
	include("../HTML/Fragment/Path.php");
	$path = ob_get_clean();

	ob_start();
	include("../HTML/Fragment/LanguageSwitcher.php");
	$languageSwitcher = ob_get_clean();

	// Render components for AJAX exactly as Page.php renders them for a full request.
	// Reading PHP components as text leaves fragment includes unexecuted, so the
	// publish compressor strips generated images and navigation from the JSON.
	ob_start();
	include($file);
	$fileContent = ob_get_clean();

	if( $bPublish ) {
		$cmd = 'java -jar'.' "'.getenv('TIGGU').'\Tools\HTML-Compressor.jar" -t html --compress-js --js-compressor closure --closure-opt-level simple --compress-css';

		$descriptorspec = array(
			0 => array("pipe", "r"),
			1 => array("pipe", "w"),
			2 => array("pipe", "w"),
		);

		$process = proc_open($cmd, $descriptorspec, $pipes);

		if (is_resource($process)) {

			fwrite($pipes[0], $fileContent);
			fclose($pipes[0]);

			$fileContent = stream_get_contents($pipes[1]);
			fclose($pipes[1]);

			proc_close($process);
		}
	}

	echo "{";
	echo "\"path\":";
	echo json_encode($path);
	echo ",";
	echo "\"async\":";
	echo "\"";
	echo getComponentModeASYNC($id);
	echo "\"";
	echo ",";
	echo "\"desc\":";
	echo "\"";
	echo getComponentDesc($id);
	echo "\"";
	echo ",";
	echo "\"prevArticle\":";
	echo json_encode($prev_article);
	echo ",";
	echo "\"nextArticle\":";
	echo json_encode($next_article);
	echo ",";
	echo "\"languageSwitcher\":";
	echo json_encode($languageSwitcher);
	echo ",";
	echo "\"content\":";
	echo json_encode($fileContent);
	echo "}";

?>
