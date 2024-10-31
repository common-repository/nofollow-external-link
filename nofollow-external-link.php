<?php
/*
Plugin Name: Nofollow External Link
Plugin URI: http://www.itchimes.com
Description: Very simple to add 'rel="nofollow"' and 'target="_blank"' automatically by using this Plugin, for all the external links of your website posts or pages.
Version: 1.0
Author: Dinesh Panchal
Author URI: http://www.itchimes.com
License: GPL2
*/

add_filter( 'the_content', 'addNofollow_url_function');

function addNofollow_url_function( $content ) {

	$regExpression = "<a\s[^>]*href=(\"??)([^\" >]*?)\\1[^>]*>";
	if(preg_match_all("/$regExpression/siU", $content, $matches, PREG_SET_ORDER)) {
		if( !empty($matches) ) {
			
			$targetUrl = get_option('siteurl');
			for ($i=0; $i < count($matches); $i++)
			{
			
				$tag = $matches[$i][0];
				$tag2 = $matches[$i][0];
				$url = $matches[$i][0];
				
				$noFollow = '';

				$pattern = '/target\s*=\s*"\s*_blank\s*"/';
				preg_match($pattern, $tag2, $match, PREG_OFFSET_CAPTURE);
				if( count($match) < 1 )
					$noFollow .= ' target="_blank" ';
					
				$pattern = '/rel\s*=\s*"\s*[n|d]ofollow\s*"/';
				preg_match($pattern, $tag2, $match, PREG_OFFSET_CAPTURE);
				if( count($match) < 1 )
					$noFollow .= ' rel="nofollow" ';
			
				$pos = strpos($url,$targetUrl);
				if ($pos === false) {
					$tag = rtrim ($tag,'>');
					$tag .= $noFollow.'>';
					$content = str_replace($tag2,$tag,$content);
				}
			}
		}
	}
	
	$content = str_replace(']]>', ']]&gt;', $content);
	return $content;
}