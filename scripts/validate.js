

// Here we check to see if we have access to getElementById
// if we do not have access to it we construct it dynamically
// if we do not even have document.all then we are screwed --
// we tell the user and then -- antisipate the explosion

if( ! document.getElementById ) {
	document.getElementById = function(strID) {
		var obj = null;
		if (document.layers) {
			obj = document[strID];
		} else if (document.all) {
			obj = document.all[strID];
		} else {
			alert( 'WARNING: Incompatible JavaScript DOM version for this application, please contact support' );
		}
		return $obj;
	}
}

g_bInitialValidation = false;

function findLabelFor(element) {
	var obj = null;
	var labels = document.getElementsByTagName('label');

	for( var l = 0; obj == null && l < labels.length; l++ ) {
		if( element.name == labels[l].htmlFor ) {
			obj = labels[l];
		}
	}
	return( obj );
}

function showValidateLayer(strMessage) {
	var element = document.getElementById('validateMessage');
	if( element ) {
		var style = element.style;
		element.innerHTML = 'Please fix the following items:<ul style="padding:0px;margin:0px;margin-left:15px;">' + strMessage + '</ul>';
		style.display = "block";
	}
	else {
		alert( 'ERROR In Validation: could not locate your validateMessage DIV -- please add a valid validateMessage DIV');
	}
}

function hideValidateLayer() {
	var element = document.getElementById('validateMessage');
	if( element ) {
		var style = element.style;
		element.innerHTML = '';
		style.display = "none";
	}
	else {
		alert( 'ERROR In Validation: could not locate your validateMessage DIV -- please add a valid validateMessage DIV');
	}
}

function isValid(element) {
	
	var bRetVal = true;

	if( element.type == 'text' || element.type == 'password' ) {
		if( bRetVal && element.required ) {
			bRetVal = ( element.value != '' );
		}
		
		if( bRetVal && element.validateIsEqual ) {
			bRetVal = validateIsEqual( element, element.validateIsEqual );
		}
		
		if( bRetVal && element.validateEmail ) {
			bRetVal = validateEmail( element.value );
		}
	}
	
	return( bRetVal );
}

function validateInline(strFormName) {
	if( g_bInitialValidation && document.forms[strFormName] ) {
		validateWorker(document.forms[strFormName]);
	}
}

function validate(form) {

	var bRetVal = true;

	initValidation();
	
	if( form.submit ) {
		if( form.submit.disabled ) {
			form.submit.disabled = true;
		}
	}

	bRetVal = validateWorker(form);

	if( ! bRetVal && form.submit ) {
		if( form.submit.disabled ) {
			form.submit.disabled = false;
		}
	}

	g_bInitialValidation = true;
	
	return( bRetVal );
}

function validateWorker(form){

	hideValidateLayer();

	var lbl = null;
	var strMessage = '';
	var bRetVal = true;
	
	for( var i = 0; i < form.length; ++i ) {
		if( ! isValid( form.elements[i] ) ) {
		
			if( form.elements[i].description ) {
				strMessage = strMessage + '<li style="padding:0px;margin:0px;">' + form.elements[i].description + '</li>';
			}
			if( ! form.elements[i].validateModified ) {
				form.elements[i].oldClassName = form.elements[i].className;
				form.elements[i].className = 'error';
	
				lbl = findLabelFor( form.elements[i] );
				if( lbl ) {
					lbl.oldClassName = lbl.className;
					lbl.className = 'error';
				}
				form.elements[i].validateModified = true;
			}
		}
		else
		{
			if( form.elements[i].validateModified ) {
				form.elements[i].className = form.elements[i].oldClassName;
				
				lbl = findLabelFor( form.elements[i] );
				if( lbl ) {
					lbl.className = lbl.oldClassName;
				}
				form.elements[i].validateModified = false;
			}
		}
	}
	if( strMessage != '' ) {
		showValidateLayer(strMessage);
		bRetVal = false;
	}
	
	return( bRetVal );
}

function resetError(element) {
	alert( 'resetting ' + element.name + element.oldClassName );
	if( element.oldClassName ) {
		element.className = element.oldClassName;
		element.oldClassName = null;
		
		lbl = findLabelFor( element );
		if( lbl ) {
			lbl.className = lbl.oldClassName;
			lbl.oldClassName = null;
		}
	}
	return( element );
}

function setError(element) {

	var strRetVal = '';
	
	if( element.description ) {
		strRetVal = '<li>' + element.description + '</li>';
	}
	
	element.oldClassName = element.className;
	element.className = 'error';
	
	lbl = findLabelFor( element );
	if( lbl ) {
		lbl.oldClassName = lbl.className;
		lbl.className = 'error';
	}
	
	return( strRetVal );
}

function validateIsEqual(element,element2) {
	return( element.value == element2.value );
}




