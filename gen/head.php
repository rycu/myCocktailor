<!DOCTYPE HTML>
<html lang="en">
<head>

<? 

echo 	$setup->getPageMeta(),
		$deployCss,
		$setup->getGoogleFontsCss(),
		'<base href=',$setup->getSiteRoot(),'/>',
		'<!--[if lt IE 9]>',
		'<script src="https://html5shiv.googlecode.com/svn/trunk/html5.js"></script>',
		'<![endif]-->',
		'<link rel="apple-touch-icon" sizes="180x180" href="./favicons/apple-touch-icon.png">',
		'<link rel="icon" type="image/png" href="./favicons/favicon-32x32.png" sizes="32x32">',
		'<link rel="icon" type="image/png" href="./favicons/favicon-16x16.png" sizes="16x16">',
		'<link rel="manifest" href="./favicons/manifest.json">',
		'<link rel="mask-icon" href="./favicons/safari-pinned-tab.svg" color="#35a8e0">',
		'<meta name="theme-color" content="#35a8e0">';
?>

</head>