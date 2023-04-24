<script>
	$(function() {
		$.ajaxSetup({
			headers: {
				'X-CSRF-TOKEN': {!! json_encode(csrf_token()) !!},
			},
			error:function(xhr,status,error){
				
			},
			statusCode: {
				401: function(){
					location.reload();
				},
				419: function(){
					location.reload();
				}
			}
		});
	});
</script>