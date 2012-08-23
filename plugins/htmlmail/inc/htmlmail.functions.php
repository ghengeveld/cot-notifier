<?php

defined('COT_CODE') or die('Wrong URL');

require_once cot_incfile('htmlmail', 'plug', 'emogrifier');

/**
 * Renders HTML email template with inline CSS
 * 
 * @global File_cache $cache
 * @param string $htmlFilePath Path to HTML file
 * @param string $inlineCSSFilePath Path to main CSS file with styles to be placed inline
 * @param string $externalCSSFilePath Path to optional CSS file with styles to be placed in <head>
 * @return string The rendered template
 */
function cot_htmlmail_template($htmlFilePath, $inlineCSSFilePath, $externalCSSFilePath = '')
{
	global $cache, $cfg;
	
	if (!is_file($htmlFilePath) || !is_file($inlineCSSFilePath) || ($externalCSSFilePath && !is_file($externalCSSFilePath)))
	{
		cot_print($htmlFilePath, $inlineCSSFilePath, $externalCSSFilePath);
		cot_log('Failed to load HTML email template. Missing file?', 'plg');
		return;
	}

	if ($cache)
	{
		$cacheFilename = md5($htmlFilePath.$inlineCSSFilePath.$externalCSSFilePath);
		$cacheFileModificationTime = (int)filemtime("{$cfg['cache_dir']}/htmlmail/$cacheFilename");
		
		$isHTMLNewer = filemtime($htmlFilePath) > $cacheFileModificationTime;
		$isInlineCSSNewer = filemtime($inlineCSSFilePath) > $cacheFileModificationTime;
		$isExternalCSSNewer = filemtime($externalCSSFilePath) > $cacheFileModificationTime;
		
		if ($isHTMLNewer || $isInlineCSSNewer || $isExternalCSSNewer)
		{
			$cache->disk->remove($cacheFilename, 'htmlmail');
		}
		
		$template = $cache->disk->get($cacheFilename, 'htmlmail');
	}
	
	if (!$template)
	{
		$emogrifier = new Emogrifier(file_get_contents($htmlFilePath), file_get_contents($inlineCSSFilePath));
		if (!$emogrifier)
		{
			cot_log('Failed to load Emogrifier.', 'plg');
			return;
		}
		
		$template = $emogrifier->emogrify();
		$template = str_replace(array('%7B', '%7D'), array('{', '}'), $template);
		
		if ($externalCSSFilePath && is_file($externalCSSFilePath))
		{
			$css = file_get_contents($externalCSSFilePath);
			$template = str_replace('</head>', "<style type=\"text/css\">$css</style></head>", $template);
		}
		
		$cache && $cache->disk->store($cacheFilename, $template, 'htmlmail');
	}
	
	return $template;
}

/**
 * Compiles HTML email from template and tags
 * 
 * @param string $template HTML email template with inline CSS
 * @param array $tags Associative array of tags for CoTemplate
 * @return string The compiled HTML email
 */
function cot_htmlmail_compile($template, $tags)
{
	$t = new XTemplate();
	$t->compile($template);
	foreach ($tags as $tag => $value)
	{
		if (is_array($value)) 
		{
			$t->assign($value);
			$t->parse('MAIN.'.strtoupper($tag));
		}
		else
		{
			$t->assign($tag, $value);
		}
	}
	$t->parse('MAIN');
	return $t->text('MAIN');
}

if (!function_exists('cot_cssfile'))
{
	/**
	* Returns path to a CSS file.
	*
	* @param mixed $base Item name (string), or base names (array)
	* @param string $type Extension type: 'plug', 'module' or 'core'
	* @param bool $admin Admin part
	* @return string
	*/
	function cot_cssfile($base, $type = 'module', $admin = false)
	{
		global $usr, $cfg;

		// Get base name parts
		if (is_string($base) && mb_strpos($base, '.') !== false)
		{
			$base = explode('.', $base);
		}
		if (!is_array($base))
		{
			$base = array($base);
		}

		$basename = $base[0];

		// Possible search directories depending on extension type
		if ($type == 'plug')
		{
			// Plugin template paths
			($admin && !empty($cfg['admintheme'])) && $scan_prefix[] = "{$cfg['themes_dir']}/admin/{$cfg['admintheme']}/css/";
			$scan_prefix[] = "{$cfg['themes_dir']}/{$usr['theme']}/css/";
			$scan_prefix[] = "{$cfg['plugins_dir']}/$basename/css/";
		}
		elseif ($type == 'core')
		{
			// Built-in core modules
			if(in_array($basename, array('admin', 'header', 'footer', 'message')))
			{
				$basename = 'admin';
				$scan_prefix[] = "{$cfg['themes_dir']}/{$usr['theme']}/$basename/css/";
				if (!empty($cfg['admintheme']))
				{
					$scan_prefix[] = "{$cfg['themes_dir']}/$basename/{$cfg['admintheme']}/css/";
				}
			}
			else
			{
				$scan_prefix[] = "{$cfg['themes_dir']}/{$usr['theme']}/css/";
			}
			$scan_prefix[] = "{$cfg['system_dir']}/$basename/css/";
		}
		else
		{
			// Module template paths
			($admin && !empty($cfg['admintheme'])) && $scan_prefix[] = "{$cfg['themes_dir']}/admin/{$cfg['admintheme']}/css/";
			$scan_prefix[] = "{$cfg['themes_dir']}/{$usr['theme']}/css/";
			$scan_prefix[] = "{$cfg['modules_dir']}/$basename/css/";
		}

		// Build template file name from base parts glued with dots
		$base_depth = count($base);
		for ($i = $base_depth; $i > 0; $i--)
		{
			$levels = array_slice($base, 0, $i);
			$themefile = implode('.', $levels) . '.css';
			// Search in all available directories
			foreach ($scan_prefix as $pfx)
			{
				if (file_exists($pfx . $themefile))
				{
					return $pfx . $themefile;
				}
			}
		}

		// throw new Exception('CSS file '.implode('.', $base).'.css ('.$type.') was not found.');
		return false;
	}
}

?>