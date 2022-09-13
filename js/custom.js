function myFunction() {
    var x = document.getElementById("myMonth").value;
    document.getElementById("demo").innerHTML = x;
}

$('.dropdown-inverse li > a').click(function(e) {
	$('.status').text(this.innerHTML);
});