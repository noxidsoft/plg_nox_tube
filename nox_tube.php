<?php
/*------------------------------------------------------------------------
# plg_nox_tube - Content - Nox Tube
# ------------------------------------------------------------------------
# author    Noxidsoft
# copyright Copyright (C) 2012 Noxidsoft. All Rights Reserved.
# @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
# Websites: http://www.noxidsoft.com
# Technical Support:  http://www.noxidsoft.com/
-------------------------------------------------------------------------*/

// no direct access
defined('_JEXEC') or die;

class plgContentNox_tube extends JPlugin
{	
	public function onContentPrepare($context, &$article, &$params, $page = 0)
	{
		// Don't run this plugin when the content is being indexed
		if ($context == 'com_finder.indexer') {
			return true;
		}
		
		// simple performance check to determine whether bot should process further
		if (strpos($article->text, 'loadtube') === false) {
			return true;
		}
		
		// additional scripts for document head
		$document = &JFactory::getDocument();
		$document->addStyleSheet( '/plugins/content/nox_tube/player/css/videobox.css' );
		// we use our own instance of mootools.js because we want this particular version
		$document->addScript( '/plugins/content/nox_tube/player/js/mootools.js' );
		$document->addScript( '/plugins/content/nox_tube/player/js/swfobject.js' );
		$document->addScript( '/plugins/content/nox_tube/player/js/videobox.js' );
		
		$popupWidth 	= $this->params->def('popupWidth');
		$popupHeight 	= $this->params->def('popupHeight');
		$thumbSizing 	= 'width="'.$this->params->def('thumbWidth').'px" height="'.$this->params->def('thumbHeight').'px"';
		
		$regex		= '/{loadtube\s+(.*?)}/i';
		preg_match_all($regex, $article->text, $matches, PREG_SET_ORDER);
	
		// No matches, skip this
		if ($matches) {
			foreach ($matches as $match) {
				
				// output
				$content = file_get_contents("http://youtube.com/get_video_info?video_id=".$match[1]);
				parse_str($content, $ytarr);
				
				if ($popupWidth != '' || $popupHeight != '') {
					$output = '<a rel="vidbox '.$popupWidth.' '.$popupHeight.'" href="http://www.youtube.com/watch?v='.$match[1].'" title="'.$ytarr['title'].'"><img src="http://img.youtube.com/vi/'.$match[1].'/0.jpg" alt="Video" '.$thumbSizing.' /></a>';
				} else {
					$output = '<a rel="vidbox" href="http://www.youtube.com/watch?v='.$match[1].'" title="'.$ytarr['title'].'"><img src="http://img.youtube.com/vi/'.$match[1].'/0.jpg" alt="Video" '.$thumbSizing.' /></a>';
				}
				
				// put output string back into the article
				$article->text = preg_replace("|$match[0]|", addcslashes($output, '\\'), $article->text, 1);
				
			}
		}
	}
}

