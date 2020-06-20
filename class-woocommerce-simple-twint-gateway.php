<?php 

class WC_Simple_Twint_Gateway extends WC_Payment_Gateway {

    private $order_status;

	public function __construct(){
		$this->id = 'simple_twint';
		$this->icon = plugin_dir_url( __FILE__ ) . 'img/twint_acceptance_mark_pos_100px.png';
		$this->twintinfo = plugin_dir_url( __FILE__ ) . 'img/simple-twint-twint-info.jpg';
		$this->method_title = __('Bezahlen Sie bequem mit Twint','woocommerce-simple-twint-gateway');
		$this->method_description = __( 'Have your customers pay with the Twint app', 'woocommerce-simple-twint-gateway' );
		$this->title = $this->get_option('title');
		$this->has_fields = true;
		$this->init_form_fields();
		$this->init_settings();
		$this->enabled = $this->get_option('enabled');
		$this->description = $this->get_option('description');
		$this->qr_code_img = $this->get_option('qr_code_img');
		$this->order_status = $this->get_option('order_status');
		$this->hide_text_box = $this->get_option('hide_text_box');

		add_action( 'woocommerce_update_options_payment_gateways_'.$this->id, array($this, 'process_admin_options' ) );
		add_action( 'woocommerce_thankyou', array( $this, 'simple_twint_thankyou_page' ) );
	}

	public function init_form_fields(){
		$this->form_fields = array(
			'enabled' => array(
			'title' 		=> __( 'Enable/Disable', 'woocommerce-simple-twint-gateway' ),
			'type' 			=> 'checkbox',
			'label' 		=> __( 'Enable Simple twint Payment Gateway', 'woocommerce-simple-twint-gateway' ),
			'default' 		=> 'yes'
			),
			'title' => array(
				'title' 		=> __( 'Method Title', 'woocommerce-simple-twint-gateway' ),
				'type' 			=> 'text',
				'description' 	=> __( 'This controls the title', 'woocommerce-simple-twint-gateway' ),
				'default'		=> __( 'Bequeme Ratenzahlung in Monatsraten', 'woocommerce-simple-twint-gateway' ),
				'desc_tip'		=> true,
			),
			'description' => array(
				'title' => __( 'Customer Message', 'woocommerce-simple-twint-gateway' ),
				'type' => 'textarea',
				'css' => 'width:500px;',
				'default' => __( 'Sie können den Bestellbetrag bequem mit Ihrer Twint-App begleichen.', 'woocommerce-simple-twint-gateway' ),
				'description' 	=> __( 'The general message which you want it to appear to the customer in the checkout page.', 'woocommerce-simple-twint-gateway' ),
			),
			'qr_code_img' => array(
				'title' 		=> __( 'Link to QR-Code image (copy the link to the image from the media libary and paste here)', 'woocommerce-simple-twint-gateway' ),
				'type' 			=> 'text',
				'description' 	=> __( 'Link to QR-Code image (copy the link to the image from the media libary and paste here', 'woocommerce-simple-twint-gateway' ),
				'default'		=> __( 'Link to QR-Code image (copy the link to the image from the media libary and paste here', 'woocommerce-simple-twint-gateway' ),
				'desc_tip'		=> true,
			),			
			'order_status' => array(
				'title' => __( 'Order Status After The Checkout', 'woocommerce-simple-twint-gateway' ),
				'type' => 'select',
				'options' => wc_get_order_statuses(),
				'default' => 'wc-on-hold',
				'description' 	=> __( 'The default order status if this gateway used in payment.', 'woocommerce-simple-twint-gateway' ),
			),
			'hide_text_box' => array(
				'title' 		=> __( 'Hide Admin Notice', 'woocommerce-simple-twint-gateway' ),
				'type' 			=> 'checkbox',
				'label' 		=> __( 'Hide', 'woocommerce-simple-twint-gateway' ),
				'default' 		=> 'no',
				'description' 	=> __( 'If you do not want to show the admin notice box to customers (so they can send you a message), enable this option.', 'woocommerce-simple-twint-gateway' ),
			),					
		);
	}
	/**
	 * Admin Panel Options
	 * - Options for bits like 'title' and availability on a country-by-country basis
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public function admin_options() {
		?>
		<h3><?php _e( 'Simple Twint Gateway', 'woocommerce-simple-twint-gateway' ); ?></h3>
			<div id="poststuff">
				<div id="post-body" class="metabox-holder columns-1">
					<div id="post-body-content">
						<table class="form-table">
							<?php $this->generate_settings_html();?>
						</table><!--/.form-table-->
					</div>
				</div>
			</div>	
				<?php
	}

	public function validate_fields() {
	    if($this->text_box_required === 'no'){
	        return true;
        }

	    if($this->hide_text_box === 'no'){
			$textbox_value = (isset($_POST['other_payment-admin-note']))? trim($_POST['other_payment-admin-note']): '';
			if($textbox_value === ''){
				wc_add_notice( __('Please, complete the payment information.','woocommerce-simple-twint-gateway'), 'error');
				return false;
			}
			return true;
		}	
	}

	public function process_payment( $order_id ) {
		global $woocommerce;
		$order = new WC_Order( $order_id );
		// Mark as on-hold (we're awaiting the cheque)
		$order->update_status($this->order_status, __( 'Awaiting payment', 'woocommerce-simple-twint-gateway' ));
		// Reduce stock levels
		wc_reduce_stock_levels( $order_id );
		if(isset($_POST[ $this->id.'-admin-note']) && trim($_POST[ $this->id.'-admin-note'])!=''){
			$order->add_order_note(esc_html($_POST[ $this->id.'-admin-note']),1);
		}
		// Remove cart
		$woocommerce->cart->empty_cart();
		// Return thankyou redirect
		return array(
			'result' => 'success',
			'redirect' => $this->get_return_url( $order )
		);	
	}

	public function payment_fields(){
	    ?>
		<fieldset>
			<p class="form-row form-row-wide">
                <label for="<?php echo $this->id; ?>-admin-note"><?php echo ($this->description); ?> </label>
                <?php if($this->hide_text_box !== 'yes'){ ?>
				    <textarea id="<?php echo $this->id; ?>-admin-note" class="input-text" type="text" name="<?php echo $this->id; ?>-admin-note"></textarea>
                <?php } ?>
			</p>						
			<div class="clear"></div>
		</fieldset>
		<?php
	}

	/**
	* Output for the order received page.
	*/
	public function simple_twint_thankyou_page( $order_id ) {
		$order = wc_get_order( $order_id );
		if ( $order->get_payment_method() === 'simple_twint' ) {
			$simple_twint_currency = $order->get_currency();
			$simple_twint_ordertotal = $order->get_total();
			echo '<img class="checkout-twint-img" style="display: block;margin: 0 auto;" src="'. $this->twintinfo . '">';
			echo '<h3>' . __('Bitte überweisen Sie den folgenden Betrag:', 'woocommerce-simple-twint-gateway') . '</h3>';
			echo '<p><b>' . $simple_twint_currency. ' ' . $simple_twint_ordertotal . '</b></p>';
			if ( $this->qr_code_img ) {
				echo '<img style="width:500px;display:block;margin:0 auto;" src="'. $this->qr_code_img . '">';
			}	
		}
	}	
}