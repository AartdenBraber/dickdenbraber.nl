<?php
/**
 * The template for displaying the footer
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Dick_den_Braber
 */

?>


	</div><!-- #content -->

</div><!-- #page -->


<?php if( is_front_page() ): ?>
<div class="page-content contactform-bottom" id="contact">
	<div class="entry-content content">
		
		<div class="footer-contact-ways">
			<a href="tel:+31650460271?call"><svg class="svg__icon"><use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#phone"></use></svg>+316 504 602 71</a>
			<a href="mailto:info@dickdenbraber.nl"><svg class="svg__icon"><use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#emailaddress"></use></svg>info@dickdenbraber.nl</a>
		</div>

		<div class="footer-contact-form">
			<?php the_field('contact_inhoud'); ?>
		</div>


	</div>
</div>
<?php endif; ?>

<footer class="page-content site-footer background-cover">

	<div class="entry-content content">


		<h3 class="footer-title"><?php bloginfo( 'name' ); ?></h3>

		<div class="three-cols">

			<div class="col">
				<ul class="text-list">
					<li><a href="<?php echo get_permalink( get_page_by_path( 'disclaimer' ) ); ?>">Vrijwaringsclausule</a></li>
					<li><a href="https://www.kvk.nl/orderstraat/product-kiezen/?kvknummer=515989730000">KvK-registratie</a></li>
				</ul>
			</div>
			<div class="col">
				<ul class="contact-icons icons-row">
					<li><a href="tel:+31650460271?call"><svg class="svg__icon"><use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#phone"></use></svg><span class="screen-reader-text">Telefoon</span></a></li>
					<li><a href="mailto:info@dickdenbraber.nl"><svg class="svg__icon"><use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#emailaddress"></use></svg><span class="screen-reader-text">E-mail</span></a></li>
				</ul>
			</div>
			<div class="col social-media-icons">
				<ul class="social-media-icons icons-row">
					<li><a href="//linkedin.com/in/dickdenbraber"><svg class="svg__icon"><use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#linkedin"></use></svg><span class="screen-reader-text">LinkedIn</span></a></li>
					<li><a href="//twitter.com/dickdenbraber"><svg class="svg__icon"><use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#twitter"></use></svg><span class="screen-reader-text">Twitter</span></a></li>
					<li><a href="//facebook.com/dickdenbraber"><svg class="svg__icon"><use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#facebook"></use></svg><span class="screen-reader-text">Facebook</span></a></li>
				</ul>
			</div>
		</div><!-- .three-cols -->
		<small>&copy; 2009 - <?php echo date('Y'); ?> <?php bloginfo( 'name' ); ?>, website door <a href="https://prosum.dev">Prosum Development</a></small>

	</div>

</footer><!-- .site-footer -->

