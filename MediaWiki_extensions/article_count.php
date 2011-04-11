<?php

/**
 * Parser hook extension to add a <article_count> tag
 *
 * @addtogroup Extensions
 * @author Mikael Korpela <mikael@ihminen.org>
 * @copyright © 2010 Mikael Korpela
 * @licence GNU General Public Licence 2.0
 */

/*
 * First add article named "($wgArticle_count)" to your wiki ($wgWikiRoot) with this content:
 *
 * {{NUMBEROFARTICLES}}
 *
 * ...and you're good to go!
 */

$wgacWikiRoot = "http://hitchwiki.org/";
$wgacArticle_count = "Hitchwiki:Article_count";

if( defined( 'MEDIAWIKI' )) {

	$wgExtensionCredits['parserhook'][] = array(
		'path'           => __FILE__,
		'name'           => 'Article Count',
		'author'         => '[http://www.ihminen.org Mikael Korpela]',
		'url'            => 'https://github.com/Hitchwiki/Misc/blob/master/MediaWiki_extensions/article_count.php',
		'description'    => '<tt>&lt;article_count lang="LANGUAGE" /&gt;</tt>'
	);

	$wgExtensionFunctions[] = "get_article_count";
	
	// Add hooks
	function get_article_count() {
	    global $wgParser, $wgHooks;
	    //$wgParser->disableCache();
	    $wgParser->setHook('article_count', "include_article_count");
	}
	
	// parse <article_count> Tags
	function include_article_count($input, $argv, &$parser) {
	    global $wgacWikiRoot, $wgacArticle_count;
	
	    //$parser->disableCache();
	
	    $lang = isset($argv['lang']) ? urlencode($argv['lang']) : '';
	    
	    $url = $wgacWikiRoot.$lang.'/'.$wgacArticle_count.'&action=render&ctype=text/plain';
	    
	    // Prefer cURL here...
		if (function_exists('curl_init')) {
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_HEADER, 0);
			$output = curl_exec($ch);
			curl_close($ch);
		}
		else {
			$output = file_get_contents($url);
		}
			
		if(strstr($output, 'There is currently no text in this page.')) return '';
		else return '<!-- ENCODED_CONTENT '.base64_encode(trim(strip_tags($output))).' -->';
		
	}

} else {
	echo( "This file is an extension to the MediaWiki software and cannot be used standalone.\n" );
	exit( 1 );
}

?>