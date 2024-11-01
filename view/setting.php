<style>
.wtd_table tr td{padding-bottom: 15px;}
.phpradar-wtd-container tr td.label{font-weight: bold;}
.input_field{width:150px;}
label.px{font-size: 12px;font-style: italic;font-weight: bold;text-transform: lowercase;}
.wtd_table tr td.setting{  border-bottom: 2px solid #000;    color: #000;    font-size: 15px;    font-weight: bold;    padding-bottom: 5px;}
.phpradar-wtd-container{width:90%;margin-top:20px;}
.phpradar-wtd-container .hndle{margin: 0px;padding: 10px 15px;}
.wtd_table{width:100%;}
</style>
<div class="clear"></div>
	<?php 
		echo $this->PHPRADAR_wtd_get_error_message($error);
	?>
<div class="postbox phpradar-wtd-container" id="dashboard_right_now" >
    <h3 class="hndle"><?php echo __('Tip/Donation Settings', 'wtd') ?></h3>
    <div class="inside">
        <div class="main">
            <form method="post" action="" name="<?php echo self::$plugin_slug; ?>">
                <input type="hidden" name="<?php echo self::$plugin_slug; ?>" value="1"/>
                <table class="wtd_table" >
                    <tr>
                        <td  width="20%" class="label"><?php echo __('Enable', 'wtd') ?></td>
                        <td>
                            <input type="checkbox" name="wtd_enable" <?php echo ($this->PHPRADAR_get_wtd_setting("wtd_enable")) ? "checked=checked" : ""; ?> value="1" />
                        </td>
                    </tr>
                    <tr>
                        <td class="label"><?php echo __('Display Fields on', 'wtd') ?></td>
                        <td>
                            <select name="wtd_display_fee" class="wtd_display_fee">
                                <option value="1" <?php echo $this->PHPRADAR_get_wtd_setting("wtd_display_fee")==1?"selected=selected":""; ?> ><?php echo __("Only Cart Page", 'wtd'); ?></option>
                                <option value="2" <?php echo $this->PHPRADAR_get_wtd_setting("wtd_display_fee")==2?"selected=selected":""; ?> ><?php echo __("Only Checkout Page", 'wtd'); ?></option>
                                <option value="3" <?php echo $this->PHPRADAR_get_wtd_setting("wtd_display_fee")==3?"selected=selected":""; ?> ><?php echo __("Both(Cart And Checkout)", 'wtd'); ?></option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td class="label"><?php echo __('Cart Page Position?', 'wtd') ?></td>
                        <td>
                            <select name="wtd_cart_page_position" class="wtd_cart_page_position">
								<?php foreach($this->wtd_cart_hook as $hook=>$hookTitle){ ?>
                                <option value="<?php echo $hook; ?>" <?php echo $this->cart_default_hook==$hook?"selected=selected":""; ?> ><?php echo __($hookTitle, 'wtd'); ?></option>
								<?php } ?>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td class="label"><?php echo __('Checkout Page Position?', 'wtd') ?></td>
                        <td>
                            <select name="wtd_checkout_page_position" class="wtd_checkout_page_position">
								<?php foreach($this->wtd_checkout_hook as $hook=>$hookTitle){ ?>
                                <option value="<?php echo $hook; ?>" <?php echo $this->checkout_default_hook==$hook?"selected=selected":""; ?> ><?php echo __($hookTitle, 'wtd'); ?></option>
								<?php } ?>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td class="label"><?php echo __('Button Label', 'wtd') ?></td>
                        <td>
                            <input type="text" name="wtd_btn_label" value="<?php echo $this->PHPRADAR_get_wtd_setting('wtd_btn_label') ?>" />
                        </td>
                    </tr>
                    <tr>
                        <td class="label"><?php echo __('Display Remove Button', 'wtd') ?></td>
                        <td>
                            <input type="checkbox" name="wtd_enable_btn_remove" <?php echo ($this->PHPRADAR_get_wtd_setting("wtd_enable_btn_remove")) ? "checked=checked" : ""; ?> value="1" />
                        </td>
                    </tr>
                    <tr>
                        <td class="label"><?php echo __('Remove Button Label', 'wtd') ?></td>
                        <td>
                            <input type="text" name="wtd_remove_label" value="<?php echo $this->PHPRADAR_get_wtd_setting('wtd_remove_label') ?>" />
                        </td>
                    </tr>
                    <tr>
                        <td class="label"><?php echo __('Fee Title', 'wtd') ?></td>
                        <td>
                            <input type="text" name="wtd_fee_title" value="<?php echo $this->PHPRADAR_get_wtd_setting('wtd_fee_title') ?>" />
                        </td>
                    </tr>
                    <tr>
                        <td class="label"><?php echo __('Fee Messsage', 'wtd') ?></td>
                        <td>
                            <textarea name="wtd_message" rows="3" cols="20" ><?php echo $this->PHPRADAR_get_wtd_setting('wtd_message'); ?></textarea>
                        </td>
                    </tr>
                    <tr>
                        <td class="label"><?php echo __('Default Amount', 'wtd') ?></td>
                        <td>
                            <?php echo get_woocommerce_currency_symbol() ?> <input type="text" name="wtd_default_amt" value="<?php echo $this->PHPRADAR_get_wtd_setting('wtd_default_amt'); ?>" />
                        </td>
                    </tr>
                    <tr>
                        <td class="label"><?php echo __('Is Taxable', 'wtd') ?></td>
                        <td>
                            <input type="checkbox" name="wtd_taxable" <?php echo ($this->PHPRADAR_get_wtd_setting("wtd_taxable")) ? "checked=checked" : ""; ?> value="1" />
                        </td>
                    </tr>
                    <tr>
                        <td class="label"><?php echo __('Enable input mode', 'wtd') ?></td>
                        <td>
                            <input type="checkbox" name="wtd_enable_input_mode" <?php echo ($this->PHPRADAR_get_wtd_setting("wtd_enable_input_mode")) ? "checked=checked" : ""; ?> value="1" />
                        </td>
                    </tr>					
                    <tr>
                        <td class="label"><?php echo __('Pre-Defined Amount Enable', 'wtd') ?></td>
                        <td>
                            <input type="checkbox" name="wtd_pre_defined_enable" <?php echo ($this->PHPRADAR_get_wtd_setting("wtd_pre_defined_enable")) ? "checked=checked" : ""; ?> value="1" />
                        </td>
                    </tr>
                    <tr>
                        <td class="label"><?php echo __('Pre-Defined Amount', 'wtd') ?></td>
                        <td>
                            <?php echo get_woocommerce_currency_symbol() ?> <input type="text" name="wtd_pre_defined_amt" value="<?php echo $this->PHPRADAR_get_wtd_setting('wtd_pre_defined_amt'); ?>" />
							<span>Exp : 5,10,15<span>
                        </td>
                    </tr>
					<tr>
                        <td>&nbsp;</td>
                        <td>
                            <input type="submit" class="button button-primary" name="btn-wtd-submit" value="<?php echo __("Save Settings", "wtd") ?>" />
                        </td>
                    </tr>
                </table>
				<input type="hidden" value="<?php echo wp_create_nonce( 'phpradar-wtd-nonce' ); ?>" name="_phpradar_nonce"/>
            </form>
        </div>
    </div>
</div>