function validateEmail (emailStr) {

	/* The following variable tells the rest of the function whether or not
	to verify that the address ends in a two-letter country or well-known
	TLD.  1 means check it, 0 means don't. */
	
	var checkTLD=1;
	
	/* The following is the list of known TLDs that an e-mail address must end with. */
	
	var knownDomsPat=/^(ac|ad|ae|aero|af|ag|ai|al|am|an|ao|aq|ar|arpa|as|at|au|aw|az|ba|bb|bd|be|bf|bg|bh|bi|biz|bj|bm|bn|bo|br|bs|bt|bv|bw|by|bz|ca|cc|cd|cf|cg|ch|ci|ck|cl|cm|cn|co|com|coop|cr|cu|cv|cx|cy|cz|de|dj|dk|dm|do|dz|ec|edu|ee|eg|er|es|et|eu|fi|fj|fk|fm|fo|fr|ga|gb|gd|ge|gf|gg|gh|gi|gl|gm|gn|gov|gp|gq|gr|gs|gt|gu|gw|gy|hk|hm|hn|hr|ht|hu|id|ie|il|im|in|info|int|io|iq|ir|is|it|je|jm|jo|jp|ke|kg|kh|ki|km|kn|kr|kw|ky|kz|la|lb|lc|li|lk|lr|ls|lt|lu|lv|ly|ma|mc|md|mg|mh|mil|mk|ml|mm|mn|mo|mp|mq|mr|ms|mt|mu|museum|mv|mw|mx|my|mz|na|name|nc|ne|net|nf|ng|ni|nl|no|np|nr|nu|nz|om|org|pa|pe|pf|pg|ph|pk|pl|pm|pn|pr|pro|ps|pt|pw|py|qa|re|ro|ru|rw|sa|sb|sc|sd|se|sg|sh|si|sj|sk|sl|sm|sn|so|sr|st|su|sv|sy|sz|tc|td|tf|tg|th|tj|tk|tl|tm|tn|to|tp|tr|tt|tv|tw|tz|ua|ug|uk|um|us|uy|uz|va|vc|ve|vg|vi|vn|vu|wf|ws|ye|yt|yu|za|zm|zw)$/;
	
	/* The following pattern is used to check if the entered e-mail address
	fits the user@domain format.  It also is used to separate the username
	from the domain. */
	
	var emailPat=/^(.+)@(.+)$/;
	
	/* The following string represents the pattern for matching all special
	characters.  We don't want to allow special characters in the address. 
	These characters include ( ) < > @ , ; : \ " . [ ] */
	
	var specialChars="\\(\\)><@,;:\\\\\\\"\\.\\[\\]";
	
	/* The following string represents the range of characters allowed in a 
	username or domainname.  It really states which chars aren't allowed.*/
	
	var validChars="\[^\\s" + specialChars + "\]";
	
	/* The following pattern applies if the "user" is a quoted string (in
	which case, there are no rules about which characters are allowed
	and which aren't; anything goes).  E.g. "jiminy cricket"@disney.com
	is a legal e-mail address. */
	
	var quotedUser="(\"[^\"]*\")";
	
	/* The following pattern applies for domains that are IP addresses,
	rather than symbolic names.  E.g. joe@[123.124.233.4] is a legal
	e-mail address. NOTE: The square brackets are required. */
	
	var ipDomainPat=/^\[(\d{1,3})\.(\d{1,3})\.(\d{1,3})\.(\d{1,3})\]$/;
	
	/* The following string represents an atom (basically a series of non-special characters.) */
	
	var atom=validChars + '+';
	
	/* The following string represents one word in the typical username.
	For example, in john.doe@somewhere.com, john and doe are words.
	Basically, a word is either an atom or quoted string. */
	
	var word="(" + atom + "|" + quotedUser + ")";
	
	// The following pattern describes the structure of the user
	
	var userPat=new RegExp("^" + word + "(\\." + word + ")*$");
	
	/* The following pattern describes the structure of a normal symbolic
	domain, as opposed to ipDomainPat, shown above. */
	
	var domainPat=new RegExp("^" + atom + "(\\." + atom +")*$");
	
	/* Finally, let's start trying to figure out if the supplied address is valid. */
	
	/* Begin with the coarse pattern to simply break up user@domain into
	different pieces that are easy to analyze. */
	
	var matchArray=emailStr.match(emailPat);
	
	if (matchArray==null) {
	
		/* Too many/few @'s or something; basically, this address doesn't
		even fit the general mould of a valid e-mail address. */
	
		// alert("Email address seems incorrect (check @ and .'s)");
		return false;
	}

	var user=matchArray[1];
	var domain=matchArray[2];
	
	// Start by checking that only basic ASCII characters are in the strings (0-127).
	
	for (i=0; i<user.length; i++) {
		if (user.charCodeAt(i)>127) {
			//alert("Ths username contains invalid characters.");
			return false;
		}
	}
	for (i=0; i<domain.length; i++) {
		if (domain.charCodeAt(i)>127) {
			//alert("Ths domain name contains invalid characters.");
			return false;
		}
	}
	
	// See if "user" is valid 
	
	if (user.match(userPat)==null) {
	
		// user is not valid
		//alert("The username doesn't seem to be valid.");
		return false;
	}
	
	/* if the e-mail address is at an IP address (as opposed to a symbolic
	host name) make sure the IP address is valid. */
	
	var IPArray=domain.match(ipDomainPat);
	if (IPArray!=null) {
		// this is an IP address
		for (var i=1;i<=4;i++) {
			if (IPArray[i]>255) {
				//alert("Destination IP address is invalid!");
				return false;
			}
		}
	return true;
	}
	
	// Domain is symbolic name.  Check if it's valid.
	 
	var atomPat=new RegExp("^" + atom + "$");
	var domArr=domain.split(".");
	var len=domArr.length;
	for (i=0;i<len;i++) {
		if (domArr[i].search(atomPat)==-1) {
			//alert("The domain name does not seem to be valid.");
			return false;
		}
	}
	
	/* domain name seems valid, but now make sure that it ends in a
	known top-level domain (like com, edu, gov) or a two-letter word,
	representing country (uk, nl), and that there's a hostname preceding 
	the domain or country. */
	
	if (checkTLD && domArr[domArr.length-1].length!=2 && domArr[domArr.length-1].search(knownDomsPat)==-1) {
		//alert("The address must end in a well-known domain or two letter " + "country.");
		return false;
	}
	
	// Make sure there's a host name preceding the domain.
	
	if (len<2) {
		//alert("This address is missing a hostname!");
		return false;
	}
	
	// If we've gotten this far, everything's valid!
	return true;
}