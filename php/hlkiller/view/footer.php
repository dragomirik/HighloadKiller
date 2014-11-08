<script type="text/javascript">
  $(function(){
  
	$('.hl_btn').on('click', function() {
		$('.generete_block').hide()
		$('.high_load_block').show()
		$('.hl_btn').addClass('btn-success')
		$('.gen_btn').removeClass('btn-success')
	});
	$('.gen_btn').on('click', function() {
		$('.generete_block').show()
		$('.high_load_block').hide()
		$('.gen_btn').addClass('btn-success')
		$('.hl_btn').removeClass('btn-success')
	});
	
	$('.gen_fish').on('click', function() {
		
			$.get('index.php?do=generate_fish', function(data){
			
				$('#result_div').html(data);
			
			});
	});
	
	$('.clear_db').on('click', function() {
		
			$.get('index.php?do=clear_db', function(data){
			
				$('#result_div').html(data);
			
			});
	});
	
	$('.max_select_attack').on('click', function() {
	
		$.get('index.php?do=make_select_attack', function(data){
			
			$('#result_div').html(data);
			
		});
	
	});

  
  });
</script>