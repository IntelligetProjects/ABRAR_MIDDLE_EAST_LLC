<div class="panel panel-default  p15 no-border m0" style="    font-size: 15px;">
	<span class="mr10"><b><?php echo lang("journal_entries") ?></b></span>
	<span class="mr10"><?php echo lang("transaction") . ": " . $transactions_info->id; ?></span>
	<span class="mr10"><?php echo lang("date") . ": " . format_to_date($transactions_info->date); ?></span>
    <span class="mr10"><?php echo lang("status") . ": " . $transaction_valid_label; ?></span>
    <span class="mr10"><?php echo lang("note") . ": " . $transactions_info->reference; ?></span>
</div>