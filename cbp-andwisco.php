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
		'heading'    => 'We are an <strong class="andwis-brand">andwis<span class="andwis-brand__dot">.</span></strong> group company.',
		'subheading' => 'Experts in Technical &amp; Compliance Services',
		'paragraphs' => array(
			'As an andwis group company, we are proud to be part of a national network of technical and compliance services across six core areas of expertise.',
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
			'icon'     => 'service-asbestos-management-remediation.svg',
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
									<img class="andwis-item__watermark" src="<?php echo esc_url( $icon ); ?>" alt="" aria-hidden="true" decoding="async">
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
