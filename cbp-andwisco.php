<?php
/**
 * Plugin Name:       CBP Andwisco
 * Plugin URI:        https://cpsbuildingservices.co.uk/
 * Description:       Provides the [andwis_block] shortcode: an andwis group "areas of expertise" block that behaves as a left-tabbed list on desktop and a single-open accordion on mobile. Requires Bootstrap 5 to be available on the page.
 * Version:           1.0.0
 * Author:            CBP
 * License:           GPL-2.0-or-later
 * Text Domain:       cbp-andwisco
 *
 * @package CBP_Andwisco
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // No direct access.
}

define( 'CBP_ANDWISCO_VERSION', '1.0.0' );
define( 'CBP_ANDWISCO_FILE', __FILE__ );
define( 'CBP_ANDWISCO_PATH', plugin_dir_path( __FILE__ ) );
define( 'CBP_ANDWISCO_URL', plugin_dir_url( __FILE__ ) );

/**
 * The intro copy shown in the first column.
 *
 * @return array{heading:string,subheading:string,paragraphs:string[],cta_label:string,cta_url:string}
 */
function cbp_andwisco_intro() {
	$intro = array(
		'heading'    => 'We are an andwis group company.',
		'subheading' => 'Experts in Technical &amp; Compliance Services',
		'paragraphs' => array(
			'As an andwis group company, we are proud to be part of a national network of technical and compliance services across five core areas of expertise.',
			'Like us, our colleagues treat customer delivery and long-term partnerships in the highest regard, ensuring we deliver industry-leading technical and compliance solutions from a single provider.',
		),
		'cta_label'  => 'Find out More',
		'cta_url'    => 'https://www.andwis.com/',
	);

	return apply_filters( 'cbp_andwisco_intro', $intro );
}

/**
 * The list of services rendered as triggers + detail panels.
 *
 * Each service:
 *  - id       : unique slug used for ids/aria wiring.
 *  - title    : the short title (may contain a <br> for the trigger layout).
 *  - subtitle : short descriptive line shown in the detail panel.
 *  - content  : the body copy (allowed inline HTML).
 *  - icon     : SVG file name located in assets/icons/.
 *  - alt      : icon alt text.
 *  - url      : andwis site link URL.
 *
 * @return array<int,array<string,string>>
 */
function cbp_andwisco_services() {
	$services = array(
		array(
			'id'       => 'fire-and-security',
			'title'    => 'Fire<br>&amp; Security',
			'subtitle' => 'Complete fire, safety and security solutions',
			'content'  => 'Whether you&rsquo;re completing a Cat A fit out or looking for a fully integrated access control and CCTV system for an enterprise organisation, our Fire &amp; Security services offer a comprehensive solution for all your security and life safety systems.',
			'icon'     => 'service-fire-and-security.svg',
			'alt'      => 'Fire and Security Icon',
			'url'      => 'https://www.andwis.com/expertise-area/fire-security/',
		),
		array(
			'id'       => 'environmental-services',
			'title'    => 'Environmental<br>Services',
			'subtitle' => 'Simple, fast, and fully assured water, air, and asbestos compliance',
			'content'  => 'Combining water, air and asbestos solutions to provide total environmental compliance for high risk and multi-site estates nationwide.',
			'icon'     => 'service-environmental-services.svg',
			'alt'      => 'Environmental Services Icon',
			'url'      => 'https://www.andwis.com/expertise-area/environmental-services/',
		),
		array(
			'id'       => 'lifts-and-entrance',
			'title'    => 'Lifts<br>&amp; Entrance',
			'subtitle' => 'Improving movement flow and access for people in buildings',
			'content'  => 'Managing the flow of people within a busy commercial space can be challenging. Yet, with our expertise, combined with the industry&rsquo;s leading technology, we can improve how people move, use and access your building.',
			'icon'     => 'service-lifts-entrance.svg',
			'alt'      => 'Lifts and Entrance Icon',
			'url'      => 'https://www.andwis.com/expertise-area/lifts-entrance/',
		),
		array(
			'id'       => 'maintenance-and-response',
			'title'    => 'Maintenance<br>&amp; Response',
			'subtitle' => 'Turnkey building maintenance with fast and effective emergency support',
			'content'  => 'Keeping your building fully functional, safe and compliant with planned and reactive maintenance across all andwis disciplines. For those unexpected emergencies, you can enjoy round-the-clock emergency response.',
			'icon'     => 'service-maintenance-response.svg',
			'alt'      => 'Maintenance and Response Icon',
			'url'      => 'https://www.andwis.com/expertise-area/maintenance-response/',
		),
		array(
			'id'       => 'mechanical-and-electrical',
			'title'    => 'Mechanical<br>&amp; Electrical',
			'subtitle' => 'Expert M&amp;E engineering to deliver more efficient and sustainable buildings',
			'content'  => 'Our focus is on delivering more efficient, sustainable and comfortable buildings using expert engineering, design and innovation. We work across all sectors to provide heating, cooling, ventilation, refrigeration, and electrical installations.',
			'icon'     => 'service-m-e.svg',
			'alt'      => 'Mechanical and Electrical Icon',
			'url'      => 'https://www.andwis.com/expertise-area/mechanical-electrical/',
		),
	);

	return apply_filters( 'cbp_andwisco_services', $services );
}

