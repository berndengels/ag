<script>
	const type = "{{ $type }}",text = "{{ $text }}";
	if(undefined !== toastr[type] && text) {
		toastr[type](text);
	}
</script>
