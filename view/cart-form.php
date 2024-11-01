<!-- MANUAL AMOUNT OR PREDEFINED AMOUNT ON/OFF -->
<?php if($this->PHPRADAR_get_wtd_setting('wtd_enable_input_mode')==1 || $this->PHPRADAR_get_wtd_setting('wtd_pre_defined_enable')==1): ?>
<div class="phpradar-donation-section cart_section" >
	<?php if(!empty($this->PHPRADAR_get_wtd_setting('wtd_message'))){ ?>
	<!-- HEADING MESSAGE -->
    <p class="message"><strong><?php echo $this->PHPRADAR_get_wtd_setting('wtd_message'); ?></strong></p>
	<?php } ?>
	<?php if($this->PHPRADAR_get_wtd_setting('wtd_pre_defined_enable')==1):
			if (preg_match($integerRegx,str_replace(' ','',$this->PHPRADAR_get_wtd_setting('wtd_pre_defined_amt'))) ) : ?>
				<!-- PREDEFINED AMOUNT BAR ON/OFF -->
				<div class="input text" style="margin-bottom:5px;">
					<?php foreach(explode(',',str_replace(' ','',$this->PHPRADAR_get_wtd_setting('wtd_pre_defined_amt'))) as $preAmt): ?>
					<a href="javascript:void(0);" class="fee-button phpradar-default-fee-add <?php echo ($amount==$preAmt ? 'fee-button-added' : ''); ?>" data-value="<?php echo $preAmt; ?>" data-selected="<?php echo ($amount==$preAmt ? 'true' : 'false'); ?>"><?php echo $preAmt; ?></a>
					<?php endforeach; ?>
				</div>
	<?php endif; endif; ?>
	
	<?php 
		if($this->PHPRADAR_get_wtd_setting('wtd_enable_input_mode')==1): 
			$feeAddLabel = $this->PHPRADAR_get_wtd_setting('wtd_btn_label');
			$feeRemoveLabel = $this->PHPRADAR_get_wtd_setting('wtd_remove_label');
	?>
	<!-- MANUAL AMOUNT INPUT ON/OFF -->
    <div class="input text">
		<input type="text" value="<?php echo $amount; ?>" class="input-text donation-amount" name="donation-amount" style="margin-bottom: 20px;" />
        <a href="javascript:void(0);" class="phpradar-fee-add"><?php echo ($feeAddLabel ? $feeAddLabel : 'Add'); ?></a>
		<?php if($this->PHPRADAR_get_wtd_setting('wtd_enable_btn_remove')==1): ?>
			<a href="javascript:void(0);" class="phpradar-fee-remove"><?php echo ($feeRemoveLabel ? $feeRemoveLabel : 'Remove'); ?></a>
		<?php endif; ?>
    </div>
	<?php endif; ?>
	
	<!-- SECURITY NONCE -->
	<input type="hidden" value="<?php echo wp_create_nonce( 'phpradar-wtd-nonce' ); ?>" class="_phpradar_nonce"/>
	
	<!-- ERROR DISPLAY -->
    <div class="phpradar-error">&nbsp;</div>
</div>
<style>
.phpradar-error {
	visibility: hidden;
}
.fee-button-added {
	 color:#fff !important;
	 background-color: #2F6EA6 !important;
}
.fee-button {
  border:1px solid #2F6EA6 !important;
   background-color:#fff;
    color: #2F6EA6;
    padding: 5px 10px !important;
    text-align: center !important;
    text-decoration: none !important;
    display: inline-block !important;
    font-size: 16px !important;
}
.phpradar-fee-add, .phpradar-fee-remove{
	padding: 14px 25px;
	background: #2F6EA6;
	color:#fff;
}
</style>
<?php endif; ?>