/**
 * Register front-end assets (registered now, enqueued on demand by the shortcode).
 */
function cbp_andwisco_register_assets() {
	$css = CBP_ANDWISCO_PATH . 'assets/css/andwis-block.css';
	$js  = CBP_ANDWISCO_PATH . 'assets/js/andwis-block.js';

	wp_register_style(
		'cbp-andwisco',
		CBP_ANDWISCO_URL . 'assets/css/andwis-block.css',
		array(),
		file_exists( $css ) ? filemtime( $css ) : CBP_ANDWISCO_VERSION
	);

	wp_register_script(
		'cbp-andwisco',
		CBP_ANDWISCO_URL . 'assets/js/andwis-block.js',
		array(),
		file_exists( $js ) ? filemtime( $js ) : CBP_ANDWISCO_VERSION,
		true
	);
}
add_action( 'wp_enqueue_scripts', 'cbp_andwisco_register_assets' );

/**
 * Render the [andwis_block] shortcode.
 *
 * @return string
 */
function cbp_andwisco_shortcode() {
	wp_enqueue_style( 'cbp-andwisco' );
	wp_enqueue_script( 'cbp-andwisco' );

	$intro    = cbp_andwisco_intro();
	$services = cbp_andwisco_services();

	// Inline HTML we allow inside content/title fields.
	$allowed = array(
		'br'     => array(),
		'strong' => array( 'class' => array() ),
		'em'     => array(),
		'b'      => array(),
		'i'      => array(),
		'span'   => array( 'class' => array() ),
		'a'      => array(
			'href'   => array(),
			'target' => array(),
			'rel'    => array(),
		),
	);

	ob_start();
	?>
	<section class="andwis-block py-5">
		<div class="container">
			<div class="row g-4">
				<div class="col-12 col-xl-4 col-xxl-4 mb-4">
					<h2><?php echo wp_kses( $intro['heading'], $allowed ); ?></h2>
					<h3><?php echo wp_kses( $intro['subheading'], $allowed ); ?></h3>
					<?php foreach ( (array) $intro['paragraphs'] as $paragraph ) : ?>
						<p><?php echo wp_kses( $paragraph, $allowed ); ?></p>
					<?php endforeach; ?>
					<?php if ( ! empty( $intro['cta_url'] ) && ! empty( $intro['cta_label'] ) ) : ?>
						<a class="btn btn-primary" href="<?php echo esc_url( $intro['cta_url'] ); ?>" target="_blank" rel="noopener">
							<?php echo esc_html( $intro['cta_label'] ); ?>
						</a>
					<?php endif; ?>
				</div>

				<div class="col-12 col-xl-8 col-xxl-8">
					<div class="andwis-items has-active" data-andwis>
						<?php
						foreach ( $services as $index => $service ) {
							$base    = 'andwis-' . sanitize_html_class( $service['id'] );
							$tab_id  = $base . '-tab';
							$pane_id = $base . '-panel';
							$icon    = CBP_ANDWISCO_URL . 'assets/icons/' . $service['icon'];
							$active  = ( 0 === $index ); // First service open by default.
							?>
							<div class="andwis-item">
								<button type="button"
									class="andwis-item__trigger"
									id="<?php echo esc_attr( $tab_id ); ?>"
									role="tab"
									aria-controls="<?php echo esc_attr( $pane_id ); ?>"
									aria-expanded="<?php echo $active ? 'true' : 'false'; ?>">
									<span class="andwis-item__icon">
										<img src="<?php echo esc_url( $icon ); ?>" alt="<?php echo esc_attr( $service['alt'] ); ?>" loading="lazy" decoding="async">
									</span>
									<span class="andwis-item__title"><?php echo wp_kses( $service['title'], $allowed ); ?></span>
									<span class="andwis-item__chevron" aria-hidden="true"></span>
								</button>

								<div class="andwis-item__panel"
									id="<?php echo esc_attr( $pane_id ); ?>"
									role="tabpanel"
									aria-labelledby="<?php echo esc_attr( $tab_id ); ?>"
									<?php echo $active ? '' : 'hidden'; ?>>
									<div class="andwis-item__body">
										<div class="andwis-item__title-lg"><?php echo wp_kses( str_replace( '<br>', ' ', $service['title'] ), $allowed ); ?></div>
										<div class="andwis-item__subtitle"><?php echo wp_kses( $service['subtitle'], $allowed ); ?></div>
										<div class="andwis-item__content"><p><?php echo wp_kses( $service['content'], $allowed ); ?></p></div>
										<div class="andwis-item__link">
											<a href="<?php echo esc_url( $service['url'] ); ?>" target="_blank" rel="noopener" class="">
												Find out More
											</a>
										</div>
									</div>
									<div class="logo"><svg xmlns="http://www.w3.org/2000/svg" role="img" aria-label="andwis." viewBox="0 0 194 46" xml:space="preserve"><path class="dot" d="M193.54 37.77c0 2.9-2.3 5.29-5.14 5.29a5.22 5.22 0 0 1-5.14-5.3c0-2.9 2.3-5.28 5.14-5.28a5.2 5.2 0 0 1 5.14 5.29z"></path><path class="andwis" d="m157.03 33.92-.06-.6h8.59v.55-.54h.53v.81c.02 1.35.96 2.23 2.58 2.27 1.62-.06 2.3-.68 2.35-2.05-.04-1.02-.33-1.28-1.26-1.71-.93-.39-2.42-.66-4.25-1.25-5.09-1.59-7.8-4.64-7.76-9.33a9.93 9.93 0 0 1 3.1-7.5c2-1.85 4.89-2.9 8.42-2.9 6.15-.05 10.3 3.73 10.6 9.6l.03.57h-8.7l-.07-.46c-.38-1.87-.74-2.24-1.98-2.3-1.44.04-2.05.71-2.1 1.9.03.85.3 1.22 1 1.66a15 15 0 0 0 3.32 1.17c5.93 1.62 9.26 4.2 9.22 9.39 0 3.11-1.2 5.84-3.32 7.76-2.13 1.92-5.14 3.03-8.73 3.03h-.1c-6.56 0-10.85-3.86-11.4-10.07zM154.3 12.04v31.38h-10.11V12.04M154.5 8.06a5.69 5.69 0 0 1-5.25 3.6h-.02a5.5 5.5 0 0 1-2.16-.44 5.84 5.84 0 0 1-3.5-5.39c0-.74.14-1.5.43-2.22A5.68 5.68 0 0 1 149.25 0c.73 0 1.47.14 2.18.45 2.19.93 3.5 3.1 3.5 5.39 0 .74-.14 1.5-.43 2.22M98.4 12.11h10.04l4.94 11.62 3.38-7.72-1.73-3.9h8.96l4.94 12.01 4.35-12.01h9.26v.54-.54h.81l-13.64 33.01-9.15-19.52-8.38 19.57zM88.13 2.17v12.34c-1.99-1.99-4.61-2.95-7.76-2.94-3.85 0-7.37 1.45-10.2 4.33a16.11 16.11 0 0 0-4.89 11.68c0 4.88 1.83 9.13 5.34 12.32 2.67 2.45 5.89 3.7 9.58 3.7h.06c3.18 0 5.77-.81 8.16-2.87v2.72h9.47V2.17h-9.76zm-6 32.27c-4.17-.02-7.07-2.94-7.08-6.9 0-3.8 2.96-6.99 6.77-7 3.74.01 6.63 3 6.65 7.1 0 3.92-2.91 6.8-6.34 6.8zM54.3 43.47v-.54h.53-.54v.54h-.54V26.34c0-1.39-.07-3.33-1.48-4.73a5.42 5.42 0 0 0-3.58-1.44c-1.3 0-2.7.56-3.5 1.3-1.64 1.47-1.7 3.54-1.7 5.34v16.66h-9.43V26.94c0-3.48.16-7.35 3.77-11.1 2.8-2.95 6.38-4.3 11-4.3 5.2 0 8.97 1.84 11.07 4.16 2.95 3.3 3.27 7.68 3.28 11.24v16.54h-8.89zM21.95 13.18v2.35c-1.9-1.9-4.41-2.8-7.42-2.8-3.73 0-7.12 1.44-9.84 4.21A15.58 15.58 0 0 0 0 28.19c0 4.68 1.76 8.77 5.15 11.85a13.13 13.13 0 0 0 9.21 3.57h.07c3.03 0 5.5-.78 7.79-2.73v2.55h9.16V13.18h-9.43zm-5.73 21.56c-4.02-.02-6.78-2.85-6.82-6.62a6.58 6.58 0 0 1 6.49-6.68c3.6 0 6.36 2.87 6.38 6.79 0 3.75-2.77 6.51-6.05 6.51z"></path></svg></div>
								</div>
							</div>
							<?php
						}
						?>

						<div class="andwis-items__empty" aria-hidden="true">
							<p class="mb-0">Select a service to view more details.</p>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>
	<?php
	return ob_get_clean();
}
add_shortcode( 'andwis_block', 'cbp_andwisco_shortcode' );