<!-- Icons -->
<svg xmlns="http://www.w3.org/2000/svg" style="display: none;">


	<symbol id="location" viewBox="0 0 32 32">
		<title>Locatie</title>
		<desc>Adres</desc>
		<path d="M17 8c-2.58 0-4.67 2.1-4.67 4.67 0 .82.2 1.62.6 2.28l3.8 6.9c.06.1.16.15.27.15.1 0 .2-.06.26-.15l3.8-6.9c.38-.66.6-1.46.6-2.28C21.67 10.1 19.6 8 17 8zm0 6.42c-.97 0-1.75-.78-1.75-1.75 0-.98.78-1.75 1.75-1.75s1.75.77 1.75 1.75c0 .97-.78 1.75-1.75 1.75z" fill-rule="evenodd"></path>
	</symbol>

	<symbol id="emailaddress" viewBox="0 0 32 32">
		<title>E-mail</title>
		<desc>E-mailadres</desc>
		<g fill-rule="evenodd"><path d="M9 13.2l7 5.25 7-5.25v7.18c0 .24-.1.44-.26.6-.17.18-.37.27-.6.27H9.86c-.24 0-.44-.1-.6-.26-.18-.18-.27-.38-.27-.63V13.2z"></path><path d="M23 11.63l-7 5.24-7-5.25c0-.24.1-.44.26-.6.17-.18.37-.27.62-.27h12.24c.25 0 .45.1.62.26.17.18.26.38.26.63z"></path></g>
	</symbol>

	<symbol id="phone" viewBox="0 0 32 32">
		<title>Telefoon</title>
		<desc>Telefoonnummer</desc>
		<path d="M10 10.67l.03-.1c.02-.08.08-.14.15-.18L13.65 9c.14-.05.3 0 .37.14l1.9 3.83c.08.1.04.23-.04.33l-1.86 1.86c.95 1.53 2.25 2.84 3.8 3.8l1.85-1.88c.1-.08.23-.12.34-.05l3.84 1.9c.13.08.2.24.14.38l-1.37 3.47c-.03.07-.07.13-.16.16-.03.02-.08.02-.1.02-.04 0-.07 0-.12-.02-6.35-.8-11.4-5.83-12.2-12.2l-.02-.1z" fill-rule="evenodd"></path>
	</symbol>

	<symbol id="facebook" viewBox="0 0 32 32">
	    <title>Facebook</title>
	    <desc>Link naar Facebook</desc>
	    <path d="M14 22.57h2.44v-6.2h1.98l.35-2.33h-2.33v-1.87c0-.98 1-.98 1-.98h1.5V9.12c-1.12-.2-2.03-.1-2.03-.1s-2.57-.1-2.9 2.53v2.48h-2v2.28h2v6.25z" fill-rule="evenodd"></path>
	</symbol>

	<symbol id="linkedin" viewBox="0 0 32 32">
	    <title>LinkedIn</title>
	    <desc>Link naar LinkedIn</desc>
	    <g fill-rule="evenodd"><path d="M10.74 11.5h-.02c-.5 0-.92-.15-1.24-.46-.32-.3-.48-.7-.48-1.15 0-.47.16-.85.5-1.15.3-.3.74-.46 1.26-.46s.93.15 1.25.45c.33.3.5.68.5 1.14 0 .45-.16.84-.5 1.14-.3.3-.74.46-1.26.46zM13.96 22v-7l-.05-2.04h2.88l.1 1.25c.3-.46.68-.84 1.12-1.1.44-.3.94-.43 1.5-.43 1.05 0 1.9.36 2.54 1.08.64.7.96 1.7.96 3V22h-2.92v-4.96c0-.7-.15-1.2-.44-1.54-.3-.33-.68-.5-1.14-.5-.47 0-.86.15-1.16.45-.3.3-.46.74-.46 1.3V22h-2.92zM9.3 12.96h2.9V22H9.3"></path></g>
	</symbol>

	<symbol id="twitter" viewBox="0 0 32 32">
	    <title>Twitter</title>
	    <desc>Link naar Twitter</desc>
		<path d="M24 10.87c-.44.66-.98 1.23-1.63 1.7V13c0 .73-.1 1.46-.28 2.2-.2.74-.46 1.45-.8 2.15-.35.7-.8 1.34-1.34 1.95-.54.6-1.15 1.13-1.82 1.58-.67.45-1.45.8-2.32 1.07-.88.26-1.8.4-2.78.4-1.97 0-3.65-.46-5.04-1.35.24.04.5.05.78.05.73 0 1.44-.14 2.14-.42.7-.28 1.34-.66 1.93-1.13-.7 0-1.34-.22-1.9-.65-.57-.43-.95-.97-1.15-1.63.22.04.42.06.6.06.3 0 .6-.04.88-.12-.75-.15-1.38-.52-1.88-1.12-.5-.6-.75-1.3-.75-2.1v-.03c.47.26.96.4 1.48.4-.45-.3-.8-.68-1.07-1.16-.26-.48-.4-1-.4-1.55 0-.54.15-1.07.45-1.6.83 1 1.83 1.8 3 2.38 1.16.58 2.42.9 3.77 1-.05-.22-.07-.47-.07-.77 0-.9.32-1.67.95-2.3.64-.65 1.4-.96 2.32-.96.93 0 1.74.34 2.4 1.03.76-.15 1.45-.42 2.08-.8-.25.8-.73 1.4-1.44 1.82.66-.08 1.3-.26 1.9-.53z" fill-rule="evenodd"></path>
	</symbol>

</svg>


<script type="text/javascript">
	// execute this as soon as possible
	var elementsToHide = document.getElementsByClassName('hide');
	for( var i = 0, j = elementsToHide.length; i < j; i++) {
		elementsToHide[i].style.visibility = 'hidden';
	}
</script>

<script src="//cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.2.1/owl.carousel.min.js" integrity="sha256-s5TTOyp+xlSmsDfr/aZhg0Gz+JejYr5iTJI8JxG1SkM=" crossorigin="anonymous"></script>


