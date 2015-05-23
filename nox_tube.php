<?php
// no direct access
defined('_JEXEC') or die;

$mainframe->registerEvent('onPrepareContent', 'plgContentNox_tube');

function plgContentNox_tube(&$article, &$params, $page=0)
{
	$plugin =& JPluginHelper::getPlugin('content', 'nox_tube');
	$params = new JParameter( $plugin->params );
	
	// simple performance check to determine whether bot should process further
	if (strpos($article->text, 'loadtube') === false) {
		return true;
	}
	
	$imageWidth 	= $params->get('imageWidth');
	$imageHeight 	= $params->get('imageHeight');
	
	$regex		= '/{loadtube\s+(.*?)}/i';
	preg_match_all($regex, $article->text, $matches, PREG_SET_ORDER);

	// No matches, skip this
	if ($matches) {
		foreach ($matches as $match) {
			
			// output
			$output = '<a rel="rokbox" href="http://www.youtube.com/watch?v='.$match[1].'" target="_blank"><img style="padding-left:5px;" src="http://img.youtube.com/vi/'.$match[1].'/0.jpg" width="'.$imageWidth.'" height="'.$imageHeight.'" alt="Video" /></a>';
			
			$article->text = preg_replace("|$match[0]|", addcslashes($output, '\\'), $article->text, 1);
			
		}
	}
}
