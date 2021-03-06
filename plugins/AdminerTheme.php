<?php

/**
 * Adds support for Pematon's custom theme.
 * This includes meta headers, touch icons and other stuff.
 *
 * @author    Peter Knut
 * @copyright 2014-2015 Pematon, s.r.o. (http://www.pematon.com/)
 */
class AdminerTheme {
	/** @var string */
	private $themeName;

	/**
	 * @param string $themeName File with this name and .css extension should be located in css folder.
	 */
	function AdminerTheme($themeName = "bootstrap-like")
	{
		define("PMTN_ADMINER_THEME", TRUE);

		$this->themeName = $themeName;
	}

	/**
	 * Prints HTML code inside <head>.
	 *
	 * @return false
	 */
	public function head()
	{
		$userAgent = filter_input(INPUT_SERVER, "HTTP_USER_AGENT");
		?>

		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1, target-densitydpi=medium-dpi"/>

		<link rel="icon" type="image/ico" href="images/favicon.png">

		<?php
		// Condition for Windows Phone has to be the first, because IE11 contains also iPhone and Android keywords.
		if (strpos($userAgent, "Windows") !== FALSE):
			?>
			<meta name="application-name" content="Adminer"/>
			<meta name="msapplication-TileColor" content="#ffffff"/>
			<meta name="msapplication-square150x150logo" content="images/tileIcon.png"/>
			<meta name="msapplication-wide310x150logo" content="images/tileIcon-wide.png"/>

		<?php elseif (strpos($userAgent, "iPhone") !== FALSE || strpos($userAgent, "iPad") !== FALSE): ?>
			<link rel="apple-touch-icon-precomposed" href="images/touchIcon.png"/>

		<?php elseif (strpos($userAgent, "Android") !== FALSE): ?>
			<link rel="apple-touch-icon-precomposed" href="images/touchIcon-android.png?2"/>

		<?php else: ?>
			<link rel="apple-touch-icon" href="images/touchIcon.png"/>
		<?php endif; ?>

		<link rel="stylesheet" type="text/css" href="css/<?php echo htmlspecialchars($this->themeName) ?>.css?2">

		<script>			
			(function (window) {
				"use strict";

				window.addEventListener("load", function () {
					prepareMenuButton();
					styleLogin();
					document.getElementsByTagName('body')[0].style.display = 'block';
					styleLang();
				}, false);

				function styleLogin(){
					if (document.getElementById("username")){
						document.getElementsByTagName("form")[0].className += " login-form";
					}
				}

				function styleLang() {
					var lang = document.getElementById("lang");
					var logout = document.getElementById("logout");
					if (logout) {						
						lang.style.width = lang.offsetWidth + logout.offsetWidth + 20 + 'px';
					} else {
						lang.style.width = lang.offsetWidth + 'px';
					}
				}

				function prepareMenuButton() {
					var menu = document.getElementById("menu");
					var breadcrumb = document.getElementById("breadcrumb");
					var content = document.getElementById("content");
					var button = menu.getElementsByTagName("h1")[0];
					if (!menu || !button) {
						return;
					}

					function setBreadcrumbLeft(size = 30){
						if(breadcrumb){
							breadcrumb.style.left = size + 'px';
						}
					}

					menu.style.minHeight = window.innerHeight + 'px';				

					if (localStorage.getItem('adminerMenuStatus') !== 'closed') {
						menu.className += " open";
					} else {
						setBreadcrumbLeft();
						content.style.marginLeft = 0;
					}

					button.addEventListener("click", function () {
						if (menu.className.indexOf(" open") >= 0) {
							menu.className = menu.className.replace(/ *open/, "");
							localStorage.setItem('adminerMenuStatus', 'closed');
							setBreadcrumbLeft();
							content.style.marginLeft = 0;
						} else {
							menu.className += " open";
							localStorage.setItem('adminerMenuStatus', 'open');
							setBreadcrumbLeft(261);
							content.style.marginLeft = '261px';
						}
					}, false);
				}

			})(window);

		</script>

		<?php

		// Return false to disable linking of adminer.css and original favicon.
		// Warning! This will stop executing head() function in all plugins defined after AdminerTheme.
		return FALSE;
	}


	function name()
	{
		return "<a href='https://www.adminer.org/' target='_blank' id='h1'>Adminer</a>";
	}

}