<!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/Hyphenator/5.2.1/Hyphenator.min.js" integrity="sha256-8143tJsVprOWSPnjULT+Jp+RIC6QYNdCoVd6kCh2FKo=" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Hyphenator/5.2.1/patterns/nl.min.js" integrity="sha256-Q6DlIZSfcVIVffTdcFvF+v+oM1bM8MaEzjURxEDehlE=" crossorigin="anonymous"></script>
 -->

<!-- <?php if( is_front_page() || is_single() ): ?>
<script src="//cdnjs.cloudflare.com/ajax/libs/smoothscroll/1.4.6/SmoothScroll.min.js" integrity="sha256-Yut1YAlvRzggjdRiTu+X56CcayNzkjwk3p34ZZL0WHk=" crossorigin="anonymous" async defer></script>
<?php endif; ?> -->
<script src="//cdnjs.cloudflare.com/ajax/libs/magnific-popup.js/1.1.0/jquery.magnific-popup.min.js" integrity="sha256-P93G0oq6PBPWTP1IR8Mz/0jHHUpaWL0aBJTKauisG7Q=" crossorigin="anonymous" async defer></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/history.js/1.8/bundled/html5/jquery.history.min.js" integrity="sha256-2dECSqPH3MHNvD9ZU4PQ3EMrtp/upBbtqs4LHUmEjeI=" crossorigin="anonymous" async defer></script>
<script src="https://www.google.com/recaptcha/api.js" async defer></script>

<script src="//cdnjs.cloudflare.com/ajax/libs/lazysizes/3.0.0/lazysizes.min.js" integrity="sha256-3aNzF0qAl+dcYi1jq/8xNvw6wR5tzgo4AOAacEleQVk=" crossorigin="anonymous" ></script>

<!--<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.2.1/assets/owl.carousel.min.css" integrity="sha256-AWqwvQ3kg5aA5KcXpX25sYKowsX97sTCTbeo33Yfyk0=" crossorigin="anonymous" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.2.1/assets/owl.theme.default.min.css" integrity="sha256-KWs9jp+jZzOZmmnW5jC8Y2HqI9rajJig5I00un99DtI=" crossorigin="anonymous" />-->

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cookieconsent2/3.0.3/cookieconsent.min.css" integrity="sha256-RWqxpxUH7ZGrrhTJ0I+v+zc6e8cRpm5ENBt7i3u3KrQ=" crossorigin="anonymous" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/cookieconsent2/3.0.3/cookieconsent.min.js" integrity="sha256-r0xmg4FKpSfK9TvePQIeaq/gCDO0Xy3q0EPIfteGRnQ=" crossorigin="anonymous"></script>
<script>
    window.addEventListener("load", function() {
        window.cookieconsent.initialise({
                "palette": {
                    "popup": {
                        "background": "#04131d"
                    },
                    "button": {
                        "background": "#caa000",
                        "text": "#fff"
                    }
                },
                "position": "bottom-right",
                "type": "opt-in",
                "revokeBtn": "<div class=\"cc-revoke {{classes}}\">Cookiekeuze herzien</div>",
                "content": {
                    "message": "Ik wil graag cookies gebruiken om voor de beste gebruikerservaring te zorgen en om de website te verbeteren aan de hand van wat u doet. De verkregen informatie omvat onder andere uw geschatte locatie.",
                    "dismiss": "Dat wil ik niet",
                    "allow": "Dat is goed",
                    "link": "Meer lezen",
                    "href": "https://dickdenbraber.nl/disclaimer/"
                },
                onPopupOpen: function() {
                    add_googleanalytics(false);
                },
                onInitialise: function(status) {
                    var type = this.options.type;
                    var didConsent = this.hasConsented();
                    add_googleanalytics(didConsent);
                    showRevokeButton();
                },

                onStatusChange: function(status, chosenBefore) {
                    var type = this.options.type;
                    var didConsent = this.hasConsented();
                    add_googleanalytics(didConsent);
                    //window.location.reload();
                }

        })
    });
    function showRevokeButton() {
        var $revokeButton = jQuery('.cc-revoke');
        if($revokeButton.length) {
            $revokeButton.css({
                display: "block"
            });
        } else {
            setTimeout(showRevokeButton, 500);
        }
    }
function add_googleanalytics(consentGiven) {
        console.log('consent' + consentGiven);
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'G-DVFZHLDT74');
    }

</script>
<script async src="https://www.googletagmanager.com/gtag/js?id=G-DVFZHLDT74"></script>

<?php wp_footer(); ?>

</body>
</html>
