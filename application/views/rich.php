<?php include('components/header.php') ?>
	<h2>whatever.ly</h2>
	<p class="description">whatever.ly helps you and your friends in your everyday endavour to do nothing. Now with even less effort!</p>
	<div id="providers">
	    <p style="text-align:left;">You can sign in with any of these providers or alternatively use a <a id="login-classic" href="#">whateverly account</a>:</p>
	    <div class="providerIcon" id="google">Google</div>
	    <div class="providerIcon" id="facebook">Facebook</div>
	    <div class="providerIcon" id="yahoo">Yahoo</div>
	    <div class="providerIcon" id="twitter">Twitter</div>
	    <p style="text-align:left;"></p>
	</div>
	<div id="classic">
	    <p>Sign in by providing your credentials or register for a new whateverly account. Or save yourself some hassle and <a href="#" id="login-rich">sign in with another provider</a><p>
	    <form method="post" action="/auth/classic">
		<p><input id="email" name="email" /><label for="email">Email</label></p>
		<p><input id="password" name="password" type="password" /><label for="password">Password</label></p>
		<p><input type="submit" name="action" value="Sign in" class="signin" /> <input type="submit" name="action" value="Sign up for an account"</p>
	    </form>
	</div>
	<script type="text/javascript">
	    $('.providerIcon').click(function(event) {
		$('#providers').fadeOut(400, function() {
		    $('#providers').empty().append('<p>Singing in with '+event.target.id.charAt(0).toUpperCase()+event.target.id.substr(1)+'</p>');
		    $('#providers').append('<p>Please wait...</p>');
		    $('#providers').fadeIn(400);
		});
		
		window.location = '/auth/'+this.id;
		return false;
	    });
	    $('#login-classic').click(function(event) {
		$('#providers').fadeOut(400, function() {
		    $('#classic').fadeIn(400);
		});
		return false;
	    });
	    $('#login-rich').click(function(event) {
		$('#classic').fadeOut(400, function() {
		    $('#providers').fadeIn(400);
		});
		return false;
	    });
	</script>
<?php include('components/footer.php') ?>